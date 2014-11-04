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
            $_price = isset($_POST['_price']) ? trim($_POST['_price']) : $this->redirect('/');
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : $this->redirect('/');
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $image = isset($_POST['image']) ? (array) $_POST['image'] : $this->redirect('/');
            $this->ajaxReturn(D('Goods')->addGoods($name, $price, $_price, $unit, $p_cate_id, $c_cate_id, $description, $image));
        } else {
            $this->assign('parentCategory', M('ParentCategory')->select());
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
            $id = isset($_POST['id']) ? intval($_POST['id']) : null;
            if ($id) {
                $goodsImage = M('GoodsImage');
                // 开始事务
                $goodsImage->startTrans();
                if ($goodsImage->where(array(
                    'id' => $id
                ))->delete()) {
                    if ($this->delete_local_image($filename)) {
                        // 删除成功，提交事务
                        $goodsImage->commit();
                        $this->ajaxReturn(array(
                            'status' => true
                        ));
                    } else {
                        // 删除失败，回滚事务
                        $goodsImage->rollback();
                        $this->ajaxReturn(array(
                            'status' => false,
                            'msg' => '删除图片失败'
                        ));
                    }
                } else {
                    // 删除失败，回滚事务
                    $goodsImage->rollback();
                    $this->ajaxReturn(array(
                        'status' => false,
                        'msg' => '删除图片失败'
                    ));
                }
            } else {
                if ($this->delete_local_image($filename)) {
                    $this->ajaxReturn(array(
                        'status' => true
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'status' => false,
                        'msg' => '删除图片失败'
                    ));
                }
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
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $goods = D('Goods');
            $total = $goods->getGoodsCount($keyword, intval($year));
            if ($total) {
                $rows = $goods->getGoodsList($page, $pageSize, $order, $sort, $keyword);
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
            $p_cate_id = isset($_POST['p_cate_id']) ? intval($_POST['p_cate_id']) : $this->redirect('/');
            $c_cate_id = isset($_POST['c_cate_id']) ? intval($_POST['c_cate_id']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $image = isset($_POST['image']) ? (array) $_POST['image'] : array();
            $this->ajaxReturn($goods->updateGoods($id, $name, $price, $_price, $unit, $p_cate_id, $c_cate_id, $description, $image));
        } else {
            $goodsAssign = M('Goods')->where(array(
                'id' => $id
            ))->find();
            $this->assign('goods', $goodsAssign);
            $this->assign('parentCategory', M('ParentCategory')->select());
            $this->assign('childCategory', M('ChildCategory')->where(array(
                'parent_id' => $goodsAssign['p_cate_id']
            ))->select());
            $goodsImages = M('GoodsImage')->where(array(
                'goods_id' => $id
            ))->select();
            $this->assign('goodsImages', $goodsImages);
            $image_count = array();
            foreach ($goodsImages as $v) {
                $image_count[] = $v['image'];
            }
            $this->assign('image_count', json_encode($image_count));
            $this->display();
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
     * 删除本地图片
     *
     * @param string $filename
     *            图片文件名（相对于根目录）
     * @return boolean
     */
    private function delete_local_image($filename) {
        if (empty($filename)) {
            return false;
        } else {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filename)) {
                if (unlink($_SERVER['DOCUMENT_ROOT'] . $filename)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    }

}