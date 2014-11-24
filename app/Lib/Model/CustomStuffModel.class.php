<?php

/**
 * fruit_custom_stuff 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CustomStuffModel extends Model {

    /**
     * 添加定制商品/套餐
     *
     * @param int $custom_id
     *            定制ID
     * @param int|null $goods_id
     *            商品ID
     * @param int|null $package_id
     *            套餐ID
     * @param int $quantity
     *            数量
     * @return array
     */
    public function addCustomStuff($custom_id, $goods_id, $package_id, $quantity) {
        $data = array();
        $goods_id && $data['goods_id'] = $goods_id;
        $package_id && $data['package_id'] = $package_id;
        if (empty($data) || count($data) == 2) {
            return array(
                'status' => 0,
                'result' => '添加失败'
            );
        }
        $data['custom_id'] = $custom_id;
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
     * 删除定制商品/套餐
     *
     * @param array $custom_stuff_id
     *            制商品/套餐ID
     * @return array
     */
    public function deleteCustomStuff(array $custom_stuff_id) {
        if ($this->where(array(
            'custom_stuff_id' => array(
                'in',
                $custom_stuff_id
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
     * 根据定制ID删除定制商品/套餐
     *
     * @param array $custom_id
     *            定制ID
     * @return boolean
     */
    public function deleteCustomStuffByCustomId(array $custom_id) {
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

}