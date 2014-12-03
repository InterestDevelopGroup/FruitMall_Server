<?php

/**
 * fruit_returns 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class ReturnsModel extends Model {

    /**
     * 添加退货申请
     *
     * @param int $user_id
     *            用户ID
     * @param string $order_number
     *            订单号
     * @param string $reason
     *            退货原因
     * @param string $image_1
     *            图片1
     * @param string $image_2
     *            图片2
     * @param string $image_3
     *            图片3
     * @param string $postscript
     *            补充说明
     * @return array
     */
    public function addReturns($user_id, $order_number, $reason, $image_1, $image_2, $image_3, $postscript) {
        if (!M('Order')->where(array(
            'order_number' => $order_number,
            'status' => 3
        ))->count()) {
            return array(
                'status' => 0,
                'result' => '该状态的订单不能申请退货'
            );
        }
        $image_1 = $image_1 ? base64Code2Image($image_1) : $image_1;
        $image_2 = $image_2 ? base64Code2Image($image_2) : $image_2;
        $image_3 = $image_3 ? base64Code2Image($image_3) : $image_3;
        if ($this->add(array(
            'user_id' => $user_id,
            'order_number' => $order_number,
            'reason' => $reason,
            'image_1' => $image_1,
            'image_2' => $image_2,
            'image_3' => $image_3,
            'postscript' => $postscript,
            'add_time' => time()
        ))) {
            return array(
                'status' => 1,
                'result' => '申请成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '申请失败'
            );
        }
    }

    /**
     * 删除退货申请
     *
     * @param array $id
     *            退货申请ID
     * @return array
     */
    public function deleteReturns(array $id) {
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
     * 获取退货申请总数
     *
     * @return int
     */
    public function getReturnsCount() {
        return (int) $this->count();
    }

    /**
     * 获取退货申请列表
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
    public function getReturnsList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS r ")->join(array(
            " LEFT JOIN " . M('Member')->getTableName() . " AS m ON r.user_id = m.id "
        ))->field(array(
            'r.*',
            'm.username'
        ))->order("r." . $order . " " . $sort)->limit($offset, $pageSize)->select();
    }

}