<?php

/**
 * tea_news_type表模型
*
* @author lzjjie
* @version 1.0.0
* @since 1.0.0
*/
class NewsTypeModel extends Model {

    /**
     * 添加分类
     *
     * @param string $name
     *            新增分类名称
     * @return array
     */
    public function addNewsType($name) {
        $count = $this->where("name = \"{$name}\"")->count();
        if ($count) {
            return array(
                'status' => false,
                'msg' => '分类已经存在（分类名称不能重复）'
            );
        }
        // 开启事务
        $this->startTrans();
        if ($this->add(array(
            'name' => $name,
            'add_time' => time(),
            'update_time' => time()
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
    public function deleteNewsType(array $id) {
        $news = M('news');
        // 查看该分类是否存在商品
        $count = $news->where(array(
            'type_id' => array(
                'in',
                $id
            )
        ))->count();
        if ($count > 0) {
            // 开启事务
            $news->startTrans();
            // 删除该分类下的所有商品
            if ($news->where(array(
                'type_id' => array(
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
                    $news->commit();
                    $this->commit();
                    return array(
                        'status' => true,
                        'msg' => '删除分类成功'
                    );
                } else {
                    // 删除失败，回滚事务
                    $news->rollback();
                    $this->rollback();
                    return array(
                        'status' => false,
                        'msg' => '删除分类失败'
                    );
                }
            } else {
                // 删除失败，回滚失败
                $news->rollback();
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
    public function getTypeCount() {
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
    public function getNewstypeList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    id,name,FROM_UNIXTIME(add_time) AS add_time,
                    FROM_UNIXTIME(update_time) AS update_time,
                    (SELECT
                        COUNT(1)
                    FROM
                        tea_news
                    WHERE
                        type_id = n.id
                    ) AS news_num
                FROM
                    tea_news_type n
                ORDER BY
                    {$order} {$sort}
                LIMIT
                    {$offset}, {$pageSize}";
        return $this->query($sql);
    }

    /**
     * 更新资讯分类
     *
     * @param int $id
     *            分类ID
     * @param string $name
     *            分类名称
     * @return array
     */
    public function updateNewsType($id, $name) {
        $result = $this->where("name = \"{$name}\" AND id != {$id}")->find();
        if (!empty($result)) {
            return array(
                'status' => false,
                'msg' => '分类已经存在（分类名称不能重复）'
            );
        }
        // 开启事务
        $this->startTrans();
        if ($this->where("id = {$id}")->save(array(
            'name' => $name,
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