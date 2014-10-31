<?php

/**
 * 登录/退出 Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class LoginAction extends Action {

    /**
     * 修改密码
     */
    public function chpwd() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $password = isset($_POST['password']) ? $_POST['password'] : $this->redirect('/');
            $adminUser = D('AdminUser');
            $this->ajaxReturn($adminUser->changePassword($id, $password));
        } else {
            $this->assign('adminId', $id);
            $this->display();
        }
    }

    /**
     * 登录
     */
    public function index() {
        if ($this->isPost()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $adminUser = D('AdminUser');
            $check = $adminUser->auth($username, $password);
            if ($check['status']) {
                if ($check['admin_info']['status']) {
                    session('admin_info', $check['admin_info']);
                    $adminUser->where("id = {$check['admin_info']['id']}")->save(array(
                        'last_time' => time()
                    ));
                    $this->redirect(U('/'));
                } else {
                    vendor('Message.Message');
                    Message::showMsg('对不起！您的帐号已经被禁用，请联系系统管理员！', U('/login'));
                }
            } else {
                vendor('Message.Message');
                Message::showMsg($check['msg'], U('/login'));
            }
        } else {
            if (session('admin_info')) {
                vendor('Message.Message');
                Message::showMsg('您已经登陆了');
            } else {
                $this->display();
            }
        }
    }

    /**
     * 安全退出
     */
    public function logout() {
        session('admin_info', null);
        $this->redirect('/login');
    }

}