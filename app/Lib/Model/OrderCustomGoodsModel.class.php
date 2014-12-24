<?php

/**
 * fruit_order_custom_goods 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderCustomGoodsModel extends Model {

    /**
     * 添加订单定制商品
     *
     * @param int $order_id
     *            订单ID
     * @param int $custom_id
     *            定制ID
     * @return boolean
     */
    public function addOrderCustomGoods($order_id, $custom_id) {
        $dataList = M('CustomGoods')->table(M('CustomGoods')->getTableName() . " AS cg ")->join(array(
            " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON g.id = cg.goods_id ",
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = g.p_cate_id ",
            " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON cc.id = g.c_cate_id ",
            " LEFT JOIN " . M('Tag')->getTableName() . " AS t ON t.id = g.tag "
        ))->field(array(
            "(SELECT {$order_id})" => 'order_id',
            'cg.custom_id',
            'cg.goods_id',
            'cg.quantity' => 'goods_quantity',
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
            'g.description',
            'pc.name' => 'parent_category',
            'cc.name' => 'child_category',
            't.name' => 'tag_name'
        ))->where(array(
            'cg.custom_id' => $custom_id
        ))->select();
        if ($this->addAll($dataList)) {
            return true;
        } else {
            return false;
        }
    }

}