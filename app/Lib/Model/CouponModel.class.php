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
     * @param mix $user
     *            接受水果劵的用户ID/手机
     * @param string $rule
     *            水果劵规则
     * @return boolean
     */
    public function addCoupon($user, $rule) {
        $_add = function ($user_id) use($rule) {
            $coupon_rule = C('coupon_rule');
            $publish_time = time();
            $data = array(
                'user_id' => $user_id,
                'score' => $coupon_rule[$rule]['score'],
                'type' => $rule,
                'publish_time' => $publish_time,
                'expire_time' => $publish_time + $coupon_rule[$rule]['expire']
            );
            return $data;
        };
        switch ($rule) {
            case 'recommend' :
                $user_info = M('Member')->where(array(
                    'phone' => $user
                ))->find();
                if (empty($user_info)) {
                    return false;
                }
                if ($this->add($_add($user_info['id']))) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'register' :
                if ($this->add($_add($user))) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

}