<?php

/**
 * fruit_custom 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CustomModel extends Model {

    /**
     * 获取定制列表（API）
     *
     * @param int $user_id
     *            用户ID
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @return array
     */
    public function _getCustomList($user_id, $offset, $pagesize) {
        return array_map(function ($value) {
            $value['goods_list'] = M('CustomGoods')->table(M('CustomGoods')->getTableName() . " AS cg ")->join(array(
                " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON g.id = cg.goods_id "
            ))->field(array(
                'cg.custom_goods_id',
                'cg.goods_id',
                'cg.quantity',
                'g.name' => 'goods_name',
                'g.price' => 'goods_price',
                'g._price' => 'goods_market_price',
                'g.unit' => 'goods_price_unit',
                'g.thumb' => 'goods_thumb'
            ))->where(array(
                'cg.custom_id' => $value['custom_id'],
                'g.status' => 1,
                'g.is_delete' => 0
            ))->select();
            return $value;
        }, $this->where(array(
            'user_id' => $user_id,
            'is_delete' => 0
        ))->limit($offset, $pagesize)->select());
    }

    /**
     * 添加定制
     *
     * @param int $user_id
     *            用户ID
     * @param string $name
     *            定制名称
     * @param string $goods_list
     *            商品列表
     * @return array
     */
    public function addCustom($user_id, $name, $goods_list) {
        if ($this->where(array(
            'user_id' => $user_id,
            'name' => $name
        ))->count()) {
            return array(
                'status' => 0,
                'result' => '定制名称不能重复'
            );
        }
        $now = time();
        // 开启事务
        $this->startTrans();
        if ($this->add(array(
            'user_id' => $user_id,
            'name' => $name,
            'create_time' => $now
        ))) {
            $custom_id = $this->getLastInsID();
            $goods_list = ob2ar(json_decode($goods_list));
            $dataList = array();
            foreach ($goods_list as $v) {
                $dataList[] = array(
                    'custom_id' => $custom_id,
                    'goods_id' => $v['goods_id'],
                    'quantity' => $v['quantity'],
                    'add_time' => $now
                );
            }
            if (M('CustomGoods')->addAll($dataList)) {
                // 添加成功，提交事务
                $this->commit();
                return array(
                    'status' => 1,
                    'result' => '添加成功'
                );
            } else {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '添加失败'
                );
            }
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'result' => '添加失败'
            );
        }
    }

    /**
     * 删除定制
     *
     * @param array $custom_id
     *            定制ID
     * @return array
     */
    public function deleteCustom(array $custom_id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'custom_id' => array(
                'in',
                $custom_id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            if (!D('ShoppingCar')->deleteShoppingCarByCustomId((array) $custom_id)) {
                // 删除成功，提交事务
                $this->commit();
                return array(
                    'status' => 1,
                    'result' => '删除成功'
                );
            } else {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '删除失败'
                );
            }
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'result' => '删除失败'
            );
        }
        // // 开启事务
        // $this->startTrans();
        // if ($this->where(array(
        // 'custom_id' => array(
        // 'in',
        // $custom_id
        // )
        // ))->delete()) {
        // if (!D('CustomGoods')->deleteCustomGoodsByCustomId((array) $custom_id)) {
        // // 删除失败，回滚事务
        // $this->rollback();
        // return array(
        // 'status' => 0,
        // 'result' => '删除失败'
        // );
        // }
        // if (!D('ShoppingCar')->deleteShoppingCarByCustomId((array) $custom_id)) {
        // // 删除失败，回滚事务
        // $this->rollback();
        // return array(
        // 'status' => 0,
        // 'result' => '删除失败'
        // );
        // }
        // // 删除成功，提交事务
        // $this->commit();
        // return array(
        // 'status' => 1,
        // 'result' => '删除成功'
        // );
        // } else {
        // // 删除失败，回滚事务
        // $this->rollback();
        // return array(
        // 'status' => 0,
        // 'result' => '删除失败'
        // );
        // }
    }

}