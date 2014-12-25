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
        // $order_goods = M('OrderGoods')->table(M('OrderGoods')->getTableName() . " AS og ")->field(array(
        // 'og.goods_id',
        // 'og.amount' => 'quantity',
        // 'og.order_price',
        // 'g.name',
        // 'g.price',
        // 'g._price',
        // 'g.unit',
        // 'g.tag',
        // 'g.amount',
        // 'g.weight',
        // 'g.thumb',
        // 'g.image_1',
        // 'g.image_2',
        // 'g.image_3',
        // 'g.image_4',
        // 'g.image_5',
        // 'g.description',
        // 'pc.name' => 'parent_category',
        // 'cc.name' => 'child_category',
        // 't.name' => 'tag'
        // ))->join(array(
        // " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON og.goods_id = g.id ",
        // " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON g.p_cate_id = pc.id ",
        // " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON g.c_cate_id = cc.id ",
        // " LEFT JOIN " . M('Tag')->getTableName() . " AS t ON g.tag = t.id "
        // ))->where(array(
        // 'og.order_id' => $order_id
        // ))->select();
        // $order_package = M('OrderPackage')->table(M('OrderPackage')->getTableName() . " AS op ")->field(array(
        // 'op.package_id',
        // 'op.amount' => 'quantity',
        // 'op.order_price',
        // 'p.name',
        // 'p.price',
        // 'p._price',
        // 'p.thumb',
        // 'p.image_1',
        // 'p.image_2',
        // 'p.image_3',
        // 'p.image_4',
        // 'p.image_5',
        // 'p.description'
        // ))->join(array(
        // " LEFT JOIN " . M('Package')->getTableName() . " AS p ON op.package_id = p.id "
        // ))->where(array(
        // 'op.order_id' => $order_id
        // ))->select();
        // $order_custom = M('OrderCustom')->table(M('OrderCustom')->getTableName() . " AS oc")->join(array(
        // " LEFT JOIN " . M('Custom')->getTableName() . " AS c ON oc.custom_id = c.custom_id "
        // ))->field(array(
        // 'oc.custom_id',
        // 'oc.amount' => 'quantity',
        // 'oc.order_price',
        // 'c.name',
        // "(
        // SELECT
        // sum(cg.quantity * g.price)
        // FROM
        // fruit_custom_goods AS cg
        // LEFT JOIN
        // fruit_goods AS g ON cg.goods_id = g.id
        // WHERE
        // cg.custom_id = oc.custom_id)" => 'price'
        // ))->where(array(
        // 'oc.order_id' => $order_id
        // ))->select();
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
            'o.coupon',
            'o.shipping_fee',
            'o.total_amount',
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