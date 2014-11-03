<?php

/**
 * 管理员 Action
 *
 * @author Zonkee
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
            $this->ajaxReturn(D('AdminUser')->deleteAdministrator((array) $id));
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
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['last_time'] = $v['last_time'] ? date("Y-m-d H:i:s", $v['last_time']) : $v['last_time'];
                    return $v;
                }, $admin->getAdminList($page, $pageSize, $order, $sort));
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

    /**
     * 设置权限
     */
    public function set_privileges() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        $id || $this->redirect('/');
        if ($this->isAjax()) {
            $priv = isset($_POST['priv']) ? implode(',', $_POST['priv']) : $this->redirect('/');
            $this->ajaxReturn(D('AdminPriv')->setPriv($id, $priv));
        } else {
            $privileges = M('AdminUser')->table(M('AdminUser')->getTableName() . " AS au ")->join(array(
                " LEFT JOIN " . M('AdminPriv')->getTableName() . " AS ap ON au.id = ap.admin_id "
            ))->where(array(
                'admin_id' => $id
            ))->find();
            $privileges['priv'] = explode(',', $privileges['priv']);
            $this->assign('user_info', $privileges);
            $this->assign('_privileges', $privileges['priv']);
            $this->assign('privileges', C('priv'));
            $this->assign('language', C('priv_language'));
            $this->display();
        }
    }

    /**
     * 更新管理员状态
     */
    public function update() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $status = isset($_POST['status']) ? intval($_POST['status']) : $this->redirect('/');
            $this->ajaxReturn(D('AdminUser')->updateAdministrator($id, $status));
        } else {
            $this->redirect('/');
        }
    }

}
