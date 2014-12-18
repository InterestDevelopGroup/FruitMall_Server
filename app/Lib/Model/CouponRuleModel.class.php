<?php

/**
 * fruit_coupon_rule 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CouponRuleModel extends Model {

    /**
     * 获取水果劵规则列表（API）
     *
     * @param int $offset
     *            偏移量
     * @param int $pageSize
     *            条数
     * @return array
     */
    public function _getCouponRuleList($offset, $pageSize) {
        return $this->limit($offset, $pageSize)->select();
    }

    /**
     * 添加规则
     *
     * @param string $description
     *            描述
     * @param int $type
     *            类型（1：注册，2：推荐，3：满X送N）
     * @param int $score
     *            面值
     * @param string $condition
     *            X值（只有type=3时才传此值）
     * @param string $expire_time
     *            有效期（不传则为永久有效）
     * @return array
     */
    public function addCouponRule($description, $type, $score, $condition, $expire_time) {
        if ($type != 3) {
            if ($this->where(array(
                'type' => $type
            ))->count()) {
                return array(
                    'status' => false,
                    'msg' => '该类型的规则只能添加一条'
                );
            }
        }
        $data = array(
            'description' => $description,
            'type' => $type,
            'score' => $score,
            'add_time' => time()
        );
        strlen($condition) && $data['condition'] = intval($condition);
        strlen($expire_time) && $data['expire_time'] = intval($expire_time);
        if ($this->add($data)) {
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
     * 获取规则总数
     *
     * @return int
     */
    public function getCouponRuleCount() {
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
    public function getCouponRuleList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 删除规则
     *
     * @param array $id
     *            规则ID
     * @return array
     */
    public function deleteRule(array $id) {
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
     * 更新规则
     *
     * @param int $id
     *            规则ID
     * @param string $description
     *            描述
     * @param int $type
     *            类型（1：注册，2：推荐，3：满X送N）
     * @param int $score
     *            面值
     * @param string $condition
     *            X值（只有type=3时才传此值）
     * @param string $expire_time
     *            有效期（不传则为永久有效）
     * @return array
     */
    public function updateCouponRule($id, $description, $type, $score, $condition, $expire_time) {
        if ($type != 3) {
            if ($this->where(array(
                'type' => $type,
                'id' => array(
                    'neq',
                    $id
                )
            ))->count()) {
                return array(
                    'status' => false,
                    'msg' => '该类型的规则只能添加一条'
                );
            }
        }
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'description' => $description,
            'type' => $type,
            'score' => $score,
            'condition' => strlen($condition) ? intval($condition) : null,
            'expire_time' => strlen($expire_time) ? intval($expire_time) : null,
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