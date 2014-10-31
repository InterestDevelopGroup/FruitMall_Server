<?php

/**
 * 管理员 Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdministratorAction extends AdminAction {

    /**
     * 添加管理员
     */
    public function add() {
        if ($this->isAjax()) {
            $username = isset($_POST['username']) ? $_POST['username'] : $this->redirect('/');
            $password = isset($_POST['password']) ? $_POST['password'] : $this->redirect('/');
            $realname = isset($_POST['realname']) ? $_POST['realname'] : $this->redirect('/');
            $email = isset($_POST['email']) ? $_POST['email'] : $this->redirect('/');
            $desc = isset($_POST['desc']) ? $_POST['desc'] : $this->redirect('/');
            $adminUser = D('AdminUser');
            $this->ajaxReturn($adminUser->addAdministrator($username, $password, $realname, $email, $desc));
        } else {
            $this->display();
        }
    }

    /**
     * 删除管理员
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $adminUser = D('AdminUser');
            $this->ajaxReturn($adminUser->deleteAdministrator((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 管理员管理
     */
    public function management() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $admin = D('AdminUser');
            $total = $admin->getAdminCount();
            if ($total) {
                $rows = $admin->getAdminList($page, $pageSize, $order, $sort);
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->assign('type', $this->admin_info['type']);
            $this->display();
        }
    }

}
