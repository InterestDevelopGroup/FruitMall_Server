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
     * @param string $phone
     *            手机
     * @param string $password
     *            密码
     * @return array
     */
    public function addCourier($real_name, $phone, $password) {
        if ($this->where(array(
            'real_name' => $real_name,
            'phone' => $phone
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '该送货员已经存在'
            );
        }
        if ($this->add(array(
            'real_name' => $real_name,
            'phone' => $phone,
            'password' => md5($password),
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
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            if (!D('BranchCourier')->deleteCourierByCourierId($id)) {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除失败'
                );
            }
            // 删除成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '删除成功'
            );
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '删除失败'
            );
        }
    }

    /**
     * 获取送货员总数
     *
     * @return int
     */
    public function getCourierCount() {
        // 获取当前管理员
        $admin = session('admin_info');
        if ($admin && $admin['type'] != 1) {
            $branch_id[] = array_map(function ($value) {
                return $value['id'];
            }, M('Branch')->where(array(
                'admin_id' => $admin['id']
            ))->select());
        }
        if ($branch_id) {
            return $this->table($this->getTableName() . " AS c ")->join(array(
                " LEFT JOIN " . M('BranchCourier')->getTableName() . " AS bc ON c.id = bc.courier_id "
            ))->where(array(
                'bc.branch_id' => array(
                    'in',
                    $branch_id[0]
                )
            ))->count();
        } else {
            return (int) $this->count();
        }
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
        // 获取当前管理员
        $admin = session('admin_info');
        if ($admin && $admin['type'] != 1) {
            $branch_id[] = array_map(function ($value) {
                return $value['id'];
            }, M('Branch')->where(array(
                'admin_id' => $admin['id']
            ))->select());
        }
        $offset = ($page - 1) * $pageSize;
        if ($branch_id) {
            return $this->table($this->getTableName() . " AS c ")->field(array(
                'c.*',
                "(SELECT COUNT(1) FROM " . M('Order')->getTableName() . " WHERE courier_id = c.id AND status > 2)" => 'sent_amount',
                "(SELECT COUNT(1) FROM " . M('Order')->getTableName() . " WHERE courier_id = c.id AND status <= 2)" => 'unsend_amount',
                "(SELECT COUNT(1) FROM " . M('Returns')->getTableName() . " AS r LEFT JOIN " . M('Order')->getTableName() . " AS o ON o.order_number = r.order_number WHERE o.courier_id = c.id)" => 'complain_amount',
                "(SELECT b.name FROM " . M('BranchCourier')->getTableName() . " AS bc LEFT JOIN " . M('Branch')->getTableName() . " AS b ON b.id = bc.branch_id WHERE courier_id = c.id)" => 'branch'
            ))->join(array(
                " LEFT JOIN " . M('BranchCourier')->getTableName() . " AS bc ON c.id = bc.courier_id "
            ))->where(array(
                'bc.branch_id' => array(
                    'in',
                    $branch_id[0]
                )
            ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
        } else {
            return $this->table($this->getTableName() . " AS c ")->field(array(
                'c.*',
                "(SELECT COUNT(1) FROM " . M('Order')->getTableName() . " WHERE courier_id = c.id AND status > 2)" => 'sent_amount',
                "(SELECT COUNT(1) FROM " . M('Order')->getTableName() . " WHERE courier_id = c.id AND status <= 2)" => 'unsend_amount',
                "(SELECT COUNT(1) FROM " . M('Returns')->getTableName() . " AS r LEFT JOIN " . M('Order')->getTableName() . " AS o ON o.order_number = r.order_number WHERE o.courier_id = c.id)" => 'complain_amount',
                "(SELECT b.name FROM " . M('BranchCourier')->getTableName() . " AS bc LEFT JOIN " . M('Branch')->getTableName() . " AS b ON b.id = bc.branch_id WHERE courier_id = c.id)" => 'branch'
            ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
        }
    }

    /**
     * 送货员登录
     *
     * @param string $phone
     *            送货员手机
     * @param string $password
     *            登录密码
     * @return array
     */
    public function login($phone, $password) {
        $result = $this->where(array(
            'phone' => $phone
        ))->find();
        if (empty($result)) {
            return array(
                'status' => 0,
                'result' => '送货员不存在'
            );
        }
        if (md5($password) == $result['password']) {
            return array(
                'status' => 1,
                'result' => $result
            );
        } else {
            return array(
                'status' => 0,
                'result' => '密码错误'
            );
        }
    }

    /**
     * 更新小分类
     *
     * @param int $id
     *            送货员ID
     * @param string $real_name
     *            真实姓名
     * @param string $phone
     *            手机
     * @param string $password
     *            密码
     * @return array
     */
    public function updateCourier($id, $real_name, $phone, $password) {
        if ($this->where(array(
            'id' => array(
                'neq',
                $id
            ),
            'real_name' => $real_name,
            'phone' => $phone
        ))->count()) {
            return array(
                'status' => false,
                'msg' => '该送货员已经存在'
            );
        }
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'real_name' => $real_name,
            'phone' => $phone,
            'password' => md5($password),
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