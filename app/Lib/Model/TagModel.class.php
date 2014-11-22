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
     * 获取商品标签列表（API）
     *
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @param int|null $goods_amount
     *            附带商品数量
     * @param string|null $keyword
     *            关键字
     * @return array
     */
    public function _getTagList($offset, $pagesize, $goods_amount, $keyword) {
        $keyword && $this->where(array(
            't.name' => array(
                "like",
                "%{$keyword}%"
            )
        ));
        $result = $this->table($this->getTableName() . " AS t ")->field(array(
            't.*',
            "(SELECT COUNT(1) FROM " . M('Goods')->getTableName() . " WHERE tag = t.id)" => 'goods_amount'
        ))->limit($offset, $pagesize)->select();
        if ($goods_amount && !empty($result)) {
            foreach ($result as &$v) {
                $v['goods_list'] = array_map(function ($value) {
                    $value['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
                    $value['update_time'] = $value['update_time'] ? date("Y-m-d H:i:s", $value['update_time']) : $value['update_time'];
                    return $value;
                }, D('Goods')->_getGoodsList(0, $goods_amount, null, null, $v['id'], null));
            }
        }
        return $result;
    }

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