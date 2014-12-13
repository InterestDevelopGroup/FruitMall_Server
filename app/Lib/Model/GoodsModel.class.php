<?php

/**
 * fruit_goods 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class GoodsModel extends Model {

    /**
     * 获取商品列表（API）
     *
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @param int $user_id
     *            用户ID
     * @param int|null $p_cate_id
     *            大分类ID
     * @param int|null $c_cate_id
     *            小分类ID
     * @param int|null $tag
     *            标签
     * @param string|null $keyword
     *            关键字
     */
    public function _getGoodsList($offset, $pagesize, $user_id, $p_cate_id, $c_cate_id, $tag, $keyword) {
        $where = array(
            'g.is_delete' => 0,
            'g.status' => 1
        );
        $p_cate_id && $where['g.p_cate_id'] = $p_cate_id;
        $c_cate_id && $where['g.c_cate_id'] = $c_cate_id;
        $tag && $where['g.tag'] = $tag;
        $keyword && $where['g.name'] = array(
            "like",
            "%{$keyword}%"
        );
        empty($where) || $this->where($where);
        $fields = array(
            'g.*',
            'pc.name' => 'parent_category',
            'cc.name' => 'child_category',
            't.name' => 'tag_name'
        );
        $user_id && $fields["(SELECT quantity FROM " . M('ShoppingCar')->getTableName() . " WHERE goods_id = g.id)"] = 'shopping_car_amount';
        return $this->table($this->getTableName() . " AS g ")->join(array(
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = g.p_cate_id ",
            " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON cc.id = g.c_cate_id ",
            " LEFT JOIN " . M('Tag')->getTableName() . " AS t ON t.id = g.tag "
        ))->field($fields)->order("g.priority DESC")->limit($offset, $pagesize)->select();
    }

    /**
     * 添加商品
     *
     * @param string $name
     *            名称
     * @param float $price
     *            总价
     * @param float $single_price
     *            单价
     * @param string $_price
     *            市场价
     * @param string $unit
     *            总价单位
     * @param string $single_unit
     *            单价单位
     * @param int $p_cate_id
     *            大分类ID
     * @param int $c_cate_id
     *            小分类ID
     * @param string $amount
     *            每盒数量
     * @param string $weight
     *            每盒重量
     * @param string $thumb_image
     *            缩略图
     * @param array $introduction_image
     *            介绍图
     * @param string $description
     *            简介
     * @return array
     */
    public function addGoods($name, $price, $single_price, $_price, $unit, $single_unit, $p_cate_id, $c_cate_id, $tag, $amount, $weight, $thumb_image, array $introduction_image, $description) {
        $data = array(
            'name' => $name,
            'price' => $price,
            'single_price' => $single_price,
            'unit' => $unit,
            'single_unit' => $single_unit,
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'thumb' => $thumb_image,
            'add_time' => time()
        );
        for ($i = 1; $i <= 5; $i++) {
            $data["image_{$i}"] = $introduction_image[$i - 1];
        }
        strlen($_price) && $data['_price'] = floatval($_price);
        $tag && $data['tag'] = $tag;
        strlen($amount) && $data['amount'] = intval($amount);
        strlen($weight) && $data['weight'] = intval($weight);
        strlen($description) && $data['description'] = $description;
        if ($this->add($data)) {
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
     * 删除商品
     *
     * @param array $id
     *            商品ID
     * @return array
     */
    public function deleteGoods(array $id) {
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->save(array(
            'is_delete' => 1
        ))) {
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
     * 获取商品总数
     *
     * @param string $keyword
     *            查询关键字
     * @param int $p_cate_id
     *            大分类
     * @param int $c_cate_id
     *            小分类
     * @param int $status
     *            状态
     * @return int
     */
    public function getGoodsCount($keyword, $p_cate_id, $c_cate_id, $status) {
        $where = array(
            'is_delete' => 0
        );
        empty($keyword) || $where['name'] = array(
            "like",
            "%{$keyword}%"
        );
        $p_cate_id && $where['p_cate_id'] = $p_cate_id;
        $c_cate_id && $where['c_cate_id'] = $c_cate_id;
        if ($status != -1) {
            $where['status'] = $status;
        }
        return (int) $this->where($where)->count();
    }

    /**
     * 获取商品列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param string $order
     *            排序字段
     * @param string $sort
     *            排序方式
     * @param string $keyword
     *            关键字
     * @param int $p_cate_id
     *            大分类
     * @param int $c_cate_id
     *            小分类
     * @param int $status
     *            状态
     */
    public function getGoodsList($page, $pageSize, $order, $sort, $keyword, $p_cate_id, $c_cate_id, $status) {
        $offset = ($page - 1) * $pageSize;
        $where = array(
            'g.is_delete' => 0
        );
        empty($keyword) || $where['g.name'] = array(
            "like",
            "%{$keyword}%"
        );
        $p_cate_id && $where['g.p_cate_id'] = $p_cate_id;
        $c_cate_id && $where['g.c_cate_id'] = $c_cate_id;
        if ($status != -1) {
            $where['g.status'] = $status;
        }
        return $this->table($this->getTableName() . " AS g ")->join(array(
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = g.p_cate_id ",
            " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON cc.id = g.c_cate_id ",
            " LEFT JOIN " . M('Tag')->getTableName() . " AS t ON t.id = g.tag "
        ))->field(array(
            'g.*',
            'pc.name' => 'parent_category',
            'cc.name' => 'child_category',
            't.name' => 'tag_name',
            "(SELECT COUNT(1) FROM " . M('Advertisement')->getTableName() . " WHERE goods_id = g.id)" => 'is_advertisement'
        ))->where($where)->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新商品
     *
     * @param int $id
     *            商品ID
     * @param string $name
     *            名称
     * @param float $price
     *            总价
     * @param float $single_price
     *            单价
     * @param string $_price
     *            市场价
     * @param string $unit
     *            总价单位
     * @param string $single_unit
     *            单价单位
     * @param int $p_cate_id
     *            大分类ID
     * @param int $c_cate_id
     *            小分类ID
     * @param string $amount
     *            每盒数量
     * @param string $weight
     *            每盒质量
     * @param string $thumb_image
     *            缩略图
     * @param array $introduction_image
     *            介绍图
     * @param string $description
     *            简介
     * @return array
     */
    public function updateGoods($id, $name, $price, $single_price, $_price, $unit, $single_unit, $p_cate_id, $c_cate_id, $tag, $amount, $weight, $thumb_image, array $introduction_image, $description) {
        $data = array(
            'name' => $name,
            'price' => $price,
            'single_price' => $single_price,
            'unit' => $unit,
            'single_unit' => $single_unit,
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'thumb' => $thumb_image,
            'update_time' => time()
        );
        $data['_price'] = strlen($_price) ? floatval($_price) : null;
        $data['description'] = strlen($description) ? $description : null;
        $data['amount'] = strlen($amount) ? intval($amount) : null;
        $data['weight'] = strlen($weight) ? intval($weight) : null;
        $data['tag'] = $tag ? $tag : null;
        for ($i = 1; $i <= 5; $i++) {
            $data["image_{$i}"] = $introduction_image[$i - 1];
        }
        if ($this->where(array(
            'id' => $id
        ))->save($data)) {
            return array(
                'status' => true,
                'msg' => '商品更新成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '商品更新失败'
            );
        }
    }

    /**
     * 更新权重
     *
     * @param int $goods_id
     *            商品ID
     * @param int $priority
     *            权重
     * @return array
     */
    public function updateGoodsPriority($goods_id, $priority) {
        if ($this->where(array(
            'id' => $goods_id
        ))->save(array(
            'priority' => $priority
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

    /**
     * 更新商品状态
     *
     * @param int $goods_id
     *            商品ID
     * @param int $status
     *            商品状态
     * @return array
     */
    public function updateGoodsStatus($goods_id, $status) {
        $data = array(
            'status' => $status
        );
        $status || $data['priority'] = 0;
        if ($this->where(array(
            'id' => $goods_id
        ))->save($data)) {
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

    /**
     * 更新商品标签
     *
     * @param int $id
     *            商品ID
     * @param string $tag
     *            标签
     * @return array
     */
    public function updateGoodsTag($id, $tag) {
        if ($tag == "暂无") {
            $tag_id = null;
        } else {
            $tag = M('Tag')->where(array(
                'name' => $tag
            ))->find();
            $tag_id = $tag['id'];
        }
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'tag' => $tag_id
        ))) {
            return array(
                'status' => true,
                'msg' => $this->getLastSql()
            );
        } else {
            return array(
                'status' => false,
                'msg' => '更新失败'
            );
        }
    }

}