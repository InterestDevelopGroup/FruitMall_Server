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
        'Tag' => array(
            'text' => '商品标签',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '标签一览',
                    'url' => '/tag/index'
                )
            )
        ),
        'Package' => array(
            'text' => '套餐',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '所有套餐',
                    'url' => '/package/index'
                ),
                'add' => array(
                    'text' => '添加套餐',
                    'url' => '/package/add'
                )
            )
        ),
        'Shipping' => array(
            'text' => '配送地址',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '所有地址',
                    'url' => '/shipping/index'
                ),
                'add' => array(
                    'text' => '添加配送地址',
                    'url' => '/shipping/add'
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
        /*
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
        */
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
        'member' => array(
            'index' => 'member|index',
            'delete' => 'member|delete'
        ),
        'goods' => array(
            'index' => 'goods|index',
            'add' => 'goods|add',
            'delete' => 'goods|delete',
            'update' => 'goods|update'
        ),
        'category' => array(
            'parent_index' => 'category|parent_index',
            'parent_add' => 'category|parent_add',
            'parent_delete' => 'category|parent_delete',
            'parent_update' => 'category|parent_update',
            'child_index' => 'category|child_index',
            'child_add' => 'category|child_add',
            'child_delete' => 'category|child_delete',
            'child_update' => 'category|child_update'
        ),
        'tag' => array(
            'index' => 'tag|index',
            'add' => 'tag|add',
            'delete' => 'tag|delete',
            'update' => 'tag|update'
        ),
        'package' => array(
            'index' => 'package|index',
            'add' => 'package|add',
            'delete' => 'package|delete',
            'update' => 'package|update'
        ),
        'shipping' => array(
            'index' => 'shipping|index',
            'add' => 'shipping|add',
            'delete' => 'shipping|delete',
            'update' => 'shipping|update'
        ),
        'order' => array(
            'index' => 'order|index'
        ),
        'version' => array(
            'android' => 'version|android',
            'add' => 'version|add',
            'delete' => 'version|delete'
        )
    ),
    // 子权限
    'child_priv' => array(
        'goods|add' => 'category|getChildCategoryByParentId,goods|delete_image,goods|upload,goods|upload_image',
        'goods|update' => 'category|getChildCategoryByParentId,goods|delete_image,goods|upload,goods|upload_image',
        'tag|add' => 'tag|delete_image,tag|upload',
        'tag|update' => 'tag|delete_image,tag|upload',
        'package|add' => 'package|delete_image,package|upload,package|upload_image',
        'package|update' => 'package|delete_image,package|upload,package|upload_image'
    ),
    // 权限对应翻译
    'priv_language' => array(
        'member' => array(
            'member' => '会员',
            'index' => '会员一览',
            'delete' => '删除会员'
        ),
        'goods' => array(
            'goods' => '商品',
            'index' => '所有商品',
            'add' => '添加商品',
            'delete' => '删除商品',
            'update' => '更新商品'
        ),
        'category' => array(
            'category' => '商品分类',
            'parent_index' => '大分类',
            'parent_add' => '添加大分类',
            'parent_delete' => '删除大分类',
            'parent_update' => '删除大分类',
            'child_index' => '小分类',
            'child_add' => '添加小分类',
            'child_delete' => '删除小分类',
            'child_update' => '更新小分类'
        ),
        'tag' => array(
            'tag' => '商品标签',
            'index' => '标签一览',
            'add' => '添加标签',
            'delete' => '删除标签',
            'update' => '更新标签'
        ),
        'package' => array(
            'package' => '套餐',
            'index' => '所有套餐',
            'add' => '添加套餐',
            'delete' => '删除套餐',
            'update' => '更新套餐'
        ),
        'shipping' => array(
            'shipping' => '配送地址',
            'index' => '所有地址',
            'add' => '添加地址',
            'delete' => '删除地址',
            'update' => '更新地址'
        ),
        'order' => array(
            'order' => '订单',
            'index' => '订单一览'
        ),
        'version' => array(
            'version' => 'App版本管理',
            'android' => 'Android App版本管理',
            'add' => '添加版本',
            'delete' => '删除版本'
        )
    ),
    // 菜单权限
    'menu_priv' => array(
        'administrator|management',
        'member|index',
        'goods|index',
        'goods|add',
        'goods|update',
        'category|parent_index',
        'category|child_index',
        'tag|index',
        'package|index',
        'package|add',
        'package|update',
        'shipping|index',
        'shipping|add',
        'shipping|update',
        'order|index',
        'version|android'
    ),
    'MAX_SIZE' => 2097152,
    'ALLOW_EXTENSIONS' => array(
        'jpg',
        'jpeg',
        'png'
    ),
    'SMS' => array(
        'sn' => 'SDK-WSS-010-07201',
        'pwd' => 'wddydg'
    )
);
return array_merge(require 'config.php', $app_config);