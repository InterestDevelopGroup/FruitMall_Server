<?php

/**
 * 退货申请Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class ReturnsAction extends AdminAction {

    /**
     * 删除退货申请
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Returns')->deleteReturns((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 所有退货申请
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $returns = D('Returns');
            $total = $returns->getReturnsCount();
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    return $v;
                }, $returns->getReturnsList($page, $pageSize, $order, $sort));
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