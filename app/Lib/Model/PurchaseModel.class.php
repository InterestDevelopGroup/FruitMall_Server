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
                    goods_id, amount AS quantity, o.order_id, o.branch_id,
                    o.add_time AS order_time, {$now} AS add_time
                FROM
                    fruit_order_goods AS og
                LEFT JOIN
                    fruit_order AS o ON o.order_id = og.order_id
                WHERE
                    o.status = 2 AND
                    o.update_time > {$add_time}
                UNION ALL
                SELECT
                    pg.goods_id, (op.amount * pg.amount) AS quantity, o.order_id,
                    o.branch_id, o.add_time AS order_time, {$now} AS add_time
                FROM
                    fruit_order_package AS op
                LEFT JOIN
                    fruit_order AS o ON o.order_id = op.order_id
                LEFT JOIN
                    fruit_package_goods AS pg ON pg.package_id = op.package_id
                WHERE
                    o.status = 2 AND
                    o.update_time > {$add_time}
                UNION ALL
                SELECT
                    cg.goods_id, (cg.quantity * oc.amount) AS quantity, o.order_id,
                    o.branch_id, o.add_time AS order_time, {$now} AS add_time
                FROM
                    fruit_order_custom AS oc
                LEFT JOIN
                    fruit_order AS o ON o.order_id = oc.order_id
                LEFT JOIN
                    fruit_custom_goods AS cg ON cg.custom_id = oc.custom_id
                WHERE
                    o.status = 2 AND
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
                'last_update_time' => $add_time,
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
                    g.name, p.goods_id, SUM(p.quantity) AS amount
                FROM
                    fruit_purchase AS p
                LEFT JOIN
                    fruit_goods AS g ON p.goods_id = g.id
                WHERE
                    p.is_purchase = 0
                GROUP BY
                    p.goods_id
                LIMIT $offset, $pageSize";
        return $this->query($sql);
    }

}