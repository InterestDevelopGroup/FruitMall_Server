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
        $result = $this->where(array(
            'user_id' => $user_id
        ))->limit($offset, $pagesize)->select();
        foreach ($result as &$v) {
            if ($v['goods_id']) {
                $v = array_merge($v, M('Goods')->field(array(
                    'name' => 'goods_name',
                    'price' => 'goods_price',
                    '_price' => 'goods_market_price',
                    'unit' => 'goods_price_unit',
                    'thumb' => 'goods_thumb'
                ))->where(array(
                    'id' => $v['goods_id']
                ))->find());
            }
            if ($v['package_id']) {
                $v = array_merge($v, M('Package')->field(array(
                    'name' => 'package_name',
                    'price' => 'package_price',
                    '_price' => 'package_market_price',
                    'thumb'
                ))->where(array(
                    'id' => $v['package_id']
                ))->find());
            }
            if ($v['custom_id']) {
                $v = array_merge($v, M('Custom')->field(array(
                    'name' => 'custom_name'
                ))->where(array(
                    'custom_id' => $v['custom_id']
                ))->find());
                $v['goods_list'] = D('CustomGoods')->getCustomGoodsByCustomId($v['custom_id']);
            }
        }
        return $result;
    }

    /**
     * 加入购物车
     *
     * @param int $user_id
     *            用户ID
     * @param string $shopping_list
     *            购物单
     * @return array
     */
    public function addShoppingCar($user_id, $shopping_list) {
        $add_time = time();
        $shopping_list = ob2ar(json_decode($shopping_list));
        $dataList = array();
        foreach ($shopping_list as $v) {
            $dataList[] = array(
                'user_id' => $user_id,
                'goods_id' => $v['goods_id'] ? $v['goods_id'] : null,
                'package_id' => $v['package_id'] ? $v['package_id'] : null,
                'custom_id' => $v['custom_id'] ? $v['custom_id'] : null,
                'quantity' => $v['quantity'],
                'add_time' => $add_time
            );
        }
        if ($this->addAll($dataList)) {
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