<?php
/**
 * 市场Action
 */
class MarketAction extends CommonAction {

    /**
     * 加入购物车
     */
    public function add_cart() {
        $c = M('Cart');
        $goods_id = $_REQUEST['goods_id'];
        $member_id = $_SESSION['member_info']['id'];
        if (!$member_id) {
            $result['status'] = 0;
            $result['msg'] = '请先登录，才能加入购物车！';
            $this->ajaxReturn($result);
        }
        $where['goods_id'] = $goods_id;
        $where['user_id'] = $member_id;
        if ($c->where($where)->find()) {
            $result['status'] = 0;
            $result['msg'] = '你已加入购物车';
            $this->ajaxReturn($result);
        }

        $data['goods_id'] = $goods_id;
        $data['user_id'] = $member_id;
        $data['add_time'] = NOW_TIME;
        if ($c->add($data)) {
            $result['status'] = 1;
            $result['msg'] = '加入购物车成功';
        } else {
            $result['status'] = 0;
            $result['msg'] = '加入购物车失败';
            $this->ajaxReturn($result);
        }
        $this->ajaxReturn($result);
    }

    /**
     * 商品列表
     */
    public function goodlist() {
        // 渲染分类
        $this->render_category();
        $where = 'g.is_sell = 1';
        // 商品系列
        $series = M('Series')->select();
        // 茶叶分类
        if (isset($_GET['cate_id'])) {
            $_SESSION['cate_id'] = $_GET['cate_id'];
        }
        if (isset($_SESSION['cate_id'])) {
            $where .= ' and g.cate_id = ' . $_SESSION['cate_id'];
            $cate_id = $_SESSION['cate_id'];
        }
        // 获取类名
        if (isset($cate_id)) {
            $cate_info = M('Category')->field('name')->where('id = ' . $cate_id)->find();
            $cate_name = $cate_info['name'];
        }
        // 年份筛选
        if (isset($_GET['year'])) {
            $_SESSION['search']['year'] = $_GET['year'];
        }
        if (isset($_SESSION['search']['year'])) {
            $year = $_SESSION['search']['year'];
            if ($year) {
                if ($year == '1999') {
                    $where .= ' and g.year <= 1999';
                } else {
                    $where .= ' and g.year = ' . $year;
                }
            }
        }
        // 类型筛选
        if (isset($_GET['type'])) {
            $_SESSION['search']['type'] = $_GET['type'];
        }
        if (isset($_SESSION['search']['type'])) {
            $type = $_SESSION['search']['type'];
            if ($type) {
                $where .= ' and g.type = ' . $type;
            }
        }

        // 生产工艺筛选
        if (isset($_GET['proart'])) {
            $_SESSION['search']['proart'] = $_GET['proart'];
        }
        if (isset($_SESSION['search']['proart'])) {
            $proart = $_SESSION['search']['proart'];
            if ($proart) {
                $where .= ' and g.product_art = ' . $proart;
            }
        }

        // 系列筛选
        if (isset($_GET['series'])) {
            $_SESSION['search']['series'] = $_GET['series'];
        }
        if (isset($_SESSION['search']['series'])) {
            $series_id = $_SESSION['search']['series'];
            if ($series_id) {
                $where .= ' and g.series_id = ' . $series_id;
            }
        }

        import('ORG.Util.Page'); // 导入分页类
        $count = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->where($where)->count(); // 查询满足要求的总记录数

        $Page = new Page($count, 40); // 实例化分页类 传入总记录数
        $currentPage = isset($_GET['p']) ? $_GET['p'] : 1;
        $good_list = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->field('g.*,c.name as cate_name')->where($where)->page($currentPage . ',' . $Page->listRows)->order('g.add_time desc')->select();
        $this->assign('cate_name', $cate_name);
        $this->assign('good_list', $good_list);
        $this->page = $Page->show(); // 分页显示输出
        $this->assign('cate_id', $cate_id); // 输出分类值
        $this->assign('search', $search);
        $this->assign('series', $series);
        $this->display();
    }

