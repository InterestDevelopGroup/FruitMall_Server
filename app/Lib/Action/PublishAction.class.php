<?php

/**
 * 用户发布Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class PublishAction extends AdminAction {

    /**
     * 审核发布
     */
    public function allow() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Publish')->allowPublish($id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除发布信息
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Publish')->deletePublish((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 买茶
     */
    public function buy() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $publish = D('Publish');
            $total = $publish->getPublishCount($keyword, 1);
            if ($total) {
                $rows = $publish->getPublishList($page, $pageSize, $order, $sort, $keyword, 1);
                foreach ($rows as &$v) {
                    $v['publish_time'] = date("Y-m-d H:i:s", $v['publish_time']);
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
     * 卖茶
     */
    public function sell() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $publish = D('Publish');
            $total = $publish->getPublishCount($keyword, 0);
            if ($total) {
                $rows = $publish->getPublishList($page, $pageSize, $order, $sort, $keyword, 0);
                foreach ($rows as &$v) {
                    $v['publish_time'] = date("Y-m-d H:i:s", $v['publish_time']);
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

}