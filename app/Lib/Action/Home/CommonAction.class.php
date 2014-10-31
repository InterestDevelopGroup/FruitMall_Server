<?php
/**
 * 共用Action
 *
 */
class CommonAction extends Action {

    public function _initialize() {
        header("Content-Type:text/html;charset=utf8");
        $this->member_info = $_SESSION['member_info'];
        $url = $_SERVER['SCRIPT_FILENAME'];
        $url = str_replace('/index.php', '', $url);
        // 导入配置
        $this->web_conf = include ($url . '/app/Conf/Home/web_default.php');
        // 购物车数量
        if ($_SESSION['member_info']['id']) {
            $user_id = $_SESSION['member_info']['id'];
            $count = M('Cart')->where('user_id = ' . $user_id)->count();
            $this->assign('cart_count', $count);
        }
    }

    // 上传茶叶图片
    public function upload_image() {
        $path = str_replace('\\', '/', substr(__FILE__, 0, strrpos(__FILE__, 'Lib')));
        $web_conf = include ($path . 'Conf/Home/web_default.php');
        $web_conf['thum_width'] = 150;
        $web_conf['thum_height'] = 150;
        $time = date('Ymd', time());
        $up = up_img($_FILES['Filedata'], 'uploads/', $web_conf, 1, 0, 450, 450);
        echo json_encode($up);
    }

    // 删除茶叶图片
    public function delete_image() {
        $pic = $_GET['pic'];
        $thumb = $_GET['thumb'];
        @unlink($pic);
        @unlink($thumb);
        if ($_GET['id']) {
            $model = M('Publish');
            $model->where('id=' . $_GET['id'])->delete();
        }
    }

    /**
     * 渲染banner
     */
    protected function render_banner() {
        $banners = M('Advertisement')->field(array(
            'id',
            'image',
            'url'
        ))->where(array(
            'type' => 2
        ))->order('id desc')->select();
        foreach ($banners as &$v) {
            $v['image'] = "http://www.yichatea.com{$v['image']}";
        }
        $this->assign('banners', $banners);
    }

    /**
     * 渲染分类
     */
    protected function render_category() {
        $category = M('Category')->field(array(
            'id',
            'name',
            'image'
        ))->select();
        foreach ($category as &$v) {
            $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
        }
        $this->assign('brand_info', array_chunk($category, 5));
        $this->assign('category', $category);
    }

    /**
     * 渲染降价商品
     */
    protected function render_fall_list() {
        $fall_list = M('Goods')->field(array(
            'id',
            'name',
            'price',
            '_price',
            'image'
        ))->where(array(
            'is_sell' => 0,
            'price' => array(
                'exp',
                " > _price "
            )
        ))->order('update_time DESC')->limit(8)->select();
        foreach ($fall_list as &$v) {
            $v['delta'] = $v['price'] - $v['_price'];
            $v['scale'] = intval(($v['delta'] / $v['price']) * 100);
            $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
        }
        $this->assign('fall_list', $fall_list);
    }

    /**
     * 渲染热门商品
     */
    protected function render_hot_goods_list() {
        $hot_goods_list = M('Goods')->field(array(
            'id',
            'name',
            'price',
            '_price',
            'weight',
            'image'
        ))->where(array(
            'is_sell' => 1
        ))->order('id DESC')->limit(5)->select();
        foreach ($hot_goods_list as &$v) {
            $v['_price'] = $v['_price'] ? $v['_price'] : $v['price'];
            $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
        }
        $this->assign('hot_goods_list', $hot_goods_list);
    }

    /**
     * 渲染热门搜索
     */
    protected function render_hot_search_list() {
        $hot_search_list = M('Goods')->field(array(
            'id',
            'name',
            'price',
            '_price',
            'description'
        ))->where(array(
            'is_sell' => 0
        ))->order('update_time DESC')->limit(8)->select();
        foreach ($hot_search_list as &$v) {
            $v['_price'] = $v['_price'] ? floatval($v['_price'] / 10000) : floatval($v['price'] / 10000);
        }
        $this->assign('hot_search_list', $hot_search_list);
    }

    /**
     * 渲染升价商品
     */
    protected function render_rise_list() {
        $rise_list = M('Goods')->field(array(
            'id',
            'name',
            'price',
            '_price',
            'image'
        ))->where(array(
            'is_sell' => 0,
            'price' => array(
                'exp',
                " < _price "
            )
        ))->order('update_time DESC')->limit(8)->select();
        foreach ($rise_list as &$v) {
            $v['delta'] = $v['_price'] - $v['price'];
            $v['scale'] = intval(($v['delta'] / $v['price']) * 100);
            $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
        }
        $this->assign('rise_list', $rise_list);
    }

    /**
     * 渲染客服
     */
    protected function render_service() {
        $service_info = M('Service')->field(array(
            'id',
            'qq',
            'contact',
            'image'
        ))->select();
        foreach ($service_info as &$v) {
            $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
        }
        $this->assign('service_info', $service_info);
    }

    /**
     * 渲染顶置新闻
     */
    protected function render_top_news() {
        $top_news = M('News')->field(array(
            'id',
            'title',
            'image'
        ))->where(array(
            'is_top' => 1
        ))->order('update_time DESC')->find();
        $top_news['image'] = "http://www.yichatea.com{$top_news['image']}";
        $this->assign('top_news', $top_news);
    }

}