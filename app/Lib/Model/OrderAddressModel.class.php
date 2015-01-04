<?php

/**
 * fruit_order_address 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderAddressModel extends Model {

    /**
     * 添加订单地址
     *
     * @param int $order_id
     *            订单ID
     * @param int $address_id
     *            地址ID
     * @return boolean
     */
    public function addOrderAddress($order_id, $address_id) {
        $data = array(
            'order_id' => $order_id,
            'address_id' => $address_id
        );
        $data = array_merge($data, M('Address')->field(array(
            'user_id',
            'consignee',
            'phone',
            'province',
            'city',
            'district',
            'community',
            'road_number',
            'building',
            'address',
            '_consignee',
            '_phone'
        ))->where(array(
            'address_id' => $address_id
        ))->find());
        if ($this->add($data)) {
            return true;
        } else {
            return false;
        }
    }

}