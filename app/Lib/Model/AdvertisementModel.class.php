<?php

/**
 * tea_advertisement表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class AdvertisementModel extends Model {

    /**
     * 添加广告
     *
     * @param string $url
     *            广告链接
     * @param int $type
     *            广告类型（1：APP广告，2：网站广告）
     * @param string $image
     *            广告图片
     * @return array
     */
    public function addAdvertisement($url, $type, $image) {
        // 开启事务
        $this->startTrans();
        if ($this->add(array(
            'url' => $url,
            'type' => $type,
            'image' => $image,
            'add_time' => time()
        ))) {
            // 添加成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '添加广告成功'
            );
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '添加广告失败'
            );
        }
    }

    /**
     * 删除广告
     *
     * @param array $id
     *            广告ID
     * @return array
     */
    public function deleteAdvertisement(array $id) {
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
                        'msg' => '删除广告图片失败'
                    );
                }
            } else {
                continue;
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
                'msg' => '删除广告成功'
            );
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '删除广告失败'
            );
        }
    }

    /**
     * 获取广告总数
     *
     * @return int
     */
    public function getAdvertisementCount() {
        return (int) $this->count();
    }

    /**
     * 获取广告列表
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
    public function getAdvertisementList($page, $pageSize, $order, $sort) {
        return $this->limit(($page - 1) * $pageSize, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * 更新广告
     *
     * @param int $id
     *            广告ID
     * @param string $url
     *            广告链接
     * @param int $type
     *            广告类型（1：APP广告，2：网站广告）
     * @param string $image
     *            广告图片
     * @return array
     */
    public function updateAdvertisement($id, $url, $type, $image) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'url' => $url,
            'type' => $type,
            'image' => $image,
            'update_time' => time()
        ))) {
            // 更新成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '更新广告成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '更新广告失败'
            );
        }
    }

}