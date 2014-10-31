<?php

/**
 * 会员管理Action
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class MemberAction extends AdminAction {

    /**
     * 删除会员
     */
    public function delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('Member')->deleteMember((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 会员一览index
     */
    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $member = D('Member');
            $total = $member->getMemberCount($keyword);
            if ($total) {
                $rows = $member->getMemberList($page, $pageSize, $order, $sort, $keyword);
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
     * 设置用户等级
     */
    public function setLevel() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        $id || $this->redirect('/');
        if ($this->isAjax()) {
            $level = isset($_POST['level']) ? intval($_POST['level']) : $this->redirect('/');
            $this->ajaxReturn(D('Member')->updateUserLevel($id, $level));
        } else {
            $this->assign('member', M('Member')->where(array('id' => $id))->find());
            $this->display();
        }
    }

}