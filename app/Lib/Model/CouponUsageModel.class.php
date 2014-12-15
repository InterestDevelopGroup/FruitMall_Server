<?php

/**
 * fruit_coupon_usage 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CouponUsageModel extends Model {

    /**
     * 添加规则
     *
     * @param string $description
     *            描述
     * @param int $score
     *            面值
     * @param string $condition
     *            使用条件
     * @return array
     */
    public function addCouponUsage($description, $score, $condition) {
        if ($this->add(array(
            'description' => $description,
            'condition' => $condition,
            'score' => $score,
            'add_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '添加成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '添加失败'
            );
        }
    }

    /**
     * 删除规则
     *
     * @param array $id
     *            规则ID
     * @return array
     */
    public function deleteUsage(array $id) {
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            return array(
                'status' => true,
                'msg' => '删除成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '删除失败'
            );
        }
    }

    /**
     * 获取规则总数
     *
     * @return int
     */
    public function getCouponUsageCount() {
        return (int) $this->count();
    }

    /**
     * 获取规则列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param string $order
     *            排序字段
     * @param string $sort
     *            排序方式
     */
    public function getCouponUsageList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新规则
     *
     * @param int $id
     *            规则ID
     * @param string $description
     *            描述
     * @param int $score
     *            面值
     * @param string $condition
     *            使用条件
     * @return array
     */
    public function updateCouponUsage($id, $description, $score, $condition) {
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'description' => $description,
            'score' => $score,
            'condition' => $condition,
            'update_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '更新成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '更新失败'
            );
        }
    }

}