<?php

/**
 * fruit_courier表模型
*
* @author Zonkee
* @version 1.0.0
* @since 1.0.0
*/
class CourierModel extends Model {

    /**
     * 添加送货人员
     *
     * @param string $real_name
     *            真实姓名
     * @param int $phone
     *            手机
     * @return array
     */
    public function addCourier($real_name, $phone) {
        if ($this->add(array(
            'real_name' => $real_name,
            'phone' => $phone,
            'add_time' => time()
        ))) {
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
     * 删除送货员
     *
     * @param array $id
     *            送货员ID
     * @return array
     */
    public function deleteCourier(array $id) {
        // // 开启事务
        // $this->startTrans();
        // if ($this->where(array(
        // 'id' => array(
        // 'in',
        // $id
        // )
        // ))->delete()) {
        // if (M('Goods')->where(array(
        // 'c_cate_id' => array(
        // 'in',
        // $id
        // )
        // ))->count()) {
        // if (M('Goods')->where(array(
        // 'c_cate_id' => array(
        // 'in',
        // $id
        // )
        // ))->delete()) {
        // // 删除成功，提交事务
        // $this->commit();
        // return array(
        // 'status' => true,
        // 'msg' => '删除成功'
        // );
        // } else {
        // // 删除失败，回滚事务
        // $this->rollback();
        // return array(
        // 'status' => false,
        // 'msg' => '删除失败'
        // );
        // }
        // } else {
        // // 删除成功，提交事务
        // $this->commit();
        // return array(
        // 'status' => true,
        // 'msg' => '删除成功'
        // );
        // }
        // } else {
        // // 删除失败，回滚事务
        // $this->rollback();
        // return array(
        // 'status' => false,
        // 'msg' => '删除失败'
        // );
        // }
    }

    /**
     * 获取送货员总数
     *
     * @return int
     */
    public function getCourierCount() {
        return (int) $this->count();
    }

    /**
     * 获取送货员列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param string $order
     *            排序字段
     * @param string $sort
     *            排序方式
     * @return array
     */
    public function getCourierList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新小分类
     *
     * @param int $id
     *            送货员ID
     * @param string $real_name
     *            真实姓名
     * @param int $phone
     *            手机
     * @return array
     */
    public function updateCourier($id, $real_name, $phone) {
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'real_name' => $real_name,
            'phone' => $phone,
            'update_time' => time()
        ))) {
            return array(
                'status' => true,
                'msg' => '更新成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '更新失败'
            );
        }
    }

}