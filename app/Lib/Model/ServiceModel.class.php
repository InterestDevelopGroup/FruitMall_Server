<?php

/**
 * tea_service 表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class ServiceModel extends Model {

    /**
     * 添加客服
     *
     * @param string $contact
     *            联系人
     * @param string $phone
     *            联系电话
     * @param int $level
     *            服务星级
     * @param string $image
     *            客服头像
     * @return array
     */
    public function addService($contact, $phone, $qq, $level, $image) {
        $result = $this->where("contact = \"{$contact}\" AND phone = \"{$phone}\" AND qq = \"{$qq}\"")->find();
        if (!empty($result)) {
            return array(
                'status' => false,
                'msg' => "已经存在一位号码为{$phone}和qq号码为{$qq}的{$contact}了"
            );
        }
        $data = array(
            'contact' => $contact,
            'phone' => $phone,
            'qq' => $qq,
            'image' => $image,
            'add_time' => time(),
            'update_time' => time()
        );
        strlen($level) && $data['level'] = intval($level);
        // 开启事务
        $this->startTrans();
        if ($this->add($data)) {
            // 添加成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '添加客服成功'
            );
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '添加客服失败'
            );
        }
    }

    /**
     * 删除客服
     *
     * @param array $id
     *            客服ID
     * @return array
     */
    public function deleteService(array $id) {
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
                        'msg' => '删除客服头像失败'
                    );
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
                'msg' => '删除客服成功'
            );
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '删除客服失败'
            );
        }
    }

    /**
     * 获取客服总数
     *
     * @return int
     */
    public function getServiceCount() {
        $result = $this->field("COUNT(1) AS total")->select();
        return (int) $result[0]['total'];
    }

    /**
     * 获取客服列表
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
    public function getServiceList($page, $pageSize, $order, $sort) {
        return $this->field(array(
            "id",
            "contact",
            "phone",
            "level",
            "image",
            "FROM_UNIXTIME(add_time)" => "add_time",
            "FROM_UNIXTIME(update_time)" => "update_time"
        ))->limit(($page - 1) * $pageSize, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * 编辑客服
     *
     * @param int $id
     *            客服ID
     * @param string $contact
     *            联系人
     * @param string $phone
     *            联系电话
     * @param int $level
     *            服务星级
     * @param string $image
     *            客服头像
     * @return array
     */
    public function updateService($id, $contact, $phone, $qq, $level, $image) {
        $result = $this->where("contact = \"{$contact}\" AND phone = \"{$phone}\" AND qq = \"{$qq}\" AND id != {$id}")->find();
        if (!empty($result)) {
            return array(
                'status' => false,
                'msg' => "已经存在一位号码为{$phone},qq号为{$qq}的{$contact}了"
            );
        }
        $data = array(
            'contact' => $contact,
            'phone' => $phone,
            'qq' => $qq,
            'image' => $image,
            'update_time' => time()
        );
        $data['level'] = strlen($level) ? intval($level) : null;
        // 开启事务
        $this->startTrans();
        if ($this->where("id = {$id}")->save($data)) {
            // 更新成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '更新客服成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '更新客服失败'
            );
        }
    }

}