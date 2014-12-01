<?php

/**
 * 订单Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderAction extends AdminAction {

    /**
     * 删除订单
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Order')->deleteOrder((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取订单详细
     */
    public function getOrderDetail() {
        if ($this->isAjax()) {
            $order_id = (isset($_POST['order_id']) && intval($_POST['order_id'])) ? intval($_POST['order_id']) : $this->redirect('/');
            $this->ajaxReturn(D('Order')->getOrderDetail($order_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 订单一览
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'order_id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $orderModel = D('Order');
            $total = $orderModel->getOrderCount($keyword);
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    return $v;
                }, $orderModel->getOrderList($page, $pageSize, $order, $sort, $keyword));
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->assign('keyword', $keyword);
            $this->display();
        }
    }

    public function print_order() {
        if ($this->isAjax()) {
            $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : $this->redirect('/');
            $this->ajaxReturn(array(
                'status' => true,
                'result' => D('Order')->printOrder($order_id)
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新订单状态
     */
    public function update_status() {
        $id = (isset($_GET['id']) && intval($_GET['id'])) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $status = (isset($_POST['status']) && intval($_POST['status'])) ? intval($_POST['status']) : $this->redirect('/');
            $this->ajaxReturn(D('Order')->updateOrderStatus($id, $status));
        } else {
            $this->assign('order', M('Order')->where(array(
                'order_id' => $id
            ))->find());
            $this->display();
        }
    }

}