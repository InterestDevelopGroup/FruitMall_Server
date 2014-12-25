<?php

/**
 * fruit_coupon 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CouponModel extends Model {

    /**
     * 获取水果券列表（API）
     *
     * @param int $user_id
     *            用户ID
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @return array
     */
    public function _getCouponList($user_id, $offset, $pagesize) {
        return $this->where(array(
            'user_id' => $user_id,
            "_string" => " expire_time > " . time() . " OR expire_time is null"
        ))->order("score DESC, expire_time DESC")->limit($offset, $pageszie)->select();
    }

    /**
     * 添加水果券
     *
     * @param int $user_id
     *            用户ID
     * @param int $rule_type
     *            水果券规则（1：注册，2：推荐，3：满X送N，4：手动赠送）
     * @param int|null $score
     *            面值
     * @param int|null $expire_time
     *            有效期
     * @return boolean
     */
    public function addCoupon($user_id, $rule_type, $score = null, $expire_time = null) {
        $_add = function ($score, $expire_time) use($user_id, $rule_type) {
            return array(
                'user_id' => $user_id,
                'score' => $score,
                'type' => $rule_type,
                'publish_time' => time(),
                'expire_time' => $expire_time ? ($expire_time * 24 * 3600 + strtotime(date("Y-m-d"))) : $expire_time
            );
        };
        switch ($rule_type) {
            case 1 :
            case 2 :
                $coupon_rule = M('CouponRule')->where(array(
                    'type' => $rule_type
                ))->find();
                if (!$coupon_rule) {
                    return true;
                }
                if ($this->add($_add($coupon_rule['score'], $coupon_rule['expire_time']))) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 4 :
                if ($this->add($_add($score, $expire_time))) {
                    return array(
                        'status' => true,
                        'msg' => '赠送成功'
                    );
                } else {
                    return array(
                        'status' => false,
                        'msg' => '赠送失败'
                    );
                }
                break;
        }
    }

    /**
     * 给指定用户赠送水果券
     *
     * @param array $user_id
     *            指定的用户ID
     * @param int $score
     *            水果券面值
     * @param string $expire
     *            过期时间（传空为永久有效）
     * @return array
     */
    public function addCouponToSpecialUser(array $user_id, $score, $expire, $type = 4) {
        $len = count($user_id);
        $dataList = array();
        for ($i = 0; $i < $len; $i++) {
            $dataList[] = array(
                'user_id' => $user_id[$i],
                'score' => $score,
                'type' => $type,
                'publish_time' => time(),
                'expire_time' => strlen($expire) ? (intval($expire) * 24 * 3600 + strtotime(date("Y-m-d"))) : null
            );
        }
        if ($this->addAll($dataList)) {
            return array(
                'status' => true,
                'msg' => '赠送成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '赠送失败'
            );
        }
    }

    /**
     * 确认订单后送券
     *
     * @param array $order_id
     *            订单ID
     * @return boolean
     */
    public function awardCouponByCompleteOrder(array $order_id) {
        // 获取总金额
        $result = M('Order')->where(array(
            'order_id' => array(
                'in',
                $order_id
            )
        ))->field(array(
            'user_id',
            'total_amount'
        ))->select();
        $toAward = array();
        foreach ($result as $v) {
            if ($is = M('CouponRule')->where(array(
                'type' => 3,
                'condition' => array(
                    'elt',
                    $v['total_amount']
                )
            ))->order('`condition` DESC')->find()) {
                $toAward[] = array(
                    'user_id' => $v['user_id'],
                    'score' => $is['score'],
                    'type' => 3,
                    'publish_time' => time(),
                    'expire_time' => $is['expire_time'] ? (intval($is['expire_time']) * 24 * 3600 + strtotime(date("Y-m-d"))) : null
                );
            }
        }
        if (!$toAward) {
            return true;
        }
        if ($this->addAll($toAward)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取可用的水果券列表
     *
     * @param int $user_id
     *            用户ID
     * @param float $total_amount
     *            总金额
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @return array
     */
    public function getAvailableCoupon($user_id, $total_amount, $offset, $pagesize) {
        $coupon_usgae = M('CouponUsage')->field(array(
            'condition' => '_condition',
            'score'
        ))->where(array(
            'condition' => array(
                'elt',
                $total_amount
            )
        ))->order("_condition DESC")->find();
        return $this->field(array(
            "*",
            "IF(score > {$coupon_usgae['score']}, {$coupon_usgae['score']}, score)" => 'available_score'
        ))->where(array(
            'user_id' => $user_id,
            // 'score' => array(
            // 'elt',
            // intval($coupon_usgae['score'])
            // ),
            "_string" => " expire_time > " . time() . " OR expire_time is null"
        ))->order("score DESC, expire_time DESC")->limit($offset, $pagesize)->select();
    }

    /**
     * 检测是否可用水果券
     *
     * @param int $user_id
     *            水果券
     * @param float $total_amount
     *            总金额
     * @return array
     */
    public function isAvailable($user_id, $total_amount) {
        if (!$this->where(array(
            'user_id' => $user_id,
            "_string" => " expire_time > " . time() . " OR expire_time is null"
        ))->count()) {
            return array(
                'status' => 0,
                'result' => '没有可用代金券'
            );
        }
        $result = M('CouponUsage')->where(array(
            'condition' => array(
                'elt',
                $total_amount
            )
        ))->order("score DESC")->find();
        if ($result) {
            return array(
                'status' => 1,
                'result' => "代金券可抵扣{$result['score']}元"
            );
        } else {
            return array(
                'status' => 0,
                'result' => '不可使用代金券'
            );
        }
    }

    /**
     * 使用水果券
     *
     * @param int $coupon_id
     *            水果券ID
     * @param float $total_amount
     *            订单总金额
     * @return array
     */
    public function useCoupon($coupon_id, $total_amount) {
        $coupon = $this->where(array(
            'id' => $coupon_id
        ))->find();
        // 不存在水果券
        if (!$coupon) {
            return array(
                'status' => 0,
                'result' => '水果券不存在'
            );
        }
        // 水果券已经过期
        if ($coupon['expire_time'] && $coupon['expire_time'] < time()) {
            return array(
                'status' => 0,
                'result' => '水果券已经过期'
            );
        }
        // 获取使用规则
        $usage = M('CouponUsage')->field(array(
            'condition' => '_condition',
            'score'
        ))->where(array(
            'condition' => array(
                'elt',
                $total_amount
            )
        ))->order("_condition DESC")->find();
        if ($usage['score'] >= $coupon['score']) {
            // 允许使用面值>=水果券面值，删除水果券
            if ($this->where(array(
                'id' => $coupon['id']
            ))->delete()) {
                return array(
                    'status' => 1,
                    'result' => $coupon['score']
                );
            } else {
                return array(
                    'status' => 0,
                    'result' => '使用水果券失败'
                );
            }
        } else {
            // 允许使用面值<水果券面值，更新水果券面值
            // if ($this->where(array(
            // 'id' => $coupon['id']
            // ))->save(array(
            // 'score' => $coupon['score'] - $usage['score']
            // ))) {
            // return array(
            // 'status' => 1,
            // 'result' => $usage['score']
            // );
            // } else {
            // return array(
            // 'status' => 0,
            // 'result' => '使用水果券失败'
            // );
            // }
            if ($this->where(array(
                'id' => $coupon['id']
            ))->delete()) {
                return array(
                    'status' => 1,
                    'result' => $usage['score']
                );
            } else {
                return array(
                    'status' => 0,
                    'result' => '使用水果券失败'
                );
            }
        }
    }

}