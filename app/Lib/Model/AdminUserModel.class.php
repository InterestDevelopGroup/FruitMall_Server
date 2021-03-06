<?php

/**
 * fruit_admin_user 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class AdminUserModel extends Model {

    /**
     * 添加管理员
     *
     * @param string $username
     *            管理员帐号
     * @param string $password
     *            密码
     * @param string $realname
     *            真实姓名
     * @param string $email
     *            邮箱
     * @param string $desc
     *            简介
     * @return array
     */
    public function addAdministrator($username, $password, $realname, $email, $desc) {
        $result = $this->where("username = \"{$username}\"")->find();
        if (!empty($result)) {
            return array(
                'status' => false,
                'msg' => '该管理员已经存在（管理员帐号不能重复）'
            );
        }
        $data = array(
            'username' => $username,
            'password' => md5($password),
            'real_name' => $realname,
            'add_time' => time()
        );
        strlen($email) && $data['email'] = $email;
        strlen($desc) && $data['desc'] = $desc;
        // 开始事务
        $this->startTrans();
        if ($this->add($data)) {
            $id = $this->getLastInsID();
            // 初始化权限
            $privileges = 'index|all,login|all';
            if (M('AdminPriv')->add(array(
                'admin_id' => $id,
                'priv' => $privileges
            ))) {
                // 添加成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '添加管理员成功'
                );
            } else {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '添加管理员失败'
                );
            }
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '添加管理员失败'
            );
        }
    }

    /**
     * 登录验证
     *
     * @param string $username
     *            帐号
     * @param string $password
     *            密码
     * @return array
     */
    public function auth($username, $password) {
        $result = $this->where("username = \"{$username}\"")->find();
        if (empty($result)) {
            return array(
                'status' => false,
                'msg' => '管理员不存在'
            );
        } else {
            if (md5($password) == $result['password']) {
                return array(
                    'status' => true,
                    'admin_info' => $result
                );
            } else {
                return array(
                    'status' => false,
                    'msg' => '密码不正确'
                );
            }
        }
    }

    /**
     * 修改密码
     *
     * @param int $id
     *            管理员密码
     * @param string $password
     *            新密码
     * @return array
     */
    public function changePassword($id, $password) {
        // 开启事务
        $this->startTrans();
        if ($this->where("id = {$id}")->save(array(
            'password' => md5($password)
        ))) {
            // 修改成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '修改密码成功'
            );
        } else {
            // 修改失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '修改密码失败'
            );
        }
    }

    /**
     * 删除管理员
     *
     * @param array $id
     *            管理员ID
     * @return array
     */
    public function deleteAdministrator(array $id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            if (M('AdminPriv')->where(array(
                'admin_id' => array(
                    'in',
                    $id
                )
            ))->delete()) {
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
     * 获取管理员总数
     *
     * @return int
     */
    public function getAdminCount() {
        $count = $this->field("COUNT(1) AS total")->select();
        return (int) $count[0]['total'];
    }

    /**
     * 获取管理员列表
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
    public function getAdminList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS au ")->field(array(
            'au.id',
            'au.username',
            'au.password',
            'au.real_name',
            'au.email',
            'au.add_time',
            'au.last_time',
            'au.type',
            'au.status',
            'au.desc',
            'b.name' => 'branch'
        ))->join(array(
            " LEFT JOIN " . M('Branch')->getTableName() . " AS b ON b.admin_id = au.id "
        ))->limit($offset, $pageSize)->order($order . " " . $sort)->select();
    }

    /**
     * 更新管理员状态
     *
     * @param int $id
     *            管理员ID
     * @param int $status
     *            状态（1：启用，0：禁用）
     * @return array
     */
    public function updateAdministrator($id, $status) {
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'status' => $status
        ))) {
            return array(
                'status' => true,
                'msg' => '操作成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '操作失败'
            );
        }
    }

}