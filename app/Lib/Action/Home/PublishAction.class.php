<?php
/**
 * 用户发布Action
 *
 */
class PublishAction extends CommonAction {

    /**
     * 拍下用户发布
     */
    public function add_shopping() {
        if ($this->isAjax()) {
            if (!isset($_SESSION['member_info'])) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '请先登录'
                ));
            }
            $publish_id = isset($_POST['publish_id']) ? intval($_POST['publish_id']) : $this->redirect('/');
            $publisher_id = isset($_POST['publisher_id']) ? intval($_POST['publisher_id']) : $this->redirect('/');
            if (M('Shopping')->add(array(
                'user_id' => $_SESSION['member_info']['id'],
                'publisher_id' => $publisher_id,
                'publish_id' => $publish_id,
                'add_time' => time()
            ))) {
                // 拍下的商品信息
                $goods_info = M('Publish')->where(array(
                    'id' => $publish_id
                ))->find();
                $member = M('Member');
                // 获取发布者客服
                $publisher_service = $member->table($member->getTableName() . " AS m ")->join(array(
                    " LEFT JOIN " . M('Service')->getTableName() . " AS s ON m.service = s.id "
                ))->field(array(
                    'm.account',
                    's.phone'
                ))->where(array(
                    'm.id' => $publisher_id
                ))->find();
                // 获取用户客服
                $user_service = $member->table($member->getTableName() . " AS m ")->join(array(
                    " LEFT JOIN " . M('Service')->getTableName() . " AS s ON m.service = s.id "
                ))->field(array(
                    'm.account',
                    's.phone'
                ))->where(array(
                    'm.id' => $_SESSION['member_info']['id']
                ))->find();
                $publisher_service['phone'] && send_shopping_sms($publisher_service['phone'], array(
                    $publisher_service['account'],
                    $goods_info['name'],
                    $user_service['account']
                ), 6276);
                $user_service['phone'] && send_shopping_sms($user_service['phone'], array(
                    $user_service['account'],
                    $publisher_service['account'],
                    $goods_info['name']
                ), 6275);
                $this->ajaxReturn(array(
                    'status' => true,
                    'msg' => '拍下商品成功'
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => false,
                    'result' => '请稍后再试'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 发布
     */
    public function release() {
        if (!$_POST) {
            $is_buy = $_GET['is_buy'];
            if (!$_SESSION['member_info']['id']) {
                $this->error('对不起，登陆后才能发布买卖', U('Member/login'));
            }
            // 渲染分类
            $this->render_category();
            // 品牌(获取系统分类)
            // $brand_info = M('Category')->select();
            // 用户发布列表
            $publish_list = M('Publish')->table('tea_publish p')->join('tea_member m on m.id = p.user_id')->field('p.*,m.account')->order('publish_time desc')->select();
            foreach ($publish_list as $key => $val) {
                $publish_list[$key]['publish_time'] = date('m/d', $val['publish_time']);
                if ($val['is_buy'] == 1) {
                    $publish_list[$key]['is_buy'] = '买茶';
                } elseif ($val['is_buy'] == 0) {
                    $publish_list[$key]['is_buy'] = '卖茶';
                }
                $publish_list[$key]['name'] = msubstr($val['name'], 0, 6);
                $publish_list[$key]['account'] = msubstr($val['account'], 0, 8);
                $publish_list[$key]['business_number'] = msubstr($val['business_number'], 0, 6);
            }
            $this->assign('publish_list', $publish_list);
            // $this->assign('brand_info', $brand_info);
            $this->assign('is_buy', $is_buy);
            $this->display();
        } else {
            if (!$_SESSION['member_info']['id']) {
                $this->error('对不起，登陆后才能发布买卖', U('Member/login'));
            }

            if ($_POST['is_buy'] != 0 && $_POST['is_buy'] != 1) {
                $this->error('请选择供求方式');
            }
            if (!$_POST['brand_name']) {
                $this->error('请输入茶叶品牌');
            }
            if (!$_POST['tea_name']) {
                $this->error('茶叶名称不能为空');
            }
            if (!$_POST['unit']) {
                $this->error('请选择茶叶单位');
            }
            if (!$_POST['pic']) {
                $this->error('请上传茶叶图片');
            }
            $member_info = $_SESSION['member_info'];
            $business_number = '';
            $pattern = '1234567890ABCDEFGHIJKLOMNOPQRSTUVWXYZ';
            for ($i = 0; $i < 15; $i++) {
                $business_number .= $pattern[mt_rand(0, 35)]; // 生成php随机数
            }
            $data = array(
                'user_id' => intval($member_info['id']),
                'brand_name' => trim($_POST['brand_name']),
                'name' => trim($_POST['tea_name']),
                'amount' => intval($_POST['amount']),
                'unit' => trim($_POST['unit']),
                'price' => floatval($_POST['price']),
                'business_number' => $business_number,
                'batch' => trim($_POST['batch']),
                'is_buy' => intval($_POST['is_buy']),
                'is_distribute' => intval($_POST['is_distribute']),
                'publish_time' => NOW_TIME,
                'details' => trim($_POST['details'])
            );
            // 添加图片
            $i = 1;
            foreach ($_POST['pic'] as $key => $val) {
                if ($i <= 3) {
                    $data['image_' . $i] = '/' . $val;
                }
                $i++;
            }
            $publish = M('Publish');
            // 开启事务
            $publish->startTrans();
            if ($publish->add($data)) {
                // 添加成功，提交事务
                $publish->commit();
                $this->redirect('/');
            } else {
                // 添加失败，回滚事务
                $publish->rollback();
                $this->error('发布买卖失败');
            }
        }
    }

    /**
     * 广场
     */
    public function square() {
        // 渲染banner
        $this->render_banner();
        // 渲染分类
        $this->render_category();
        // 渲染顶部头条
        $this->render_top_news();
        // 渲染热门商品
        $this->render_hot_goods_list();
        // 渲染热门搜索
        $this->render_hot_search_list();
        // 渲染升价商品
        $this->render_rise_list();
        // 渲染降价商品
        $this->render_fall_list();
        // 发布列表
        import('ORG.Util.Page'); // 导入分页类
        $count = M('Publish')->table('tea_publish p')->join('tea_category c on c.id = p.brand')->join('tea_member m on m.id = p.user_id')->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 12); // 实例化分页类 传入总记录数
        $currentPage = isset($_GET['p']) ? $_GET['p'] : 1;
        $publish_list = M('Publish')->table('tea_publish p')->join('tea_category c on c.id = p.brand')->join('tea_member m on m.id = p.user_id')->field('p.id, p.name, c.name AS brand, p.amount,p.unit, p.price, p.image_1,p.image_2, p.image_3, p.is_distribute, p.user_id,p.business_number,p.batch, p.publish_time, p.is_buy, m.account,m.phone, m.avatar, m.sex, m.register_time, m.last_time')->page($currentPage . ',' . $Page->listRows)->order('p.publish_time desc')->select();
        foreach ($publish_list as $key => $val) {
            $publish_list[$key]['publish_time'] = date('Y-m-d', $val['publish_time']);
            $publish_list[$key]['price'] = substr($val['price'], 0, strpos($val['price'], '.'));
        }
        // 获取最新的发布条数
        $publish_count = M('Publish')->where('publish_time > ' . strtotime('-1 week'))->count();
        $this->assign('publish_count', $publish_count);
        $this->assign('publish_list', $publish_list);
        $this->page = $Page->show(); // 分页显示输出
        $this->display();
    }

}