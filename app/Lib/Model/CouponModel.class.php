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
     * 获取水果劵列表（API）
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
            'user_id' => $user_id
        ))->limit($offset, $pageszie)->select();
    }

    /**
     * 添加水果劵
     *
     * @param int $user_id
     *            用户ID
     * @param int $rule_type
     *            水果劵规则（1：注册，2：推荐，3：满X送N，4：手动赠送）
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
     * 使用水果劵
     *
     * @param int $coupon_id
     *            水果劵ID
     * @param float $total_amount
     *            订单总金额
     * @return array
     */
    public function useCoupon($coupon_id, $total_amount) {
        $coupon = $this->where(array(
            'id' => $coupon_id
        ))->find();
        // 不存在水果劵
        if (!$coupon) {
            return array(
                'status' => 0,
                'result' => '水果劵不存在'
            );
        }
        // 水果劵已经过期
        if ($coupon['expire_time'] && $coupon['expire_time'] < time()) {
            return array(
                'status' => 0,
                'result' => '水果劵已经过期'
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
            // 允许使用面值>=水果劵面值，删除水果劵
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
                    'result' => '使用水果劵失败'
                );
            }
        } else {
            // 允许使用面值<水果劵面值，更新水果劵面值
            if ($this->where(array(
                'id' => $coupon['id']
            ))->save(array(
                'score' => $coupon['score'] - $usage['score']
            ))) {
                return array(
                    'status' => 1,
                    'result' => $usage['score']
                );
            } else {
                return array(
                    'status' => 0,
                    'result' => '使用水果劵失败'
                );
            }
        }
    }

}