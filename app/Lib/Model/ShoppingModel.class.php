<?php

/**
 * tea_shopping表模型
 *
 * @author lzjjie
 * @version 1.0.1
 * @since 1.0.1
 */
class ShoppingModel extends Model {

    /**
     * 获取拍下的发布列表
     *
     * @param int $user_id
     *            用户ID
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     */
    public function getShoppingList($user_id, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS s ")->field(array(
            's.id' => 'shopping_id',
            's.publisher_id',
            's.publish_id',
            'p.brand',
            'p.brand_name',
            'p.name',
            'p.amount',
            'p.unit',
            'p.price',
            'p.business_number',
            'p.batch',
            'p.is_buy',
            'p.is_distribute',
            'p.status',
            'p.image_1',
            'p.image_2',
            'p.image_3',
            'p.details',
            'p.supply',
            'p.publish_time',
            's.add_time'
        ))->join(array(
            " LEFT JOIN " . M('Publish')->getTableName() . " AS p ON s.publish_id = p.id "
        ))->where(array(
            's.user_id' => $user_id
        ))->limit($offset, $pageSize)->order("s.id DESC")->select();
    }

}