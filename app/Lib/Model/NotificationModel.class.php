<?php

/**
 * fruit_notification 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class NotificationModel extends Model {

    /**
     * 添加消息
     *
     * @param string $title
     *            标题
     * @param string $content
     *            内容
     * @return array
     */
    public function addNotification($title, $content) {
        if ($this->add(array(
            'title' => $title,
            'content' => $content,
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
     * 删除消息
     *
     * @param array $id
     *            消息ID
     * @return array
     */
    public function deleteNotification(array $id) {
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
     * 获取消息总数
     *
     * @return int
     */
    public function getNotificationCount() {
        return (int) $this->count();
    }

    /**
     * 获取消息列表
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
    public function getNotificationList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS n ")->field(array(
            'n.*',
            "(SELECT send_time FROM " . M('send_history')->getTableName() . " WHERE notification_id = n.id ORDER BY id DESC LIMIT 1)" => 'send_time'
        ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新消息
     *
     * @param int $id
     *            消息ID
     * @param string $title
     *            标题
     * @param string $content
     *            内容
     * @return array
     */
    public function updateNotification($id, $title, $content) {
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'title' => $title,
            'content' => $content,
            'update_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '更新成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '添加失败'
            );
        }
    }

}