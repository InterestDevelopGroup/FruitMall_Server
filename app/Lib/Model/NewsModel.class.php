<?php

/**
 * tea_news表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class NewsModel extends Model {

    /**
     * 添加市场资讯
     *
     * @param string $title
     *            资讯标题
     * @param string $business_start_time
     *            开始营业时间
     * @param string $business_end_time
     *            结束营业时间
     * @param int $is_free_parking
     *            是否免费停车（0：否，1：是）
     * @param float $per_fee
     *            人均消费
     * @param string $address
     *            地址
     * @param string $bus_path
     *            乘车路线
     * @param string $description
     *            简介
     * @param string $image
     *            图片
     * @return array
     */
    // public function addNews($title,$type_id,$business_start_time, $business_end_time, $is_free_parking, $is_top, $per_fee, $address, $bus_path, $description, $image) {
    public function addNews($title, $type_id, $is_top, $description, $image) {
        if ((int) $this->where(array(
            'title' => $title
        ))->count()) {
            return array(
                'status' => true,
                'msg' => '该资讯已经存在（资讯标题不能重复）'
            );
        }
        $data = array(
            'title' => $title,
            'type_id' => $type_id,
            'is_top' => $is_top,
            'description' => $description,
            'image' => $image,
            'add_time' => time(),
            'update_time' => time()
        );
        // $data = array(
        // 'title' => $title,
        // 'type_id' => $type_id,
        // 'image' => $image,
        // 'is_free_parking' => $is_free_parking,
        // 'is_top' => $is_top,
        // 'add_time' => time(),
        // 'update_time' => time()
        // );
        // strlen($business_start_time) && $data['business_start_time'] = $business_start_time;
        // strlen($business_end_time) && $data['business_end_time'] = $business_end_time;
        // strlen($per_fee) && $data['per_fee'] = floatval($per_fee);
        // strlen($address) && $data['address'] = $address;
        // strlen($bus_path) && $data['bus_path'] = $bus_path;
        // strlen($description) && $data['description'] = $description;
        // 开启事务
        $this->startTrans();
        if ($this->add($data)) {
            // 添加成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '添加市场资讯成功'
            );
        } else {
            // 添加失败，回滚事务
            return array(
                'status' => false,
                'msg' => '添加市场资讯失败'
            );
        }
    }

    /**
     * 删除市场资讯
     *
     * @param array $id
     *            资讯ID
     * @return array
     */
    public function deleteNews(array $id) {
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
                            'msg' => '删除资讯图片失败'
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
                'msg' => '删除资讯成功'
            );
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '删除资讯失败'
            );
        }
    }

    /**
     * 获取资讯总数
     *
     * @param string $keyword
     *            关键字
     * @return int
     */
    public function getNewsCount($keyword) {
        $this->field("COUNT(1) AS total");
        empty($keyword) || $this->where("title LIKE '%{$keyword}%'");
        $result = $this->select();
        return (int) $result[0]['total'];
    }

    /**
     * 获取资讯列表
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
     */
    public function getNewsList($page, $pageSize, $order, $sort, $keyword) {
        $this->field("id, title, address, description, business_start_time, business_end_time, bus_path, is_free_parking, is_top, per_fee,type_id");
        empty($keyword) || $this->where("title LIKE '%{$keyword}%'");
        return $this->limit(($page - 1) * $pageSize, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * 更新市场资讯
     *
     * @param int $id
     *            资讯ID
     * @param string $title
     *            标题
     * @param string $business_start_time
     *            开始营业时间
     * @param string $business_end_time
     *            结束营业时间
     * @param int $is_free_parking
     *            是否免费停车（0：否，1：是）
     * @param int $is_top
     *            是否顶置（0：否，1：是）
     * @param float $per_fee
     *            人均消费
     * @param string $address
     *            地址
     * @param string $bus_path
     *            乘车路线
     * @param string $description
     *            简介
     * @param string $image
     *            图片
     * @return array
     */
    // public function updateNews($id, $title, $type_id, $business_start_time, $business_end_time, $is_free_parking, $is_top, $per_fee, $address, $bus_path, $description, $image) {
    public function updateNews($id, $title, $type_id, $is_top, $description, $image) {
        // $result = $this->where("title = \"{$title}\" AND id != {$id}")->find();
        // if (!empty($result)) {
        // return array(
        // 'status' => true,
        // 'msg' => '该资讯已经存在（资讯标题不能重复）'
        // );
        // }
        if ((int) $this->where(array(
            'title' => $title,
            'id' => array(
                'neq',
                $id
            )
        ))->count()) {
            return array(
                'status' => true,
                'msg' => '该资讯已经存在（资讯标题不能重复）'
            );
        }
        $data = array(
            'title' => $title,
            'type_id' => $type_id,
            'is_top' => $is_top,
            'description' => $description,
            'image' => $image,
            'update_time' => time()
        );
        // $data = array(
        // 'title' => $title,
        // 'type_id' => $type_id,
        // 'image' => $image,
        // 'is_free_parking' => $is_free_parking,
        // 'is_top' => $is_top,
        // 'update_time' => time()
        // );
        // $data['business_start_time'] = strlen($business_start_time) ? $business_start_time : null;
        // $data['business_end_time'] = strlen($business_end_time) ? $business_end_time : null;
        // $data['per_fee'] = strlen($per_fee) ? floatval($per_fee) : null;
        // $data['address'] = strlen($address) ? $address : null;
        // $data['bus_path'] = strlen($bus_path) ? $bus_path : null;
        // $data['description'] = strlen($description) ? $description : null;
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save($data)) {
            // 更新成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '编辑市场资讯成功'
            );
        } else {
            // 更新失败，回滚事务
            return array(
                'status' => false,
                'msg' => '编辑市场资讯失败'
            );
        }
    }

}