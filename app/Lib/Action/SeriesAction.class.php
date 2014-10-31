<?php

/**
 * 商品分类Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class SeriesAction extends AdminAction {

    /**
     * 添加商品分类
     */
    public function add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $series = D('Series');
            $this->ajaxReturn($series->addSeries($name));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除分类
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $series = D('Series');
            $this->ajaxReturn($series->deleteSeries((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 商品分类
     */
    public function index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $series = D('Series');
            $total = $series->getSeriesCount();
            if ($total) {
                $rows = $series->getSeriesList($page, $pageSize, $order, $sort);
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
     * 更新商品分类
     */
    public function update() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        $series = D('Series');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $this->ajaxReturn($series->updateSeries($id, $name));
        } else {
            $this->assign('series', $series->where("id = {$id}")->find());
            $this->display();
        }
    }

}