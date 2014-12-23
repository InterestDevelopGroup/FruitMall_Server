<?php

/**
 * fruit_order_package 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderPackageModel extends Model {

    /**
     * 添加订单套餐
     *
     * @param int $order_id
     *            订单ID
     * @param int $package_id
     *            套餐ID
     * @param int $amount
     *            订单数量
     * @return boolean
     */
    public function addOrderPackage($order_id, $package_id, $amount) {
        $data = array(
            'order_id' => $order_id,
            'order_quantity' => $amount,
            'package_id' => $package_id
        );
        $data = array_merge($data, M('Package')->field(array(
            'name',
            'price',
            '_price',
            'thumb',
            'image_1',
            'image_2',
            'image_3',
            'image_4',
            'image_5',
            'description'
        ))->where(array(
            'id' => $package_id
        ))->find());
        // 开启事务
        $this->startTrans();
        if ($this->add($data)) {
            if (D('OrderPackageGoods')->addOrderPackageGoods($order_id, $package_id)) {
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
     * 删除订单套餐
     *
     * @param array $order_id
     *            订单ID
     * @return boolean
     */
    public function deleteOrderPackage(array $order_id) {
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