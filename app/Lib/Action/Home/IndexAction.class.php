<?php

/**
 * 首页Action
 */
class IndexAction extends CommonAction {

    /**
     * 网站故障
     */
    public function bug() {
        $this->display();
    }

    /**
     * 帮助中心
     */
    public function help() {
        $this->display();
    }

    /**
     * 首页
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
        // 发布列表
        $publish_list = M('Publish')->table('tea_publish p')->join(array(
            " LEFT JOIN " . M('Member')->getTableName() . " AS m ON m.id = p.user_id "
        ))->field(array(
            'p.id',
            'p.name',
            'p.brand_name',
            'p.amount',
            'p.unit',
            'p.price',
            'p.image_1',
            'p.image_2',
            'p.image_3',
            'p.is_distribute',
            'p.user_id',
            'p.business_number',
            'p.batch',
            'p.publish_time',
            'p.is_buy',
            'm.account',
            'm.phone',
            'm.avatar',
            'm.sex',
            'm.register_time',
            'm.last_time'
        ))->where(array(
            'p.status' => 1
        ))->order('p.id DESC')->limit(4)->select();
        foreach ($publish_list as &$v) {
            $v['avatar'] = "http://{$_SERVER['HTTP_HOST']}{$v['avatar']}";
            $v['image_1'] = "http://{$_SERVER['HTTP_HOST']}{$v['image_1']}";
            $v['image_2'] = "http://{$_SERVER['HTTP_HOST']}{$v['image_2']}";
            $v['image_3'] = "http://{$_SERVER['HTTP_HOST']}{$v['image_3']}";
        }
        $this->assign('publish_list', $publish_list);
        // 专区列表
        $zone_list = M('Zone')->select();
        foreach ($zone_list as &$v) {
            $v['goods_list'] = M('Goods')->where(array(
                'zone' => $v['id']
            ))->limit(8)->order("id DESC")->select();
            foreach ($v['goods_list'] as &$v_1) {
                $v_1['_price'] = $v_1['_price'] ? $v_1['_price'] : $v_1['price'];
            }
        }
        $this->assign('zone_list', $zone_list);
        $this->display();
    }

    /**
     * 订购方式
     */
    public function order() {
        $this->display();
    }

    /**
     * 货到付款
     */
    public function pay() {
        $this->display();
    }

    /**
     * 支付方式说明
     */
    public function payexplain() {
        $this->display();
    }

    /**
     * 专业服务
     */
    public function profession() {
        $this->display();
    }

    /**
     * 质量保证
     */
    public function quailty() {
        $this->display();
    }

    /**
     * 售货方式
     */
    public function sales() {
        $this->display();
    }

    /**
     * 投诉建议
     */
    public function suggestion() {
        $this->display();
    }

}