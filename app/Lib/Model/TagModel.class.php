<?php

/**
 * fruit_tag 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class TagModel extends Model {

    /**
     * 添加标签
     *
     * @param string $name
     *            标签名字
     * @param string $image
     *            标签图片
     * @return array
     */
    public function addTag($name, $image) {
        if ($this->where(array(
            'name' => $name
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '该标签已经存在'
            );
        }
        if ($this->add(array(
            'name' => $name,
            'image' => $image,
            'add_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '添加成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '添加失败'
            );
        }
    }

    /**
     * 删除标签
     *
     * @param array $id
     *            标签ID
     * @return array
     */
    public function deleteTag(array $id) {
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            return array(
                'status' => true,
                'msg' => '删除成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '删除失败'
            );
        }
    }

    /**
     * 获取标签总数
     *
     * @return int
     */
    public function getTagCount() {
        return (int) $this->count();
    }

    /**
     * 获取标签列表
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
    public function getTagList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS t ")->field(array(
            't.*',
            "(SELECT COUNT(1) FROM " . M('Goods')->getTableName() . " WHERE tag = t.id)" => 'goods_amount'
        ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新标签
     *
     * @param int $id
     *            标签ID
     * @param string $name
     *            标签名
     * @param string $image
     *            标签图片
     * @return array
     */
    public function updateTag($id, $name, $image) {
        if ($this->where(array(
            'name' => $name,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '该标签已经存在'
            );
        }
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'name' => $name,
            'image' => $image,
            'update_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '更新成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '更新失败'
            );
        }
    }

}