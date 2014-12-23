<?php

/**
 * fruit_order_custom 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderCustomModel extends Model {

    public function addOrderCustom($order_id, $custom_id, $amount) {
        $data = array(
            'order_id' => $order_id,
            'order_quantity' => $amount,
            'custom_id' => $custom_id
        );

        $order_price = M('CustomGoods')->table(M('CustomGoods')->getTableName() . " AS cg ")->field(array(
            "SUM(g.price * cg.quantity)" => 'order_price'
        ))->join(array(
            " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON cg.goods_id = g.id "
        ))->where(array(
            'cg.custom_id' => $custom_id
        ))->find();
        $data['order_price'] = $order_price['order_price'];

        $data = array_merge($data, M('Custom')->field(array(
            'custom_id',
            'name'
        ))->where(array(
            'custom_id' => $custom_id
        ))->find());
        // 开启事务
        $this->startTrans();
        if ($this->add($data)) {
            if (D('OrderCustomGoods')->addOrderCustomGoods($order_id, $custom_id)) {
                // 提交事务
                $this->commit();
                return true;
            } else {
                // 回滚事务
                $this->rollback();
                return false;
            }
        } else {
            // 回滚事务
            $this->rollback();
            return false;
        }
    }

    /**
     * 删除订单定制
     *
     * @param array $order_id
     *            订单ID
     * @return boolean
     */
    public function deleteOrderCustom(array $order_id) {
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