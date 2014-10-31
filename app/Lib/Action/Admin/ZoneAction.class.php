<?php

/**
 * 专区Action
 *
 * @author lzjjie
 * @version 1.0.1
 * @since 1.0.1
 */
class ZoneAction extends AdminAction {

    /**
     * 添加专区
     */
    public function add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn(D('Zone')->addZone($name, $image));
        } else {
            $this->display();
        }
    }

    /**
     * 删除专区
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Zone')->deleteZone((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除专区图片
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
                        'msg' => '删除专区图片失败'
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '专区图片已经删除'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 专区一览
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $zone = D('Zone');
            $total = $zone->getZoneCount();
            if ($total) {
                $rows = $zone->getZoneList($page, $pageSize, $order, $sort);
                foreach ($rows as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                    $v['src'] = "http://{$_SERVER['HTTP_HOST']}{$v['image']}";
                }
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Rows' => $rows,
                'Total' => $total
            ));
        } else {
            $this->display();
        }
    }

    /**
     * 更新客服
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $id || $this->redirect('/');
        $zone = D('Zone');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn($zone->updateZone($id, $name, $image));
        } else {
            $zoneAssign = $zone->where(array(
                'id' => $id
            ))->find();
            $zoneAssign['src'] = 'http://' . $_SERVER['HTTP_HOST'] . $zoneAssign['image'];
            $this->assign('zone', $zoneAssign);
            $this->display();
        }
    }

    /**
     * 上传专区图片
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['files']['size'][0] > C('ZONE_MAX_UPLOAD_FILE_SIZE')) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '图片文件过大，请选择另外的图片'
                ));
            } else {
                $fileParts = pathinfo($_FILES['files']['name'][0]);
                $tempFile = $_FILES['files']['tmp_name'][0];
                if (in_array($fileParts['extension'], C('ZONE_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
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
     * 生成上传图片文件名
     *
     * @param string $extension
     *            扩展名
     * @return string
     */
    private function generateTargetFileName($extension) {
        return 'zone_' . time() . rand(1000, 9999) . "." . $extension;
    }

}