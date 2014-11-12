<?php

/**
 * 会员Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class MemberAction extends AdminAction {

    /**
     * 删除用户
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
     * 会员一览
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
                $rows = array_map(function ($v) {
                    $v['register_time'] = date("Y-m-d H:i:s", $v['register_time']);
                    $v['last_time'] = $v['last_time'] ? date("Y-m-d H:i:s", $v['last_time']) : $v['last_time'];
                    return $v;
                }, $member->getMemberList($page, $pageSize, $order, $sort, $keyword));
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