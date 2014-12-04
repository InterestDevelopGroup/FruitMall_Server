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
     * 送水果劵
     */
    public function add_coupon() {
        if ($this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $score = isset($_POST['score']) ? intval($_POST['score']) : $this->redirect('/');
            $expire = isset($_POST['expire']) ? intval($_POST['expire']) : $this->redirect('/');
            $this->ajaxReturn(D('Coupon')->addCoupon($user_id, 4, $score, $expire ? $expire : null));
        } else {
            $id = (isset($_GET['id']) && intval($_GET['id'])) ? intval($_GET['id']) : $this->redirect('/');
            $this->assign('id', $id);
            $this->display();
        }
    }

    /**
     * 拉入黑名单
     */
    public function addBlacklist() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? (array) $_POST['id'] : $this->redirect('/');
            $this->ajaxReturn(D('Blacklist')->addBlacklist((array) $id));
        } else {
            $this->redirect('/');
        }
    }

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
     * 移出黑名单
     */
    public function deleteBlacklist() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? (array) $_POST['id'] : $this->redirect('/');
            $this->ajaxReturn(D('Blacklist')->deleteBlacklist((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * ajax获取用户地址列表
     */
    public function getUserAddressList() {
        if ($this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $this->ajaxReturn(D('Address')->_getAddressList($user_id, null, null));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * ajax获取用户定制列表
     */
    public function getUserCustomList() {
        if ($this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $this->ajaxReturn(array_map(function ($value) {
                $value['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
                return $value;
            }, D('Custom')->_getCustomList($user_id, null, null)));
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

    /**
     * 用户备注
     */
    public function remark() {
        if ($this->isAjax()) {
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : $this->redirect('/');
            $remark = isset($_POST['remark']) ? trim($_POST['remark']) : $this->redirect('/');
            $this->ajaxReturn(D('Member')->remark($user_id, $remark));
        } else {
            $id = (isset($_GET['id']) && intval($_GET['id'])) ? intval($_GET['id']) : $this->redirect('/');
            $this->assign('member', M('Member')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

}