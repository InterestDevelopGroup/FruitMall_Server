<?php

/**
 * 基本设置Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class SettingAction extends AdminAction {

    /**
     * App封面设置
     */
    public function cover() {
        if ($this->isAjax()) {
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn(D('Cover')->addOrUpdateCover($image));
        } else {
            $cover = M('Cover')->order("id DESC")->find();
            if ($cover) {
                $cover['add_time'] = date("Y-m-d H:i:s", $cover['add_time']);
                $cover['update_time'] = $cover['update_time'] ? date("Y-m-d H:i:s", $cover['update_time']) : $cover['update_time'];
                $cover['src'] = "http://{$_SERVER['HTTP_HOST']}{$cover['image']}";
            }
            $this->assign('cover', $cover);
            $this->display();
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
     * 上传APP封面
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['files']['size'][0] > C('COVER_MAX_UPLOAD_FILE_SIZE')) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '图片文件过大，请选择另外的图片'
                ));
            } else {
                $fileParts = pathinfo($_FILES['files']['name'][0]);
                $tempFile = $_FILES['files']['tmp_name'][0];
                if (in_array($fileParts['extension'], C('COVER_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
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
        return 'cover_' . time() . rand(1000, 9999) . "." . $extension;
    }

}