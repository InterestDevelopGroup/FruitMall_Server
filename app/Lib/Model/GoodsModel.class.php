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
     * 添加商品
     *
     * @param string $name
     *            名称
     * @param float $price
     *            单价
     * @param string $_price
     *            市场价
     * @param string $unit
     *            价格单位
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
    public function addGoods($name, $price, $_price, $unit, $p_cate_id, $c_cate_id, $amount, $weight, $thumb_image, array $introduction_image, $description) {
        $data = array(
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'thumb' => $thumb_image,
            'add_time' => time()
        );
        for ($i = 1; $i <= 5; $i++) {
            $data["image_{$i}"] = $introduction_image[$i - 1];
        }
        strlen($_price) && $data['_price'] = floatval($_price);
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
     * 获取商品总数
     *
     * @param string $keyword
     *            关键字
     * @return int
     */
    public function getGoodsCount($keyword) {
        empty($keyword) || $this->where(array(
            'name' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return (int) $this->count();
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
     * @return array
     */
    public function getGoodsList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        $this->table($this->getTableName() . " AS g ")->join(array(
            " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = g.p_cate_id ",
            " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON cc.id = g.c_cate_id "
        ))->field(array(
            'g.id',
            'g.p_cate_id',
            'g.c_cate_id',
            'g.name',
            'g.price',
            'g._price',
            'g.unit',
            'g.description',
            'g.add_time',
            'g.update_time',
            'pc.name' => 'parent_category',
            'cc.name' => 'child_category'
        ))->order($order . " " . $sort)->limit($offset, $pageSize);
        empty($keyword) || $this->where(array(
            'g.name' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return $this->select();
    }

    /**
     * 更新商品
     *
     * @param int $id
     *            商品ID
     * @param string $name
     *            名称
     * @param float $price
     *            单价
     * @param string $_price
     *            市场价
     * @param string $unit
     *            价格单位
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
    public function updateGoods($id, $name, $price, $_price, $unit, $p_cate_id, $c_cate_id, $amount, $weight, $thumb_image, array $introduction_image, $description) {
        $data = array(
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'thumb' => $thumb_image,
            'update_time' => time()
        );
        $data['_price'] = strlen($_price) ? floatval($_price) : null;
        $data['description'] = strlen($description) ? $description : null;
        $data['amount'] = strlen($amount) ? intval($amount) : null;
        $data['weight'] = strlen($weight) ? intval($weight) : null;
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

}