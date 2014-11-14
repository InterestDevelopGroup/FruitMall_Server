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
        empty($keyword) || $this->where(array(
            "name" => array(
                "like",
                "%{$keyword}%"
            )
        ));
        return $this->limit($offset, $pagesize)->select();
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
     * @param string $description
     *            简介
     * @return array
     */
    public function addPackage($name, $price, $_price, $thumb_image, array $introduction_image, $description) {
        $data = array(
            'name' => $name,
            'price' => $price,
            'thumb' => $thumb_image,
            'add_time' => time()
        );
        for ($i = 1; $i <= 5; $i++) {
            $data["image_{$i}"] = $introduction_image[$i - 1];
        }
        strlen($_price) && $data['_price'] = floatval($_price);
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
     * 获取套餐总数
     *
     * @param string $keyword
     *            关键字
     * @return int
     */
    public function getPackageCount($keyword) {
        empty($keyword) || $this->where(array(
            "name" => array(
                "like",
                "%{$keyword}%"
            )
        ));
        return (int) $this->count();
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
     * @return array
     */
    public function getPackageList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            "name" => array(
                "like",
                "%{$keyword}%"
            )
        ));
        return $this->order($order . " " . $sort)->limit($offset, $pageSize)->select();
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
    public function updatePackage($id, $name, $price, $_price, $thumb_image, $introduction_image, $description) {
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
        if ($this->where(array(
            'id' => $id
        ))->save($data)) {
            return array(
                'status' => true,
                'msg' => '套餐更新成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '套餐更新失败'
            );
        }
    }

}