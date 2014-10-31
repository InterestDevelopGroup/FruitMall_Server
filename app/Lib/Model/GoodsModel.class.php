<?php

/**
 * tea_goods 表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class GoodsModel extends Model {

    /**
     * 添加商品
     *
     * @param string $name
     *            商品名称
     * @param float $price
     *            商品原价
     * @param float $_price
     *            商品现价
     * @param string $unit
     *            单位
     * @param array $image
     *            商品图片
     * @param int $weight
     *            单片质量
     * @param int $stock
     *            库存
     * @param int $year
     *            年份
     * @param int $cate
     *            分类ID
     * @param int $type
     *            类型（1：饼茶、2：沱茶、3：砖茶、4：散茶）
     * @param int $product_art
     *            生产工艺（web）（1：生茶、2：熟茶、3：生熟混合）
     * @param int $series
     *            商品系列id
     * @param int $zone
     *            所属专区ID
     * @param int $is_sell
     *            是否出售（1：是、0：否）
     * @param string $url
     *            淘宝链接
     * @param string $production
     *            生产工艺（app）
     * @param string $specification
     *            规格
     * @param string $burden
     *            配料
     * @param string $producer
     *            出品商
     * @param string $storage
     *            存储方式
     * @param string $description
     *            描述
     * @return array
     */
    public function addGoods($name, $price, $_price, $unit, array $image, $weight, $stock, $year, $cate, $type, $product_art, $series, $zone, $is_sell, $url, $production, $specification, $burden, $producer, $storage, $description) {
        if ($this->where(array(
            'name' => $name,
            'cate_id' => $cate,
            'year' => $year
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '同一分类同一年份不能<br />有两个名称相同的商品'
            );
        }
        $data = array(
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            'image' => $image[0],
            'weight' => $weight,
            'year' => $year,
            'cate_id' => $cate,
            'type' => $type,
            'product_art' => $product_art,
            'series_id' => $series,
            'zone' => $zone,
            'is_sell' => $is_sell,
            'add_time' => time()
        );
        strlen($_price) && $data['_price'] = floatval($_price);
        strlen($stock) && $data['stock'] = intval($stock);
        strlen($url) && $data['url'] = $url;
        strlen($production) && $data['production'] = $production;
        strlen($specification) && $data['specification'] = $specification;
        strlen($burden) && $data['burden'] = $burden;
        strlen($producer) && $data['producer'] = $producer;
        strlen($storage) && $data['storage'] = $storage;
        strlen($description) && $data['description'] = $description;
        isset($image[1]) && $data['image_2'] = $image[1];
        isset($image[2]) && $data['image_3'] = $image[2];
        isset($image[3]) && $data['image_4'] = $image[3];
        isset($image[4]) && $data['image_5'] = $image[4];
        // 开启事务
        $this->startTrans();
        if ($goods_id = $this->add($data)) {
            // 添加成功，提交事务
            $this->commit();
            if (!$is_sell) {
                M('goods_price')->add(array(
                    'goods_id' => $goods_id,
                    'price' => $_price,
                    'add_time' => time()
                ));
            }
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
    }

    /**
     * 删除商品
     *
     * @param array $id
     *            商品ID
     * @return array
     */
    public function deleteGoods(array $id) {
        $images = $this->field('image')->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->select();
        foreach ($images as $v) {
            if (empty($v['image'])) {
                continue;
            } else {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $v['image'])) {
                    if (unlink($_SERVER['DOCUMENT_ROOT'] . $v['image'])) {
                        continue;
                    } else {
                        return array(
                            'status' => false,
                            'msg' => '删除商品图片失败'
                        );
                    }
                } else {
                    continue;
                }
            }
        }
        // 开启事务
        $this->startTrans();
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
                'msg' => '删除商品成功'
            );
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '删除商品失败'
            );
        }
    }

    /**
     * 按照收藏ID获取商品收藏
     *
     * @param int $id
     *            收藏ID
     * @return array
     */
    public function getGoodsCollectionById($id) {
        return $this->table($this->getTableName() . " AS g ")->join(array(
            "LEFT JOIN " . M('Category')->getTableName() . " AS c ON g.cate_id = c.id"
        ))->field(array(
            'g.id',
            'g.name',
            'g.price',
            'g._price',
            'g.unit',
            'g.cate_id',
            'g.image',
            'g.image_2',
            'g.image_3',
            'g.image_4',
            'g.image_5',
            'g.weight',
            'g.year',
            'g.stock',
            'g.production',
            'g.specification',
            'g.burden',
            'g.producer',
            'g.storage',
            'g.description',
            'g.is_sell',
            'g.url',
            'g.type',
            'g.product_art',
            'g.series_id',
            'g.zone',
            'g.add_time',
            'g.update_time',
            'c.name' => 'cate'
        ))->where(array(
            'g.id' => $id
        ))->limit(1)->select();
    }

    /**
     * 获取商品总数
     *
     * @param string $keyword
     *            关键字
     * @return int
     */
    public function getGoodsCount($keyword, $year) {
        $where = array();
        empty($keyword) || $where['name'] = array('like', "%$keyword%");
        empty($year) || $where['year'] = $year;
        return (int) (empty($where) ? $this->count() : $this->where($where)->count());
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
    public function getGoodsList($page, $pageSize, $order, $sort, $keyword, $year, $is_sell = null) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    a.id, a.name, a.price, a._price, a.unit, a.cate_id, a.image,
                    a.image_2, a.image_3, a.image_4, a.image_5, a.weight,
                    a.year, a.stock, a.production, a.specification, a.burden,
                    a.producer, a.storage, a.description, a.is_sell, a.url,
                    a.type, a.product_art, a.series_id, a.zone, a.add_time,
                    a.update_time, b.name AS cate, c.name AS series,
                    d.name AS zone_name,
                    (
                    SELECT
                        COUNT(1)
                    FROM
                        tea_order
                    WHERE
                        goods_id = a.id AND
                        status = 1
                    ) AS sales
                FROM
                    " . $this->getTableName() . " AS a
                LEFT JOIN
                    " . M('Category')->getTableName() . " AS b
                ON
                    a.cate_id = b.id
                LEFT JOIN
                    " . M('Series')->getTableName() . " AS c
                ON
                    a.series_id = c.id
                LEFT JOIN
                    " . M('Zone')->getTableName() . " AS d
                ON
                    a.zone = d.id";
        $where = " WHERE 1 = 1 ";
        if (!is_null($is_sell)) {
            $where .= " AND a.is_sell = {$is_sell} ";
        }
        if (!empty($year)) {
            $where .= " AND a.year = {$year} ";
        }
        if (!empty($keyword)) {
            $where .= " AND a.name LIKE '%{$keyword}%' ";
        }
        $sql .= $where . " ORDER BY a.{$order} {$sort} LIMIT {$offset}, {$pageSize}";
        return $this->query($sql);
    }

    /**
     * 根据专区获取商品列表
     *
     * @param int $zone
     *            专区
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @return array
     */
    public function getGoodsListByZone($zone, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS g ")->join(array(
            " LEFT JOIN " . M('Category')->getTableName() . " AS c ON g.cate_id = c.id ",
            " LEFT JOIN " . M('Series')->getTableName() . " AS s ON g.series_id = s.id ",
            " LEFT JOIN " . M('Zone')->getTableName() . " AS z ON g.zone = z.id "
        ))->field(array(
            'g.id',
            'g.name',
            'g.price',
            'g._price',
            'g.unit',
            'g.cate_id',
            'g.image',
            'g.image_2',
            'g.image_3',
            'g.image_4',
            'g.image_5',
            'g.weight',
            'g.year',
            'g.stock',
            'g.production',
            'g.specification',
            'g.burden',
            'g.producer',
            'g.storage',
            'g.description',
            'g.is_sell',
            'g.url',
            'g.type',
            'g.product_art',
            'g.series_id',
            'g.zone',
            'g.add_time',
            'g.update_time',
            'c.name' => 'cate',
            's.name' => 'series',
            'z.name' => 'zone_name'
        ))->where(array(
            'g.zone' => $zone
        ))->order("g.id DESC")->limit($offset, $pageSize)->select();
    }

    /**
     * 获取升降价商品总数
     *
     * @param string $keyword
     *            关键字
     * @param int $type
     *            升降价（1为升价，0为降价）
     * @return int
     */
    public function getRiseOrReduceGoodsCount($keyword, $type) {
        $condition = array(
            'is_sell' => 0,
            'price' => $type ? array(
                'exp',
                ' < _price '
            ) : array(
                'exp',
                ' > _price '
            )
        );
        empty($keyword) || $condition['name'] = array(
            'like',
            "%$keyword%"
        );
        return (int) $this->where($condition)->count();
    }

    /**
     * 获取升降价商品列表
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
     * @param int $type
     *            升降价（1为升价，0为降价）
     * @param int $cate_id
     *            分类ID
     * @return array
     */
    public function getRiseOrReduceGoodsList($page, $pageSize, $order, $sort, $keyword, $type, $cate_id = null) {
        $_price = 'price';
        $condition = array(
            'g.is_sell' => 0,
            'price' => $type ? array(
                'exp',
                ' < _price '
            ) : array(
                'exp',
                ' > _price '
            )
        );
        $cate_id && $condition['g.cate_id'] = $cate_id;
        empty($keyword) || $condition['g.name'] = array(
            'like',
            "%$keyword%"
        );
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS g ")->join(array(
            " LEFT JOIN " . M('Category')->getTableName() . " AS c ON g.cate_id = c.id "
        ))->field(array(
            'g.id',
            'g.name',
            'g.price',
            'g._price',
            'g.unit',
            'g.cate_id',
            'g.image',
            'g.image_2',
            'g.image_3',
            'g.image_4',
            'g.image_5',
            'g.weight',
            'g.year',
            'g.stock',
            'g.production',
            'g.specification',
            'g.burden',
            'g.producer',
            'g.storage',
            'g.description',
            'g.is_sell',
            'g.url',
            'g.type',
            'g.product_art',
            'g.series_id',
            'g.zone',
            'g.add_time',
            'g.update_time',
            'c.name' => 'cate'
        ))->where($condition)->order("g." . $order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 商品API获取商品列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param int $cate_id
     *            分类ID
     * @param int $year
     *            年份
     * @param int $is_sell
     *            是否可售（0：不可售，1：可售）
     * @return array
     */
    public function goodsApiGetGoodsList($page, $pageSize, $cate_id, $year, $is_sell) {
        $condition = " a.is_sell = {$is_sell} ";
        $cate_id && $condition .= " AND a.cate_id = {$cate_id} ";
        $year && $condition .= " AND a.year = {$year} ";
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    a.id, a.name, a.price, a._price, a.unit, a.cate_id, a.image,
                    a.image_2, a.image_3, a.image_4, a.image_5, a.weight,
                    a.year, a.stock, a.production, a.specification, a.burden,
                    a.producer, a.storage, a.description, a.is_sell, a.url,
                    a.type, a.product_art, a.series_id, a.zone, a.add_time,
                    a.update_time, b.name AS cate,
                    (SELECT
                        COUNT(1)
                    FROM
                        " . M('Order')->getTableName() . "
                    WHERE
                        goods_id = a.id AND
                        status = 1
                    ) AS sales
                FROM
                    " . $this->getTableName() . " AS a
                LEFT JOIN
                    tea_category AS b
                ON
                    a.cate_id = b.id
                WHERE
                    {$condition}
                ORDER BY
                    a.id DESC
                LIMIT
                    {$offset}, {$pageSize}";
        return $this->query($sql);
    }

    /**
     * 更新商品
     *
     * @param int $id
     *            商品ID
     * @param string $name
     *            商品名称
     * @param float $price
     *            商品原价
     * @param float $_price
     *            商品现价
     * @param string $unit
     *            单位
     * @param array $image
     *            商品图片
     * @param int $weight
     *            商品单片质量
     * @param int $stock
     *            库存
     * @param int $year
     *            年份
     * @param int $cate
     *            分类ID
     * @param string $production
     *            生产工艺
     * @param string $specification
     *            规格
     * @param string $burden
     *            配料
     * @param string $producer
     *            出品商
     * @param string $storage
     *            存储方式
     * @param string $description
     *            商品简介
     * @return array
     */
    public function updateGoods($id, $name, $price, $_price, $unit, array $image, $weight, $stock, $year, $cate, $type, $product_art, $series, $zone, $is_sell, $url, $production, $specification, $burden, $producer, $storage, $description) {
        if ($this->where(array(
            'name' => $name,
            'cate_id' => $cate,
            'year' => $year,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '同一分类同一年份不能<br />有两个名称相同的商品'
            );
        }
        $data = array(
            'name' => $name,
            'price' => $price,
            'unit' => $unit,
            'image' => $image[0],
            'weight' => $weight,
            'year' => $year,
            'cate_id' => $cate,
            'type' => $type,
            'product_art' => $product_art,
            'series_id' => $series,
            'zone' => $zone,
            'is_sell' => $is_sell,
            'update_time' => time()
        );
        $data['_price'] = strlen($_price) ? floatval($_price) : null;
        $data['stock'] = strlen($stock) ? intval($stock) : null;
        $data['url'] = strlen($url) ? $url : null;
        $data['production'] = strlen($production) ? $production : null;
        $data['specification'] = strlen($specification) ? $specification : null;
        $data['burden'] = strlen($burden) ? $burden : null;
        $data['producer'] = strlen($producer) ? $producer : null;
        $data['storage'] = strlen($storage) ? $storage : null;
        $data['description'] = strlen($description) ? $description : null;
        $data['image_2'] = isset($image[1]) ? $image[1] : null;
        $data['image_3'] = isset($image[2]) ? $image[1] : null;
        $data['image_4'] = isset($image[3]) ? $image[1] : null;
        $data['image_5'] = isset($image[4]) ? $image[1] : null;
        // 开启事务
        $this->startTrans();
        if ($this->where("id = {$id}")->save($data)) {
            // 更新成功，提交事务
            $this->commit();
            if (!$is_sell) {
                M('goods_price')->add(array(
                    'goods_id' => $id,
                    'price' => $_price,
                    'add_time' => time()
                ));
            }
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

}