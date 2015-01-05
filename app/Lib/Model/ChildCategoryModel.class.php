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
     * 获取小分类列表（API）
     *
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @param int|null $p_cate_id
     *            小分类ID
     * @param string|null $keyword
     *            关键字
     */
    public function _getChildCategoryList($offset, $pagesize, $p_cate_id, $keyword) {
        $where = array();
        $p_cate_id && $where['cc.parent_id'] = $p_cate_id;
        $keyword && $where['cc.name'] = array(
            "like",
            "%{$keyword}%"
        );
        empty($where) || $this->where($where);
        return $this->table($this->getTableName() . " AS cc ")->field(array(
            'cc.id',
            'cc.name',
            'cc.description',
            'pc.name' => 'parent',
            'cc.parent_id',
            'cc.add_time',
            'cc.update_time',
            "(SELECT COUNT(1) FROM " . M('Goods')->getTableName() . " WHERE c_cate_id = cc.id)" => 'goods_amount'
        ))->join(array(
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = cc.parent_id "
        ))->where(array(
            'cc.is_delete' => 0
        ))->limit($offset, $pagesize)->select();
    }

    /**
     * 添加小分类
     *
     * @param string $name
     *            小分类名称
     * @param int $parent_id
     *            大分类ID
     * @param string $description
     *            描述
     * @return array
     */
    public function addChildCategory($name, $parent_id, $description) {
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
            'parent_id' => $parent_id,
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
        ))->save(array(
            'is_delete' => 1
        ))) {
            if (!D('Goods')->deleteGoodsByChildCategoryId($id)) {
                // 删除失败，回滚事务
                $this->commit();
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
            $this->commit();
            return array(
                'status' => false,
                'msg' => '删除失败'
            );
        }
    }

    /**
     * 根据大分类ID删除小分类
     *
     * @param array $parent_id
     *            大分类ID
     * @return boolean
     */
    public function deleteChildCategoryByParentCategoryId(array $parent_id) {
        if (!$this->where(array(
            'parent_id' => array(
                'in',
                $parent_id
            ),
            'is_delete' => 0
        ))->count()) {
            return true;
        }
        if ($this->where(array(
            'parent_id' => array(
                'in',
                $parent_id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
            if (!D('Goods')->deleteGoodsByParentCategoryId($parent_id)) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取分类总数
     *
     * @return int
     */
    public function getChildCategoryCount() {
        return $this->where(array(
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
    public function getChildCategoryList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS cc ")->field(array(
            'cc.id',
            'cc.name',
            'cc.description',
            'pc.name' => 'parent',
            'cc.parent_id',
            'cc.add_time',
            'cc.update_time',
            "(SELECT COUNT(1) FROM " . M('Goods')->getTableName() . " WHERE c_cate_id = cc.id AND is_delete = 0)" => 'goods_amount'
        ))->join(array(
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = cc.parent_id "
        ))->where(array(
            'cc.is_delete' => 0
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
     * @param string $description
     *            描述
     * @return array
     */
    public function updateChildCategory($id, $name, $parent_id, $description) {
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