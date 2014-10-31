<?php
/**
 * 会员Action
 *
 */
class MemberAction extends CommonAction {

    /**
     * 登录页面
     */
    public function login() {
        // 渲染分类
        $this->render_category();
        $this->display();
    }

    public function ajax_login() {
        if ($this->isPost() || $this->isAjax()) {
            $account = isset($_POST['account']) ? trim($_POST['account']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $member = M('Member');
            $is_exists = $member->where("account = \"{$account}\" AND password = \"" . md5($password) . "\"")->count();
            if ($is_exists) {
                $result = $member->field(array(
                    'id',
                    'account',
                    'phone',
                    'wechat',
                    'avatar',
                    'sex',
                    'email',
                    'FROM_UNIXTIME(register_time)' => 'register_time',
                    'last_time'
                ))->where("account = \"{$account}\" AND password = \"" . md5($password) . "\"")->find();
                $result['password'] = md5($password);
                $result['last_time'] = $result['last_time'] ? date("Y-m-d H:i:s", $result['last_time']) : $result['last_time'];
                // 更新用户上一次登录的时间，开启事务
                $member->startTrans();
                if ($member->where("id = {$result['id']}")->save(array(
                    'last_time' => time()
                ))) {
                    // 更新成功，提交事务
                    $member->commit();
                    $_SESSION['member_info'] = $result;
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => '登录成功'
                    ));
                } else {
                    // 更新失败，回滚事务
                    $member->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '更新用户上一次登录时间失败'
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '用户名或密码不正确'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 注册页面
     */
    public function register() {
        // 渲染分类
        $this->render_category();
        $this->display();
    }

    public function ajax_register() {
        if ($this->isPost() || $this->isAjax()) {
            $account = isset($_POST['account']) ? trim($_POST['account']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $member = M('Member');
            $is_exists = $member->where("account = \"{$account}\"")->count();
            if ($is_exists) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '用户名已经存在'
                ));
            }
            $data = array(
                'account' => $account,
                'password' => md5($password),
                'phone' => $phone,
                'register_time' => time()
            );
            $member_id = $member->add($data);
            // 开启事务
            $member->startTrans();
            if ($member_id) {
                $member_info = $member->where('id = ' . $member_id)->find();
                $_SESSION['member_info'] = $member_info;
                // 添加成功，提交事务
                $member->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '注册成功'
                ));
            } else {
                // 添加失败，回滚事务
                $member->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    // 退出
    public function logout() {
        if ($_SESSION['member_info']) {
            unset($_SESSION['member_info']);
        }
        if ($_COOKIE['teamall']) {
            setcookie('teamall', '', time() - 3600, '/');
        }
        $this->redirect('/');
    }

    /**
     * 更多页面
     */
    public function more() {
        // 渲染分类
        $this->render_category();
        // 渲染客服
        $this->render_service();
        // 我的发布
        if ($_SESSION['member_info']['id']) {
            $member_id = $_SESSION['member_info']['id'];
            $publish_info = M('Publish')->where('user_id = ' . $member_id)->order('publish_time desc')->limit(3)->select();
            foreach ($publish_info as $key => $val) {
                $publish_info[$key]['publish_time'] = date('Y-m-d', $val['publish_time']);
            }
            $this->assign('publish_info', $publish_info);
        }
        // 我的收藏
        if ($_SESSION['member_info']['id']) {
            $member_id = $_SESSION['member_info']['id'];
            $collection_info = M('Collection')->table('tea_collection c')->join('tea_goods g on g.id = c.collection_id')->join('tea_member m on m.id = c.user_id')->field('c.id as coll_id,m.*,g.*,g.id as goods_id')->where('c.type = 1 and c.user_id = ' . $member_id)->limit(3)->order('c.add_time desc')->select();
            $this->assign('collection_info', $collection_info);
        }
        // 我的购物车
        if ($_SESSION['member_info']['id']) {
            $member_id = $_SESSION['member_info']['id'];
            $cart_info = M('Cart')->table('tea_cart c')->join('tea_goods g on g.id = c.goods_id')->field('g.*')->where('c.user_id = ' . $member_id)->limit(3)->order('c.add_time desc')->select();
            $this->assign('cart_info', $cart_info);
        }
        // 收货地址
        if ($_SESSION['member_info']['id']) {
            $member_id = $_SESSION['member_info']['id'];
            $addr_info = M('Address')->where('user_id = ' . $member_id)->find();
            $this->assign('addr_info', $addr_info);
        }
        $this->display();
    }

    // 编辑个人信息
    public function edit() {
        if (!IS_POST) {
            // 渲染分类
            $this->render_category();
            $this->member_info = $_SESSION['member_info'];
            // 收货信息
            $user_id = $_SESSION['member_info']['id'];
            $addr_info = M('Address')->where('user_id = ' . $user_id)->find();
            $this->assign('addr_info', $addr_info);
            $this->display();
        } else {
            $m = M('Member');
            $web_conf = $this->web_conf;
            $web_conf['thum_width'] = 50;
            $web_conf['thum_height'] = 50;
            if ($_FILES['avatar']['name']) {
                $head_portrait = up_img($_FILES['avatar'], 'uploads/', $web_conf, 1, 0, 200, 200);
                // print_r($head_portrait);exit();
                $uinfo['avatar'] = '/' . $head_portrait['pic'];
            }
            $uinfo['sex'] = $_REQUEST['sex'];
            $uinfo['phone'] = $_REQUEST['phone'];
            $uinfo['wechat'] = $_REQUEST['wechat'];
            $uinfo['email'] = $_REQUEST['email'];
            $exists = M('Address')->where('user_id = ' . $_SESSION['member_info']['id'])->find();
            if ($exists) {
                $addr_info = array(
                    'user_id' => $_SESSION['member_info']['id'],
                    'name' => $_REQUEST['name'],
                    'address' => $_REQUEST['address'],
                    'update_time' => time()
                );
                $update_addr = M('Address')->where('user_id = ' . $_SESSION['member_info']['id'])->save($addr_info);
            } else {
                $addr_info = array(
                    'user_id' => $_SESSION['member_info']['id'],
                    'name' => $_REQUEST['name'],
                    'address' => $_REQUEST['address'],
                    'add_time' => time(),
                    'update_time' => time()
                );
                $update_addr = M('Address')->where('user_id = ' . $_SESSION['member_info']['id'])->add($addr_info);
            }
            $old_uinfo = $m->where('id = ' . $_SESSION['member_info']['id'])->find();
            $update_uinfo = $m->where('id = ' . $_SESSION['member_info']['id'])->save($uinfo);
            if ($update_uinfo || $update_addr) {
                if ($_FILES['avatar']['name']) {
                    @unlink($old_uinfo['avatar']);
                }
                update_user_session();
                $this->redirect('Member/edit');
            } else {
                $this->error('更新用户信息失败');
            }
        }
    }

    // 删除发布信息
    public function del_pub() {
        $p = M('Publish');
        $id = $_REQUEST['id'];
        $exists = $p->where('id = ' . $id)->find();
        if (!$exists) {
            $result['status'] = 0;
            $result['msg'] = '该发布信息不存在';
            $this->ajaxReturn($result);
        }
        if ($p->where('id = ' . $id)->delete()) {
            $result['status'] = 1;
            $result['msg'] = '删除该发布信息成功';
        } else {
            $result['status'] = 0;
            $result['msg'] = '删除该发布信息失败';
        }
        $this->ajaxReturn($result);
    }

    // 删除收藏信息
    public function del_coll() {
        $c = M('Collection');
        $id = $_REQUEST['id'];
        $exists = $c->where('id = ' . $id)->find();
        if (!$exists) {
            $result['status'] = 0;
            $result['msg'] = '该收藏信息不存在';
            $this->ajaxReturn($result);
        }
        if ($c->where('id = ' . $id)->delete()) {
            $result['status'] = 1;
            $result['msg'] = '删除该收藏信息成功';
        } else {
            $result['status'] = 0;
            $result['msg'] = '删除该收藏信息失败';
        }
        $this->ajaxReturn($result);
    }

    /**
     * 网站第三方登录（qq）
     */
    public function qq_login() {
        vendor('OpenLogin.Tecent.API.qqConnectAPI');
        $qc = new QC();
        $qc->qq_login();
    }

    /**
     * 第三方登录回调（qq）
     */
    public function qq_login_callback() {
        vendor('OpenLogin.Tecent.API.qqConnectAPI');
        $qc = new QC();
        // 获取access_token
        $qc->qq_callback();
        $access_token = $qc->get_access_token();
        // 获取openId
        $qq_openId = $qc->get_openid();
        // 注销并从新实例化QC对象
        unset($qc);
        $qc = new QC($access_token, $qq_openId);
        // 获取用户信息
        $user_info = $qc->get_user_info();
        // 获取用户昵称
        $nickname = $user_info['nickname'];
        // 获取用户头像
        $avatar = $user_info['figureurl_qq_2'] ? trim($user_info['figureurl_qq_2']) : $user_info['figureurl_qq_1'];
        // 获取用户性别
        $sex = $user_info['gender'];
        // 判断用户是否曾经使用过该QQ登陆
        $is_logined = M('Member')->where(array(
            'qq_open_id' => $qq_openId
        ))->count();
        if ($is_logined) {
            // 更新上一次登陆时间
            M('Member')->where(array(
                'qq_open_id' => $qq_openId
            ))->save(array(
                'last_time' => time()
            ));
        } else {
            // 组装用户数组
            $data = array(
                'account' => $this->generate_noname_user(1),
                'password' => md5($qq_openId),
                'phone' => '',
                'avatar' => $avatar,
                'sex' => $sex == '男' ? 1 : 0,
                'qq_open_id' => $qq_openId,
                'register_time' => time()
            );
            // 插入一个新用户
            M('Member')->add($data);
        }
        // 根据openID获取用户信息
        $result = $this->get_user_info_by_openid(1, $qq_openId);
        // 伪装用户SESSION
        $this->pretend_user_session($result, $nickname);
        if ($is_logined) {
            $this->to_user_center();
        } else {
            $this->assign('nickname', $nickname);
            $this->display();
        }
    }

    /**
     * 微博登录
     */
    public function wb_login() {
        vendor('OpenLogin.Sina.API.index');
        $o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
        $code_url = $o->getAuthorizeURL(WB_CALLBACK_URL) . '&forcelogin=true';
        header("Location: $code_url");
    }

    /**
     * 微博登录回调
     */
    public function wb_login_callback() {
        vendor('OpenLogin.Sina.API.index');
        $o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
        if (isset($_GET['code'])) {
            $access_token = $o->getAccessToken('code', array(
                'code' => $_GET['code'],
                'redirect_uri' => WB_CALLBACK_URL
            ));
        } else {
            // 用户取消登录，关闭授权窗口并退出
            echo <<<EOT
                <script type="text/javascript">
                    window.close();
                </script>
EOT;
            exit;
        }
        $c = new SaeTClientV2(WB_AKEY, WB_SKEY, $access_token['access_token']);
        // 获取用户信息
        $user_info = $c->show_user_by_id($access_token['uid']);
        // 获取昵称
        $nickname = $user_info['screen_name'];
        // 获取用户头像
        $avatar = $user_info['avatar_large'] ? trim($user_info['avatar_large']) : $user_info['profile_image_url'];
        // 获取用户性别
        switch ($user_info['gender']) {
            case 'm' :
                $sex = 1;
                break;
            case 'f' :
                $sex = 0;
                break;
            case 'n' :
                $sex = null;
                break;
        }
        // 判断该微博账号是否登录过
        $is_logined = M('Member')->where(array(
            'sina_open_id' => $access_token['uid']
        ))->count();
        if ($is_logined) {
            // 更新上一次登陆时间
            M('Member')->where(array(
                'sina_open_id' => $access_token['uid']
            ))->save(array(
                'last_time' => time()
            ));
        } else {
            // 组装用户数组
            $data = array(
                'account' => $this->generate_noname_user(2),
                'password' => md5($access_token['uid']),
                'phone' => '',
                'avatar' => $avatar,
                'sex' => $sex,
                'sina_open_id' => $access_token['uid'],
                'register_time' => time()
            );
            // 插入一个新用户
            M('Member')->add($data);
        }
        // 根据openID获取用户信息
        $result = $this->get_user_info_by_openid(2, $access_token['uid']);
        // 伪装用户SESSION
        $this->pretend_user_session($result, $nickname);
        if ($is_logined) {
            $this->to_user_center();
        } else {
            $this->assign('nickname', $nickname);
            $this->display();
        }
    }

    /**
     * 生成匿名用户的账号
     *
     * @param int $type
     *            匿名用户的第三方登陆方式（1：qq，2：新浪微博）
     * @return string
     */
    private function generate_noname_user($type) {
        $max_id = M('Member')->field(array(
            "max(id)" => 'max_id'
        ))->find();
        switch ($type) {
            case 1 :
                return 'qq_user' . ($max_id['max_id'] + 1000);
            case 2 :
                return 'sina_user' . ($max_id['max_id'] + 1000);
        }
    }

    /**
     * 根据openId获取用户信息
     *
     * @param int $type
     *            用户第三方登录账号类型（1：qq，2：新浪微博）
     * @param string $openId
     *            openID
     * @return array
     */
    private function get_user_info_by_openid($type, $openId) {
        switch ($type) {
            case 1 :
                $where = array(
                    'qq_open_id' => $openId
                );
                break;
            case 2 :
                $where = array(
                    'sina_open_id' => $openId
                );
                break;
        }
        return M('Member')->field(array(
            'id',
            'account',
            'phone',
            'wechat',
            'avatar',
            'sex',
            'email',
            'register_time',
            'last_time'
        ))->where($where)->find();
    }

    /**
     * 伪装用户登陆的SESSION
     *
     * @param array $user_info
     *            用户信息数组
     * @param string $nickname
     *            用户昵称
     */
    private function pretend_user_session(array $user_info, $nickname) {
        $user_info['register_time'] = date("Y-m-d H:i:s", $user_info['register_time']);
        $user_info['last_time'] = $user_info['last_time'] ? date("Y-m-d H:i:s", $user_info['last_time']) : $user_info['last_time'];
        // 使用用户的昵称来伪装登录用户的account
        $user_info['account'] = $nickname;
        // 组装用户登陆SESSION
        $_SESSION['member_info'] = $user_info;
    }

    /**
     * 非首次登录
     * 登录成功后跳转用户个人中心页面
     */
    private function to_user_center() {
        echo <<<EOT
            <script type="text/javascript">
                (function() {window.close();window.opener.toUserCenter();})();
            </script>
EOT;
    }

}