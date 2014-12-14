<?php

/**
 * 任务管理Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class TaskAction extends AdminAction {

    /**
     * 采购任务
     */
    public function purchase() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $_order = D('Order');
            $total = $_order->getPurchaseCount();
            if ($total) {
                $rows = $_order->getPurchaseList($page, $pageSize, $order, $sort);
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
     * 退货任务
     */
    public function returns() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            // $tag = D('Tag');
            // $total = $tag->getTagCount();
            // if ($total) {
            // $rows = array_map(function ($v) {
            // $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
            // $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
            // return $v;
            // }, $tag->getTagList($page, $pageSize, $order, $sort));
            // } else {
            // $rows = null;
            // }
            // $this->ajaxReturn(array(
            // 'Rows' => $rows,
            // 'Total' => $total
            // ));
        } else {
            $this->display();
        }
    }

    /**
     * 送货任务
     */
    public function shipping() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            // $tag = D('Tag');
            // $total = $tag->getTagCount();
            // if ($total) {
            // $rows = array_map(function ($v) {
            // $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
            // $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
            // return $v;
            // }, $tag->getTagList($page, $pageSize, $order, $sort));
            // } else {
            // $rows = null;
            // }
            // $this->ajaxReturn(array(
            // 'Rows' => $rows,
            // 'Total' => $total
            // ));
        } else {
            $this->display();
        }
    }

}