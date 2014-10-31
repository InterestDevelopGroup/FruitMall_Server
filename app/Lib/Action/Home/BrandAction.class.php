<?php
/**
 * 品牌查询Action
 *
 */
class BrandAction extends CommonAction {

    /**
     * 品牌查询
     */
    public function index() {
        // 渲染分类
        $this->render_category();
        $where = 'g.is_sell =0';
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
            $_SESSION['search2']['year'] = $_GET['year'];
        }
        if (isset($_SESSION['search2']['year'])) {
            $year = $_SESSION['search2']['year'];
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
            $_SESSION['search2']['type'] = $_GET['type'];
        }
        if (isset($_SESSION['search2']['type'])) {
            $type = $_SESSION['search2']['type'];
            if ($type) {
                $where .= ' and g.type = ' . $type;
            }
        }

        // 生产工艺筛选
        if (isset($_GET['proart'])) {
            $_SESSION['search2']['proart'] = $_GET['proart'];
        }
        if (isset($_SESSION['search2']['proart'])) {
            $proart = $_SESSION['search2']['proart'];
            if ($proart) {
                $where .= ' and g.product_art = ' . $proart;
            }
        }

        // 系列筛选
        if (isset($_GET['series'])) {
            $_SESSION['search2']['series'] = $_GET['series'];
        }
        if (isset($_SESSION['search2']['series'])) {
            $series_id = $_SESSION['search2']['series'];
            if ($series_id) {
                $where .= ' and g.series_id = ' . $series_id;
            }
        }

        import('ORG.Util.Page'); // 导入分页类
        $count = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->where($where)->count(); // 查询满足要求的总记录数

        $Page = new Page($count, 12); // 实例化分页类 传入总记录数
        $currentPage = isset($_GET['p']) ? $_GET['p'] : 1;
        $good_list = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->field('g.*,c.name as cate_name')->where($where)->page($currentPage . ',' . $Page->listRows)->order('g.add_time desc')->select();
        foreach ($good_list as $key => $val) {
            if ($val['price'] > $val['_price']) {
                $good_list[$key]['trend'] = 'fall';
            } else {
                $good_list[$key]['trend'] = 'rise';
            }
            $var_price = abs(intval($val['_price'] - $val['price']));
            $var_scale = abs(intval(100 * ($var_price / intval($val['_price'])))) . '%';
            $good_list[$key]['var_price'] = $var_price;
            $good_list[$key]['var_scale'] = $var_scale;
            $good_list[$key]['price'] = substr($val['price'], 0, strpos($val['price'], '.'));
            $good_list[$key]['_price'] = substr($val['_price'], 0, strpos($val['_price'], '.'));
            // $good_list[$key]['price_b'] = substr($val['price_b'],0,strpos($val['price_b'],'.'));
            // $good_list[$key]['price_p'] = substr($val['price_p'],0,strpos($val['price_p'],'.'));
        }
        // print_r($good_list);exit;

