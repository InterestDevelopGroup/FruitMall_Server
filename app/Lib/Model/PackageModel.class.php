<?php

/**
 * fruit_package 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class PackageModel extends Model {

    /**
     * 获取套餐列表（API）
     *
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @param string|null $keyword
     *            关键字
     */
    public function _getPackageList($offset, $pagesize, $keyword) {
        $where = array(
            'is_delete' => 0
        );
        empty($keyword) || $where['name'] = array(
            "like",
            "%{$keyword}%"
        );
        return array_map(function ($value) {
            $value['goods_list'] = M('PackageGoods')->table(M('PackageGoods')->getTableName() . " AS pg ")->join(array(
                " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON g.id = pg.goods_id "
            ))->field(array(
                'pg.goods_id',
                'pg.amount',
                'g.name',
                'g.price',
                'g._price',
                'g.unit',
                'g.thumb'
            ))->where(array(
                'pg.package_id' => $value['id'],
                'g.is_delete' => 0
            ))->select();
            return $value;
        }, $this->limit($offset, $pagesize)->where($where)->select());
    }

    /**
     * 添加套餐
     *
     * @param string $name
     *            名称
     * @param float $price
     *            单价
     * @param string $_price
     *            市场价
     * @param string $thumb_image
     *            缩略图
     * @param array $introduction_image
     *            介绍图
     * @param array $package_goods
     *            套餐商品
     * @param string $description
     *            简介
     * @return array
     */
    public function addPackage($name, $price, $_price, $thumb_image, array $introduction_image, array $package_goods, $description) {
        $data = array(
            'name' => $name,
            'price' => $price,
            'thumb' => $thumb_image,
            'add_time' => time()
        );
        for ($i = 1; $i <= 5; $i++) {
            $data["image_{$i}"] = $introduction_image[$i - 1] ? $introduction_image[$i - 1] : null;
        }
        strlen($_price) && $data['_price'] = floatval($_price);
        strlen($description) && $data['description'] = $description;
        // 开启事务
        $this->startTrans();
        if ($this->add($data)) {
            $package_id = $this->getLastInsID();
            if (D('PackageGoods')->addPackageGoods($package_id, $package_goods)) {
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
     * 删除套餐
     *
     * @param array $id
     *            套餐ID
     * @return array
     */
    public function deletePackage(array $id) {
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
     * 获取套餐总数
     *
     * @param string $keyword
     *            关键字
     * @param int $is_delete
     *            是否刪除（0：否，1：是）
     * @return int
     */
    public function getPackageCount($keyword, $is_delete = 0) {
        $where = array(
            'is_delete' => $is_delete
        );
        empty($keyword) || $where['name'] = array(
            "like",
            "%{$keyword}%"
        );
        return (int) $this->where($where)->count();
    }

    /**
     * 获取套餐列表
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
     * @param int $is_delete
     *            是否刪除（0：否，1：是）
     * @return array
     */
    public function getPackageList($page, $pageSize, $order, $sort, $keyword, $is_delete = 0) {
        $offset = ($page - 1) * $pageSize;
        $where = array(
            'is_delete' => $is_delete
        );
        empty($keyword) || $where['name'] = array(
            "like",
            "%{$keyword}%"
        );
        return array_map(function ($value) {
            $value['goods_list'] = M('PackageGoods')->table(M('PackageGoods')->getTableName() . " AS pg ")->join(array(
                " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON g.id = pg.goods_id "
            ))->field(array(
                'pg.goods_id',
                'pg.amount',
                'g.name',
                'g.thumb'
            ))->where(array(
                'pg.package_id' => $value['id'],
                'g.is_delete' => 0
            ))->select();
            return $value;
        }, $this->table($this->getTableName() . " AS p ")->field(array(
            'p.*',
            "(SELECT COUNT(1) FROM " . M('Advertisement')->getTableName() . " WHERE package_id = p.id)" => 'is_advertisement'
        ))->where($where)->limit($offset, $pagesize)->select());
    }

    /**
     * 更新套餐
     *
     * @param int $id
     *            套餐ID
     * @param string $name
     *            名称
     * @param float $price
     *            单价
     * @param string $_price
     *            市场价
     * @param string $thumb_image
     *            缩略图
     * @param array $introduction_image
     *            介绍图
     * @param string $description
     *            简介
     * @return array
     */
    public function updatePackage($id, $name, $price, $_price, $thumb_image, array $introduction_image, array $package_goods, $description) {
        $data = array(
            'name' => $name,
            'price' => $price,
            'thumb' => $thumb_image,
            'update_time' => time()
        );
        $data['_price'] = strlen($_price) ? floatval($_price) : null;
        $data['description'] = strlen($description) ? $description : null;
        for ($i = 1; $i <= 5; $i++) {
            $data["image_{$i}"] = $introduction_image[$i - 1];
        }
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save($data)) {
            if (D('PackageGoods')->addPackageGoods($id, $package_goods)) {
                // 添加成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '套餐更新成功'
                );
            } else {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '套餐更新失败'
                );
            }
        } else {
            // 更新失败，回滚事务
            return array(
                'status' => false,
                'msg' => '套餐更新失败'
            );
        }
    }

}