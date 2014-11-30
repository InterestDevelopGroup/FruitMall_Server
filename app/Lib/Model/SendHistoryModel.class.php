<?php

/**
 * fruit_send_history 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class SendHistoryModel extends Model {

    /**
     * 添加发送记录
     *
     * @param int $notification_id
     *            消息ID
     * @param int $sendno
     *            发送编号
     * @param string $title
     *            标题
     * @param string $content
     *            内容
     * @return array
     */
    public function addSendHistory($notification_id, $sendno, $title, $content) {
        if ($this->add(array(
            'sendno' => $sendno,
            'notification_id' => $notification_id,
            'title' => $title,
            'content' => $content,
            'send_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '推送成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '推送失败'
            );
        }
    }

    /**
     * 获取发送历史列表
     *
     * @param int $user_id
     *            用户ID
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     */
    public function getSendHistory($user_id, $offset, $pagesize) {
        $user_info = M('Member')->where(array(
            'id' => $user_id
        ))->find();
        return $this->where(array(
            'send_time' => array(
                'gt',
                intval($user_info['register_time'])
            )
        ))->limit($offset, $pagesize)->select();
    }

}