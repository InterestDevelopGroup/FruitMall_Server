<?php

/**
 * fruit_order_package_goods 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderPackageGoodsModel extends Model {

    /**
     * 添加订单套餐
     *
     * @param int $order_id
     *            订单ID
     * @param int $package_id
     *            套餐ID
     * @return boolean
     */
    public function addOrderPackageGoods($order_id, $package_id) {
        $dataList = M('PackageGoods')->table(M('PackageGoods')->getTableName() . " AS pg ")->join(array(
            " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON g.id = pg.goods_id ",
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = g.p_cate_id ",
            " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON cc.id = g.c_cate_id ",
            " LEFT JOIN " . M('Tag')->getTableName() . " AS t ON t.id = g.tag "
        ))->field(array(
            "(SELECT {$order_id})" => 'order_id',
            'pg.package_id',
            'pg.goods_id',
            'pg.amount' => 'goods_quantity',
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
            'pg.package_id' => $package_id,
            'g.is_delete' => 0,
            'g.status' => 1
        ))->select();
        if ($this->addAll($dataList)) {
            return true;
        } else {
            return false;
        }
    }

}