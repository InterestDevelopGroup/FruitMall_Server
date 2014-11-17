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

}