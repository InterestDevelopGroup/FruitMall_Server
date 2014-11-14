<?php

/**
 * 套餐Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class PackageAction extends AdminAction {

    /**
     * 添加套餐
     */
    public function add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $_price = isset($_POST['_price']) ? trim($_POST['_price']) : $this->redirect('/');
            $thumb_image = isset($_POST['thumb_image']) ? trim($_POST['thumb_image']) : $this->redirect('/');
            $introduction_image = isset($_POST['introduction_image']) ? (array) $_POST['introduction_image'] : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $this->ajaxReturn(D('Package')->addPackage($name, $price, $_price, $thumb_image, $introduction_image, $description));
        } else {
            $this->display();
        }
    }

    /**
     * 删除套餐
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Package')->deletePackage((array) $id));
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
     * 所有套餐
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $package = D('Package');
            $total = $package->getPackageCount($keyword);
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['description'] = strip_tags($v['description']);
                    return $v;
                }, $package->getPackageList($page, $pageSize, $order, $sort, $keyword));
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
     * 套餐编辑（更新）
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $id || $this->redirect('/');
        $package = D('Package');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $price = isset($_POST['price']) ? floatval($_POST['price']) : $this->redirect('/');
            $_price = isset($_POST['_price']) ? trim($_POST['_price']) : $this->redirect('/');
            $thumb_image = isset($_POST['thumb_image']) ? trim($_POST['thumb_image']) : $this->redirect('/');
            $introduction_image = isset($_POST['introduction_image']) ? (array) $_POST['introduction_image'] : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $this->ajaxReturn($package->updatePackage($id, $name, $price, $_price, $thumb_image, $introduction_image, $description));
        } else {
            $packageAssign = M('Package')->where(array(
                'id' => $id
            ))->find();
            $this->assign('package', $packageAssign);
            $image_count = array();
            for ($i = 1; $i <= 5; $i++) {
                $image_count[] = $packageAssign["image_{$i}"];
            }
            $this->assign('introduction_image', $image_count);
            $this->assign('image_count', json_encode($image_count));
            $this->display();
        }
    }

    /**
     * 上传套餐图片
     */
    public function upload() {
        if (!empty($_FILES)) {
            $this->ajaxReturn(upload($_FILES, C('MAX_SIZE'), C('ALLOW_EXTENSIONS')));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 套餐简介内部图片上传
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