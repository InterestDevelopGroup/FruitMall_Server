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
     *            商品名
     * @param float $price
     *            单价
     * @param float $_price
     *            市场价
     * @param string $unit
     *            价格单位
     * @param int $p_cate_id
     *            大分类ID
     * @param int $c_cate_id
     *            小分类ID
     * @param string $description
     *            简介
     * @param array $image
     *            商品图片
     * @return array
     */
    public function addGoods($name, $price, $_price, $unit, $p_cate_id, $c_cate_id, $description, array $image) {
        if ($this->where(array(
            'name' => $name
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '商品名不能重复'
            );
        }
        $data = array(
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'add_time' => time()
        );
        strlen($_price) && $data['_price'] = floatval($_price);
        strlen($description) && $data['description'] = $description;
        // 开启事务
        $this->startTrans();
        if ($this->add($data)) {
            $goods_id = $this->getLastInsID();
            $dataList = array();
            $add_time = time();
            for ($i = 0; $i < count($image); $i++) {
                $dataList[] = array(
                    'goods_id' => $goods_id,
                    'image' => $image[$i],
                    'add_time' => $add_time
                );
            }
            if (M('GoodsImage')->addAll($dataList)) {
                // 添加成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '添加成功'
                );
            } else {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '添加失败'
                );
            }
        } else {
            // 添加失败，回滚事务
            $this->rollback();
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
     *
     * @param int $id
     *            商品ID
     * @param string $name
     *            商品名
     * @param float $price
     *            单价
     * @param float|string $_price
     *            市场价
     * @param string $unit
     *            价格单位
     * @param int $p_cate_id
     *            大分类ID
     * @param int $c_cate_id
     *            小分类ID
     * @param string $description
     *            商品简介
     * @param array $image
     *            更新的商品图片
     * @return array
     */
    public function updateGoods($id, $name, $price, $_price, $unit, $p_cate_id, $c_cate_id, $description, array $image) {
        if ($this->where(array(
            'name' => $name,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '商品名不能重复'
            );
        }
        $data = array(
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            'p_cate_id' => $p_cate_id,
            'c_cate_id' => $c_cate_id,
            'update_time' => time()
        );
        $data['_price'] = strlen($_price) ? floatval($_price) : null;
        $data['description'] = strlen($description) ? $description : null;
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save($data)) {
            if (empty($image)) {
                // 更新成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '商品更新成功'
                );
            } else {
                $dataList = array();
                $add_time = time();
                for ($i = 0; $i < count($image); $i++) {
                    $dataList[] = array(
                        'goods_id' => $id,
                        'image' => $image[$i],
                        'add_time' => $add_time
                    );
                }
                if (M('GoodsImage')->addAll($dataList)) {
                    // 更新成功，提交事务
                    $this->commit();
                    return array(
                        'status' => true,
                        'msg' => '商品更新成功'
                    );
                } else {
                    // 更新失败，回滚事务
                    $this->rollback();
                    return array(
                        'status' => false,
                        'msg' => '商品更新失败'
                    );
                }
            }
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '商品更新失败'
            );
        }
    }

}