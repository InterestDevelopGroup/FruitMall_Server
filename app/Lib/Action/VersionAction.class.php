<?php

/**
 * App版本管理Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class VersionAction extends AdminAction {

    /**
     * 添加版本
     */
    public function add() {
        if ($this->isAjax()) {
            $version = isset($_POST['version']) ? trim($_POST['version']) : $this->redirect('/');
            $download_url = isset($_POST['download_url']) ? trim($_POST['download_url']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $this->ajaxReturn(D('Version')->addVersion($version, $download_url, $type));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Android App版本管理
     */
    public function android() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $version = D('Version');
            $total = $version->getVersionCount(0);
            if ($total) {
                $rows = array_map(function ($v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    return $v;
                }, $version->getVersionList($page, $pageSize, $order, $sort, 0));
            } else {
                $rows = null;
            }
            $this->ajaxReturn(array(
                'Total' => $total,
                'Rows' => $rows
            ));
        } else {
            $this->display();
        }
    }

    /**
     * 删除版本
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Version')->deleteVersion((array) $id));
        } else {
            $this->redirect('/');
        }
    }

}