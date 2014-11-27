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

}