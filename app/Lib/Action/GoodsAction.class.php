<?php

/**
 * 商品Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class GoodsAction extends AdminAction {

    /**
     * 添加商品
     */
    public function add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $single_price = isset($_POST['single_price']) ? floatval($_POST['single_price']) : $this->redirect('/');
            $_price = isset($_POST['_price']) ? trim($_POST['_price']) : $this->redirect('/');
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : $this->redirect('/');
            $single_unit = isset($_POST['single_unit']) ? trim($_POST['single_unit']) : $this->redirect('/');
            $priority = isset($_POST['priority']) ? intval($_POST['priority']) : $this->redirect('/');
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $tag = isset($_POST['tag']) ? intval($_POST['tag']) : $this->redirect('/');
            $amount = isset($_POST['amount']) ? trim($_POST['amount']) : $this->redirect('/');
            $weight = isset($_POST['weight']) ? trim($_POST['weight']) : $this->redirect('/');
            $thumb_image = isset($_POST['thumb_image']) ? trim($_POST['thumb_image']) : $this->redirect('/');
            $introduction_image = isset($_POST['introduction_image']) ? (array) $_POST['introduction_image'] : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->addGoods($name, $price, $single_price, $_price, $unit, $single_unit, $priority, $p_cate_id, $c_cate_id, $tag, $amount, $weight, $thumb_image, $introduction_image, $description));
        } else {
            $this->assign('parentCategory', M('ParentCategory')->select());
            $this->assign('tag', M('Tag')->select());
            $this->display();
        }
    }

    /**
     * 广告设置
     */
    public function advertisement() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            if ($type == 0) {
                $this->ajaxReturn(D('Advertisement')->deleteAdvertisement(array(
                    'goods_id' => $goods_id
                )));
            } else if ($type == 1) {
                $this->ajaxReturn(D('Advertisement')->addAdvertisement(array(
                    'goods_id' => $goods_id
                )));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 检测商品权重
     */
    public function check_priority() {
        if ($this->isAjax()) {
            $priority = isset($_POST['priority']) ? intval($_POST['priority']) : $this->redirect('/');
            $id = isset($_POST['id']) ? intval($_POST['id']) : null;
            $this->ajaxReturn(D('Goods')->checkGoodsPriority($priority, $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除商品
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->deleteGoods((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除图片
     */
    public function delete_image() {
        if ($this->isAjax()) {
            $filename = isset($_POST['filename']) ? $_SERVER['DOCUMENT_ROOT'] . $_POST['filename'] : $this->redirect('/');
            if (file_exists($filename)) {
                if (unlink($filename)) {
                    $this->ajaxReturn(array(
                        'status' => true
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => false,
                        'msg' => '删除失败'
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '图片已经删除'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 所有商品
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $p_cate_id = isset($_GET['p_cate_id']) ? intval($_GET['p_cate_id']) : 0;
        $c_cate_id = isset($_GET['c_cate_id']) ? intval($_GET['c_cate_id']) : 0;
        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $goods = D('Goods');
            $total = $goods->getGoodsCount($keyword, $p_cate_id, $c_cate_id, $status);
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['description'] = strip_tags($v['description']);
                    return $v;
                }, $goods->getGoodsList($page, $pageSize, $order, $sort, $keyword, $p_cate_id, $c_cate_id, $status));
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->assign('keyword', $keyword);
            $this->assign('p_cate_id', $p_cate_id);
            $this->assign('c_cate_id', $c_cate_id);
            $this->assign('status', $status);
            $this->assign('parentCategory', M('ParentCategory')->select());
            $this->assign('childCategory', M('ChildCategory')->select());
            $tag = M('Tag')->field(array(
                'id',
                'name' => 'text'
            ))->select();
            array_unshift($tag, array(
                'id' => 0,
                'text' => '暂无'
            ));
            $this->assign('tag', json_encode($tag));
            $this->display();
        }
    }

    /**
     * 商品编辑（更新）
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $id || $this->redirect('/');
        $goods = D('Goods');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $single_price = isset($_POST['single_price']) ? floatval($_POST['single_price']) : $this->redirect('/');
            $_price = isset($_POST['_price']) ? trim($_POST['_price']) : $this->redirect('/');
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : $this->redirect('/');
            $single_unit = isset($_POST['single_unit']) ? trim($_POST['single_unit']) : $this->redirect('/');
            $priority = isset($_POST['priority']) ? intval($_POST['priority']) : $this->redirect('/');
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $tag = isset($_POST['tag']) ? intval($_POST['tag']) : $this->redirect('/');
            $amount = isset($_POST['amount']) ? trim($_POST['amount']) : $this->redirect('/');
            $weight = isset($_POST['weight']) ? trim($_POST['weight']) : $this->redirect('/');
            $thumb_image = isset($_POST['thumb_image']) ? trim($_POST['thumb_image']) : $this->redirect('/');
            $introduction_image = isset($_POST['introduction_image']) ? (array) $_POST['introduction_image'] : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $this->ajaxReturn($goods->updateGoods($id, $name, $price, $single_price, $_price, $unit, $single_unit, $priority, $p_cate_id, $c_cate_id, $tag, $amount, $weight, $thumb_image, $introduction_image, $description));
        } else {
            $goodsAssign = M('Goods')->where(array(
                'id' => $id
            ))->find();
            $this->assign('goods', $goodsAssign);
            $this->assign('parentCategory', M('ParentCategory')->select());
            $this->assign('childCategory', M('ChildCategory')->where(array(
                'parent_id' => $goodsAssign['p_cate_id']
            ))->select());
            $this->assign('tag', M('Tag')->select());
            $image_count = array();
            for ($i = 1; $i <= 5; $i++) {
                if ($goodsAssign["image_{$i}"]) {
                    $image_count[] = $goodsAssign["image_{$i}"];
                }
            }
            $this->assign('introduction_image', $image_count);
            $this->assign('image_count', json_encode($image_count));
            $this->display();
        }
    }

    /**
     * 更新商品权重
     */
    public function update_priority() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $priority = isset($_POST['priority']) ? intval($_POST['priority']) : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->updateGoodsPriority($goods_id, $priority));
        } else {
            $id = (isset($_GET['id']) && intval($_GET['id'])) ? intval($_GET['id']) : $this->redirect('/');
            $this->assign('goods', M('Goods')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

    /**
     * 更新商品状态
     */
    public function update_status() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $status = isset($_POST['status']) ? intval($_POST['status']) : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->updateGoodsStatus($goods_id, $status));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新商品标签
     */
    public function update_tag() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $tag = isset($_POST['tag']) ? trim($_POST['tag']) : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->updateGoodsTag($id, $tag));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 上传商品图片
     */
    public function upload() {
        if (!empty($_FILES)) {
            $this->ajaxReturn(upload($_FILES, C('MAX_SIZE'), C('ALLOW_EXTENSIONS')));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 商品简介内部图片上传
     */
    public function upload_image() {
        if (!empty($_FILES)) {
            $year = date("Y");
            $month = date("m");
            $day = date("d");
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/{$year}/{$month}/{$day}";
            if (!file_exists($targetPath)) {
                mkdir($targetPath, 0755, true);
            }
            if ($_FILES['upload']['size'] > C('MAX_SIZE')) {
                echo "<script type='text/javascript'>
                        window.parent.CKEDITOR.tools.callFunction(0, '', '图片不能大于2M');
                        </script>";
            } else {
                $fileParts = pathinfo($_FILES['upload']['name']);
                $tempFile = $_FILES['upload']['tmp_name'];
                if (in_array(strtolower($fileParts['extension']), C('ALLOW_EXTENSIONS'))) {
                    $uploadFileName = generateTargetFileName($fileParts['extension']);
                    $targetFile = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $uploadFileName;
                    move_uploaded_file($tempFile, $targetFile);
                    $fileName = "http://{$_SERVER['HTTP_HOST']}/uploads/{$year}/{$month}/{$day}/" . $uploadFileName;
                    $funcNum = $_GET['CKEditorFuncNum'];
                    echo "<script type='text/javascript'>
                    window.parent.CKEDITOR.tools.callFunction($funcNum, '$fileName');
                    </script>";
                } else {
                    echo "<script type='text/javascript'>
                            window.parent.CKEDITOR.tools.callFunction(0, '', '不支持的图片格式');
                            </script>";
                }
            }
        } else {
            $this->redirect('/');
        }
    }

}