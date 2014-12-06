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
     * 获取订单列表（API）
     *
     * @param int $user_id
     *            用户ID
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @param int $type
     *            类型（1：未完成，2：历史）
     * @return int
     */
    public function _getOrderList($user_id, $offset, $pagesize, $type) {
        $where = array(
            'o.user_id' => $user_id
        );
        if ($type == 1) {
            $where['o.status'] = array(
                'between',
                array(
                    1,
                    2
                )
            );
        } else if ($type == 2) {
            $where['o.status'] = array(
                'between',
                array(
                    3,
                    4
                )
            );
        }
        $order_list = $this->table($this->getTableName() . " AS o ")->field(array(
            'o.order_id',
            'o.user_id',
            'o.address_id',
            'o.order_number',
            'o.status',
            'o.shipping_time',
            'o.shipping_fee',
            'o.remark',
            'o.coupon',
            'o.total_amount',
            'o.add_time',
            'o.update_time',
            'a.consignee',
            'a.phone',
            'a.province',
            'a.city',
            'a.district',
            'a.community',
            'a.address',
            'a._consignee',
            'a._phone'
        ))->join(array(
            " LEFT JOIN " . M('Address')->getTableName() . " AS a ON o.address_id = a.address_id "
        ))->where($where)->limit($offset, $pagesize)->select();
        foreach ($order_list as &$v) {
            $v = array_merge($v, $this->getOrderDetail($v['order_id']));
        }
        return $order_list;
    }

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
    public function addOrder($user_id, $address_id, $order, $coupon_id, $shipping_time, $shipping_fee, $total_amount, $remark) {
        if ($coupon_id) {
            $_use = D('Coupon')->useCoupon($coupon_id, $total_amount);
            if ($_use['status']) {
                $coupon = $_use['result'];
            } else {
                return $_use;
            }
        } else {
            $coupon = null;
        }
        // 开启事务
        $this->startTrans();
        if ($this->add(array(
            'user_id' => $user_id,
            'address_id' => $address_id,
            'order_number' => orderNumber(),
            'status' => 1,
            'shipping_time' => $shipping_time,
            'shipping_fee' => $shipping_fee,
            'remark' => $remark,
            'coupon' => $coupon,
            'total_amount' => $total_amount,
            'add_time' => time()
        ))) {
            $order_id = $this->getLastInsID();
            $order = ob2ar(json_decode($order));
            $order_goods = array();
            $order_package = array();
            $order_custom = array();
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
                if ($v['custom_id']) {
                    $order_custom[] = array(
                        'custom_id' => $v['custom_id'],
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
            if ($order_custom) {
                if (!M('OrderCustom')->addAll($order_custom)) {
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

    /**
     * 删除订单
     *
     * @param array $id
     *            订单ID
     * @return array
     */
    public function deleteOrder(array $id) {
        if ($this->where(array(
            'order_id' => array(
                'in',
                $id
            ),
            'status' => array(
                'neq',
                1
            )
        ))->count()) {
            return array(
                'status' => 0,
                'result' => '该订单状态不允许取消订单'
            );
        }
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'order_id' => array(
                'in',
                $id
            )
        ))->delete()) {
            if (!D('OrderGoods')->deleteOrderGoods($id)) {
                // 删除订单商品失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '删除失败'
                );
            }
            if (!D('OrderPackage')->deleteOrderPackage($id)) {
                // 删除订单套餐失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '删除失败'
                );
            }
            if (!D('OrderCustom')->deleteOrderCustom($id)) {
                // 删除订单套餐失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '删除失败'
                );
            }
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
    }

    /**
     * 获取订单数量
     *
     * @param string $keyword
     *            关键字
     * @return int
     */
    public function getOrderCount($keyword) {
        empty($keyword) || $this->where(array(
            'order_number' => $keyword
        ));
        return (int) $this->select();
    }

    /**
     * 获取订单详细
     *
     * @param int $order_id
     *            订单ID
     * @return array
     */
    public function getOrderDetail($order_id) {
        $order_goods = M('OrderGoods')->table(M('OrderGoods')->getTableName() . " AS og ")->field(array(
            'og.goods_id',
            'og.amount' => 'quantity',
            'g.name',
            'g.price',
            'g._price',
            'g.unit',
            'g.tag',
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
            't.name' => 'tag'
        ))->join(array(
            " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON og.goods_id = g.id ",
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON g.p_cate_id = pc.id ",
            " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON g.c_cate_id = cc.id ",
            " LEFT JOIN " . M('Tag')->getTableName() . " AS t ON g.tag = t.id "
        ))->where(array(
            'og.order_id' => $order_id
        ))->select();
        $order_package = M('OrderPackage')->table(M('OrderPackage')->getTableName() . " AS op ")->field(array(
            'op.package_id',
            'op.amount' => 'quantity',
            'p.name',
            'p.price',
            'p._price',
            'p.thumb',
            'p.image_1',
            'p.image_2',
            'p.image_3',
            'p.image_4',
            'p.image_5',
            'p.description'
        ))->join(array(
            " LEFT JOIN " . M('Package')->getTableName() . " AS p ON op.package_id = p.id "
        ))->where(array(
            'op.order_id' => $order_id
        ))->select();
        $order_custom = M('OrderCustom')->table(M('OrderCustom')->getTableName() . " AS oc")->join(array(
            " LEFT JOIN " . M('Custom')->getTableName() . " AS c ON oc.custom_id = c.custom_id "
        ))->field(array(
            'oc.custom_id',
            'oc.amount' => 'quantity',
            'c.name',
            "(
                SELECT
                    sum(cg.quantity * g.price)
                FROM
                    fruit_custom_goods AS cg
                LEFT JOIN
                    fruit_goods AS g ON cg.goods_id = g.id
                WHERE
                    cg.custom_id = oc.custom_id)" => 'price'
        ))->where(array(
            'oc.order_id' => $order_id
        ))->select();
        return array(
            'order_goods' => $order_goods,
            'order_package' => $order_package,
            'order_custom' => $order_custom
        );
    }

    /**
     * 获取订单列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param string $order
     *            排序字段
     * @param string $sort
     *            排序方式
     * @param string $keyword
     *            关键字
     */
    public function getOrderList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            'order_number' => $keyword
        ));
        return $this->table($this->getTableName() . " AS o ")->field(array(
            'o.order_id',
            'o.user_id',
            'o.address_id',
            'o.order_number',
            'o.status',
            'o.shipping_time',
            'o.shipping_fee',
            'o.remark',
            'o.add_time',
            'o.update_time',
            'm.username',
            'a.consignee',
            'a.phone',
            'a.province',
            'a.city',
            'a.district',
            'a.address',
            'a._consignee',
            'a._phone',
            'c.real_name' => 'courier',
            "(SELECT COUNT(1) FROM " . M('Blacklist')->getTableName() . " WHERE user_id = o.user_id)" => 'is_blacklist'
        ))->join(array(
            " LEFT JOIN " . M('Member')->getTableName() . " AS m ON o.user_id = m.id ",
            " LEFT JOIN " . M('Address')->getTableName() . " AS a ON o.address_id = a.address_id ",
            " LEFT JOIN " . M('Courier')->getTableName() . " AS c ON o.courier_id = c.id "
        ))->order("o." . $order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 打印订单
     *
     * @param int $order_id
     *            订单ID
     * @return array
     */
    public function printOrder($order_id) {
        $result = $this->table($this->getTableName() . " AS o ")->join(array(
            " LEFT JOIN " . M('Address')->getTableName() . " AS a ON o.address_id = a.address_id "
        ))->where(array(
            'order_id' => $order_id
        ))->field(array(
            'o.order_id',
            'a.consignee',
            'a.phone',
            'a.address'
        ))->select();
        foreach ($result as &$v) {
            $v['goods_list'] = D('OrderGoods')->table(D('OrderGoods')->getTableName() . " AS og ")->join(array(
                " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON og.goods_id = g.id "
            ))->field(array(
                'og.amount',
                'g.name',
                'g.price'
            ))->where(array(
                'og.order_id' => $v['order_id']
            ))->select();
            $v['package_list'] = D('OrderPackage')->table(D('OrderPackage')->getTableName() . " AS op ")->join(array(
                " LEFT JOIN " . M('Package')->getTableName() . " AS p ON op.package_id = p.id "
            ))->field(array(
                'op.amount',
                'p.name',
                'p.price'
            ))->where(array(
                'op.order_id' => $v['order_id']
            ))->select();
            $v['custom_list'] = D('OrderCustom')->table(D('OrderCustom')->getTableName() . " AS oc")->join(array(
                " LEFT JOIN " . M('Custom')->getTableName() . " AS c ON oc.custom_id = c.custom_id "
            ))->field(array(
                'oc.amount',
                'c.name',
                "(
                SELECT
                    sum(cg.quantity * g.price)
                FROM
                    fruit_custom_goods AS cg
                LEFT JOIN
                    fruit_goods AS g ON cg.goods_id = g.id
                WHERE
                    cg.custom_id = oc.custom_id)" => 'price'
            ))->where(array(
                'oc.order_id' => $v['order_id']
            ))->select();
        }
        return $result;
    }

    /**
     * 更新订单送货员
     *
     * @param array $order_id
     *            订单ID
     * @param int $courier_id
     *            送货员ID
     * @return array
     */
    public function updateOrderCourier(array $order_id, $courier_id) {
        if ($this->where(array(
            'order_id' => array(
                'in',
                $order_id
            )
        ))->save(array(
            'courier_id' => $courier_id
        ))) {
            return array(
                'status' => true,
                'msg' => '指定成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '指定失败'
            );
        }
    }

    /**
     * 更新订单状态
     *
     * @param array $order_id
     *            订单ID
     * @param int $status
     *            订单状态（1：待确定，2：配送中，3：已收货，4：拒收）
     * @return array
     */
    public function updateOrderStatus(array $order_id, $status) {
        if ($this->where(array(
            'order_id' => array(
                'in',
                $order_id
            )
        ))->save(array(
            'status' => $status,
            'update_time' => time()
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