<?php

/**
 * Api Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class ApiAction extends Action {

    /**
     * 添加收藏API
     */
    public function addCollection() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $collection_id = isset($_POST['collection_id']) ? intval($_POST['collection_id']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            if ($user_id < 1 || $collection_id < 1 || !in_array($type, array(
                1,
                2
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $collection = M('Collection');
            // 开启事务
            $collection->startTrans();
            if ($collection->add(array(
                'user_id' => $user_id,
                'collection_id' => $collection_id,
                'type' => $type,
                'add_time' => time()
            ))) {
                // 添加成功，提交事务
                $collection->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '收藏成功'
                ));
            } else {
                // 添加失败，回滚事务
                $collection->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 我的收货地址列表API
     */
    public function address_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $page < 1 && $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $address = M('Address');
            $result = $address->where("user_id = {$user_id}")->limit(($page - 1) * $pageSize, $pageSize)->order("add_time DESC")->select();
            foreach ($result as &$value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 添加收货地址API
     */
    public function add_address() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : $this->redirect('/');
            $address = isset($_POST['address']) ? trim($_POST['address']) : $this->redirect('/');
            if ($user_id < 1 || empty($name) || empty($phone) || empty($address)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $addressModel = M('Address');
            $is_exists = $addressModel->where("user_id = {$user_id} AND name = \"{$name}\" AND phone = \"{$phone}\" AND zip = \"{$zip}\" AND address = \"{$address}\"")->count();
            if ($is_exists) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '您已经添加过这个地址'
                ));
            }
            // 开启事务
            $addressModel->startTrans();
            if ($addressModel->add(array(
                'user_id' => $user_id,
                'name' => $name,
                'phone' => $phone,
                'zip' => $zip,
                'address' => $address,
                'add_time' => time()
            ))) {
                // 添加成功，提交事务
                $addressModel->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '添加收货地址成功'
                ));
            } else {
                // 添加失败，回滚事务
                $addressModel->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 添加商品评论API
     */
    public function add_comment() {
        if ($this->isPost() || $this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            if ($goods_id < 1 || $user_id < 1 || empty($content)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $goodsComment = M('GoodsComment');
            // 开启事务
            $goodsComment->startTrans();
            if ($goodsComment->add(array(
                'goods_id' => $goods_id,
                'user_id' => $user_id,
                'content' => $content,
                'add_time' => time()
            ))) {
                // 添加成功，提交事务
                $goodsComment->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '添加评论成功'
                ));
            } else {
                // 添加失败，回滚事务
                $goodsComment->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '添加评论失败'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 添加资讯评论
     */
    public function add_news_comment() {
        if ($this->isPost() || $this->isAjax()) {
            $news_id = isset($_POST['news_id']) ? intval($_POST['news_id']) : $this->redirect('/');
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            if ($news_id < 1 || $user_id < 1 || empty($content)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $newsComment = M('NewsComment');
            // 开启事务
            $newsComment->startTrans();
            if ($newsComment->add(array(
                'news_id' => $news_id,
                'user_id' => $user_id,
                'content' => $content,
                'add_time' => time()
            ))) {
                // 添加成功，提交事务
                $newsComment->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '添加评论成功'
                ));
            } else {
                // 添加失败，回滚事务
                $newsComment->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '添加评论失败'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 添加订单API
     */
    public function add_order() {
        if ($this->isPost() || $this->isAjax()) {
            $order = isset($_POST['order']) ? $_POST['order'] : $this->redirect('/');
            if (empty($order)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $orderModel = D('Order');
            if ($orderModel->addOrder(json_decode($order))) {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '添加订单成功'
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '添加订单失败'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户添加真实姓名
     */
    public function add_real_name() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $real_name = isset($_POST['real_name']) ? trim($_POST['real_name']) : $this->redirect('/');
            if ($user_id < 1 || empty($real_name)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            if (M('Member')->where(array(
                'id' => $user_id
            ))->save(array(
                'real_name' => $real_name
            ))) {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '添加真实名称成功'
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

    /**
     * 添加客服
     */
    public function add_service() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : $this->redirect('/');
            if ($user_id < 1 || $service_id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            if (M('Member')->where(array(
                'id' => $user_id
            ))->save(array(
                'service' => $service_id
            ))) {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '添加客服成功'
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

    /**
     * 拍下用户发布
     */
    public function add_shopping_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $publisher_id = isset($_POST['publisher_id']) ? intval($_POST['publisher_id']) : $this->redirect('/');
            $publish_id = isset($_POST['publish_id']) ? intval($_POST['publish_id']) : $this->redirect('/');
            if ($user_id < 1 || $publisher_id < 1 || $publish_id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            if (M('Shopping')->add(array(
                'user_id' => $user_id,
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
                    'm.id' => $user_id
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
                    'status' => 1,
                    'result' => '拍下商品成功'
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

    /**
     * 添加店铺名称
     */
    public function add_shop_name() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $shop_name = isset($_POST['shop_name']) ? trim($_POST['shop_name']) : $this->redirect('/');
            if ($user_id < 1 || empty($shop_name)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            if (M('Member')->where(array(
                'id' => $user_id
            ))->save(array(
                'shop_name' => $shop_name
            ))) {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '添加店铺名称成功'
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

    /**
     * 获取广告列表
     */
    public function advertisement_list() {
        if ($this->isPost() || $this->isAjax()) {
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if (!in_array($type, array(
                1,
                2
            )) || $page < 0 || $pageSize < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $result = M('Advertisement')->where(array(
                'type' => $type
            ))->limit(($page - 1) * $pageSize, $pageSize)->select();
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
                }
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 商品分类API
     */
    public function category() {
        if ($this->isPost() || $this->isAjax()) {
            $category = M('Category');
            $result = M('Category')->field(array(
                'id',
                'name',
                'image',
                'add_time',
                'update_time'
            ))->order("id ASC")->select();
            foreach ($result as &$v) {
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除收货地址API
     */
    public function delete_address() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            if ($id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $address = M('Address');
            // 开启事务
            $address->startTrans();
            if ($address->where("id = {$id}")->delete()) {
                // 删除成功，提交事务
                $address->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '删除收获地址成功'
                ));
            } else {
                // 删除失败，回滚事务
                $address->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除收藏API
     */
    public function delete_collection() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            if ($id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $collection = M('Collection');
            // 开启事务
            $collection->startTrans();
            if ($collection->where("id = {$id}")->delete()) {
                // 删除成功，提交事务
                $collection->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '删除收藏成功'
                ));
            } else {
                // 删除失败，回滚事务
                $collection->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除发布API
     */
    public function delete_publish() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            if ($id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $publish = M('Publish');
            // 开启事务
            $publish->startTrans();
            if ($publish->where(array(
                'id' => $id
            ))->delete()) {
                $collection = M('Collection');
                if ((int) $collection->where(array(
                    'collection_id' => $id,
                    'type' => 2
                ))->count()) {
                    // 删除收藏了该发布的收藏，开启事务
                    $collection->startTrans();
                    if ($collection->where(array(
                        'collection_id' => $id,
                        'type' => 2
                    ))->delete()) {
                        // 删除成功，提交事务
                        $collection->commit();
                        $publish->commit();
                        $this->ajaxReturn(array(
                            'status' => 1,
                            'result' => '删除发布成功'
                        ));
                    } else {
                        // 删除失败，回滚事务
                        $collection->rollback();
                        $publish->rollback();
                        $this->ajaxReturn(array(
                            'status' => 0,
                            'result' => '删除该发布的收藏失败'
                        ));
                    }
                } else {
                    // 该发布没有被收藏过，提交事务
                    $publish->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => '删除发布成功'
                    ));
                }
            } else {
                // 删除失败，回滚事务
                $publish->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取APP封面
     */
    public function get_cover() {
        if ($this->isPost() || $this->isAjax()) {
            $result = M('Cover')->order("id DESC")->limit(1)->select();
            if (!empty($result)) {
                foreach ($result as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
                }
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 根据专区获取商品列表
     */
    public function get_goods_by_zone() {
        if ($this->isPost() || $this->isAjax()) {
            $zone = isset($_POST['zone']) ? intval($_POST['zone']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($zone < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $result = D('Goods')->getGoodsListByZone($zone, $page, $pageSize);
            foreach ($result as &$value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = date("Y-m-d H:i:s", $value['update_time']);
                $value['image'] = "http://{$_SERVER['HTTP_HOST']}{$value['image']}";
                $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                $value['image_4'] = $value['image_4'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_4']}" : $value['image_4'];
                $value['image_5'] = $value['image_5'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_5']}" : $value['image_5'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取用户发布列表
     */
    public function get_shopping_list() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $result = D('Shopping')->getShoppingList($user_id, $page, $pageSize);
            foreach ($result as &$value) {
                $value['image_1'] = $value['image_1'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_1']}" : $value['image_1'];
                $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                $value['publish_time'] = date("Y-m-d H:i:s", $value['publish_time']);
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取android app版本信息
     */
    public function get_version_info() {
        if ($this->isPost() || $this->isAjax()) {
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => M('Version')->where(array(
                    'type' => 0
                ))->order("add_time DESC")->find()
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取专区列表
     */
    public function get_zone_list() {
        if ($this->isPost() || $this->isAjax()) {
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $result = D('Zone')->getZoneList($page, $pageSize, "add_time", "DESC");
            foreach ($result as &$v) {
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取专区列表附带专区商品
     */
    public function get_zone_with_goods() {
        if ($this->isPost() || $this->isAjax()) {
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            $goodsSize = isset($_POST['goodsSize']) ? intval($_POST['goodsSize']) : $this->redirect('/');
            if ($page < 1 || $pageSize < 0 || $goodsSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $result = D('Zone')->getZoneList($page, $pageSize, "add_time", "DESC");
            foreach ($result as &$v) {
                $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                $v['image'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
                if ($goodsSize > 0) {
                    $v['goods_list'] = D('Goods')->getGoodsListByZone($v['id'], 1, $goodsSize);
                    foreach ($v['goods_list'] as &$value) {
                        $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                        $value['update_time'] = date("Y-m-d H:i:s", $value['update_time']);
                        $value['image'] = "http://{$_SERVER['HTTP_HOST']}{$value['image']}";
                        $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                        $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                        $value['image_4'] = $value['image_4'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_4']}" : $value['image_4'];
                        $value['image_5'] = $value['image_5'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_5']}" : $value['image_5'];
                    }
                }
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户反馈API
     */
    public function feedback() {
        if ($this->isPost() || $this->isAjax()) {
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            if (empty($content)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '内容不能为空'
                ));
            } else {
                $feedback = M('Feedback');
                // 开启事务
                $feedback->startTrans();
                if ($feedback->add(array(
                    'content' => $content,
                    'add_time' => time()
                ))) {
                    // 添加成功，提交事务
                    $feedback->commit();
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => '反馈成功'
                    ));
                } else {
                    // 添加失败，回滚事务
                    $feedback->rollback();
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '未知错误'
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 商品列表API
     */
    public function goods() {
        if ($this->isPost() || $this->isAjax()) {
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            $is_sell = isset($_POST['is_sell']) ? intval($_POST['is_sell']) : 1;
            $cate_id = isset($_POST['cate_id']) ? intval($_POST['cate_id']) : 0;
            $year = isset($_POST['year']) ? intval($_POST['year']) : 0;
            // 只传递年代却没有分类ID则会重定向
            if ($year > 0 && $cate_id <= 0) {
                $this->redirect('/');
            }
            if ($page < 1 || $pageSize < 0 || $cate_id < 0 || $year < 0 || !in_array($is_sell, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $goods = D('Goods');
            $result = $goods->goodsApiGetGoodsList($page, $pageSize, $cate_id, $year, $is_sell);
            foreach ($result as &$value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = date("Y-m-d H:i:s", $value['update_time']);
                $value['image'] = "http://{$_SERVER['HTTP_HOST']}{$value['image']}";
                $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                $value['image_4'] = $value['image_4'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_4']}" : $value['image_4'];
                $value['image_5'] = $value['image_5'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_5']}" : $value['image_5'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取商品评论列表API
     */
    public function goods_comment_list() {
        if ($this->isPost() || $this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($goods_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $goodsComment = D('GoodsComment');
            $result = $goodsComment->getGoodsCommentList($goods_id, $page, $pageSize);
            foreach ($result as &$v) {
                $v['comment_time'] = date("Y-m-d H:i:s", $v['comment_time']);
                $v['register_time'] = date("Y-m-d H:i:s", $v['register_time']);
                $v['last_time'] = $v['last_time'] ? date("Y-m-d H:i:s", $v['last_time']) : $v['last_time'];
                $v['avatar'] = $v['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$v['avatar']}" : $v['avatar'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 按关键词搜索商品API
     */
    public function ksearch() {
        if ($this->isPost() || $this->isAjax()) {
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : $this->redirect('/');
            $is_sell = isset($_POST['is_sell']) ? intval($_POST['is_sell']) : null;
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if (empty($keyword) || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $result = D('Goods')->getGoodsList($page, $pageSize, "id", "DESC", $keyword, '', $is_sell);
            foreach ($result as &$value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                $value['image'] = "http://{$_SERVER['HTTP_HOST']}{$value['image']}";
                $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                $value['image_4'] = $value['image_4'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_4']}" : $value['image_4'];
                $value['image_5'] = $value['image_5'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_5']}" : $value['image_5'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 登录API
     */
    public function login() {
        if ($this->isPost() || $this->isAjax()) {
            $account = isset($_POST['account']) ? trim($_POST['account']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : null;
            $open_id = isset($_POST['open_id']) ? trim($_POST['open_id']) : null;
            if (empty($account) || empty($password)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            if ($type) {
                if (!in_array($type, array(
                    1,
                    2
                )) || empty($open_id)) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '参数错误'
                    ));
                }
            }
            $result = M('Member')->field(array(
                'id',
                'account',
                'phone',
                'real_name',
                'shop_name',
                'wechat',
                'avatar',
                'sex',
                'level',
                'email',
                'service',
                'register_time',
                'last_time'
            ))->where(array(
                'account' => $account,
                'password' => md5($password)
            ))->select();
            // 用户名和密码不对应，不能登录和绑定
            if (empty($result)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '用户名或密码错误'
                ));
            }
            $result[0]['password'] = $password;
            // 如果传了open_id和type则就是将open_id和account绑定
            if ($type && $open_id) {
                $data = array(
                    'last_time' => time()
                );
                if ($type == 1) {
                    $data['qq_open_id'] = $open_id;
                } else if ($type == 2) {
                    $data['sina_open_id'] = $open_id;
                }
                // 绑定open_id到account并更新上一次登录时间
                if (M('Member')->where(array(
                    'id' => $result[0]['id']
                ))->save($data)) {
                    foreach ($result as &$value) {
                        $value['password'] = $password;
                        $value['register_time'] = date("Y-m-d H:i:s", $value['register_time']);
                        $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
                        $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                    }
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => $result
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '未知错误'
                    ));
                }
            } else {
                // 更新上一次登录时间
                if (M('Member')->where(array(
                    'id' => $result[0]['id']
                ))->save(array(
                    'last_time' => time()
                ))) {
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => $result
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '未知错误'
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 市场行情API
     */
    public function market() {
        if ($this->isPost() || $this->isAjax()) {
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : "";
            $cate_id = isset($_POST['cate_id']) ? intval($_POST['cate_id']) : null;
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($page < 1 || $pageSize < 0 || !in_array($type, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $goods = D('Goods');
            $result = $goods->getRiseOrReduceGoodsList($page, $pageSize, "update_time", "DESC", $keyword, $type, $cate_id);
            foreach ($result as &$value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                $value['image'] = "http://{$_SERVER['HTTP_HOST']}{$value['image']}";
                $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                $value['image_4'] = $value['image_4'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_4']}" : $value['image_4'];
                $value['image_5'] = $value['image_5'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_5']}" : $value['image_5'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 我的收藏列表API
     */
    public function my_collection() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $collection = D('Collection');
            $myCollectionList = $collection->getCollectionList($user_id, $page, $pageSize);
            $goods = array();
            $publish = array();
            $goodsModel = D('Goods');
            $publishModel = D('Publish');
            foreach ($myCollectionList as $v) {
                $collection_time = date("Y-m-d H:i:s", $v['add_time']);
                $collection_id = $v['id'];
                $avatar = $v['avatar'];
                if ($v['type'] == 1) {
                    if ($goodsModel->where("id = {$v['collection_id']}")->count()) {
                        $result = $goodsModel->getGoodsCollectionById($v['collection_id']);
                        foreach ($result as &$value) {
                            $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                            $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                            $value['image'] = "http://{$_SERVER['HTTP_HOST']}{$value['image']}";
                            $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                            $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                            $value['image_4'] = $value['image_4'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_4']}" : $value['image_4'];
                            $value['image_5'] = $value['image_5'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_5']}" : $value['image_5'];
                            $value['collection_time'] = $collection_time;
                            $value['collection_id'] = $collection_id;
                            $value['avatar'] = $avatar ? "http://{$_SERVER['HTTP_HOST']}{$avatar}" : $avatar;
                        }
                        $goods[] = $result;
                    } else {
                        continue;
                    }
                } else {
                    $result = $publishModel->getPublishCollectionById($v['collection_id']);
                    foreach ($result as &$value) {
                        $value['publish_time'] = date("Y-m-d H:i:s", $value['publish_time']);
                        $value['image_1'] = $value['image_1'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_1']}" : $value['image_1'];
                        $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                        $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                        $value['collection_time'] = $collection_time;
                        $value['collection_id'] = $collection_id;
                        $value['avatar'] = $avatar ? "http://{$_SERVER['HTTP_HOST']}{$avatar}" : $avatar;
                    }
                    $publish[] = $result;
                }
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'goods' => $goods,
                'publish' => $publish
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 我的发布API
     */
    public function my_publish() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($user_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $publish = D('Publish');
            $result = $publish->publishApiGetPublishList($page, $pageSize, $user_id);
            foreach ($result as &$value) {
                $value['image_1'] = $value['image_1'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_1']}" : $value['image_1'];
                $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                $value['publish_time'] = date("Y-m-d H:i:s", $value['publish_time']);
                $value['register_time'] = date("Y-m-d H:i:s", $value['register_time']);
                $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 资讯API
     */
    public function news() {
        if ($this->isPost() || $this->isAjax()) {
            $is_top = isset($_POST['is_top']) ? intval($_POST['is_top']) : $this->redirect('/');
            if (!in_array($is_top, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $news = M('News');
            $news->where("is_top = {$is_top}")->order("update_time DESC");
            $result = $is_top ? $news->limit(0, 5)->select() : $news->limit(0, 6)->select();
            foreach ($result as &$value) {
                $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                $value['update_time'] = date("Y-m-d H:i:s", $value['update_time']);
                $value['image'] = 'http://' . $_SERVER['HTTP_HOST'] . $value['image'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 获取资讯评论列表
     */
    public function news_comment_list() {
        if ($this->isPost() || $this->isAjax()) {
            $news_id = isset($_POST['news_id']) ? intval($_POST['news_id']) : $this->redirect('/');
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($news_id < 1 || $page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $newsComment = D('NewsComment');
            $result = $newsComment->getNewsCommentList($news_id, $page, $pageSize);
            foreach ($result as &$v) {
                $v['comment_time'] = date("Y-m-d H:i:s", $v['comment_time']);
                $v['register_time'] = date("Y-m-d H:i:s", $v['register_time']);
                $v['last_time'] = $v['last_time'] ? date("Y-m-d H:i:s", $v['last_time']) : $v['last_time'];
                $v['avatar'] = $v['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$v['avatar']}" : $v['avatar'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 第三方登录
     */
    public function open_login() {
        if ($this->isPost() || $this->isAjax()) {
            $open_id = isset($_POST['open_id']) ? trim($_POST['open_id']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            if (empty($open_id) || !in_array($type, array(
                1,
                2
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $where = array();
            if ($type == 1) {
                $where['qq_open_id'] = $open_id;
            } else if ($type == 2) {
                $where['sina_open_id'] = $open_id;
            }
            $result = M('Member')->field(array(
                'id',
                'account',
                'phone',
                'real_name',
                'shop_name',
                'wechat',
                'avatar',
                'sex',
                'level',
                'email',
                'service',
                'register_time',
                'last_time'
            ))->where($where)->select();
            if ($result) {
                if (M('Member')->where(array(
                    'id' => $result[0]['id']
                ))->save(array(
                    'last_time' => time()
                ))) {
                    foreach ($result as &$value) {
                        $value['password'] = null;
                        $value['register_time'] = date("Y-m-d H:i:s", $value['register_time']);
                        $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
                        $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                    }
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => $result
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '未知错误'
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => 0
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户发布API
     */
    public function publish() {
        if ($this->isPost() || $this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : $this->redirect('/');
            $amount = isset($_POST['amount']) ? intval($_POST['amount']) : $this->redirect('/');
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $business_number = isset($_POST['business_number']) ? trim($_POST['business_number']) : $this->redirect('/');
            $batch = isset($_POST['batch']) ? trim($_POST['batch']) : $this->redirect('/');
            $is_buy = isset($_POST['is_buy']) ? intval($_POST['is_buy']) : $this->redirect('/');
            $is_distribute = isset($_POST['is_distribute']) ? intval($_POST['is_distribute']) : $this->redirect('/');
            $image_1 = isset($_POST['image_1']) ? trim($_POST['image_1']) : null;
            $image_2 = isset($_POST['image_2']) ? trim($_POST['image_2']) : null;
            $image_3 = isset($_POST['image_3']) ? trim($_POST['image_3']) : null;
            $supply = isset($_POST['supply']) ? trim($_POST['supply']) : null;
            if ($user_id < 1 || empty($name) || empty($brand_name) || $amount < 0 || $price < 0 || !in_array($is_buy, array(
                0,
                1
            )) || !in_array($is_distribute, array(
                0,
                1
            )) || empty($business_number) || empty($batch)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            if ($image_1) {
            }
            // 保存图片
            if ($image_1) {
                $image_1 = $this->saveImage('publish_', $image_1);
                $image_1 || $this->ajaxReturn(array(
                    'status' => 0,
                    'msg' => '保存图片1失败'
                ));
            }
            if ($image_2) {
                $image_2 = $this->saveImage('publish_', $image_2);
                $image_2 || $this->ajaxReturn(array(
                    'status' => 0,
                    'msg' => '保存图片2失败'
                ));
            }
            if ($image_3) {
                $image_3 = $this->saveImage('publish_', $image_3);
                $image_3 || $this->ajaxReturn(array(
                    'status' => 0,
                    'msg' => '保存图片3失败'
                ));
            }
            $publish = M('Publish');
            // 开启事务
            $publish->startTrans();
            if ($publish->add(array(
                'user_id' => $user_id,
                'name' => $name,
                'brand_name' => $brand_name,
                'amount' => $amount,
                'unit' => $unit,
                'price' => $price,
                'business_number' => $business_number,
                'batch' => $batch,
                'is_buy' => $is_buy,
                'is_distribute' => $is_distribute,
                'image_1' => $image_1,
                'image_2' => $image_2,
                'image_3' => $image_3,
                'supply' => $supply,
                'publish_time' => time()
            ))) {
                // 添加成功，提交事务
                $publish->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '发布成功'
                ));
            } else {
                // 添加失败，回滚事务
                $publish->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '发布失败'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 用户发布列表
     */
    public function publish_list() {
        if ($this->isPost() || $this->isAjax()) {
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : null;
            if ($page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $publish = D('Publish');
            $result = $publish->publishApiGetPublishList($page, $pageSize, null, $keyword);
            foreach ($result as &$value) {
                $value['image_1'] = $value['image_1'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_1']}" : $value['image_1'];
                $value['image_2'] = $value['image_2'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_2']}" : $value['image_2'];
                $value['image_3'] = $value['image_3'] ? "http://{$_SERVER['HTTP_HOST']}{$value['image_3']}" : $value['image_3'];
                $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                $value['publish_time'] = date("Y-m-d H:i:s", $value['publish_time']);
                $value['register_time'] = date("Y-m-d H:i:s", $value['register_time']);
                $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 注册API
     */
    public function register() {
        if ($this->isPost() || $this->isAjax()) {
            $account = isset($_POST['account']) ? trim($_POST['account']) : $this->redirect('/');
            $password = isset($_POST['password']) ? trim($_POST['password']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : null;
            $open_id = isset($_POST['open_id']) ? trim($_POST['open_id']) : null;
            if (empty($account) || empty($password) || empty($phone)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            if ($type) {
                if (!in_array($type, array(
                    1,
                    2
                )) || empty($open_id)) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '参数错误'
                    ));
                }
            }
            // 判断用户名是否存在
            if (M('Member')->where(array(
                'account' => $account
            ))->count()) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '用户名已经存在'
                ));
            }
            // 如果传上open_id和type则为注册一个新帐号并将新帐号和open_id绑定
            if ($type && $open_id) {
                $data = array(
                    'account' => $account,
                    'password' => md5($password),
                    'phone' => $phone,
                    'register_time' => time(),
                    'last_time' => time()
                );
                if ($type == 1) {
                    $data['qq_open_id'] = $open_id;
                } else if ($type == 2) {
                    $data['sina_open_id'] = $open_id;
                }
                if (M('Member')->add($data)) {
                    $result = M('Member')->field(array(
                        'id',
                        'account',
                        'phone',
                        'real_name',
                        'shop_name',
                        'wechat',
                        'avatar',
                        'sex',
                        'level',
                        'email',
                        'service',
                        'register_time',
                        'last_time'
                    ))->where($data)->select();
                    foreach ($result as &$value) {
                        $value['password'] = $password;
                        $value['register_time'] = date("Y-m-d H:i:s", $value['register_time']);
                        $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
                        $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                    }
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => $result
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '未知错误'
                    ));
                }
            } else {
                if (M('Member')->add(array(
                    'account' => $account,
                    'password' => md5($password),
                    'phone' => $phone,
                    'register_time' => time()
                ))) {
                    $this->ajaxReturn(array(
                        'status' => 1,
                        'result' => '注册成功'
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '未知错误'
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 客服列表API
     */
    public function service() {
        if ($this->isPost() || $this->isAjax()) {
            $page = isset($_POST['page']) ? intval($_POST['page']) : $this->redirect('/');
            $pageSize = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : $this->redirect('/');
            if ($page < 1 || $pageSize < 0) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $service = D('Service');
            $result = $service->getServiceList($page, $pageSize, "update_time", "DESC");
            foreach ($result as &$value) {
                $value['image'] = 'http://' . $_SERVER['HTTP_HOST'] . $value['image'];
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'result' => $result
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新收货地址
     */
    public function update_address() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : $this->redirect('/');
            $address = isset($_POST['address']) ? trim($_POST['address']) : $this->redirect('/');
            if ($id < 1 || $user_id < 1 || empty($name) || empty($phone) || empty($address)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $addressModel = M('Address');
            $is_exists = $addressModel->where("user_id = {$user_id} AND name = \"{$name}\" AND phone = \"{$phone}\" AND zip = \"{$zip}\" AND address = \"{$address}\" AND id != {$id}")->count();
            if ($is_exists) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '您已经添加过这个收货地址'
                ));
            }
            // 开启事务
            $addressModel->startTrans();
            if ($addressModel->where("id = {$id}")->save(array(
                'user_id' => $user_id,
                'name' => $name,
                'phone' => $phone,
                'zip' => $zip,
                'address' => $address,
                'update_time' => time()
            ))) {
                // 更新成功，提交事务
                $addressModel->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '更新收货地址成功'
                ));
            } else {
                // 更新失败，回滚事务
                $addressModel->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新用户资料API
     */
    public function update_member() {
        if ($this->isPost() || $this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $account = isset($_POST['account']) ? trim($_POST['account']) : null;
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
            $sex = isset($_POST['sex']) ? intval($_POST['sex']) : null;
            $avatar = isset($_POST['avatar']) ? trim($_POST['avatar']) : null;
            $wechat = isset($_POST['wechat']) ? trim($_POST['wechat']) : null;
            if ($id < 1) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $member = M('Member');
            $data = array();
            if ($account) {
                if ($member->where(array(
                    'account' => $account,
                    'id' => array(
                        'neq',
                        $id
                    )
                ))->count()) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '用户名已经存在'
                    ));
                } else {
                    $data['account'] = $account;
                }
            }
            $phone && $data['phone'] = $phone;
            if (!is_null($sex)) {
                if (!in_array($sex, array(
                    0,
                    1
                ))) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'result' => '参数错误'
                    ));
                } else {
                    $data['sex'] = $sex;
                }
            }
            $wechat && $data['wechat'] = $wechat;
            if ($avatar) {
                $result = $member->field('avatar')->where("id = {$id}")->find();
                if ($result['avatar']) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $result['avatar'])) {
                        if (unlink($_SERVER['DOCUMENT_ROOT'] . $result['avatar'])) {
                            $data['avatar'] = $this->saveImage('member_', $avatar);
                        } else {
                            $this->ajaxReturn(array(
                                'status' => 0,
                                'result' => '删除用户旧头像失败'
                            ));
                        }
                    }
                } else {
                    $data['avatar'] = $this->saveImage('member_', $avatar);
                }
            }
            if (empty($data)) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '没有要更新的资料'
                ));
            }
            if ($member->where("id = {$id}")->save($data)) {
                $user_info = $member->field(array(
                    'id',
                    'account',
                    'phone',
                    'real_name',
                    'shop_name',
                    'wechat',
                    'avatar',
                    'sex',
                    'level',
                    'email',
                    'service',
                    'register_time',
                    'last_time'
                ))->where("id = {$id}")->select();
                foreach ($user_info as &$value) {
                    $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
                    $value['register_time'] = date("Y-m-d H:i:s", $value['register_time']);
                    $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
                }
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => $user_info
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

    /**
     * 更新订单状态
     */
    public function update_order() {
        if ($this->isPost() || $this->isAjax()) {
            $order_number = isset($_POST['order_number']) ? trim($_POST['order_number']) : $this->redirect('/');
            $status = isset($_POST['status']) ? intval($_POST['status']) : $this->redirect('/');
            if (empty($order_number) || !in_array($status, array(
                0,
                1
            ))) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '参数错误'
                ));
            }
            $order = M('Order');
            // 开启事务
            $order->startTrans();
            if ($order->where("order_number = \"{$order_number}\"")->save(array(
                'status' => $status,
                'update_time' => time()
            ))) {
                // 更新成功，提交事务
                $order->commit();
                $this->ajaxReturn(array(
                    'status' => 1,
                    'result' => '更新订单成功'
                ));
            } else {
                // 更新失败，回滚事务
                $order->rollback();
                $this->ajaxReturn(array(
                    'status' => 0,
                    'result' => '未知错误'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 保存图片
     *
     * @param string $prefix
     *            保存图片名前缀
     * @param string $imgCode
     *            base64_encode的图片字符串
     * @return string boolean
     */
    private function saveImage($prefix, $imgCode) {
        $fileName = $prefix . time() . rand(1000, 9999) . ".png";
        $img = base64_decode($imgCode);
        if (file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/uploads/{$fileName}", $img)) {
            return "/uploads/{$fileName}";
        } else {
            return false;
        }
    }

}
