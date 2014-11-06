<?php

/**
 * Api Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class ApiAction extends Action {

    public function index() {
        $this->ajaxReturn(array(
            'status' => 0,
            'result' => '未知错误'
        ));
    }

    /**
     * 找回密码
     */
    public function find_password() {
        if ($this->isPost() || $this->isAjax()) {
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : $this->redirect('/');
            if (empty($phone) || empty($new_password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Member')->find_password($phone, $new_password));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户登录
     */
    public function login() {
        if ($this->isPost() || $this->isAjax()) {
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            if (empty($phone) || empty($password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Member')->login($phone, $password));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户注册
     */
    public function register() {
        if ($this->isPost() || $this->isAjax()) {
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            if (empty($phone) || empty($password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Member')->register($phone, $password));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 验证码
     */
    public function validate_code() {
        if ($this->isPost() || $this->isAjax()) {
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            if (empty($phone)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $validate_code = rand(1000, 9999);
            if (sendSms($phone, "您好，您的验证码是{$validate_code}【鲜果送】")) {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => $validate_code
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

}