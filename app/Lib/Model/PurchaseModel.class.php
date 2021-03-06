<?php

/**
 * fruit_purchase 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class PurchaseModel extends Model {

    /**
     * 抓取采购数据
     */
    public function addPurchase() {
        $orders = $this->order("add_time DESC")->find();
        $add_time = $orders['add_time'] ? $orders['add_time'] : 0;
        $now = time();
        $sql = "SELECT
                    og.goods_id, og.order_quantity AS quantity, og.order_id,
                    o.branch_id, o.add_time AS order_time, {$now} AS add_time
                FROM
                    fruit_order_goods AS og
                LEFT JOIN fruit_order AS o ON o.order_id = og.order_id
                WHERE
                    o.status = 2 AND
                    o.update_time > {$add_time}
                UNION ALL
                SELECT
                    opg.goods_id, (og.order_quantity * opg.goods_quantity) AS quantity,
                    o.order_id, o.branch_id, o.add_time AS order_time, {$now} AS add_time
                FROM
                    fruit_order_package AS og
                LEFT JOIN fruit_order AS o ON o.order_id = og.order_id
                LEFT JOIN fruit_order_package_goods AS opg ON og.order_id = opg.order_id
                WHERE
                    o.status = 2 AND
                    o.update_time > {$add_time}
                UNION ALL
                SELECT
                    ocg.goods_id, (oc.order_quantity * ocg.goods_quantity) AS quantity,
                    o.order_id, o.branch_id, o.add_time AS order_time, {$now} AS add_time
                FROM
                    fruit_order_custom AS oc
                LEFT JOIN fruit_order AS o ON o.order_id = oc.order_id
                LEFT JOIN fruit_order_custom_goods AS ocg ON oc.order_id = ocg.order_id
                WHERE
                    o. status = 2 AND
                    o.update_time > {$add_time}";
        $result = $this->query($sql);
        foreach ($result as $k => &$v) {
            if ($this->where(array(
                'order_id' => $v['order_id'],
                'goods_id' => $v['goods_id']
            ))->count()) {
                $this->where(array(
                    'order_id' => $v['order_id'],
                    'goods_id' => $v['goods_id']
                ))->save(array(
                    'quantity' => $v['quantity'],
                    'branch_id' => $v['branch_id'],
                    'order_time' => $v['order_time'],
                    'add_time' => $v['add_time']
                ));
                array_splice($result, $k, 1);
            }
        }
        if ($result) {
            $log = json_encode(array(
                'last_update_time' => date("Y-m-d H:i:s", $add_time),
                'amount' => count($result)
            ));
            if ($this->addAll($result)) {
                write_log("success : {$log}");
            } else {
                write_log("failed : {$log}");
            }
        }
        exit();
    }

    /**
     * 获取导出的商品数据
     */
    public function export() {
        return $this->table($this->getTableName() . " AS p ")->field(array(
            'g.name',
            'p.goods_id'
        ))->join(array(
            " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON p.goods_id = g.id "
        ))->where(array(
            'p.is_purchase' => 0
        ))->group("p.goods_id")->select();
    }

    /**
     * 订单状态改变时删除采购任务
     *
     * @param array $order_id
     *            订单ID
     * @return boolean
     */
    public function deletePurchaseWhenOrderStatusChange(array $order_id) {
        if (!$this->where(array(
            'order_id' => array(
                'in',
                $order_id
            ),
            'is_purchase' => 0
        ))->count()) {
            return true;
        }
        if ($this->where(array(
            'order_id' => array(
                'in',
                $order_id
            ),
            'is_purchase' => 0
        ))->delete()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据分店ID和商品ID获取分店的商品数量
     *
     * @param int $branch_id
     *            分店ID
     * @param int $goods_id
     *            商品ID
     * @return int
     */
    public function getPerBranchAmount($branch_id, $goods_id) {
        $result = $this->field(array(
            'SUM(quantity)' => 'amount'
        ))->where(array(
            'goods_id' => $goods_id,
            'branch_id' => $branch_id,
            'is_purchase' => 0
        ))->select();
        return $result[0]['amount'] ? $result[0]['amount'] : 0;
    }

    /**
     * 获取采购总数
     *
     * @return int
     */
    public function getPurchaseCount() {
        $sql = "SELECT
                    goods_id
                FROM
                    fruit_purchase
                WHERE
                    is_purchase = 0
                GROUP BY
                    goods_id";
        return count($this->query($sql));
    }

    /**
     * 获取采购任务详细
     *
     * @param int $goods_id
     *            商品ID
     */
    public function getPurchaseDetail($goods_id) {
        return $this->table($this->getTableName() . " AS p ")->field(array(
            "SUM(quantity)" => 'quantity',
            'b.name' => 'branch'
        ))->join(array(
            " LEFT JOIN " . M('Branch')->getTableName() . " AS b ON b.id = p.branch_id "
        ))->where(array(
            'goods_id' => $goods_id,
            'is_purchase' => 0
        ))->group('branch_id')->select();
    }

    /**
     * 获取采购列表
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
    public function getPurchaseList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    g.name, p.goods_id, SUM(p.quantity) AS amount, p.branch_id,
                    (SELECT
                        name
                    FROM " . M('Branch')->getTableName() . "
                    WHERE
                        id = p.branch_id) AS branch_name
                FROM
                    fruit_purchase AS p
                LEFT JOIN
                    fruit_goods AS g ON p.goods_id = g.id
                WHERE
                    p.is_purchase = 0
                GROUP BY
                    p.goods_id, p.branch_id
                LIMIT $offset, $pageSize";
        $result = $this->query($sql);
        return $result;
    }

    /**
     * 打印商品列表
     *
     * @param array $goods_id
     *            商品ID
     * @return array
     */
    public function printPurchase(array $goods_id) {
        $result = $this->table($this->getTableName() . " AS p ")->field(array(
            'goods_id',
            'g.name' => 'goods_name',
            'SUM(quantity)' => 'quantity'
        ))->join(" LEFT JOIN " . M('Goods')->getTableName() . " AS g ON p.goods_id = g.id ")->where(array(
            "goods_id" => array(
                'in',
                $goods_id
            ),
            'p.is_purchase' => 0
        ))->group("goods_id")->select();
        foreach ($result as &$v) {
            $v['branch_list'] = $this->table($this->getTableName() . " AS p ")->field(array(
                "SUM(quantity)" => 'quantity',
                'b.name' => 'branch'
            ))->join(array(
                " LEFT JOIN " . M('Branch')->getTableName() . " AS b ON b.id = p.branch_id "
            ))->where(array(
                'goods_id' => intval($v['goods_id']),
                'is_purchase' => 0
            ))->group("branch_id")->order(null)->select();
        }
        return $result;
    }

    /**
     * 确认订单
     *
     * @param array $goods_id
     *            商品ID
     * @return array
     */
    public function surePurchase(array $goods_id) {
        if ($this->where(array(
            'goods_id' => array(
                'in',
                $goods_id
            )
        ))->save(array(
            'is_purchase' => 1
        ))) {
            return array(
                'status' => true,
                'msg' => '确认成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '确认失败'
            );
        }
    }

}