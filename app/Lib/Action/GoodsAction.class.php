<?php

/**
 * 茶叶超市Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class GoodsAction extends AdminAction {

    /**
     * 添加茶叶商品
     */
    public function add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $_price = isset($_POST['_price']) ? trim($_POST['_price']) : $this->redirect('/');
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : $this->redirect('/');
            $weight = isset($_POST['weight']) ? intval($_POST['weight']) : $this->redirect('/');
            $stock = isset($_POST['stock']) ? trim($_POST['stock']) : $this->redirect('/');
            $year = isset($_POST['year']) ? intval($_POST['year']) : $this->redirect('/');
            $cate = isset($_POST['cate']) ? intval($_POST['cate']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $product_art = isset($_POST['product_art']) ? intval($_POST['product_art']) : $this->redirect('/');
            $series = isset($_POST['series']) ? intval($_POST['series']) : $this->redirect('/');
            $zone = isset($_POST['zone']) ? intval($_POST['zone']) : $this->redirect('/');
            $is_sell = isset($_POST['is_sell']) ? intval($_POST['is_sell']) : $this->redirect('/');
            $production = isset($_POST['production']) ? trim($_POST['production']) : $this->redirect('/');
            $specification = isset($_POST['specification']) ? trim($_POST['specification']) : $this->redirect('/');
            $burden = isset($_POST['burden']) ? trim($_POST['burden']) : $this->redirect('/');
            $producer = isset($_POST['producer']) ? trim($_POST['producer']) : $this->redirect('/');
            $storage = isset($_POST['storage']) ? trim($_POST['storage']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $url = isset($_POST['url']) ? trim($_POST['url']) : $this->redirect('/');
            $image = isset($_POST['image']) ? (array) $_POST['image'] : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->addGoods($name, $price, $_price, $unit, $image, $weight, $stock, $year, $cate, $type, $product_art, $series, $zone, $is_sell, $url, $production, $specification, $burden, $producer, $storage, $description));
        } else {
            $this->assign('cate', M('Category')->field(array(
                'id',
                'name'
            ))->order('id ASC')->select());
            $this->assign('series', M('Series')->field(array(
                'id',
                'name'
            ))->order('id ASC')->select());
            $this->assign('zone', M('Zone')->field(array(
                'id',
                'name'
            ))->order("id ASC")->select());
            $this->display();
        }
    }

    /**
     * 删除商品
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $goods = D('Goods');
            $this->ajaxReturn($goods->deleteGoods((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除图片
     */
    public function delete_image() {
        if ($this->isAjax()) {
            $filename = isset($_POST['filename']) ? trim($_POST['filename']) : $this->redirect('/');
            if (empty($filename)) {
                $this->ajaxReturn(array(
                    'status' => true
                ));
            } else {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filename)) {
                    if (unlink($_SERVER['DOCUMENT_ROOT'] . $filename)) {
                        $this->ajaxReturn(array(
                            'status' => true
                        ));
                    } else {
                        $this->ajaxReturn(array(
                            'status' => false,
                            'msg' => '删除图片失败'
                        ));
                    }
                } else {
                    $this->ajaxReturn(array(
                        'status' => true
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 茶叶超市
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $year = isset($_GET['year']) ? trim($_GET['year']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $goods = D('Goods');
            $total = $goods->getGoodsCount($keyword, intval($year));
            if ($total) {
                $rows = $goods->getGoodsList($page, $pageSize, $order, $sort, $keyword, intval($year));
                foreach ($rows as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                }
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->assign('keyword', $keyword);
            $this->assign('year', $year);
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
            $_price = isset($_POST['_price']) ? trim($_POST['_price']) : $this->redirect('/');
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : $this->redirect('/');
            $weight = isset($_POST['weight']) ? intval($_POST['weight']) : $this->redirect('/');
            $stock = isset($_POST['stock']) ? trim($_POST['stock']) : $this->redirect('/');
            $year = isset($_POST['year']) ? intval($_POST['year']) : $this->redirect('/');
            $cate = isset($_POST['cate']) ? intval($_POST['cate']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $product_art = isset($_POST['product_art']) ? intval($_POST['product_art']) : $this->redirect('/');
            $series = isset($_POST['series']) ? intval($_POST['series']) : $this->redirect('/');
            $zone = isset($_POST['zone']) ? intval($_POST['zone']) : $this->redirect('/');
            $is_sell = isset($_POST['is_sell']) ? intval($_POST['is_sell']) : $this->redirect('/');
            $production = isset($_POST['production']) ? trim($_POST['production']) : $this->redirect('/');
            $specification = isset($_POST['specification']) ? trim($_POST['specification']) : $this->redirect('/');
            $burden = isset($_POST['burden']) ? trim($_POST['burden']) : $this->redirect('/');
            $producer = isset($_POST['producer']) ? trim($_POST['producer']) : $this->redirect('/');
            $storage = isset($_POST['storage']) ? trim($_POST['storage']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $url = isset($_POST['url']) ? trim($_POST['url']) : $this->redirect('/');
            $image = isset($_POST['image']) ? (array) $_POST['image'] : $this->redirect('/');
            $this->ajaxReturn($goods->updateGoods($id, $name, $price, $_price, $unit, $image, $weight, $stock, $year, $cate, $type, $product_art, $series, $zone, $is_sell, $url, $production, $specification, $burden, $producer, $storage, $description));
        } else {
            $this->assign('cate', M('Category')->field(array(
                'id',
                'name'
            ))->order('id ASC')->select());
            $this->assign('series', M('Series')->field(array(
                'id',
                'name'
            ))->order('id ASC')->select());
            $this->assign('zone', M('Zone')->field(array(
                'id',
                'name'
            ))->order('id ASC')->select());
            $goodsAssign = $goods->where("id = {$id}")->find();
            $src[] = array(
                "src" => 'http://' . $_SERVER['HTTP_HOST'] . $goodsAssign['image'],
                "filename" => $goodsAssign['image']
            );
            $image_count = "'" . $goodsAssign['image'] . "'";
            for ($i = 2; $i <= 5; $i++) {
                if ($goodsAssign["image_{$i}"]) {
                    $src[] = array(
                        "src" => 'http://' . $_SERVER['HTTP_HOST'] . $goodsAssign["image_{$i}"],
                        "filename" => $goodsAssign["image_{$i}"]
                    );
                    $image_count .= "," . "'" . $goodsAssign["image_{$i}"] . "'";
                }
            }
            $this->assign('goods', $goodsAssign);
            $this->assign('src', $src);
            $this->assign('image_count', $image_count);
            $this->display();
        }
    }

    /**
     * 上传商品图片
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['files']['size'][0] > C('GOODS_MAX_UPLOAD_FILE_SIZE')) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '图片文件过大，请选择另外的图片'
                ));
            } else {
                $fileParts = pathinfo($_FILES['files']['name'][0]);
                $tempFile = $_FILES['files']['tmp_name'][0];
                if (in_array($fileParts['extension'], C('GOODS_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
                    $uploadFileName = $this->generateTargetFileName($fileParts['extension']);
                    $targetFile = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $uploadFileName;
                    move_uploaded_file($tempFile, $targetFile);
                    $this->ajaxReturn(array(
                        'status' => true,
                        'src' => 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $uploadFileName,
                        'filename' => '/uploads/' . $uploadFileName
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => false,
                        'msg' => '不支持的图片格式'
                    ));
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 生成上传文件的名称
     *
     * @param string $extension
     *            扩展名
     * @return string
     */
    private function generateTargetFileName($extension) {
        return "goods_" . time() . rand(1000, 9999) . "." . $extension;
    }

}