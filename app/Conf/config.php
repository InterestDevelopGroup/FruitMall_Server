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
                    'text' => '所有商品',
                    'url' => '/goods/index'
                ),
                'add' => array(
                    'text' => '添加商品',
                    'url' => '/goods/add'
                )
            )
        ),
        'Category' => array(
            'text' => '商品分类',
            'default' => 'parent',
            'children' => array(
                'parent' => array(
                    'text' => '大分类',
                    'url' => '/category/parent_index'
                ),
                'child' => array(
                    'text' => '小分类',
                    'url' => '/category/child_index'
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
    ),
    'priv' => array(
        'administrator' => array(
            'management' => 'administrator|management'
        ),
        'member' => array(
            'index' => 'member|index'
        ),
        'goods' => array(
            'index' => 'goods|index',
            'add' => 'goods|add'
        ),
        'category' => array(
            'parent_index' => 'category|parent_index',
            'child_index' => 'category|child_index'
        )
    ),
    'child_priv' => array(
        'administrator|management' => 'administrator|add,administrator|delete',
        'member|index' => 'member|delete',
        'goods|index' => 'goods|delete,goods|update',
        'category|parent_index' => 'catagory|parent_add,category|parent_delete,category|parent_update',
        'category|child_index' => 'category|child_add,category|child_delete,category|child_update'
    ),
    'priv_language' => array(
        'administrator' => array(
            'administrator' => '管理员',
            'management' => '管理员一览'
        ),
        'member' => array(
            'member' => '会员',
            'index' => '会员一览'
        ),
        'goods' => array(
            'goods' => '商品',
            'index' => '所有商品',
            'add' => '添加商品'
        ),
        'category' => array(
            'category' => '商品分类',
            'parent_index' => '大分类',
            'child_index' => '小分类'
        )
    ),
    'MAX_SIZE' => 2097152,
    'ALLOW_EXTENSIONS' => array(
        'jpg',
        'jpeg',
        'png'
    )
);
return array_merge(require 'config.php', $app_config);