    /**
     * 商品详情
     */
    public function goods() {
        // 渲染分类
        $this->render_category();
        $goods_id = $_GET['goods_id'];
        $details = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->where('g.id = ' . $goods_id)->field('g.*,c.name as cate_name')->find();
        $details['price'] = substr($details['price'], 0, strpos($details['price'], '.'));
        $details['_price'] = substr($details['_price'], 0, strpos($details['_price'], '.'));
        // 商品评论内容
        $comments = M('goods_comment')->table('tea_goods_comment gc')->join('tea_member m on m.id = gc.user_id')->field('m.account,m.avatar,gc.add_time,gc.content')->where('gc.goods_id = ' . $goods_id)->select();
        foreach ($comments as $key => $val) {
            $comments[$key]['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
        }
        $this->assign('comments', $comments);
        $this->assign('details', $details);
        $this->assign('member_info', $_SESSION['member_info']);
        $this->display();
    }

    /**
     * 超市首页
     */
    public function index() {
        // 渲染banner
        $this->render_banner();
        // 渲染分类
        $this->render_category();
        // 渲染客服
        $this->render_service();
        // 渲染热门商品
        $this->render_hot_goods_list();
        // 渲染热门搜索
        $this->render_hot_search_list();
        // 渲染升价商品
        $this->render_rise_list();
        // 渲染降价商品
        $this->render_fall_list();
        // 渲染顶部头条
        $this->render_top_news();
        // 品牌
        $this->assign('category', M('Category')->select());
        // 生产工艺
        $this->assign('product_art', array(
            array(
                'id' => 1,
                'name' => '生茶'
            ),
            array(
                'id' => 2,
                'name' => '熟茶'
            ),
            array(
                'id' => 3,
                'name' => '生熟混合'
            )
        ));
        // 类型
        $this->assign('type', array(
            array(
                'id' => 1,
                'name' => '饼茶'
            ),
            array(
                'id' => 2,
                'name' => '沱茶'
            ),
            array(
                'id' => 3,
                'name' => '砖茶'
            ),
            array(
                'id' => 4,
                'name' => '散茶'
            )
        ));
        $_cate_id = isset($_GET['cate_id']) ? intval($_GET['cate_id']) : 0;
        $_year = isset($_GET['year']) ? intval($_GET['year']) : 0;
        $_product_art = isset($_GET['product_art']) ? intval($_GET['product_art']) : 0;
        $_type = isset($_GET['type']) ? intval($_GET['type']) : 0;
        $where = array();
        $_cate_id && $where['cate_id'] = $_cate_id;
        if ($_year) {
            if ($_year != 1) {
                $where['year'] = $_year;
            } else {
                $where['year'] = array(
                    'exp',
                    ' < 2010'
                );
            }
        }
        $_product_art && $where['product_art'] = $_product_art;
        $_type && $where['type'] = $_type;
        import('ORG.Util.Page');
        $goodsModel = M('Goods');
        $where && $goodsModel->where($where);
        $count = $goodsModel->count();
        $page = new Page($count, 32);
        $show = $page->show();
        $where && $goodsModel->where($where);
        $goods_market_list = $goodsModel->order("add_time DESC")->limit($page->firstRow, $page->listRows)->select();
        foreach ($goods_market_list as &$v) {
            $v['_price'] = $v['_price'] ? $v['_price'] : $v['price'];
        }
        $this->assign('goods_market_list', $goods_market_list);
        $this->assign('page', $show);
        $this->assign('_cate_id', $_cate_id);
        $this->assign('_year', $_year);
        $this->assign('_product_art', $_product_art);
        $this->assign('_type', $_type);
        $this->display();
    }

    /**
     * 市场行情
     */
    public function quotations() {
        // 渲染分类
        $this->render_category();
        // 读取前两个商品的行情
        $rise_one = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->field('g.id, g.name, g.price, g._price, g.weight, g.year,g.description,FROM_UNIXTIME(g.add_time) AS add_time,FROM_UNIXTIME(g.update_time) AS update_time,g.stock, g.image, c.name AS cate_name')->where('g.price < g._price and is_sell = 0')->order('g.update_time desc')->find();
        $r_price = intval($rise_one['_price'] - $rise_one['price']);
        $r_scale = intval(100 * ($r_price / intval($rise_one['_price']))) . '%';
        $rise_one['rise_price'] = $r_price;
        $rise_one['rise_scale'] = $r_scale;
        $fall_one = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->field('g.id, g.name, g.price, g._price, g.weight, g.year,g.description,FROM_UNIXTIME(g.add_time) AS add_time,FROM_UNIXTIME(g.update_time) AS update_time,g.stock, g.image, c.name AS cate_name')->where('g.price > g._price and is_sell = 0')->order('g.update_time desc')->find();
        $f_price = intval($fall_one['price'] - $fall_one['_price']);
        $f_scale = intval(100 * ($f_price / intval($fall_one['price']))) . '%';
        $fall_one['fall_price'] = $f_price;
        $fall_one['fall_scale'] = $f_scale;
        // 读取所有品牌
        $brands = M('Category')->field('id,name')->order('update_time asc')->select();
        $brand_goods = array();
        // 循环品牌，分别读取品牌对应产品升降价
        foreach ($brands as $k => $v) {
            // 升价产品
            $rise_list = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->field('g.id, g.name, g.price, g._price, g.weight, g.year,g.description,FROM_UNIXTIME(g.add_time) AS add_time,FROM_UNIXTIME(g.update_time) AS update_time,g.stock, g.image, c.name AS cate_name')->where('g.price <= g._price and g.is_sell = 0 and g.cate_id = ' . $v['id'])->order('g.update_time desc')->limit(50)->select();
            foreach ($rise_list as $key => $val) {
                $rise_price = intval($val['_price'] - $val['price']);
                $rise_scale = intval(100 * ($rise_price / intval($val['_price']))) . '%';
                $rise_list[$key]['rise_price'] = $rise_price;
                $rise_list[$key]['rise_scale'] = $rise_scale;
            }
            $brand_goods[$k]['rise_list'] = $rise_list;
            // 降价产品
            $fall_list = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->field('g.id, g.name, g.price, g._price, g.weight, g.year,g.description,FROM_UNIXTIME(g.add_time) AS add_time,FROM_UNIXTIME(g.update_time) AS update_time,g.stock, g.image, c.name AS cate_name')->where('g.price > g._price and g.is_sell = 0 and g.cate_id = ' . $v['id'])->order('g.update_time desc')->limit(50)->select();
            foreach ($fall_list as $key => $val) {
                $fall_price = intval($val['price'] - $val['_price']);
                $fall_scale = intval(100 * ($fall_price / intval($val['price']))) . '%';
                $fall_list[$key]['fall_price'] = $fall_price;
                $fall_list[$key]['fall_scale'] = $fall_scale;
            }
            $brand_goods[$k]['fall_list'] = $fall_list;
            $brand_goods[$k]['cate_name'] = $v['name']; // 品牌名称
        }
        $this->assign('brand_goods', $brand_goods);
        $this->assign('rise_one', $rise_one);
        $this->assign('fall_one', $fall_one);
        $this->display();
    }

    /**
     * 商品筛选
     */
    public function search() {
        // 渲染分类
        $this->render_category();
        if (isset($_POST['keyword'])) {
            $keyword = $_POST['keyword'];
        } elseif (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
        }

        $where = '1 = 1';
        if ($keyword) {
            $where .= ' and concat(g.name,c.name,g.producer,g.production) like "%' . $keyword . '%"';
            $search['keyword'] = $keyword;
        }

        $search_type = $_POST['search_type'];
        if ($search_type == '0') {
            $where .= ' and g.is_sell = 0';
        } else {
            $where .= ' and g.is_sell = 1';
        }

        import('ORG.Util.Page'); // 导入分页类
        $count = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->where($where)->count(); // 查询满足要求的总记录数

        $Page = new Page($count, 40); // 实例化分页类 传入总记录数
        $currentPage = isset($_GET['p']) ? $_GET['p'] : 1;
        $good_list = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->field('g.*,c.name as cate_name')->where($where)->page($currentPage . ',' . $Page->listRows)->order('g.add_time desc')->select();
        // echo M('Goods')->getLastSql();exit;
        // print_r($good_list);exit;

        $this->assign('search_type', $search_type);
        $this->assign('good_list', $good_list);
        $this->page = $Page->show(); // 分页显示输出
        $this->assign('search', $search);
        $this->display();
    }

    /**
     * 评论
     */
    public function send_comment() {
        $user_id = $_POST['user_id'];
        if (!$_SESSION['member_info']['id']) {
            $result['info'] = '对不起，登陆后才能评论，请先登录！';
            $result['status'] = 2;
            $this->ajaxReturn($result);
        }
        $goods_id = $_POST['goods_id'];
        $exists = M('Goods')->where('id = ' . $goods_id)->find();
        if (!$exists) {
            $result['info'] = '对不起，您要评论的商品不存在！';
            $result['status'] = 0;
            $this->ajaxReturn($result);
        }
        if (!$_POST['content']) {
            $result['info'] = '留言内容不能为空';
            $result['status'] = 0;
            $this->ajaxReturn($result);
        }
        $data = array(
            'user_id' => $user_id,
            'goods_id' => $goods_id,
            'content' => $_POST['content'],
            'add_time' => time()
        );
        if (M('goods_comment')->add($data)) {
            $result['info'] = '评论该商品成功';
            $result['status'] = 1;
        } else {
            $result['info'] = '评论该商品失败';
            $result['status'] = 0;
        }
        $this->ajaxReturn($result);
    }

}