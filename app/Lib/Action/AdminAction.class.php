<?php

/**
 * 后台基类
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class AdminAction extends Action {

    /**
     * 管理员信息
     *
     * @var unknown
     */
    protected $admin_info = null;

    /**
     * 初始化
     */
    public function _initialize() {
        // 检测是否登录
        $this->admin_info = $this->isLogin() ? $this->isLogin() : $this->redirect(U('/login'));
        if (!$this->check_privileges(strtolower(MODULE_NAME), strtolower(ACTION_NAME))) {
            $menu_priv = C('menu_priv');
            if (in_array(strtolower(MODULE_NAME) . "|" . strtolower(ACTION_NAME), $menu_priv)) {
                vendor('Message.Message');
                Message::onshowMsg('抱歉，您没有权限查看该页面！您可以点击左侧或头部导航，查看其它页面，或联系管理员申请权限！');
            } else {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '对不起！您没有该操作的权限！'
                ));
            }
        }
    }

    /**
     * 获取主菜单
     *
     * @return array
     */
    protected function getMenu() {
        $menu = C('menu');
        foreach ($menu as $key => $value) {
            unset($children_1);
            foreach ($value['children'] as $key1 => $value1) {
                $children_1[] = array(
                    'url' => $value1['url'],
                    'text' => $value1['text']
                );
            }
            $tmpArr[] = array(
                'text' => $value['text'],
                'isexpand' => false,
                'children' => $children_1
            );
        }
        return $tmpArr;
    }

    /**
     * 权限检测
     *
     * @param string $module
     *            模块名
     * @param string $action
     *            方法名
     * @return boolean
     */
    private function check_privileges($module, $action) {
        $privileges = $module . "|" . $action;
        $user_privileges = M('AdminPriv')->where(array(
            'admin_id' => $this->admin_info['id']
        ))->find();
        if ($user_privileges['priv'] == 'all') {
            return true;
        }
        $privs = explode(',', $user_privileges['priv']);
        $check_priv = function ($tmp_priv) use($module, $privileges) {
            if (in_array($module . '|all', $tmp_priv) || in_array($privileges, $tmp_priv)) {
                return true;
            } else {
                return false;
            }
        };
        if ($check_priv($privs)) {
            return true;
        }
        // 获取系统权限列表
        $_priv = C('priv');
        // 获取系统子权限列表
        $_child_priv = C('child_priv');
        // 检测子权限的授权情况
        foreach ($privs as $v) {
            if (isset($_child_priv[$v])) {
                if ($check_priv(explode(',', $_child_priv[$v]))) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 检测是否登录
     *
     * @return array null
     */
    private function isLogin() {
        return session('admin_info');
    }

}