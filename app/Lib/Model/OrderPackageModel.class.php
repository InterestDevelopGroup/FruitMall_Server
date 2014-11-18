<?php

/**
 * fruit_order_package 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderPackageModel extends Model {

    /**
     * 删除订单套餐
     *
     * @param array $order_id
     *            订单ID
     * @return boolean
     */
    public function deleteOrderPackage(array $order_id) {
        if (!$this->where(array(
            'order_id' => array(
                'in',
                $order_id
            )
        ))->count()) {
            return true;
        }
        if ($this->where(array(
            'order_id' => array(
                'in',
                $order_id
            )
        ))->delete()) {
            return true;
        } else {
            return false;
        }
    }

}