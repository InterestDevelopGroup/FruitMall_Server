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
     * ajax获取采购详细
     */
    public function getPurchaseDetail() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $this->ajaxReturn(D('Purchase')->getPurchaseDetail($goods_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 采购任务
     */
    public function purchase() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $purchase = D('Purchase');
            $total = $purchase->getPurchaseCount();
            if ($total) {
                $rows = $purchase->getPurchaseList($page, $pageSize, $order, $sort);
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
     * 打印采购任务
     */
    public function print_purchase() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? array_map(function ($value) {
                return intval($value);
            }, $_POST['goods_id']) : $this->redirect('/');
            $this->ajaxReturn(array(
                'status' => true,
                'result' => D('Purchase')->printPurchase($goods_id)
            ));
        } else {
            $this->redirect('/');
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

    /**
     * 确认采购
     */
    public function sure_purchase() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? array_map(function ($value) {
                return intval($value);
            }, $_POST['goods_id']) : $this->redirect('/');
            $this->ajaxReturn(D('Purchase')->surePurchase($goods_id));
        } else {
            $this->redirect('/');
        }
    }

}