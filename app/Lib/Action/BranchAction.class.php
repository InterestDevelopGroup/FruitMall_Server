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
            $shipping_addresses_id = isset($_POST['shipping_addresses_id']) ? (array) $_POST['shipping_addresses_id'] : $this->redirect('/');
            $courier_id = isset($_POST['courier_id']) ? (array) $_POST['courier_id'] : $this->redirect('/');
            $this->ajaxReturn(D('Branch')->addBranch($name, $admin_id, $remark, $shipping_addresses_id, $courier_id));
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
     * 添加配送地址
     */
    public function add_shipping_address() {
        if ($this->isAjax()) {
            // $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            // $this->ajaxReturn(array(
            // 'status' => true,
            // 'goods' => M('Goods')->field(array(
            // 'id',
            // 'name',
            // 'thumb'
            // ))->where(array(
            // 'id' => $goods_id
            // ))->find()
            // ));
        } else {
            import('ORG.Util.Page');
            $shippingAddress = M('ShippingAddress');
            $count = $shippingAddress->count();
            $page = new Page($count, 1);
            $page->setConfig('theme', "共&nbsp;&nbsp;%totalRow%&nbsp;&nbsp;%header%&nbsp;&nbsp;%nowPage%/%totalPage%页&nbsp;&nbsp;%upPage% %downPage% %first% %prePage% %linkPage% %nextPage%&nbsp;&nbsp;%end%");
            $page->setConfig('header', '个地址');
            $show = $page->show();
            $shippingAddressList = $shippingAddress->limit($page->firstRow, $page->listRows)->select();
            $this->assign('shippingAddressList', $shippingAddressList);
            $this->assign('count', ceil($count / 1));
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
     * 所有分店
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $branch = D('Branch');
            $total = $branch->getBranchCount();
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    return $v;
                }, $branch->getBranchList($page, $pageSize, $order, $sort));
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
     * 套餐分店（更新）
     */
    public function update() {
        $id = (isset($_GET['id']) && intval($_GET['id'])) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : $this->redirect('/');
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : $this->redirect('/');
            $shipping_addresses_id = isset($_POST['shipping_addresses_id']) ? (array) $_POST['shipping_addresses_id'] : $this->redirect('/');
            $courier_id = isset($_POST['courier_id']) ? (array) $_POST['courier_id'] : $this->redirect('/');
            $this->ajaxReturn(D('Branch')->updateBranch($id, $name, $admin_id, $remark, $shipping_addresses_id, $courier_id));
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

}