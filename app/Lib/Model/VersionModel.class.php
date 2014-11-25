<?php

/**
 * fruit_version表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class VersionModel extends Model {

    /**
     * 添加版本
     *
     * @param string $version
     *            版本号
     * @param string $download_url
     *            下载链接
     * @param int $type
     *            版本类型(0:android,1:ios)
     * @return array
     */
    public function addVersion($version, $download_url, $type) {
        if ($this->where(array(
            'version' => $version,
            'type' => $type,
            'download_url' => $download_url
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '已经存在一个与此相同的版本'
            );
        }
        if ($this->add(array(
            'version' => $version,
            'download_url' => $download_url,
            'type' => $type,
            'add_time' => time()
        ))) {
            return array(
                'status' => 1,
                'msg' => '添加版本成功'
            );
        } else {
            return array(
                'status' => 0,
                'msg' => '添加版本失败'
            );
        }
    }

    /**
     * 删除版本
     *
     * @param array $id
     *            版本ID
     * @return array
     */
    public function deleteVersion(array $id) {
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
     * 获取版本总数
     *
     * @param int $type
     *            版本类型(0:android,1:ios)
     * @return int
     */
    public function getVersionCount($type) {
        return (int) $this->where(array(
            'type' => $type
        ))->count();
    }

    /**
     * 获取版本列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param string $order
     *            排序字段
     * @param string $sort
     *            排序方式
     * @param int $type
     *            版本类型(0:android,1:ios)
     */
    public function getVersionList($page, $pageSize, $order, $sort, $type) {
        $offset = ($page - 1) * $pageSize;
        return $this->where(array(
            'type' => $type
        ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 获取最新版本信息
     *
     * @param int $type
     *            版本类型（0：android、1：IOS）
     */
    public function lastVersion($type) {
        return $this->where(array(
            'type' => $type
        ))->order("id DESC")->limit(1)->select();
    }

}