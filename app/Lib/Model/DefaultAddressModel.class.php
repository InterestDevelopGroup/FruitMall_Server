<?php

/**
 * fruit_default_address 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class DefaultAddressModel extends Model {

    /**
     * 设置/取消默认地址
     *
     * @param int $user_id
     *            用户ID
     * @param int $address_id
     *            地址ID
     * @param int $is_default
     *            是否默认（0：否，1：是）
     * @return array
     */
    public function changeDefaultAddress($user_id, $address_id, $is_default) {
        $affected_rows = $this->where(array(
            'user_id' => $user_id
        ))->delete();
        if ($is_default) {
            // 设置默认地址
            if ($this->add(array(
                'user_id' => $user_id,
                'address_id' => $address_id,
                'add_time' => time()
            ))) {
                return array(
                    'status' => 1,
                    'result' => '设置成功'
                );
            } else {
                return array(
                    'status' => 0,
                    'result' => '设置失败'
                );
            }
        } else {
            // 取消默认地址
            if ($affected_rows) {
                return array(
                    'status' => 1,
                    'result' => '取消成功'
                );
            } else {
                return array(
                    'status' => 0,
                    'result' => '取消失败'
                );
            }
        }
    }

}