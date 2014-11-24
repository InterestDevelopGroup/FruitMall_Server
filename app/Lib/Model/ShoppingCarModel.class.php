<?php

/**
 * fruit_shopping_car 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class ShoppingCarModel extends Model {

    /**
     * 获取购物车列表（API）
     *
     * @param int $user_id
     *            用户ID
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     */
    public function _getShoppingCar($user_id, $offset, $pagesize) {
        return $this->table($this->getTableName() . " AS s ")->join(array(
            " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON s.goods_id = g.id ",
            " LEFT JOIN " . M('Package')->getTableName() . " AS p ON s.package_id = p.id "
        ))->field(array(
            's.user_id',
            's.goods_id',
            's.package_id',
            's.quantity',
            's.add_time',
            'g.name' => 'goods_name',
            'g.price' => 'goods_price',
            'g._price' => 'goods_market_price',
            'g.unit' => 'goods_price_unit',
            'g.thumb' => 'goods_thumb',
            'p.name' => 'package_name',
            'p.price' => 'package_price',
            'p._price' => 'package_market_price',
            'p.thumb' => 'package_thumb'
        ))->where(array(
            's.user_id' => $user_id
        ))->limit($offset, $pagesize)->select();
    }

    /**
     * 加入购物车
     *
     * @param int $user_id
     *            用户ID
     * @param int|null $goods_id
     *            商品ID
     * @param int|null $package_id
     *            套餐ID
     * @param int $quantity
     *            数量
     * @return array
     */
    public function addShoppingCar($user_id, $goods_id, $package_id, $quantity) {
        $data = array();
        $goods_id && $data['goods_id'] = $goods_id;
        $package_id && $data['package_id'] = $package_id;
        if (empty($data) || count($data) == 2) {
            return array(
                'status' => 0,
                'result' => '添加失败'
            );
        }
        $data['user_id'] = $user_id;
        $data['quantity'] = $quantity;
        $data['add_time'] = time();
        if ($this->add($data)) {
            return array(
                'status' => 1,
                'result' => '添加成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '添加失败'
            );
        }
    }

    /**
     * 删除购物车的商品或套餐
     *
     * @param array $shopping_car_id
     *            购物车ID
     * @return array
     */
    public function deleteShoppingCar(array $shopping_car_id) {
        if ($this->where(array(
            'shopping_car_id' => array(
                'in',
                $shopping_car_id
            )
        ))->delete()) {
            return array(
                'status' => 1,
                'result' => '删除成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '删除失败'
            );
        }
    }

    /**
     * 更新购物车
     *
     * @param int $shopping_car_id
     *            购物车ID
     * @param int $quantity
     *            数量
     * @return array
     */
    public function updateShoppingCar($shopping_car_id, $quantity) {
        if ($this->where(array(
            'shopping_car_id' => $shopping_car_id
        ))->save(array(
            'quantity' => $quantity
        ))) {
            return array(
                'status' => 1,
                'result' => '更新成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '更新失败'
            );
        }
    }

}