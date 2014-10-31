<?php

/**
 * tea_feedback表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class FeedbackModel extends Model {

    /**
     * 删除用户反馈
     *
     * @param array $id
     *            用户反馈ID
     * @return array
     */
    public function deleteFeedback(array $id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            // 删除成功，提交事务
            $this->commit();
            return array(
                'status' => 1,
                'msg' => '删除用户反馈成功'
            );
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'msg' => '删除用户反馈失败'
            );
        }
    }

    /**
     * 获取用户反馈总数
     *
     * @return int
     */
    public function getFeedbackCount() {
        return (int) $this->count();
    }

    /**
     * 获取用户反馈列表
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
    public function getFeedbackList($page, $pageSize, $order, $sort) {
        return $this->field(array(
            'id',
            'content',
            'FROM_UNIXTIME(add_time)' => 'add_time'
        ))->order($order . " " . $sort)->limit(($page - 1) * $pageSize, $pageSize)->select();
    }

}