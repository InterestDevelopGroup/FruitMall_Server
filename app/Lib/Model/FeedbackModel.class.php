<?php

/**
 * fruit_feedback 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class FeedbackModel extends Model {

    /**
     * 添加用户反馈
     *
     * @param int $user_id
     *            用户ID
     * @param string $order_number
     *            订单号
     * @param int $shipping_service
     *            送货服务（0：踩，1：赞）
     * @param int $quality
     *            水果质量（0：踩，1：赞）
     * @param int $price
     *            水果价格（0：踩，1：赞）
     * @param string $postscript
     *            补充说明
     * @return array
     */
    public function addFeedback($user_id, $order_number, $shipping_service, $quality, $price, $postscript) {
        if ($this->add(array(
            'user_id' => $user_id,
            'order_number' => $order_number,
            'shipping_service' => $shipping_service,
            'quality' => $quality,
            'price' => $price,
            'postscript' => $postscript,
            'add_time' => time()
        ))) {
            return array(
                'status' => 1,
                'result' => '反馈成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '反馈失败'
            );
        }
    }

    /**
     * 删除投诉/反馈
     *
     * @param array $id
     *            投诉/反馈ID
     * @return array
     */
    public function deleteFeedback(array $id) {
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
     * 获取投诉/反馈总数
     *
     * @return int
     */
    public function getFeedbackCount() {
        return (int) $this->count();
    }

    /**
     * 获取投诉/反馈列表
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
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS f ")->join(array(
            " LEFT JOIN " . M('Member')->getTableName() . " AS m ON f.user_id = m.id "
        ))->field(array(
            'f.*',
            'm.username',
            "(SELECT
                c.real_name
            FROM
                " . M('Order')->getTableName() . " AS o
            LEFT JOIN
                " . M('Courier')->getTableName() . " AS c ON o.courier_id = c.id
            WHERE
                o.order_number = f.order_number)" => 'courier_name'
        ))->order("f." . $order . " " . $sort)->limit($offset, $pageSize)->select();
    }

}