<?php

/**
 * 客服Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class ServiceAction extends AdminAction {

    /**
     * 添加客服
     */
    public function add() {
        if ($this->isAjax()) {
            $contact = isset($_POST['contact']) ? trim($_POST['contact']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $qq = isset($_POST['qq']) ? trim($_POST['qq']) : $this->redirect('/');
            $level = isset($_POST['level']) ? trim($_POST['level']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $service = D('Service');
            $this->ajaxReturn($service->addService($contact, $phone, $qq, $level, $image));
        } else {
            $this->display();
        }
    }

    /**
     * 删除客服
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $service = D('Service');
            $this->ajaxReturn($service->deleteService((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除客服头像
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
                        'msg' => '删除头像失败'
                    ));
                }
            } else {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '头像已经删除'
                ));
            }
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 客服一览
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $service = D('Service');
            $total = $service->getServiceCount();
            if ($total) {
                $rows = $service->getServiceList($page, $pageSize, $order, $sort);
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
        $service = D('Service');
        if ($this->isAjax()) {
            $contact = isset($_POST['contact']) ? trim($_POST['contact']) : $this->redirect('/');
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : $this->redirect('/');
            $qq = isset($_POST['qq']) ? trim($_POST['qq']) : $this->redirect('/');
            $level = isset($_POST['level']) ? trim($_POST['level']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn($service->updateService($id, $contact, $phone, $qq, $level, $image));
        } else {
            $serviceAssign = $service->where("id = {$id}")->find();
            $serviceAssign['src'] = 'http://' . $_SERVER['HTTP_HOST'] . $serviceAssign['image'];
            $this->assign('service', $serviceAssign);
            $this->display();
        }
    }

    /**
     * 上传客服头像
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['files']['size'][0] > C('SERVICE_MAX_UPLOAD_FILE_SIZE')) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '图片文件过大，请选择另外的图片'
                ));
            } else {
                $fileParts = pathinfo($_FILES['files']['name'][0]);
                $tempFile = $_FILES['files']['tmp_name'][0];
                if (in_array($fileParts['extension'], C('SERVICE_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
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
     * 生成上传头像文件名
     *
     * @param string $extension
     *            扩展名
     * @return string
     */
    private function generateTargetFileName($extension) {
        return 'avatar_' . time() . rand(1000, 9999) . "." . $extension;
    }

}