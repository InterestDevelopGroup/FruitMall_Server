<?php

/**
 * fruit_child_category表模型
*
* @author Zonkee
* @version 1.0.0
* @since 1.0.0
*/
class ChildCategoryModel extends Model {

    /**
     * 添加小分类
     *
     * @param string $name
     *            小分类名称
     * @param int $parent_id
     *            大分类ID
     * @return array
     */
    public function addChildCategory($name, $parent_id) {
        if ($this->where(array(
            'name' => $name
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '分类已经存在（分类名称不能重复）'
            );
        }
        if ($this->add(array(
            'name' => $name,
            'parent_id' => $parent_id,
            'add_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '添加分类成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '添加分类失败'
            );
        }
    }

    /**
     * 删除小分类
     *
     * @param array $id
     *            小分类ID
     * @return array
     */
    public function deleteChildCategory(array $id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            if (M('Goods')->where(array(
                'c_cate_id' => array(
                    'in',
                    $id
                )
            ))->delete()) {
                // 删除成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '删除成功'
                );
            } else {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除失败'
                );
            }
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '删除失败'
            );
        }
    }

    /**
     * 获取分类总数
     *
     * @return int
     */
    public function getChildCategoryCount() {
        return (int) $this->count();
    }

    /**
     * 获取分类列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param string $order
     *            排序字段
     * @param string $sort
     *            排序方式
     * @return array
     */
    public function getChildCategoryList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS cc ")->field(array(
            'cc.id',
            'cc.name',
            'pc.name' => 'parent',
            'cc.parent_id',
            'cc.add_time',
            'cc.update_time',
            "(SELECT COUNT(1) FROM " . M('Goods')->getTableName() . " WHERE c_cate_id = cc.id)" => 'goods_amount'
        ))->join(array(
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = cc.parent_id "
        ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新小分类
     *
     * @param int $id
     *            小分类ID
     * @param string $name
     *            小分类名称
     * @param int $parent_id
     *            大分类ID
     * @return array
     */
    public function updateChildCategory($id, $name, $parent_id) {
        if ($this->where(array(
            'name' => $name,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '分类已经存在（分类名称不能重复）'
            );
        }
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'name' => $name,
            'parent_id' => $parent_id,
            'update_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '更新分类成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '更新分类失败'
            );
        }
    }

}