<?php

/**
 * 分店Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class BranchAction extends AdminAction {

    /**
     * 添加分店
     */
    public function add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : $this->redirect('/');
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : $this->redirect('/');
            $shipping_address_id = isset($_POST['shipping_address_id']) ? (array) $_POST['shipping_address_id'] : $this->redirect('/');
            $courier_id = isset($_POST['courier_id']) ? (array) $_POST['courier_id'] : $this->redirect('/');
            $this->ajaxReturn(D('Branch')->addBranch($name, $admin_id, $remark, $shipping_address_id, $courier_id));
        } else {
            $this->assign('admin', M('AdminUser')->where(array(
                'type' => array(
                    'neq',
                    1
                )
            ))->select());
            $this->display();
        }
    }

    /**
     * 添加送货人员
     */
    public function add_courier() {
        if ($this->isAjax()) {
            $courier_id = isset($_POST['courier_id']) ? (array) ($_POST['courier_id']) : $this->redirect('/');
            if (M('BranchCourier')->where(array(
                'courier_id' => array(
                    'in',
                    $courier_id
                )
            ))->count()) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'result' => '该送货员已经隶属其他分店'
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => true,
                    'result' => M('Courier')->where(array(
                        'id' => array(
                            'in',
                            $courier_id
                        )
                    ))->select()
                ));
            }
        } else {
            import('ORG.Util.Page');
            $courier = M('Courier');
            $count = $courier->count();
            $page = new Page($count, 12);
            $page->setConfig('theme', "共&nbsp;&nbsp;%totalRow%&nbsp;&nbsp;%header%&nbsp;&nbsp;%nowPage%/%totalPage%页&nbsp;&nbsp;%upPage% %downPage% %first% %prePage% %linkPage% %nextPage%&nbsp;&nbsp;%end%");
            $page->setConfig('header', '个地址');
            $show = $page->show();
            $courierList = $courier->limit($page->firstRow, $page->listRows)->select();
            $this->assign('courierList', $courierList);
            $this->assign('count', ceil($count / 12));
            $this->assign('page', $show);
            $this->display();
        }
    }

    /**
     * 添加配送地址
     */
    public function add_shipping_address() {
        if ($this->isAjax()) {
            $shipping_address_id = isset($_POST['shipping_address_id']) ? (array) ($_POST['shipping_address_id']) : $this->redirect('/');
            if (M('BranchShippingAddress')->where(array(
                'shipping_address_id' => array(
                    'in',
                    $shipping_address_id
                )
            ))->count()) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'result' => '该地址已经隶属其他分店'
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => true,
                    'result' => M('ShippingAddress')->where(array(
                        'id' => array(
                            'in',
                            $shipping_address_id
                        )
                    ))->select()
                ));
            }
        } else {
            import('ORG.Util.Page');
            $shippingAddress = M('ShippingAddress');
            $count = $shippingAddress->count();
            $page = new Page($count, 12);
            $page->setConfig('theme', "共&nbsp;&nbsp;%totalRow%&nbsp;&nbsp;%header%&nbsp;&nbsp;%nowPage%/%totalPage%页&nbsp;&nbsp;%upPage% %downPage% %first% %prePage% %linkPage% %nextPage%&nbsp;&nbsp;%end%");
            $page->setConfig('header', '个地址');
            $show = $page->show();
            $shippingAddressList = $shippingAddress->limit($page->firstRow, $page->listRows)->select();
            $this->assign('shippingAddressList', $shippingAddressList);
            $this->assign('count', ceil($count / 12));
            $this->assign('page', $show);
            $this->display();
        }
    }

    /**
     * 删除分店
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Branch')->deleteBranch((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Ajax获取分店送货人员列表
     */
    public function getBranchCourier() {
        if ($this->isAjax()) {
            $branch_id = isset($_POST['branch_id']) ? intval($_POST['branch_id']) : $this->redirect('/');
            $this->ajaxReturn(D('BranchCourier')->getBranchCourierList($branch_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Ajax获取分店送货地址列表
     */
    public function getBranchShippingAddress() {
        if ($this->isAjax()) {
            $branch_id = isset($_POST['branch_id']) ? intval($_POST['branch_id']) : $this->redirect('/');
            $this->ajaxReturn(D('BranchShippingAddress')->getBranchShippingAddressList($branch_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 所有分店
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $branch = D('Branch');
            $total = $branch->getBranchCount($keyword, $this->admin_info);
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    return $v;
                }, $branch->getBranchList($page, $pageSize, $order, $sort, $keyword, $this->admin_info));
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

    /**
     * 套餐分店（更新）
     */
    public function update() {
        $id = (isset($_GET['id']) && intval($_GET['id'])) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : $this->redirect('/');
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : $this->redirect('/');
            $shipping_address_id = isset($_POST['shipping_address_id']) ? (array) $_POST['shipping_address_id'] : $this->redirect('/');
            $courier_id = isset($_POST['courier_id']) ? (array) $_POST['courier_id'] : $this->redirect('/');
            $this->ajaxReturn(D('Branch')->updateBranch($id, $name, $admin_id, $remark, $shipping_address_id, $courier_id));
        } else {
            $this->assign('admin', M('AdminUser')->where(array(
                'type' => array(
                    'neq',
                    1
                )
            ))->select());
            $this->assign('branch', M('Branch')->where(array(
                'id' => $id
            ))->find());
            $shipping_address_list = M('BranchShippingAddress')->table(M('BranchShippingAddress')->getTableName() . " AS bsa ")->join(array(
                " LEFT JOIN " . M('ShippingAddress')->getTableName() . " AS sa ON sa.id = bsa.shipping_address_id "
            ))->field(array(
                'sa.*'
            ))->where(array(
                'branch_id' => $id
            ))->select();
            $courier_list = M('BranchCourier')->table(M('BranchCourier')->getTableName() . " AS bc ")->join(array(
                " LEFT JOIN " . M('Courier')->getTableName() . " AS c ON bc.courier_id = c.id "
            ))->field(array(
                'c.*'
            ))->where(array(
                'branch_id' => $id
            ))->select();
            $this->assign('shipping_address_list', $shipping_address_list);
            $this->assign('courier_list', $courier_list);
            $shipping_address_id = array();
            $courier_id = array();
            foreach ($shipping_address_list as $v) {
                $shipping_address_id[] = intval($v['id']);
            }
            foreach ($courier_list as $v) {
                $courier_id[] = intval($v['id']);
            }
            $this->assign('shipping_address_id', json_encode($shipping_address_id));
            $this->assign('courier_id', json_encode($courier_id));
            $this->display();
        }
    }

}