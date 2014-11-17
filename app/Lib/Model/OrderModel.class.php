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
    public function addOrder($user_id, $address_id, $order, $shipping_time, $shipping_fee, $remark) {
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
            'og.amount',
            'g.name',
            'g.price',
            'g._price',
            'g.unit',
            'g.tag',
            'g.amount' => '_amount',
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
            'op.amount',
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
        return array(
            'order_goods' => $order_goods,
            'order_package' => $order_package
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
            'a._phone'
        ))->join(array(
            " LEFT JOIN " . M('Member')->getTableName() . " AS m ON o.user_id = m.id ",
            " LEFT JOIN " . M('Address')->getTableName() . " AS a ON o.address_id = a.address_id "
        ))->order("o." . $order . " " . $sort)->limit($offset, $pageSize)->select();
    }

}