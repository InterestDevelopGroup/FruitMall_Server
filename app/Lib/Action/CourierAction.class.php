<?php

/**
 * 送货人员Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CourierAction extends AdminAction {

    /**
     * 添加送货人员
     */
    public function add() {
        if ($this->isAjax()) {
            $real_name = isset($_POST['real_name']) ? trim($_POST['real_name']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $this->ajaxReturn(D('Courier')->addCourier($real_name, $phone));
        } else {
            $this->display();
        }
    }

    /**
     * 删除送货人员
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Courier')->deleteCourier((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 所有送货人员
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $courier = D('Courier');
            $total = $courier->getCourierCount();
            if ($total) {
                $rows = array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, $courier->getCourierList($page, $pageSize, $order, $sort));
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
     * 更新送货人员
     */
    public function update() {
        $id = (isset($_GET['id']) && intval($_GET['id'])) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $real_name = isset($_POST['real_name']) ? trim($_POST['real_name']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $this->ajaxReturn(D('Courier')->updateCourier($id, $real_name, $phone));
        } else {
            $this->assign('courier', M('Courier')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

}