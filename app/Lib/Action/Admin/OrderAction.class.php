<?php

/**
 * 订单Action
 *
 * @author lzjjie
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
     * 订单一览
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $orderModel = D('Order');
            $total = $orderModel->getOrderCount();
            if ($total) {
                $rows = $orderModel->getOrderList($page, $pageSize, $order, $sort);
                foreach ($rows as &$value) {
                    $value['order_time'] = date("Y-m-d H:i:s", $value['order_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                }
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