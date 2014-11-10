<?php

/**
 * fruit_member表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class MemberModel extends Model {

    /**
     * 删除用户
     *
     * @param array $id
     *            用户ID
     * @return array
     */
    public function deleteMember(array $id) {
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
     * 找回密码
     *
     * @param string $phone
     *            用户手机
     * @param string $new_password
     *            新密码
     * @return array
     */
    public function find_password($phone, $new_password) {
        if (!$this->where(array(
            'phone' => $phone
        ))->count()) {
            return array(
                'status' => 0,
                'result' => '该手机尚未注册'
            );
        }
        if ($this->where(array(
            'phone' => $phone
        ))->save(array(
            'password' => md5($new_password)
        ))) {
            return array(
                'status' => 1,
                'result' => '修改密码成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '未知错误'
            );
        }
    }

    /**
     * 获取会员数量
     *
     * @param string $keyword
     *            查询关键字
     * @return int
     */
    public function getMemberCount($keyword) {
        empty($keyword) || $this->where(array(
            'username' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return (int) $this->count();
    }

    /**
     * 获取会员列表
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
     *            查询关键字
     */
    public function getMemberList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            'username' => array(
                'like',
                "%{$keyword}%"
            )
        ));
        return $this->field(array(
            'id',
            'phone',
            'username',
            'real_name',
            'avatar',
            'sex',
            'register_time',
            'last_time'
        ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 用户登录
     *
     * @param string $phone
     *            用户手机
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
                'result' => '该手机尚未注册'
            );
        }
        if (md5($password) == $result['password']) {
            // 登录成功，更新登录时间
            if ($this->where(array(
                'id' => $result['id']
            ))->save(array(
                'last_time' => time()
            ))) {
                $result['password'] = $password;
                return array(
                    'status' => 1,
                    'result' => $result
                );
            } else {
                return array(
                    'status' => 0,
                    'result' => '未知错误'
                );
            }
        } else {
            return array(
                'status' => 0,
                'result' => '密码错误'
            );
        }
    }

    /**
     * 用户注册
     *
     * @param string $phone
     *            用户手机
     * @param string $password
     *            登录密码
     * @return array
     */
    public function register($phone, $password) {
        if ($this->where(array(
            'phone' => $phone
        ))->count()) {
            return array(
                'status' => 0,
                'result' => '该手机已经注册'
            );
        }
        if ($this->add(array(
            'phone' => $phone,
            'password' => md5($password),
            'register_time' => time()
        ))) {
            return array(
                'status' => 1,
                'result' => '注册成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '未知错误'
            );
        }
    }

}