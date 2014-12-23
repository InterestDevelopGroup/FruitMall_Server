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
     * 取消订单一览
     */
    public function cancels() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $courier = isset($_GET['courier']) ? intval($_GET['courier']) : 0;
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'order_id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $orderModel = D('Order');
            $total = $orderModel->getOrderCount($keyword, $courier, 3);
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    return $v;
                }, $orderModel->getOrderList($page, $pageSize, $order, $sort, $keyword, $courier, 3));
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->assign('keyword', $keyword);
            $this->assign('courier', $courier);
            $this->assign('courier_list', M('Courier')->select());
            $this->display();
        }
    }

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
     * 指定送货员
     */
    public function distribute() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? array_map(function ($value) {
                $value = intval($value);
                return $value;
            }, $_POST['id']) : $this->redirect('/');
            $branch_id = isset($_POST['branch_id']) ? intval($_POST['branch_id']) : $this->redirect('/');
            $courier_id = isset($_POST['courier_id']) ? intval($_POST['courier_id']) : $this->redirect('/');
            $this->ajaxReturn(D('Order')->updateOrderBranchAndCourier($id, $branch_id, $courier_id));
        } else {
            $id = isset($_GET['id']) ? array_map(function ($value) {
                $value = intval($value);
                return $value;
            }, explode(',', $_GET['id'])) : $this->redirect('/');
            $this->assign('id', json_encode($id));
            $this->assign('branch', M('Branch')->select());
            $this->assign('courier', M('Courier')->select());
            $this->display();
        }
    }

    /**
     * ajax根据分店获取送货员
     */
    public function getCourierByBranchId() {
        if ($this->isAjax()) {
            $branch_id = isset($_POST['branch_id']) ? intval($_POST['branch_id']) : $this->redirect('/');
            $this->ajaxReturn(M('BranchCourier')->table(M('BranchCourier')->getTableName() . " AS bc ")->join(array(
                " LEFT JOIN " . M('Courier')->getTableName() . " AS c ON bc.courier_id = c.id "
            ))->field(array(
                'c.id',
                'c.real_name'
            ))->where(array(
                'bc.branch_id' => $branch_id
            ))->select());
        } else {
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
     * 历史订单一览
     */
    public function history() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $status = isset($_GET['status']) ? intval($_GET['status']) : 0;
        $courier = isset($_GET['courier']) ? intval($_GET['courier']) : 0;
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'order_id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $orderModel = D('Order');
            $total = $orderModel->getOrderCount($keyword, $courier, 1, $status);
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    return $v;
                }, $orderModel->getOrderList($page, $pageSize, $order, $sort, $keyword, $courier, 1, $status));
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->assign('keyword', $keyword);
            $this->assign('status', $status);
            $this->assign('courier', $courier);
            $this->assign('courier_list', M('Courier')->select());
            $this->display();
        }
    }

    /**
     * 新订单一览
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $courier = isset($_GET['courier']) ? intval($_GET['courier']) : 0;
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'order_id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $orderModel = D('Order');
            $total = $orderModel->getOrderCount($keyword, $courier, 2);
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    return $v;
                }, $orderModel->getOrderList($page, $pageSize, $order, $sort, $keyword, $courier, 2));
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->assign('keyword', $keyword);
            $this->assign('courier', $courier);
            $this->assign('courier_list', M('Courier')->select());
            $this->display();
        }
    }

    /**
     * 打印订单
     */
    public function print_order() {
        if ($this->isAjax()) {
            $order_id = isset($_POST['order_id']) ? (array) $_POST['order_id'] : $this->redirect('/');
            $this->ajaxReturn(array(
                'status' => true,
                'result' => D('Order')->printOrder($order_id)
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 确认订单
     */
    public function sure() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Order')->updateOrderStatus((array) $id, 2));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新订单状态
     */
    public function update_status() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? array_map(function ($value) {
                $value = intval($value);
                return $value;
            }, $_POST['id']) : $this->redirect('/');
            $status = (isset($_POST['status']) && intval($_POST['status'])) ? intval($_POST['status']) : $this->redirect('/');
            $this->ajaxReturn(D('Order')->updateOrderStatus((array) $id, $status));
        } else {
            $id = isset($_GET['id']) ? array_map(function ($value) {
                $value = intval($value);
                return $value;
            }, explode(',', $_GET['id'])) : $this->redirect('/');
            $this->assign('id', json_encode($id));
            $this->display();
        }
    }

}