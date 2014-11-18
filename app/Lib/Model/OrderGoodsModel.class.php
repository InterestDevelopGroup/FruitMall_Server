<?php

/**
 * fruit_order_goods 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderGoodsModel extends Model {

    /**
     * 删除订单商品
     *
     * @param array $order_id
     *            订单ID
     * @return boolean
     */
    public function deleteOrderGoods(array $order_id) {
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