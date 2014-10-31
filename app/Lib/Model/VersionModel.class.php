<?php

/**
 * tea_version表模型
 *
 * @author lzjjie
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
        $is_exists = $this->where("version = {$version} AND type = {$type} AND download_url = {$download_url}")->count();
        if ($is_exists) {
            return array(
                'status' => false,
                'msg' => '已经存在一个与此相同的版本'
            );
        }
        // 开启事务
        $this->startTrans();
        if ($this->add(array(
            'version' => $version,
            'download_url' => $download_url,
            'type' => $type,
            'add_time' => time()
        ))) {
            // 添加成功，提交事务
            $this->commit();
            return array(
                'status' => 1,
                'msg' => '添加版本成功'
            );
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'msg' => '添加版本失败'
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
        return (int) $this->where("type = {$type}")->count();
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
        return $this->field(array(
            'id',
            'version',
            'download_url',
            'FROM_UNIXTIME(add_time)' => 'add_time'
        ))->where("type = {$type}")->order($order . " " . $sort)->limit(($page - 1) * $pageSize, $pageSize)->select();
    }

}