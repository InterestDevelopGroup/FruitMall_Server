<?php

/**
 * tea_category表模型
*
* @author lzjjie
* @version 1.0.0
* @since 1.0.0
*/
class SeriesModel extends Model {

    /**
     * 添加分类
     *
     * @param string $name
     *            新增分类名称
     * @return array
     */
    public function addSeries($name) {
        $count = $this->where("name = \"{$name}\"")->count();
        if ($count) {
            return array(
                'status' => false,
                'msg' => '系列已经存在（分类名称不能重复）'
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
                'msg' => '添加系列成功'
            );
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '添加系列失败'
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
    public function deleteSeries(array $id) {
        $goods = M('Goods');
        // 查看该系列是否存在商品
        $count = $goods->where(array(
            'series_id' => array(
                'in',
                $id
            )
        ))->count();
        if ($count > 0) {
            // 开启事务
            $goods->startTrans();
            // 删除该系列下的所有商品
            if ($goods->where(array(
                'series_id' => array(
                    'in',
                    $id
                )
            ))->delete()) {
                // 开启事务
                $this->startTrans();
                // 删除系列
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
                        'msg' => '删除系列成功'
                    );
                } else {
                    // 删除失败，回滚事务
                    $goods->rollback();
                    $this->rollback();
                    return array(
                        'status' => false,
                        'msg' => '删除系列失败'
                    );
                }
            } else {
                // 删除失败，回滚失败
                $goods->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除该系列下的商品失败，尚未能删除该系列'
                );
            }
        } else {
            // 开启事务
            $this->startTrans();
            // 删除系列
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
                    'msg' => '删除系列成功'
                );
            } else {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除系列失败'
                );
            }
        }
    }

    /**
     * 获取分类总数
     *
     * @return int
     */
    public function getSeriesCount() {
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
    public function getSeriesList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    name, id, FROM_UNIXTIME(add_time) AS add_time,
                    FROM_UNIXTIME(update_time) AS update_time,
                    (SELECT
                        COUNT(1)
                    FROM
                        tea_goods
                    WHERE
                        series_id = s.id
                    ) AS goods_num
                FROM
                    tea_series AS s
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
     * @return array
     */
    public function updateSeries($id, $name) {
        $result = $this->where("name = \"{$name}\" AND id != {$id}")->find();
        if (!empty($result)) {
            return array(
                'status' => false,
                'msg' => '系列已经存在（系列名称不能重复）'
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
                'msg' => '更新系列成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '更新系列失败'
            );
        }
    }

}