        $this->assign('series', $series);
        $this->assign('good_list', $good_list);
        $this->page = $Page->show(); // 分页显示输出
        $this->assign('cate_id', $cate_id); // 输出分类值
        $this->assign('search', $search);
        $this->display();
    }

    // 商品详情
    public function detail() {
        // 渲染分类
        $this->render_category();
        if (!IS_POST) {
            $goods_id = $_GET['goods_id'];
        } else {
            $goods_id = $_POST['goods_id'];
        }
        $details = M('Goods')->table('tea_goods g')->join('tea_category c on c.id = g.cate_id')->where('g.id = ' . $goods_id)->field('g.*,c.name as cate_name')->find();
        $details['price'] = substr($details['price'], 0, strpos($details['price'], '.'));
        $details['_price'] = substr($details['_price'], 0, strpos($details['_price'], '.'));
        // $details['price_b'] = substr($details['price_b'],0,strpos($details['price_b'],'.'));
        // $details['price_p'] = substr($details['price_p'],0,strpos($details['price_p'],'.'));
        // print_r($details);exit;

        // 价格趋势
        $where = 'goods_id = ' . $goods_id;
        if (IS_POST) {
            if ($_POST['begin'] && $_POST['end']) {
                $begin = strtotime($_POST['begin'] . ' 00:00:00');
                $end = strtotime($_POST['end'] . ' 23:59:59');
                $where .= " and add_time >= " . $begin . " and add_time <= " . $end;
                $search['begin'] = $_POST['begin'];
                $search['end'] = $_POST['end'];
            }
        } elseif (isset($_GET['sort_time'])) {
            $sort_time = $_GET['sort_time'];
            switch ($sort_time) {
                case '1' :
                    $where .= " and (add_time > " . strtotime('-1 week') . ")";
                    break;
                case '2' :
                    $where .= " and (add_time > " . strtotime('-1 month') . ")";
                    break;
                case '3' :
                    $where .= " and (add_time > " . strtotime('-3 month') . ")";
                    break;
                case '4' :
                    $where .= " and (add_time > " . strtotime('-1 year') . ")";
                    break;
                default :
                    $where .= " and (add_time > " . strtotime('-1 week') . ")";
                    break;
            }
        } else {
            $where .= " and (add_time > " . strtotime('-1 week') . ")";
            $sort_time = 1;
        }
        $date_arr = array();
        $price_arr = array();
        $price_trend = M('Goods_price')->where($where)->field("FROM_UNIXTIME(add_time,'%Y-%m-%d') as add_time,price")->select();
        // echo M('goods_price')->getLastSql();exit;

        foreach ($price_trend as $key => $val) {
            // 曲线图数据
            $date_arr[$key] = '"' . $val['add_time'] . '"';
            $price_arr[$key] = $val['price'];
            // 列表数据
            if ($key) {
                $t_data = intval($val['price']);
                $y_data = intval($price_trend[$key - 1]['price']);
                if ($t_data > $y_data) {
                    $up_per = intval(100 * (($t_data - $y_data) / $t_data)) . '%';
                    $price_trend[$key]['per'] = $up_per;
                    $price_trend[$key]['cur'] = 'up';
                } elseif ($t_data < $y_data) {
                    $down_per = intval(100 * (($y_data - $t_data) / $y_data)) . '%';
                    $price_trend[$key]['per'] = $down_per;
                    $price_trend[$key]['cur'] = 'down';
                }
            }
        }
        // print_r($price_trend);exit;
        $date_str = implode(',', $date_arr);
        $price_str = implode(',', $price_arr);

        // 商品评论内容
        $comments = M('goods_comment')->table('tea_goods_comment gc')->join('tea_member m on m.id = gc.user_id')->field('m.account,m.avatar,gc.add_time,gc.content')->where('gc.goods_id = ' . $goods_id)->select();
        foreach ($comments as $key => $val) {
            $comments[$key]['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
        }
        // print_r($comments);exit;

        $this->assign('comments', $comments);
        $this->assign('search', $search);
        $this->assign('price_trend', $price_trend);
        $this->assign('sort_time', $sort_time);
        $this->assign('date_str', $date_str);
        $this->assign('price_str', $price_str);
        $this->assign('details', $details);
        $this->assign('id', $goods_id);
        $this->display();
    }

    // 添加收藏
    public function add_collection() {
        $c = M('Collection');
        $goods_id = $_REQUEST['goods_id'];
        $member_id = $_SESSION['member_info']['id'];
        if (!$member_id) {
            $result['status'] = 0;
            $result['msg'] = '请先登录，才能加入收藏！';
            $this->ajaxReturn($result);
        }
        $where['collection_id'] = $goods_id;
        $where['user_id'] = $member_id;
        if ($c->where($where)->find()) {
            $result['status'] = 0;
            $result['msg'] = '你已加入收藏';
            $this->ajaxReturn($result);
        }

        $data['collection_id'] = $goods_id;
        $data['user_id'] = $member_id;
        $data['type'] = '1';
        $data['add_time'] = NOW_TIME;
        if ($c->add($data)) {
            $result['status'] = 1;
            $result['msg'] = '加入收藏成功';
        } else {
            $result['status'] = 0;
            $result['msg'] = '加入收藏失败';
            $this->ajaxReturn($result);
        }
        $this->ajaxReturn($result);
    }

    // 评论
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
?>