<?php

/**
 * 任务管理Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class TaskAction extends AdminAction {

    /**
     * 导出任务excel
     */
    public function export_purchase() {
        vendor('PHPExcel.PHPExcel');
        $obj = new PHPExcel();
        $allBranch = M('Branch')->field(array(
            'id',
            'name'
        ))->order('id ASC')->select();
        $obj->setActiveSheetIndex(0)->setCellValue('A1', '商品名');
        foreach ($allBranch as $k => $per) {
            $cell = sprintf("%c1", $k + 66);
            $obj->setActiveSheetIndex(0)->setCellValue($cell, $per['name']);
        }
        $data = D('Purchase')->export();
        foreach ($data as $k_1 => $v) {
            $obj->setActiveSheetIndex(0)->setCellValue('A' . ($k_1 + 2), $v['name']);
            foreach ($allBranch as $k => $per) {
                $cell = sprintf("%c%d", ($k + 66), ($k_1 + 2));
                $obj->setActiveSheetIndex(0)->setCellValue($cell, D('Purchase')->getPerBranchAmount($per['id'], $v['goods_id']));
            }
        }
        $obj->getActiveSheet()->setTitle('采购任务');
        $obj->setActiveSheetIndex(0);
        // 下载
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"采购任务.xlsx\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
        $objWriter->save('php://output');
    }

    /**
     * ajax获取采购详细
     */
    public function getPurchaseDetail() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : $this->redirect('/');
            $this->ajaxReturn(D('Purchase')->getPurchaseDetail($goods_id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 采购任务
     */
    public function purchase() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $purchase = D('Purchase');
            $total = $purchase->getPurchaseCount();
            if ($total) {
                $rows = $purchase->getPurchaseList($page, $pageSize, $order, $sort);
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
     * 打印采购任务
     */
    public function print_purchase() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? array_map(function ($value) {
                return intval($value);
            }, $_POST['goods_id']) : $this->redirect('/');
            $this->ajaxReturn(array(
                'status' => true,
                'result' => D('Purchase')->printPurchase($goods_id)
            ));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 退货任务
     */
    public function returns() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            // $tag = D('Tag');
            // $total = $tag->getTagCount();
            // if ($total) {
            // $rows = array_map(function ($v) {
            // $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
            // $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
            // return $v;
            // }, $tag->getTagList($page, $pageSize, $order, $sort));
            // } else {
            // $rows = null;
            // }
            // $this->ajaxReturn(array(
            // 'Rows' => $rows,
            // 'Total' => $total
            // ));
        } else {
            $this->display();
        }
    }

    /**
     * 送货任务
     */
    public function shipping() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            // $tag = D('Tag');
            // $total = $tag->getTagCount();
            // if ($total) {
            // $rows = array_map(function ($v) {
            // $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
            // $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
            // return $v;
            // }, $tag->getTagList($page, $pageSize, $order, $sort));
            // } else {
            // $rows = null;
            // }
            // $this->ajaxReturn(array(
            // 'Rows' => $rows,
            // 'Total' => $total
            // ));
        } else {
            $this->display();
        }
    }

    /**
     * 确认采购
     */
    public function sure_purchase() {
        if ($this->isAjax()) {
            $goods_id = isset($_POST['goods_id']) ? array_map(function ($value) {
                return intval($value);
            }, $_POST['goods_id']) : $this->redirect('/');
            $this->ajaxReturn(D('Purchase')->surePurchase($goods_id));
        } else {
            $this->redirect('/');
        }
    }

}