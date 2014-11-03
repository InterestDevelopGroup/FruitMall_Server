<?php

/**
 * tea_zone 表模型
 *
 * @author lzjjie
 * @version 1.0.1
 * @since 1.0.1
 */
class ZoneModel extends Model {

    /**
     * 添加专区
     *
     * @param string $name
     *            专区名称
     * @param string $image
     *            专区图片
     * @return array
     */
    public function addZone($name, $image) {
        if ($this->where(array(
            'name' => $name
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '该专区已经存在'
            );
        }
        if ($this->add(array(
            'name' => $name,
            'image' => $image,
            'add_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '添加专区成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '添加专区失败'
            );
        }
    }

    /**
     * 删除专区
     *
     * @param array $id
     *            专区ID
     * @return array
     */
    public function deleteZone(array $id) {
        $images = $this->field("CONCAT('{$_SERVER['DOCUMENT_ROOT']}', image) AS image_path")->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->select();
        foreach ($images as $v) {
            if (file_exists($v['image_path'])) {
                if (unlink($v['image_path'])) {
                    continue;
                } else {
                    return array(
                        'status' => false,
                        'msg' => '删除专区图片失败'
                    );
                }
            }
        }
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            return array(
                'status' => true,
                'msg' => '删除专区成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '删除专区失败'
            );
        }
    }

    /**
     * 获取专区总数
     *
     * @return int
     */
    public function getZoneCount() {
        return (int) $this->count();
    }

    /**
     * 获取专区列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param string $order
     *            排序字段
     * @param string $sort
     *            排序方式
     */
    public function getZoneList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新专区
     *
     * @param int $id
     *            专区ID
     * @param string $name
     *            专区名称
     * @param string $image
     *            专区图片
     * @return array
     */
    public function updateZone($id, $name, $image) {
        if ($this->where(array(
            'id' => array(
                'neq',
                $id
            ),
            'name' => $name
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '该专区已经存在'
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
                'msg' => '更新专区成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '更新专区失败'
            );
        }
    }

}