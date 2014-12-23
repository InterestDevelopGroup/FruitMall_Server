<?php

/**
 * 水果券Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CouponAction extends AdminAction {

    /**
     * 添加规则
     */
    public function add_rule() {
        if ($this->isAjax()) {
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $score = isset($_POST['score']) ? intval($_POST['score']) : $this->redirect('/');
            $condition = isset($_POST['condition']) ? trim($_POST['condition']) : $this->redirect('/');
            $expire_time = isset($_POST['expire_time']) ? trim($_POST['expire_time']) : $this->redirect('/');
            $this->ajaxReturn(D('CouponRule')->addCouponRule($description, $type, $score, $condition, $expire_time));
        } else {
            $this->display();
        }
    }

    /**
     * 添加使用规则
     */
    public function add_usage() {
        if ($this->isAjax()) {
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $score = isset($_POST['score']) ? intval($_POST['score']) : $this->redirect('/');
            $condition = isset($_POST['condition']) ? trim($_POST['condition']) : $this->redirect('/');
            $this->ajaxReturn(D('CouponUsage')->addCouponUsage($description, $score, $condition));
        } else {
            $this->display();
        }
    }

    /**
     * 删除规则
     */
    public function delete_rule() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('CouponRule')->deleteRule((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 删除使用规则
     */
    public function delete_usage() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('CouponUsage')->deleteUsage((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 编辑获取规则
     */
    public function how_to_get() {
        if ($this->isAjax()) {
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $this->ajaxReturn(D('CouponRuleContent')->addCouponRuleContent($content, 1));
        } else {
            $this->assign('ruleContent', M('CouponRuleContent')->where(array(
                'type' => 1
            ))->find());
            $this->display();
        }
    }

    /**
     * 编辑使用规则
     */
    public function how_to_use() {
        if ($this->isAjax()) {
            $content = isset($_POST['content']) ? trim($_POST['content']) : $this->redirect('/');
            $this->ajaxReturn(D('CouponRuleContent')->addCouponRuleContent($content, 2));
        } else {
            $this->assign('ruleContent', M('CouponRuleContent')->where(array(
                'type' => 2
            ))->find());
            $this->display();
        }
    }

    /**
     * 所有规则
     */
    public function rule() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $couponRule = D('CouponRule');
            $total = $couponRule->getCouponRuleCount();
            if ($total) {
                $rows = array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, $couponRule->getCouponRuleList($page, $pageSize, $order, $sort));
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
     * 更新规则
     */
    public function update_rule() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $type = isset($_POST['type']) ? intval($_POST['type']) : $this->redirect('/');
            $score = isset($_POST['score']) ? intval($_POST['score']) : $this->redirect('/');
            $condition = isset($_POST['condition']) ? trim($_POST['condition']) : $this->redirect('/');
            $expire_time = isset($_POST['expire_time']) ? trim($_POST['expire_time']) : $this->redirect('/');
            $this->ajaxReturn(D('CouponRule')->updateCouponRule($id, $description, $type, $score, $condition, $expire_time));
        } else {
            $this->assign('rule', M('CouponRule')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

    /**
     * 更新使用规则
     */
    public function update_usage() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $score = isset($_POST['score']) ? intval($_POST['score']) : $this->redirect('/');
            $condition = isset($_POST['condition']) ? trim($_POST['condition']) : $this->redirect('/');
            $this->ajaxReturn(D('CouponUsage')->updateCouponUsage($id, $description, $score, $condition));
        } else {
            $this->assign('usage', M('CouponUsage')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

    /**
     * 使用规则
     */
    public function usage() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $couponUsage = D('CouponUsage');
            $total = $couponUsage->getCouponUsageCount();
            if ($total) {
                $rows = array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, $couponUsage->getCouponUsageList($page, $pageSize, $order, $sort));
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

}