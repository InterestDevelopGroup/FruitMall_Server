<?php

/**
 * Api Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class ApiAction extends Action {

    /**
     * 添加地址
     */
    public function add_address() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $consignee = isset($_POST['consignee']) ? trim($_POST['consignee']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $province = isset($_POST['province']) ? trim($_POST['province']) : $this->redirect('/');
            $city = isset($_POST['city']) ? trim($_POST['city']) : $this->redirect('/');
            $district = isset($_POST['district']) ? trim($_POST['district']) : $this->redirect('/');
            $community = isset($_POST['community']) ? trim($_POST['community']) : $this->redirect('/');
            $address = isset($_POST['address']) ? trim($_POST['address']) : $this->redirect('/');
            $_consignee = (isset($_POST['_consignee']) && !empty($_POST['_consignee'])) ? trim($_POST['_consignee']) : null;
            $_phone = (isset($_POST['_phone']) && !empty($_POST['_phone'])) ? trim($_POST['_phone']) : null;
            if ($user_id < 1 || empty($consignee) || empty($phone) || empty($province) || empty($city) || empty($address)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Address')->addAddress($user_id, $consignee, $phone, $province, $city, $district, $community, $address, $_consignee, $_phone));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 添加定制
     */
    public function add_custom() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $goods_list = isset($_POST['goods_list']) ? trim($_POST['goods_list']) : $this->redirect('/');
            if ($user_id < 1 || empty($name) || empty($goods_list)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Custom')->addCustom($user_id, $name, $goods_list));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 加入定制
     */
    public function add_custom_goods() {
        if ($this->isPost() || $this->isAjax()) {
            $custom_id = isset($_POST['custom_id']) ? intval($_POST['custom_id']) : $this->redirect('/');
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : $this->redirect('/');
            if ($custom_id < 1 || $goods_id < 1 || $quantity < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('CustomGoods')->addCustomGoods($custom_id, $goods_id, $quantity));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 加入购物车
     */
    public function add_shopping_car() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $shopping_list = isset($_POST['shopping_list']) ? trim($_POST['shopping_list']) : $this->redirect('/');
            if ($user_id < 1 && empty($shopping_list)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('ShoppingCar')->addShoppingCar($user_id, $shopping_list));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 地址列表
     */
    public function address_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            if ($user_id < 1 || $offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('Address')->_getAddressList($user_id, $offset, $pagesize))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 广告列表
     */
    public function advertisement() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['advertisement_add_time'] = date("Y-m-d H:i:s", $value['advertisement_add_time']);
                    if ($value['goods_id']) {
                        $value['goods_add_time'] = $value['goods_add_time'] ? date("Y-m-d H:i:s", $value['goods_add_time']) : $value['goods_add_time'];
                        $value['goods_update_time'] = $value['goods_update_time'] ? date("Y-m-d H:i:s", $value['goods_update_time']) : $value['goods_update_time'];
                    }
                    if ($value['package_id']) {
                        $value['package_add_time'] = $value['package_add_time'] ? date("Y-m-d H:i:s", $value['package_add_time']) : $value['package_add_time'];
                        $value['package_update_time'] = $value['package_update_time'] ? date("Y-m-d H:i:s", $value['package_update_time']) : $value['package_update_time'];
                    }
                    return $value;
                }, D('Advertisement')->_getAdvertisement($offset, $pagesize))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 修改用户手机
     */
    public function change_phone() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            if ($id < 1 || empty($phone)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Member')->changePhone($id, $phone));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 小分类
     */
    public function child_category() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $p_cate_id = (isset($_POST['p_cate_id']) && intval($_POST['p_cate_id'])) ? intval($_POST['p_cate_id']) : null;
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('ChildCategory')->_getChildCategoryList($offset, $pagesize, $p_cate_id, $keyword))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 定制
     */
    public function custom() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            if ($user_id < 1 || $offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
                    return $value;
                }, D('Custom')->_getCustomList($user_id, $offset, $pagesize))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 水果劵
     */
    public function coupon() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            if ($user_id < 1 || $offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $result = array();
            $result['coupon'] = array_map(function ($value) {
                $value['publish_time'] = date("Y-m-d H:i:s", $value['publish_time']);
                $value['expire_time'] = $value['expire_time'] ? date("Y-m-d H:i:s", $value['expire_time']) : $value['expire_time'];
                return $value;
            }, D('Coupon')->_getCouponList($user_id, $offset, $pagesize));
            $result['rule'] = array_map(function ($value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                return $value;
            }, D('CouponRuleContent')->_getCouponRuleContent());
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 设置/取消默认地址
     */
    public function default_address() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : $this->redirect('/');
            $is_default = isset($_POST['is_default']) ? intval($_POST['is_default']) : $this->redirect('/');
            if ($user_id < 1 || $address_id < 1 || !in_array($is_default, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('DefaultAddress')->changeDefaultAddress($user_id, $address_id, $is_default));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除地址
     */
    public function delete_address() {
        if ($this->isPost() || $this->isAjax()) {
            $address_id = isset($_POST['address_id']) ? explode(',', $_POST['address_id']) : $this->redirect('/');
            if (empty($address_id)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Address')->deleteAddress((array) $address_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除定制
     */
    public function delete_custom() {
        if ($this->isPost() || $this->isAjax()) {
            $custom_id = isset($_POST['custom_id']) ? explode(',', $_POST['custom_id']) : $this->redirect('/');
            if (empty($custom_id)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Custom')->deleteCustom((array) $custom_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除定制商品
     */
    public function delete_custom_goods() {
        if ($this->isPost() || $this->isAjax()) {
            $custom_goods_id = isset($_POST['custom_goods_id']) ? explode(',', $_POST['custom_goods_id']) : $this->redirect('/');
            if (empty($custom_goods_id)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('CustomGoods')->deleteCustomGoods((array) $custom_goods_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除订单
     */
    public function delete_order() {
        if ($this->isPost() || $this->isAjax()) {
            $order_id = isset($_POST['order_id']) ? explode(',', $_POST['order_id']) : $this->redirect('/');
            if (empty($order_id)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Order')->deleteOrder((array) $order_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除购物车商品或套餐
     */
    public function delete_shopping_car() {
        if ($this->isPost() || $this->isAjax()) {
            $shopping_car_id = isset($_POST['shopping_car_id']) ? explode(',', $_POST['shopping_car_id']) : $this->redirect('/');
            if (empty($shopping_car_id)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('ShoppingCar')->deleteShoppingCar((array) $shopping_car_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户反馈
     */
    public function feedback() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $order_number = isset($_POST['order_number']) ? trim($_POST['order_number']) : $this->redirect('/');
            $shipping_service = isset($_POST['shipping_service']) ? intval($_POST['shipping_service']) : $this->redirect('/');
            $quality = isset($_POST['quality']) ? intval($_POST['quality']) : $this->redirect('/');
            $price = isset($_POST['price']) ? intval($_POST['price']) : $this->redirect('/');
            $postscript = (isset($_POST['postscript']) && !empty($_POST['postscript'])) ? trim($_POST['postscript']) : null;
            if ($user_id < 1 || empty($order_number) || !in_array($shipping_service, array(
                0,
                1
            )) || !in_array($quality, array(
                0,
                1
            )) || !in_array($price, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Feedback')->addFeedback($user_id, $order_number, $shipping_service, $quality, $price, $postscript));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 找回密码
     */
    public function find_password() {
        if ($this->isPost() || $this->isAjax()) {
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : $this->redirect('/');
            if (empty($phone) || empty($new_password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Member')->find_password($phone, $new_password));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取最新版本信息
     */
    public function last_version() {
        if ($this->isPost() || $this->isAjax()) {
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            if (!in_array($type, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    return $value;
                }, D('Version')->lastVersion($type))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 商品列表
     */
    public function goods() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $user_id = (isset($_POST['user_id']) && intval($_POST['user_id'])) ? intval($_POST['user_id']) : null;
            $p_cate_id = (isset($_POST['p_cate_id']) && intval($_POST['p_cate_id'])) ? intval($_POST['p_cate_id']) : null;
            $c_cate_id = (isset($_POST['c_cate_id']) && intval($_POST['c_cate_id'])) ? intval($_POST['c_cate_id']) : null;
            $tag = (isset($_POST['tag']) && isset($_POST['tag'])) ? intval($_POST['tag']) : null;
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('Goods')->_getGoodsList($offset, $pagesize, $user_id, $p_cate_id, $c_cate_id, $tag, $keyword))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户登录
     */
    public function login() {
        if ($this->isPost() || $this->isAjax()) {
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            if (empty($phone) || empty($password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Member')->login($phone, $password));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 消息中心
     */
    public function notification() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            if ($user_id < 1 || $offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['send_time'] = date("Y-m-d H:i:s", $value['send_time']);
                    return $value;
                }, D('SendHistory')->getSendHistory($user_id, $offset, $pagesize))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 提交订单
     */
    public function order() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : $this->redirect('/');
            $order = isset($_POST['order']) ? trim($_POST['order']) : $this->redirect('/');
            $coupon_id = (isset($_POST['coupon_id']) && intval($_POST['coupon_id'])) ? intval($_POST['coupon_id']) : null;
            $shipping_time = (isset($_POST['shipping_time']) && !empty($_POST['shipping_time'])) ? trim($_POST['shipping_time']) : null;
            $shipping_fee = isset($_POST['shipping_fee']) ? floatval($_POST['shipping_fee']) : $this->redirect('/');
            $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : $this->redirect('/');
            $remark = (isset($_POST['remark']) && !empty($_POST['remark'])) ? trim($_POST['remark']) : null;
            if ($user_id < 1 || $address_id < 1 || empty($order)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Order')->addOrder($user_id, $address_id, $order, $coupon_id, $shipping_time, $shipping_fee, $total_amount, $remark));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 订单列表
     */
    public function order_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            if ($user_id < 1 || $offset < 0 || $pagesize < 0 || !in_array($type, array(
                1,
                2
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('Order')->_getOrderList($user_id, $offset, $pagesize, $type))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 套餐列表
     */
    public function package() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('Package')->_getPackageList($offset, $pagesize, $keyword))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 大分类
     */
    public function parent_category() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('ParentCategory')->_getParentCategoryList($offset, $pagesize, $keyword))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户注册
     */
    public function register() {
        if ($this->isPost() || $this->isAjax()) {
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $recommend = (isset($_POST['recommend']) && !empty($_POST['recommend'])) ? trim($_POST['recommend']) : null;
            if (empty($phone) || empty($password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Member')->register($phone, $password, $recommend));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 退货申请
     */
    public function returns() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $order_number = isset($_POST['order_number']) ? trim($_POST['order_number']) : $this->redirect('/');
            $reason = isset($_POST['reason']) ? trim($_POST['reason']) : $this->redirect('/');
            $image_1 = (isset($_POST['image_1']) && !empty($_POST['image_1'])) ? trim($_POST['image_1']) : null;
            $image_2 = (isset($_POST['image_2']) && !empty($_POST['image_2'])) ? trim($_POST['image_2']) : null;
            $image_3 = (isset($_POST['image_3']) && !empty($_POST['image_3'])) ? trim($_POST['image_3']) : null;
            $postscript = (isset($_POST['postscript']) && !empty($_POST['postscript'])) ? trim($_POST['postscript']) : null;
            if ($user_id < 1 || empty($order_number) || empty($reason)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Returns')->addReturns($user_id, $order_number, $reason, $image_1, $image_2, $image_3, $postscript));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 配送地址
     */
    public function shipping_address() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('ShippingAddress')->_getShippingAddressList($offset, $pagesize))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取购物车列表
     */
    public function shopping_car() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            if ($user_id < 1 || $offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    return $value;
                }, D('ShoppingCar')->_getShoppingCar($user_id, $offset, $pagesize))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 商品标签
     */
    public function tag() {
        if ($this->isPost() || $this->isAjax()) {
            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : $this->redirect('/');
            $pagesize = isset($_POST['pagesize']) ? intval($_POST['pagesize']) : $this->redirect('/');
            $goods_amount = (isset($_POST['goods_amount']) && intval($_POST['goods_amount'])) ? intval($_POST['goods_amount']) : null;
            $keyword = (isset($_POST['keyword']) && !empty($_POST['keyword'])) ? trim($_POST['keyword']) : null;
            if ($offset < 0 || $pagesize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('Tag')->_getTagList($offset, $pagesize, $goods_amount, $keyword))
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新地址
     */
    public function update_address() {
        if ($this->isPost() || $this->isAjax()) {
            $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : $this->redirect('/');
            $consignee = (isset($_POST['consignee']) && !empty($_POST['consignee'])) ? trim($_POST['consignee']) : null;
            $phone = (isset($_POST['phone']) && !empty($_POST['phone'])) ? trim($_POST['phone']) : null;
            $province = (isset($_POST['province']) && !empty($_POST['province'])) ? trim($_POST['province']) : null;
            $city = (isset($_POST['city']) && !empty($_POST['city'])) ? trim($_POST['city']) : null;
            $district = (isset($_POST['district']) && !empty($_POST['district'])) ? trim($_POST['district']) : null;
            $community = (isset($_POST['community']) && !empty($_POST['community'])) ? trim($_POST['community']) : null;
            $address = (isset($_POST['address']) && !empty($_POST['address'])) ? trim($_POST['address']) : null;
            $_consignee = (isset($_POST['_consignee']) && !empty($_POST['_consignee'])) ? trim($_POST['_consignee']) : null;
            $_phone = (isset($_POST['_phone']) && !empty($_POST['_phone'])) ? trim($_POST['_phone']) : null;
            if ($address_id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Address')->updateAddress($address_id, $consignee, $phone, $province, $city, $district, $community, $address, $_consignee, $_phone));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新定制商品
     */
    public function update_custom_goods() {
        if ($this->isPost() || $this->isAjax()) {
            $custom_goods_id = isset($_POST['custom_goods_id']) ? intval($_POST['custom_goods_id']) : $this->redirect('/');
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : $this->redirect('/');
            if ($custom_goods_id < 1 || $quantity < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('CustomGoods')->updateCustomGoods($custom_goods_id, $quantity));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新用户
     */
    public function update_member() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $username = (isset($_POST['username']) && !empty($_POST['username'])) ? trim($_POST['username']) : null;
            $real_name = (isset($_POST['real_name']) && !empty($_POST['real_name'])) ? trim($_POST['real_name']) : null;
            $avatar = isset($_POST['avatar']) ? trim($_POST['avatar']) : null;
            $sex = isset($_POST['sex']) ? intval($_POST['sex']) : null;
            if ($id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Member')->updateMember($id, $username, $real_name, $avatar, $sex));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新订单状态
     */
    public function update_order() {
        if ($this->isPost() || $this->isAjax()) {
            $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : $this->redirect('/');
            $status = isset($_POST['status']) ? intval($_POST['status']) : $this->redirect('/');
            if ($order_id < 1 || !in_array($status, array(
                1,
                2,
                3,
                4
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('Order')->updateOrderStatus($order_id, $status));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新购物车
     */
    public function update_shopping_car() {
        if ($this->isPost() || $this->isAjax()) {
            $shopping_car_id = isset($_POST['shopping_car_id']) ? intval($_POST['shopping_car_id']) : $this->redirect('/');
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : $this->redirect('/');
            if ($shopping_car_id < 1 || $quantity < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $this->ajaxReturn(D('ShoppingCar')->updateShoppingCar($shopping_car_id, $quantity));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 验证码
     */
    public function validate_code() {
        if ($this->isPost() || $this->isAjax()) {
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            if (empty($phone)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $validate_code = rand(1000, 9999);
            if (sendSms($phone, "您好，您的验证码是{$validate_code}【鲜果送】")) {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => $validate_code
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

}