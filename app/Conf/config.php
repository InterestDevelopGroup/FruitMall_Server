<?php

/**
 * 项目配置文件
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
$app_config = array(
    'menu' => array(
        'Administrator' => array(
            'text' => '管理员',
            'default' => 'management',
            'children' => array(
                'management' => array(
                    'text' => '管理员一览',
                    'url' => '/administrator/management'
                )
            )
        ),
        'Member' => array(
            'text' => '会员',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '会员一览',
                    'url' => '/member/index'
                )
            )
        ),
        'Goods' => array(
            'text' => '商品',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '茶叶超市',
                    'url' => '/goods/index'
                ),
                'add' => array(
                    'text' => '添加商品',
                    'url' => '/goods/add'
                ),
                'category' => array(
                    'text' => '商品分类',
                    'url' => '/category/index'
                ),
                'series' => array(
                    'text' => '商品系列',
                    'url' => '/series/index'
                )
            )
        ),
        'Zone' => array(
            'text' => '专区',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '专区一览',
                    'url' => '/zone/index'
                )
            )
        ),
        'Publish' => array(
            'text' => '用户发布一览',
            'default' => 'sell',
            'children' => array(
                'sell' => array(
                    'text' => '卖茶',
                    'url' => '/publish/sell'
                ),
                'buy' => array(
                    'text' => '买茶',
                    'url' => '/publish/buy'
                )
            )
        ),
        'Market' => array(
            'text' => '市场行情',
            'default' => 'rise',
            'children' => array(
                'rise' => array(
                    'text' => '升价商品',
                    'url' => '/market/rise'
                ),
                'reduce' => array(
                    'text' => '降价商品',
                    'url' => '/market/reduce'
                )
            )
        ),
        'News' => array(
            'text' => '市场资讯',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '市场资讯管理',
                    'url' => '/news/index'
                ),
                'newstype' => array(
                    'text' => '资讯分类',
                    'url' => '/news/type'
                )
            )
        ),
        'Order' => array(
            'text' => '订单',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '订单一览',
                    'url' => '/order/index'
                )
            )
        ),
        'Version' => array(
            'text' => 'App版本管理',
            'default' => 'android',
            'children' => array(
                'android' => array(
                    'text' => 'Android App版本管理',
                    'url' => '/version/android'
                )
            )
        ),
        'Service' => array(
            'text' => '客服',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '客服一览',
                    'url' => '/service/index'
                )
            )
        ),
        'Feedback' => array(
            'text' => '用户反馈',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '用户反馈一览',
                    'url' => '/feedback/index'
                )
            )
        ),
        'Advertisement' => array(
            'text' => '广告管理',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '广告一览',
                    'url' => '/advertisement/index'
                )
            )
        ),
        'Setting' => array(
            'text' => '基本设置',
            'default' => 'cover',
            'children' => array(
                'index' => array(
                    'text' => 'App封面设置',
                    'url' => '/setting/cover'
                )
            )
        ),
        'Open' => array(
            'text' => 'API',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'API一览',
                    'url' => '/open/index'
                )
            )
        )
    )
);
return array_merge(require 'config.php', $app_config);