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
     * 添加订单商品
     *
     * @param int $order_id
     *            订单ID
     * @param int $goods_id
     *            商品ID
     * @param int $amount
     *            商品数量
     * @return boolean
     */
    public function addOrderGoods($order_id, $goods_id, $amount) {
        $data = array(
            'order_id' => $order_id,
            'order_quantity' => $amount,
            'goods_id' => $goods_id
        );
        $data = array_merge($data, M('Goods')->table(M('Goods')->getTableName() . " AS g ")->join(array(
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = g.p_cate_id ",
            " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON cc.id = g.c_cate_id ",
            " LEFT JOIN " . M('Tag')->getTableName() . " AS t ON t.id = g.tag "
        ))->field(array(
            'g.name',
            'g.price',
            'g._price',
            'g.single_price',
            'g.unit',
            'g.single_unit',
            'g.amount',
            'g.weight',
            'g.thumb',
            'g.image_1',
            'g.image_2',
            'g.image_3',
            'g.image_4',
            'g.image_5',
            'g.ad_image',
            'g.description',
            'pc.name' => 'parent_category',
            'cc.name' => 'child_category',
            't.name' => 'tag_name'
        ))->where(array(
            'g.id' => $goods_id
        ))->find());
        if ($this->add($data)) {
            return true;
        } else {
            return false;
        }
    }

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