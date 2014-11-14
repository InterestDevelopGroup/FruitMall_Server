<?php

/**
 * fruit_order 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderModel extends Model {

    /**
     * 添加订单
     *
     * @param int $user_id
     *            用户ID
     * @param int $address_id
     *            地址ID
     * @param string $order
     *            订单详细
     * @param string $start_shipping_time
     *            开始送货时间
     * @param string $end_shipping_time
     *            结束送货时间
     * @param float $shipping_fee
     *            送货费
     * @param string $remark
     *            备注
     * @return array
     */
    public function addOrder($user_id, $address_id, $order, $start_shipping_time, $end_shipping_time, $shipping_fee, $remark) {
        // 开启事务
        $this->startTrans();
        if ($this->add(array(
            'user_id' => $user_id,
            'address_id' => $address_id,
            'order_number' => orderNumber(),
            'status' => 1,
            'start_shipping_time' => $start_shipping_time,
            'end_shipping_time' => $end_shipping_time,
            'shipping_fee' => $shipping_fee,
            'remark' => $remark,
            'add_time' => time()
        ))) {
            $order_id = $this->getLastInsID();
            $order = ob2ar(json_decode($order));
            $order_goods = array();
            $order_package = array();
            foreach ($order as $v) {
                if ($v['goods_id']) {
                    $order_goods[] = array(
                        'goods_id' => $v['goods_id'],
                        'order_id' => $order_id,
                        'amount' => $v['amount']
                    );
                }
                if ($v['package_id']) {
                    $order_package[] = array(
                        'package_id' => $v['package_id'],
                        'order_id' => $order_id,
                        'amount' => $v['amount']
                    );
                }
            }
            if ($order_goods) {
                if (!M('OrderGoods')->addAll($order_goods)) {
                    // 添加失败，回滚事务
                    $this->rollback();
                    return array(
                        'status' => 0,
                        'result' => '未知错误'
                    );
                }
            }
            if ($order_package) {
                if (!M('OrderPackage')->addAll($order_package)) {
                    // 添加失败，回滚事务
                    $this->rollback();
                    return array(
                        'status' => 0,
                        'result' => '未知错误'
                    );
                }
            }
            $this->commit();
            return array(
                'status' => 1,
                'result' => '提交成功'
            );
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'result' => '未知错误'
            );
        }
    }

}