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
        'Branch' => array(
            'text' => '分店管理',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '所有分店',
                    'url' => '/branch/index'
                ),
                'add' => array(
                    'text' => '添加分店',
                    'url' => '/branch/add'
                )
            )
        ),
        'Coupon' => array(
            'text' => '水果劵',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '所有规则',
                    'url' => '/coupon/rule'
                ),
                'how_to_get' => array(
                    'text' => '编辑获取规则',
                    'url' => '/coupon/how_to_get'
                ),
                'how_to_use' => array(
                    'text' => '编辑使用规则',
                    'url' => '/coupon/how_to_use'
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
            'text' => '投诉/反馈',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '投诉/反馈一览',
                    'url' => '/feedback/index'
                )
            )
        ),
        'Returns' => array(
            'text' => '退货申请',
            'default' => 'index',
            'children' => array(
                'index' => array(
                    'text' => '所有退货申请',
                    'url' => '/returns/index'
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
        'member' => array(
            'index' => 'member|index',
            'delete' => 'member|delete',
            'addblacklist' => 'member|addblacklist',
            'deleteblacklist' => 'member|deleteblacklist',
            'add_coupon' => 'member|add_coupon'
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
        'coupon' => array(
            'rule' => 'coupon|rule',
            'add_rule' => 'coupon|add_rule',
            'delete_rule' => 'coupon|delete_rule',
            'update_rule' => 'coupon|update_rule',
            'how_to_get' => 'coupon|how_to_get',
            'how_to_use' => 'coupon|how_to_use'
        ),
        'order' => array(
            'index' => 'order|index',
            'delete' => 'order|delete',
            'update_status' => 'order|update_status'
        ),
        'version' => array(
            'android' => 'version|android',
            'add' => 'version|add',
            'delete' => 'version|delete'
        ),
        'feedback' => array(
            'index' => 'feedback|index',
            'delete' => 'feedback|delete'
        ),
        'returns' => array(
            'index' => 'returns|index',
            'delete' => 'returns|delete'
        )
    ),
    // 子权限
    'child_priv' => array(
        'goods|add' => 'category|getchildcategorybyparentid,goods|delete_image,goods|upload,goods|upload_image',
        'goods|update' => 'category|getchildcategorybyparentid,goods|delete_image,goods|upload,goods|upload_image',
        'tag|add' => 'tag|delete_image,tag|upload',
        'tag|update' => 'tag|delete_image,tag|upload',
        'package|add' => 'package|delete_image,package|upload,package|upload_image',
        'package|update' => 'package|delete_image,package|upload,package|upload_image',
        'order|index' => 'order|getorderdetail'
    ),
    // 权限对应翻译
    'priv_language' => array(
        'member' => array(
            'member' => '会员',
            'index' => '会员一览',
            'delete' => '删除会员',
            'addblacklist' => '拉入黑名单',
            'deleteblacklist' => '移出黑名单',
            'add_coupon' => '送水果劵'
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
        'coupon' => array(
            'coupon' => '水果劵',
            'rule' => '所有规则',
            'add_rule' => '添加规则',
            'delete_rule' => '删除规则',
            'update_rule' => '更新规则',
            'how_to_get' => '编辑获取规则',
            'how_to_use' => '编辑使用规则'
        ),
        'order' => array(
            'order' => '订单',
            'index' => '订单一览',
            'delete' => '删除订单',
            'update_status' => '更新订单状态'
        ),
        'version' => array(
            'version' => 'App版本管理',
            'android' => 'Android App版本管理',
            'add' => '添加版本',
            'delete' => '删除版本'
        ),
        'feedback' => array(
            'feedback' => '投诉/反馈',
            'index' => '投诉/反馈一览',
            'delete' => '删除投诉/反馈'
        ),
        'returns' => array(
            'returns' => '退货申请',
            'index' => '所有退货申请',
            'delete' => '删除退货申请'
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
        'category|parent_add',
        'category|parent_update',
        'category|child_index',
        'category|child_add',
        'category|child_update',
        'tag|index',
        'tag|add',
        'tag|update',
        'package|index',
        'package|add',
        'package|update',
        'shipping|index',
        'shipping|add',
        'shipping|update',
        'coupon|rule',
        'coupon|add_rule',
        'coupon|update_rule',
        'coupon|how_to_get',
        'coupon|how_to_use',
        'order|index',
        'order|update_status',
        'version|android',
        'feedback|index',
        'returns|index'
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