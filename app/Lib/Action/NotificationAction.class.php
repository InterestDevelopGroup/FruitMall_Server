<?php

/**
 * 推送消息Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class NotificationAction extends AdminAction {

    /**
     * 添加消息
     */
    public function add() {
        if ($this->isAjax()) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $this->ajaxReturn(D('Notification')->addNotification($title, $content));
        } else {
            $this->display();
        }
    }

    /**
     * 删除消息
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Notification')->deleteNotification((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 消息一览
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $notification = D('Notification');
            $total = $notification->getNotificationCount();
            if ($total) {
                $rows = array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, $notification->getNotificationList($page, $pageSize, $order, $sort));
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->display();
        }
    }

    /**
     * 推送
     */
    public function push() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $notification = M('Notification')->where(array(
                'id' => $id
            ))->find();
            $jPush_config = C('jPush');
            vendor('JPush.jpush');
            $jPush = new jpush($jPush_config['secret'], $jPush_config['appkey']);
            $content = json_encode(array(
                'n_builder_id' => 0,
                'n_title' => $notification['title'],
                'n_content' => $notification['content']
            ));
            $result = $jPush->send(time(), 4, '', 1, $content, 'android,ios');
            if ($result) {
                if (intval($result['errcode']) == 0) {
                    $this->ajaxReturn(D('SendHistory')->addSendHistory($id, $result['sendno'], $notification['title'], $notification['content']));
                } else {
                    $this->ajaxReturn(array(
                        'status' => true,
                        'msg' => $result['errmsg']
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '推送失败'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新消息
     */
    public function update() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $this->ajaxReturn(D('Notification')->updateNotification($id, $title, $content));
        } else {
            $this->assign('notification', M('Notification')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

}