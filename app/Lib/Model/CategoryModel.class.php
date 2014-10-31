<?php

/**
 * tea_category表模型
*
* @author lzjjie
* @version 1.0.0
* @since 1.0.0
*/
class CategoryModel extends Model {

    /**
     * 添加分类
     *
     * @param string $name
     *            新增分类名称
     * @param string $image
     *            分类图片
     * @return array
     */
    public function addCategory($name, $image) {
        if ($this->where(array(
            'name' => $name
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '分类已经存在（分类名称不能重复）'
            );
        }
        // 开启事务
        $this->startTrans();
        if ($this->add(array(
            'name' => $name,
            'image' => $image,
            'add_time' => time()
        ))) {
            // 添加成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '添加分类成功'
            );
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '添加分类失败'
            );
        }
    }

    /**
     * 删除分类
     *
     * @param array $id
     *            分类ID
     * @return array
     */
    public function deleteCategory(array $id) {
        $goods = M('Goods');
        // 查看该分类是否存在商品
        $count = $goods->where(array(
            'cate_id' => array(
                'in',
                $id
            )
        ))->count();
        if ($count > 0) {
            // 开启事务
            $goods->startTrans();
            // 删除该分类下的所有商品
            if ($goods->where(array(
                'cate_id' => array(
                    'in',
                    $id
                )
            ))->delete()) {
                // 开启事务
                $this->startTrans();
                // 删除分类
                if ($this->where(array(
                    'id' => array(
                        'in',
                        $id
                    )
                ))->delete()) {
                    // 删除成功，提交事务
                    $goods->commit();
                    $this->commit();
                    return array(
                        'status' => true,
                        'msg' => '删除分类成功'
                    );
                } else {
                    // 删除失败，回滚事务
                    $goods->rollback();
                    $this->rollback();
                    return array(
                        'status' => false,
                        'msg' => '删除分类失败'
                    );
                }
            } else {
                // 删除失败，回滚失败
                $goods->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除该分类下的商品失败，尚未能删除该分类'
                );
            }
        } else {
            // 开启事务
            $this->startTrans();
            // 删除分类
            if ($this->where(array(
                'id' => array(
                    'in',
                    $id
                )
            ))->delete()) {
                // 删除成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '删除分类成功'
                );
            } else {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除分类失败'
                );
            }
        }
    }

    /**
     * 获取分类总数
     *
     * @return int
     */
    public function getCategoryCount() {
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
    public function getCategoryList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    name, id, add_time, update_time,
                    (SELECT
                        COUNT(1)
                    FROM
                        tea_goods
                    WHERE
                        cate_id = a.id
                    ) AS goods_num
                FROM
                    " . $this->getTableName() . " AS a
                ORDER BY
                    {$order} {$sort}
                LIMIT
                    {$offset}, {$pageSize}";
        return $this->query($sql);
    }

    /**
     * 更新商品分类
     *
     * @param int $id
     *            分类ID
     * @param string $name
     *            分类名称
     * @param string $image
     *            分类图片
     * @return array
     */
    public function updateCategory($id, $name, $image) {
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
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'name' => $name,
            'image' => $image,
            'update_time' => time()
        ))) {
            // 更新成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '更新分类成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '更新分类失败'
            );
        }
    }

}