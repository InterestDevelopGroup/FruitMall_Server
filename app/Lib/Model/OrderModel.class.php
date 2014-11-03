<?php

/**
 * tea_order表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderModel extends Model {

    /**
     * 添加订单
     *
     * @param array $order
     *            订单详细信息
     * @return boolean
     */
    public function addOrder(array $order) {
        $order_time = time();
        foreach ($order as &$v) {
            $v = ob2ar($v);
            $v['order_time'] = $order_time;
        }
        if ($this->addAll($order)) {
            return true;
        } else {
            return false;
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
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            return array(
                'status' => true,
                'msg' => '删除订单成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '删除订单失败'
            );
        }
    }

    /**
     * 获取订单总数
     *
     * @return int
     */
    public function getOrderCount() {
        return (int) $this->count();
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
     * @return array
     */
    public function getOrderList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS o ")->join(array(
            " LEFT JOIN " . M('Member')->getTableName() . " AS m ON o.user_id = m.id "
        ))->field(array(
            'o.id',
            'o.user_id',
            'o.order_number',
            'o.goods_id',
            'o.goods_name',
            'o.goods_price',
            'o.amount',
            'o.unit',
            'o.status',
            'o.consignee',
            'o.phone',
            'o.zip',
            'o.address',
            'o.order_time',
            'o.update_time',
            'o.total_price',
            'm.account'
        ))->order("o." . $order . " " . $sort)->limit($offset, $pageSize)->select();
    }

}