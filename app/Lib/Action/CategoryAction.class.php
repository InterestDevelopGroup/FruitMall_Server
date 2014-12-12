<?php

/**
 * 商品分类Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CategoryAction extends AdminAction {

    /**
     * 添加小分类
     */
    public function child_add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $this->ajaxReturn(D('ChildCategory')->addChildCategory($name, $parent_id, $description));
        } else {
            $this->assign('parentCategory', M('ParentCategory')->select());
            $this->display();
        }
    }

    /**
     * 删除小分类
     */
    public function child_delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('ChildCategory')->deleteChildCategory((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 小分类
     */
    public function child_index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $childCategory = D('ChildCategory');
            $total = $childCategory->getChildCategoryCount();
            if ($total) {
                $rows = $childCategory->getChildCategoryList($page, $pageSize, $order, $sort);
                foreach ($rows as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                }
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
     * 更新小分类
     */
    public function child_update() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $this->ajaxReturn(D('ChildCategory')->updateChildCategory($id, $name, $parent_id, $description));
        } else {
            $this->assign('childCategory', M('ChildCategory')->where(array(
                'id' => $id
            ))->find());
            $this->assign('parentCategory', M('ParentCategory')->select());
            $this->display();
        }
    }

    /**
     * Ajax根据大分类ID获取小分类列表
     */
    public function getChildCategoryByParentId() {
        if ($this->isAjax()) {
            $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : $this->redirect('/');
            $this->ajaxReturn(M('ChildCategory')->field(array(
                'id',
                'name'
            ))->where(array(
                'parent_id' => $parent_id
            ))->select());
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 添加大分类
     */
    public function parent_add() {
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $this->ajaxReturn(D('ParentCategory')->addParentCategory($name, $description));
        } else {
            $this->display();
        }
    }

    /**
     * 删除大分类
     */
    public function parent_delete() {
        if ($this->isAjax()) {
            $id = isset($_POST['id']) ? explode(',', $_POST['id']) : $this->redirect('/');
            $this->ajaxReturn(D('ParentCategory')->deleteParentCategory((array) $id));
        } else {
            $this->redirect('/');
        }
    }

    /**
     * 大分类
     */
    public function parent_index() {
        if ($this->isAjax()) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
            $order = isset($_GET['sortname']) ? $_GET['sortname'] : 'id';
            $sort = isset($_GET['sortorder']) ? $_GET['sortorder'] : 'ASC';
            $parentCategory = D('ParentCategory');
            $total = $parentCategory->getParentCategoryCount();
            if ($total) {
                $rows = $parentCategory->getParentCategoryList($page, $pageSize, $order, $sort);
                foreach ($rows as &$v) {
                    $v['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
                    $v['update_time'] = $v['update_time'] ? date("Y-m-d H:i:s", $v['update_time']) : $v['update_time'];
                }
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
     * 更新大分类
     */
    public function parent_update() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->redirect('/');
        if ($this->isAjax()) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : $this->redirect('/');
            $description = isset($_POST['description']) ? trim($_POST['description']) : $this->redirect('/');
            $this->ajaxReturn(D('ParentCategory')->updateParentCategory($id, $name, $description));
        } else {
            $this->assign('parentCategory', M('ParentCategory')->where(array(
                'id' => $id
            ))->find());
            $this->display();
        }
    }

}