<?php

/**
 * 市场资讯Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class NewsAction extends AdminAction {

    /**
     * 添加市场资讯
     */
    public function add() {
        if ($this->isAjax()) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : $this->redirect('/');
            $type_id = isset($_POST['type_id']) ? intval($_POST['type_id']) : $this->redirect('/');
            // $business_start_time = isset($_POST['business_start_time']) ? $_POST['business_start_time'] : $this->redirect('/');
            // $business_end_time = isset($_POST['business_end_time']) ? $_POST['business_end_time'] : $this->redirect('/');
            // $is_free_parking = isset($_POST['is_free_parking']) ? $_POST['is_free_parking'] : $this->redirect('/');
            $is_top = isset($_POST['is_top']) ? intval($_POST['is_top']) : $this->redirect('/');
            // $per_fee = isset($_POST['per_fee']) ? $_POST['per_fee'] : $this->redirect('/');
            // $address = isset($_POST['address']) ? $_POST['address'] : $this->redirect('/');
            // $bus_path = isset($_POST['bus_path']) ? $_POST['bus_path'] : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn(D('News')->addNews($title, $type_id, $is_top, $description, $image));
            // $this->ajaxReturn($news->addNews($title,$type_id, $business_start_time, $business_end_time, $is_free_parking, $is_top, $per_fee, $address, $bus_path, $description, $image));
        } else {
            // 资讯类别
            $this->assign('news_type', M('NewsType')->select());
            $this->display();
        }
    }

    /**
     * 删除资讯
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $news = D('News');
            $this->ajaxReturn($news->deleteNews((array) $id));
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
     * 市场资讯管理
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $news = D('News');
            $total = $news->getNewsCount($keyword);
            if ($total) {
                $rows = $news->getNewsList($page, $pageSize, $order, $sort, $keyword);
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
     * 更新市场资讯
     */
    public function update() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $id || $this->redirect('/');
        $news = D('News');
        if ($this->isAjax()) {
            $title = isset($_POST['title']) ? trim($_POST['title']) : $this->redirect('/');
            $type_id = isset($_POST['type_id']) ? intval($_POST['type_id']) : $this->redirect('/');
            // $business_start_time = isset($_POST['business_start_time']) ? $_POST['business_start_time'] : $this->redirect('/');
            // $business_end_time = isset($_POST['business_end_time']) ? $_POST['business_end_time'] : $this->redirect('/');
            // $is_free_parking = isset($_POST['is_free_parking']) ? $_POST['is_free_parking'] : $this->redirect('/');
            $is_top = isset($_POST['is_top']) ? intval($_POST['is_top']) : $this->redirect('/');
            // $per_fee = isset($_POST['per_fee']) ? $_POST['per_fee'] : $this->redirect('/');
            // $address = isset($_POST['address']) ? $_POST['address'] : $this->redirect('/');
            // $bus_path = isset($_POST['bus_path']) ? $_POST['bus_path'] : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $image = isset($_POST['image']) ? trim($_POST['image']) : $this->redirect('/');
            $this->ajaxReturn($news->updateNews($id, $title, $type_id, $is_top, $description, $image));
            // $this->ajaxReturn($news->updateNews($id, $title, $type_id, $business_start_time, $business_end_time, $is_free_parking, $is_top, $per_fee, $address, $bus_path, $description, $image));
        } else {
            $newsAssign = $news->where("id = {$id}")->find();
            if ($newsAssign['business_start_time']) {
                $time = explode(':', $newsAssign['business_start_time']);
                $newsAssign['business_start_hour'] = intval($time[0]);
                $newsAssign['business_start_minute'] = intval($time[1]);
            } else {
                $newsAssign['business_start_hour'] = -1;
                $newsAssign['business_start_minute'] = -1;
            }
            if ($newsAssign['business_end_time']) {
                $time = explode(':', $newsAssign['business_end_time']);
                $newsAssign['business_end_hour'] = intval($time[0]);
                $newsAssign['business_end_minute'] = intval($time[1]);
            } else {
                $newsAssign['business_end_hour'] = -1;
                $newsAssign['business_end_minute'] = -1;
            }
            $newsAssign['src'] = 'http://' . $_SERVER['HTTP_HOST'] . $newsAssign['image'];
            // 资讯分类
            $news_type = M('news_type')->select();
            $this->assign('news', $newsAssign);
            $this->assign('news_type', $news_type);
            $this->display();
        }
    }

    /**
     * 上传资讯图片
     */
    public function upload() {
        if (!empty($_FILES)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
            if (!file_exists($targetPath)) {
                mkdir($targetPath);
            }
            if ($_FILES['files']['size'][0] > C('NEWS_MAX_UPLOAD_FILE_SIZE')) {
                $this->ajaxReturn(array(
                    'status' => false,
                    'msg' => '图片文件过大，请选择另外的图片'
                ));
            } else {
                $fileParts = pathinfo($_FILES['files']['name'][0]);
                $tempFile = $_FILES['files']['tmp_name'][0];
                if (in_array($fileParts['extension'], C('NEWS_ALLOW_UPLOAD_IMAGE_EXTENSION'))) {
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
        return time() . rand(1000, 9999) . "." . $extension;
    }

    /**
     * 资讯分类管理
     */
    public function type() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $newstype = D('NewsType');
            $total = $newstype->getTypeCount();
            if ($total) {
                $rows = $newstype->getNewstypeList($page, $pageSize, $order, $sort);
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
     * 添加资讯类别
     */
    public function add_type() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $newstype = D('NewsType');
            $this->ajaxReturn($newstype->addNewsType($name));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 更新资讯分类
     */
    public function update_type() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        $newstype = D('NewsType');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $this->ajaxReturn($newstype->updateNewsType($id, $name));
        } else {
            $this->assign('newstype', $newstype->where("id = {$id}")->find());
            $this->display();
        }
    }

    /**
     * 删除资讯分类
     */
    public function delete_type() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $newstype = D('NewsType');
            $this->ajaxReturn($newstype->deleteNewsType((array) $id));
        } else {
            $this->redirect('/');
        }
    }

}