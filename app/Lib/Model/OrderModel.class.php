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
     * 删除订单（API）
     *
     * @param array $id
     *            订单ID
     * @return array
     */
    public function _deleteOrder(array $id) {
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
            if (!D('Purchase')->deletePurchaseWhenOrderStatusChange($id)) {
                // 取消订单失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '取消成功'
                );
            }
            // 取消成功，提交事务
            $this->commit();
            return array(
                'status' => 1,
                'result' => '取消成功'
            );
        } else {
            // 取消订单失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'result' => '取消成功'
            );
        }
    }

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
                    8
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
            " LEFT JOIN " . M('OrderAddress')->getTableName() . " AS a ON o.order_id = a.order_id "
        ))->where($where)->order("o.add_time DESC")->limit($offset, $pagesize)->select();
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
                $total_amount = $total_amount - $coupon;
            } else {
                return $_use;
            }
        } else {
            $coupon = null;
        }
        // 根据地址小区分配订单
        $_address = M('Address')->where(array(
            'address_id' => $address_id
        ))->find();
        if ($_address['community']) {
            $order_branch = M('BranchShippingAddress')->table(M('BranchShippingAddress')->getTableName() . " AS bsa ")->join(array(
                " INNER JOIN " . M('ShippingAddress')->getTableName() . " AS sa ON bsa.shipping_address_id = sa.id "
            ))->where(array(
                'sa.community' => $_address['community']
            ))->find();
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
            'total_amount' => $total_amount + $shipping_fee,
            'branch_id' => $order_branch ? $order_branch['branch_id'] : null,
            'add_time' => time()
        ))) {
            $order_id = $this->getLastInsID();
            if (!D('OrderAddress')->addOrderAddress($order_id, $address_id)) {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '未知错误'
                );
            }
            $order = json_decode($order, true);
            foreach ($order as $v) {
                if ($v['goods_id']) {
                    if (!D('OrderGoods')->addOrderGoods($order_id, $v['goods_id'], $v['amount'])) {
                        // 添加失败，回滚事务
                        $this->rollback();
                        return array(
                            'status' => 0,
                            'result' => '未知错误'
                        );
                    }
                }
                if ($v['package_id']) {
                    if (!D('OrderPackage')->addOrderPackage($order_id, $v['package_id'], $v['amount'])) {
                        // 添加失败，回滚事务
                        $this->rollback();
                        return array(
                            'status' => 0,
                            'result' => '未知错误'
                        );
                    }
                }
                if ($v['custom_id']) {
                    if (!D('OrderCustom')->addOrderCustom($order_id, $v['custom_id'], $v['amount'])) {
                        // 添加失败，回滚事务
                        $this->rollback();
                        return array(
                            'status' => 0,
                            'result' => '未知错误'
                        );
                    }
                }
            }
            // 添加成功，提交事务
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
     * 取消订单
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
        ))->save(array(
            'status' => 5
        ))) {
            if (!D('Purchase')->deletePurchaseWhenOrderStatusChange($id)) {
                // 取消订单失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '取消成功'
                );
            }
            // 取消成功，提交事务
            $this->commit();
            return array(
                'status' => 1,
                'result' => '取消成功'
            );
        } else {
            // 取消订单失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'result' => '取消成功'
            );
        }
    }

    /**
     * 获取订单数量
     *
     * @param string $keyword
     *            查询关键字
     * @param int $courier
     *            送货员ID
     * @param int $type
     *            订单类型（1：历史订单，2：新订单，3：已取消的订单）
     * @param int $status
     *            订单状态
     * @return int
     */
    public function getOrderCount($keyword, $courier, $type, $status = 0) {
        // 获取当前管理员
        $admin = session('admin_info');
        if ($admin && $admin['type'] != 1) {
            $branch_id[] = array_map(function ($value) {
                return $value['id'];
            }, M('Branch')->where(array(
                'admin_id' => $admin['id']
            ))->select());
        }
        if ($status) {
            $where = array(
                'status' => $status
            );
        } else {
            if ($type == 1) {
                $where = array(
                    'status' => array(
                        'in',
                        array(
                            2,
                            3,
                            4,
                            6,
                            7,
                            8
                        )
                    )
                );
            } else if ($type == 2) {
                $where = array(
                    'status' => 1
                );
            } else if ($type == 3) {
                $where = array(
                    'status' => 5
                );
            }
        }
        $courier && $where['courier_id'] = $courier;
        empty($keyword) || $where['order_number'] = $keyword;
        $branch_id && $where['branch_id'] = array(
            'in',
            $branch_id[0]
        );
        $this->where($where)->count();
        return (int) $this->where($where)->count();
    }

    /**
     * 获取订单详细
     *
     * @param int $order_id
     *            订单ID
     * @return array
     */
    public function getOrderDetail($order_id) {
        $order_goods = M('OrderGoods')->field(array(
            '*',
            'order_quantity' => 'quantity',
            'price' => 'order_price'
        ))->where(array(
            'order_id' => $order_id
        ))->select();
        $order_package = M('OrderPackage')->where(array(
            'order_id' => $order_id
        ))->field(array(
            '*',
            'order_quantity' => 'quantity',
            'price' => 'order_price'
        ))->select();
        $order_custom = M('OrderCustom')->where(array(
            'order_id' => $order_id
        ))->field(array(
            '*',
            'order_quantity' => 'quantity',
            'order_price' => 'price'
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
     *            查询关键字
     * @param int $courier
     *            送货员ID
     * @param int $type
     *            订单类型（1：历史订单，2：新订单，3：已取消的订单）
     * @param int $status
     *            订单状态
     */
    public function getOrderList($page, $pageSize, $order, $sort, $keyword, $courier, $type, $status = 0) {
        // 获取当前管理员
        $admin = session('admin_info');
        if ($admin && $admin['type'] != 1) {
            $branch_id[] = array_map(function ($value) {
                return $value['id'];
            }, M('Branch')->where(array(
                'admin_id' => $admin['id']
            ))->select());
        }
        $offset = ($page - 1) * $pageSize;
        if ($status) {
            $where = array(
                'o.status' => $status
            );
        } else {
            if ($type == 1) {
                $where = array(
                    'o.status' => array(
                        'in',
                        array(
                            2,
                            3,
                            4,
                            6,
                            7,
                            8
                        )
                    )
                );
            } else if ($type == 2) {
                $where = array(
                    'o.status' => 1
                );
            } else if ($type == 3) {
                $where = array(
                    'o.status' => 5
                );
            }
        }
        $courier && $where['o.courier_id'] = $courier;
        $branch_id && $where['branch_id'] = array(
            'in',
            $branch_id[0]
        );
        empty($keyword) || $where['order_number'] = $keyword;
        if ($order == 'community') {
            $order = "a." . $order;
        } else {
            $order = "o." . $order;
        }
        return $this->table($this->getTableName() . " AS o ")->field(array(
            'o.order_id',
            'o.user_id',
            'o.address_id',
            'o.order_number',
            'o.status',
            'o.shipping_time',
            'o.shipping_fee',
            'o.total_amount',
            'o.coupon',
            'o.remark',
            'o.add_time',
            'o.update_time',
            'm.username',
            'a.consignee',
            'a.phone',
            'a.province',
            'a.city',
            'a.district',
            'a.community',
            'a.address',
            'a._consignee',
            'a._phone',
            'c.real_name' => 'courier',
            "(SELECT COUNT(1) FROM " . M('Blacklist')->getTableName() . " WHERE user_id = o.user_id)" => 'is_blacklist'
        ))->join(array(
            " LEFT JOIN " . M('Member')->getTableName() . " AS m ON o.user_id = m.id ",
            " LEFT JOIN " . M('OrderAddress')->getTableName() . " AS a ON o.order_id = a.order_id ",
            " LEFT JOIN " . M('Courier')->getTableName() . " AS c ON o.courier_id = c.id "
        ))->where($where)->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 获取订单的所有商品列表
     *
     * @param int $order_id
     *            订单ID
     * @return array
     */
    public function getOrderGoodsList($order_id) {
        $sql = "SELECT
                    name, NULL AS package_custom, order_quantity as goods_quantity,
                    null as package_custom_quantity, unit, single_price,
                    (price * order_quantity) AS price, NULL AS package_id
                FROM
                    fruit_order_goods
                WHERE
                    order_id = {$order_id}
                UNION ALL
                SELECT
                    opg.name, op.name AS package_custom, opg.goods_quantity,
                    order_quantity as package_custom_quantity, NULL AS unit,
                    NULL AS single_price, (op.price * op.order_quantity) AS price,
                    op.package_id
                FROM
                    fruit_order_package_goods AS opg
                LEFT JOIN
                    fruit_order_package AS op
                ON
                    opg.package_id = op.package_id AND opg.order_id = op.order_id
                WHERE
                    opg.order_id = {$order_id}
                UNION ALL
                SELECT
                    ocg.name, oc.name AS package_custom, ocg.goods_quantity,
                    order_quantity as package_custom_quantity, ocg.unit,
                    ocg.price AS single_price,
                    (ocg.price * ocg.goods_quantity) AS price, NULL AS package_id
                FROM
                    fruit_order_custom_goods AS ocg
                LEFT JOIN
                    fruit_order_custom AS oc
                ON
                    ocg.custom_id = oc.custom_id AND ocg.order_id = oc.order_id
                WHERE
                    ocg.order_id = {$order_id}";
        return $this->query($sql);
    }

    /**
     * 打印订单
     *
     * @param array $order_id
     *            订单ID
     * @return array
     */
    public function printOrder(array $order_id) {
        $result = $this->table($this->getTableName() . " AS o ")->join(array(
            " LEFT JOIN " . M('Address')->getTableName() . " AS a ON o.address_id = a.address_id "
        ))->where(array(
            'order_id' => array(
                'in',
                $order_id
            )
        ))->field(array(
            'o.order_id',
            'o.order_number',
            'o.coupon',
            'o.shipping_fee',
            'o.total_amount',
            'a.consignee',
            'a.phone',
            'a.province',
            'a.city',
            'a.district',
            'a.community',
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
            $v['package_list'] = D('OrderPackage')->field(array(
                'order_quantity' => 'amount',
                'name',
                '(price * order_quantity)' => 'price'
            ))->where(array(
                'order_id' => $v['order_id']
            ))->select();
            $v['custom_list'] = D('OrderCustom')->field(array(
                'order_quantity' => 'amount',
                'name',
                '(order_price * order_quantity)' => 'price'
            ))->where(array(
                'order_id' => $v['order_id']
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
    public function updateOrderBranchAndCourier(array $order_id, $branch_id, $courier_id) {
        if ($this->where(array(
            'order_id' => array(
                'in',
                $order_id
            )
        ))->save(array(
            'branch_id' => $branch_id,
            'courier_id' => $courier_id,
            'update_time' => time()
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
     *            订单状态（1：待确定，2：配送中，3：已收货，4：拒收，5：取消，6：待退货，7：同意退货，8：不同意退货）
     * @return array
     */
    public function updateOrderStatus(array $order_id, $status) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'order_id' => array(
                'in',
                $order_id
            )
        ))->save(array(
            'status' => $status,
            'update_time' => time()
        ))) {
            if ($status != 2) {
                if (!D('Purchase')->deletePurchaseWhenOrderStatusChange($order_id)) {
                    // 更新失败，回滚事务
                    $this->rollback();
                    return array(
                        'status' => 0,
                        'result' => '更新失败'
                    );
                }
            }
            if ($status == 3) {
                if (!D('Coupon')->awardCouponByCompleteOrder($order_id)) {
                    // 更新失败，回滚事务
                    $this->rollback();
                    return array(
                        'status' => 0,
                        'result' => '更新失败'
                    );
                }
            }
            // 更新成功，提交事务
            $this->commit();
            return array(
                'status' => 1,
                'result' => '更新成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'result' => '更新失败'
            );
        }
    }

}