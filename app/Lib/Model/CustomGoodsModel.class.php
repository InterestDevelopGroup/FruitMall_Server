<?php

/**
 * fruit_custom_goods 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CustomGoodsModel extends Model {

    /**
     * 添加定制商品
     *
     * @param int $custom_id
     *            定制ID
     * @param int $goods_id
     *            商品ID
     * @param int $quantity
     *            数量
     * @return array
     */
    public function addCustomGoods($custom_id, $goods_id, $quantity) {
        if ($this->add(array(
            'custom_id' => $custom_id,
            'goods_id' => $goods_id,
            'quantity' => $quantity,
            'add_time' => time()
        ))) {
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
     * 删除定制商品
     *
     * @param array $custom_goods_id
     *            定制商品ID
     * @return array
     */
    public function deleteCustomGoods(array $custom_goods_id) {
        if ($this->where(array(
            'custom_goods_id' => array(
                'in',
                $custom_goods_id
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
     * 根据定制ID删除定制商品
     *
     * @param array $custom_id
     *            定制ID
     * @return boolean
     */
    public function deleteCustomGoodsByCustomId(array $custom_id) {
        if (!$this->where(array(
            'custom_id' => array(
                'in',
                $custom_id
            )
        ))->count()) {
            return true;
        }
        if ($this->where(array(
            'custom_id' => array(
                'in',
                $custom_id
            )
        ))->delete()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据定制ID获取定制商品列表
     *
     * @param int $custom_id
     *            定制ID
     */
    public function getCustomGoodsByCustomId($custom_id) {
        return $this->table($this->getTableName() . " AS cg ")->join(array(
            " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON cg.goods_id = g.id "
        ))->field(array(
            'cg.goods_id',
            'cg.quantity',
            'g.name' => 'goods_name',
            'g.price' => 'goods_price',
            'g._price' => 'goods_market_price',
            'g.unit' => 'goods_price_unit',
            'g.thumb' => 'goods_thumb'
        ))->where(array(
            'custom_id' => $custom_id
        ))->select();
    }

    /**
     * 更新定制商品
     *
     * @param int $custom_goods_id
     *            定制商品ID
     * @param int $quantity
     *            数量
     * @return array
     */
    public function updateCustomGoods($custom_goods_id, $quantity) {
        if ($this->where(array(
            'custom_goods_id' => $custom_goods_id
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