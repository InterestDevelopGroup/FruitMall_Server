<?php

/**
 * Api Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class ApiAction extends Action {

    /**
     * 小分类
     */
    public function child_category() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $p_cate_id = (isset($_POST['p_cate_id']) && intval($_POST['p_cate_id'])) ? intval($_POST['p_cate_id']) : null;
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => D('ChildCategory')->_getChildCategoryList($offset, $pagesize, $p_cate_id, $keyword)
            ));
        } else {
            $this->redirect('/');
        }
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
     * 商品列表
     */
    public function goods() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $p_cate_id = (isset($_POST['p_cate_id']) && intval($_POST['p_cate_id'])) ? intval($_POST['p_cate_id']) : null;
            $c_cate_id = (isset($_POST['c_cate_id']) && intval($_POST['c_cate_id'])) ? intval($_POST['c_cate_id']) : null;
            $tag = (isset($_POST['tag']) && isset($_POST['tag'])) ? intval($_POST['tag']) : null;
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => D('Goods')->_getGoodsList($offset, $pagesize, $p_cate_id, $c_cate_id, $tag, $keyword)
            ));
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
     * 大分类
     */
    public function parent_category() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => D('ParentCategory')->_getParentCategoryList($offset, $pagesize, $keyword)
            ));
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
     * 商品标签
     */
    public function tag() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $goods_amount = (isset($_POST['goods_amount']) && intval($_POST['goods_amount'])) ? intval($_POST['goods_amount']) : null;
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => D('Tag')->_getTagList($offset, $pagesize, $goods_amount, $keyword)
            ));
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