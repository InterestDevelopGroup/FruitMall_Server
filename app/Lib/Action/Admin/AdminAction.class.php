<?php

/**
 * 后台基类
 *
 * @author lzjjie
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
        $this->admin_info = $this->isLogin() ? $this->isLogin() : $this->redirect(U('/admin/login'));
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
     * 检测是否登录
     *
     * @return array null
     */
    private function isLogin() {
        return session('admin_info');
    }

}