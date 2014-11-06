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