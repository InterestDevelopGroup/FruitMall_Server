<?php

/**
 * 项目配置文件
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
return array(
    'menu' => array(
        'Administrator' => array(
            'text' => '管理员',
            'default' => 'management',
            'children' => array(
                'management' => array(
                    'text' => '管理员一览',
                    'url' => U('administrator/management')
                )
            )
        ),
        'Member' => array(
            'text' => '会员',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '会员一览',
                    'url' => U('member/index')
                )
            )
        ),
        'Goods' => array(
            'text' => '商品',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '茶叶超市',
                    'url' => U('goods/index')
                ),
                'add' => array(
                    'text' => '添加商品',
                    'url' => U('goods/add')
                ),
                'category' => array(
                    'text' => '商品分类',
                    'url' => U('category/index')
                ),
                'series' => array(
                    'text' => '商品系列',
                    'url' => U('series/index')
                )
            )
        ),
        'Zone' => array(
            'text' => '专区',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '专区一览',
                    'url' => U('zone/index')
                )
            )
        ),
        'Publish' => array(
            'text' => '用户发布一览',
            'default' => 'sell',
            'children' => array(
                'sell' => array(
                    'text' => '卖茶',
                    'url' => U('publish/sell')
                ),
                'buy' => array(
                    'text' => '买茶',
                    'url' => U('publish/buy')
                )
            )
        ),
        'Market' => array(
            'text' => '市场行情',
            'default' => 'rise',
            'children' => array(
                'rise' => array(
                    'text' => '升价商品',
                    'url' => U('market/rise')
                ),
                'reduce' => array(
                    'text' => '降价商品',
                    'url' => U('market/reduce')
                )
            )
        ),
        'News' => array(
            'text' => '市场资讯',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '市场资讯管理',
                    'url' => U('news/index')
                ),
                'newstype' => array(
                    'text' => '资讯分类',
                    'url' => U('news/type')
                )
            )
        ),
        'Order' => array(
            'text' => '订单',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '订单一览',
                    'url' => U('order/index')
                )
            )
        ),
        'Version' => array(
            'text' => 'App版本管理',
            'default' => 'android',
            'children' => array(
                'android' => array(
                    'text' => 'Android App版本管理',
                    'url' => U('version/android')
                )
            )
        ),
        'Service' => array(
            'text' => '客服',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '客服一览',
                    'url' => U('service/index')
                )
            )
        ),
        'Feedback' => array(
            'text' => '用户反馈',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '用户反馈一览',
                    'url' => U('feedback/index')
                )
            )
        ),
        'Advertisement' => array(
            'text' => '广告管理',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '广告一览',
                    'url' => U('advertisement/index')
                )
            )
        ),
        'Setting' => array(
            'text' => '基本设置',
            'default' => 'cover',
            'children' => array(
                'index' => array(
                    'text' => 'App封面设置',
                    'url' => U('setting/cover')
                )
            )
        ),
        'Open' => array(
            'text' => 'API',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => 'API一览',
                    'url' => U('open/index')
                )
            )
        )
    ),
    'AD_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'CATEGORY_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'COVER_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'GOODS_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'NEWS_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'SERVICE_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'ZONE_ALLOW_UPLOAD_IMAGE_EXTENSION' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'AD_MAX_UPLOAD_FILE_SIZE' => 2097152,
    'CATEGORY_MAX_UPLOAD_FILE_SIZE' => 2097152,
    'COVER_MAX_UPLOAD_FILE_SIZE' => 2097152,
    'GOODS_MAX_UPLOAD_FILE_SIZE' => 2097152,
    'NEWS_MAX_UPLOAD_FILE_SIZE' => 2097152,
    'SERVICE_MAX_UPLOAD_FILE_SIZE' => 1048576,
    'ZONE_MAX_UPLOAD_FILE_SIZE' => 2097152
);