<?php

/**
 * 投诉/反馈Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class FeedbackAction extends AdminAction {

    /**
     * 删除投诉/反馈
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Feedback')->deleteFeedback((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 投诉/反馈一览
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
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    return $v;
                }, $feedback->getFeedbackList($page, $pageSize, $order, $sort));
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