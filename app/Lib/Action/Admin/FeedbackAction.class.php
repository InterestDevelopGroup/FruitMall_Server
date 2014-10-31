<?php

/**
 * 用户反馈Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class FeedbackAction extends AdminAction {

    /**
     * 删除用户反馈
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $feedback = D('Feedback');
            $this->ajaxReturn($feedback->deleteFeedback((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 查看用户反馈详细内容
     */
    public function detail() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        $feedback = M('Feedback');
        $this->assign('content', $feedback->field('content')->where("id = {$id}")->find());
        $this->display();
    }

    /**
     * 用户反馈一览
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $feedback = D('Feedback');
            $total = $feedback->getFeedbackCount();
            if ($total) {
                $rows = $feedback->getFeedbackList($page, $pageSize, $order, $sort);
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

}