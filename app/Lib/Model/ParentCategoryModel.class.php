<?php

/**
 * fruit_parent_category表模型
*
* @author Zonkee
* @version 1.0.0
* @since 1.0.0
*/
class ParentCategoryModel extends Model {

    /**
     * 获取大分类列表（API）
     *
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @param string|null $keyword
     *            关键字
     */
    public function _getParentCategoryList($offset, $pagesize, $keyword) {
        $keyword && $this->where(array(
            'pc.name' => array(
                "like",
                "%{$keyword}%"
            )
        ));
        return $this->table($this->getTableName() . " AS pc ")->field(array(
            'id',
            'name',
            'description',
            'add_time',
            'update_time',
            "(SELECT COUNT(1) FROM " . M('Goods')->getTableName() . " WHERE p_cate_id = pc.id)" => 'goods_amount'
        ))->where(array(
            'pc.is_delete' => 0
        ))->limit($offset, $pagesize)->select();
    }

    /**
     * 添加大分类
     *
     * @param string $name
     *            大分类名称
     * @param string $description
     *            描述
     * @return array
     */
    public function addParentCategory($name, $description) {
        if ($this->where(array(
            'name' => $name,
            'is_delete' => 0
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '分类已经存在（分类名称不能重复）'
            );
        }
        if ($this->add(array(
            'name' => $name,
            'description' => strlen($description) ? $description : null,
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
     * 删除大分类
     *
     * @param array $id
     *            大分类ID
     * @return array
     */
    public function deleteParentCategory(array $id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            if (!D('ChildCategory')->deleteChildCategoryByParentCategoryId($id)) {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除失败'
                );
            }
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
    }

    /**
     * 获取分类总数
     *
     * @return int
     */
    public function getParentCategoryCount() {
        return (int) $this->where(array(
            'is_delete' => 0
        ))->count();
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
    public function getParentCategoryList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS pc ")->field(array(
            'id',
            'name',
            'description',
            'add_time',
            'update_time',
            "(SELECT COUNT(1) FROM " . M('Goods')->getTableName() . " WHERE p_cate_id = pc.id AND is_delete = 0)" => 'goods_amount'
        ))->where(array(
            'is_delete' => 0
        ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新大分类
     *
     * @param int $id
     *            大分类ID
     * @param string $name
     *            大分类名称
     * @param string $description
     *            描述
     * @return array
     */
    public function updateParentCategory($id, $name, $description) {
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
            'description' => strlen($description) ? $description : null,
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