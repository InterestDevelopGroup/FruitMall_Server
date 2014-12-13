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
     * 修改用户手机
     *
     * @param int $id
     *            用户ID
     * @param string $phone
     *            新手机号码
     * @return array
     */
    public function changePhone($id, $phone) {
        if ($this->where(array(
            'phone' => $phone
        ))->count()) {
            return array(
                'status' => 0,
                'result' => '手机号码已经存在'
            );
        }
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'phone' => $phone
        ))) {
            return array(
                'status' => 1,
                'result' => '修改成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '未知错误'
            );
        }
    }

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
        // 密码修改前后没有变化
        if ($this->where(array(
            'phone' => $phone,
            'password' => md5($new_password)
        ))->count()) {
            return array(
                'status' => 1,
                'result' => '修改密码成功'
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
     * @param int $is_blacklist
     *            是否黑名单
     * @return int
     */
    public function getMemberCount($keyword, $is_blacklist) {
        $where = array(
            'b.id' => array(
                'exp',
                $is_blacklist ? " is not null " : " is null "
            )
        );
        empty($keyword) || $where['m.username'] = array(
            "like",
            "%{$keyword}%"
        );
        return (int) $this->table($this->getTableName() . " AS m ")->join(array(
            " LEFT JOIN " . M('Blacklist')->getTableName() . " AS b ON m.id = b.user_id "
        ))->where($where)->count();
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
    public function getMemberList($page, $pageSize, $order, $sort, $keyword, $is_blacklist) {
        $offset = ($page - 1) * $pageSize;
        $where = array(
            'b.id' => array(
                'exp',
                $is_blacklist ? " is not null " : " is null "
            )
        );
        empty($keyword) || $where['m.username'] = array(
            "like",
            "%{$keyword}%"
        );
        return $this->table($this->getTableName() . " AS m ")->field(array(
            'm.id',
            'm.phone',
            'm.username',
            'm.real_name',
            'm.avatar',
            'm.sex',
            'm.remark',
            'm.register_time',
            'm.last_time',
            "(SELECT COUNT(1) FROM " . M('Order')->getTableName() . " WHERE user_id = m.id AND status = 4)" => 'refuse_amount',
            "(SELECT COUNT(1) FROM " . M('Order')->getTableName() . " WHERE user_id = m.id)" => 'order_amount',
            "(SELECT SUM(total_amount) FROM " . M('Order')->getTableName() . " WHERE user_id = m.id)" => 'total_amount'
        ))->join(array(
            " LEFT JOIN " . M('Blacklist')->getTableName() . " AS b ON m.id = b.user_id "
        ))->where($where)->order($order . " " . $sort)->limit($offset, $pageSize)->select();
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
                $result['register_time'] = date("Y-m-d H:i:s", $result['register_time']);
                $result['last_time'] = $result['last_time'] ? date("Y-m-d H:i:s", $result['last_time']) : $result['last_time'];
                if (M('DefaultAddress')->where(array(
                    'user_id' => $result['id']
                ))->count()) {
                    $result = array_merge($result, M('DefaultAddress')->table(M('DefaultAddress')->getTableName() . " AS da ")->join(array(
                        " LEFT JOIN " . M('Address')->getTableName() . " AS a ON da.address_id = a.address_id "
                    ))->field(array(
                        'a.address_id',
                        'a.consignee',
                        'a.phone' => 'consignee_phone',
                        'a.province',
                        'a.city',
                        'a.district',
                        'a.community',
                        'a.address',
                        'a._consignee',
                        'a._phone'
                    ))->where(array(
                        'da.user_id' => $result['id']
                    ))->find());
                }
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
     * @param string|null $recommend
     *            推荐人手机
     * @return array
     */
    public function register($phone, $password, $recommend) {
        if ($this->where(array(
            'phone' => $phone
        ))->count()) {
            return array(
                'status' => 0,
                'result' => '该手机已经注册'
            );
        }
        $register_time = time();
        // 开启事务
        $this->startTrans();
        if ($this->add(array(
            'phone' => $phone,
            'password' => md5($password),
            'register_time' => $register_time
        ))) {
            $id = $this->getLastInsID();
            if ($recommend) {
                $_recommend = $this->where(array(
                    'phone' => $recommend
                ))->find();
                if (!$_recommend) {
                    // 推荐人不存在，回滚事务
                    $this->rollback();
                    return array(
                        'status' => 0,
                        'result' => '推荐失败'
                    );
                }
                if (!D('Coupon')->addCoupon($_recommend['id'], 2)) {
                    // 添加失败，回滚事务
                    $this->rollback();
                    return array(
                        'status' => 0,
                        'result' => '推荐失败'
                    );
                }
            }
            if (!D('Coupon')->addCoupon($id, 1)) {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '注册失败'
                );
            }
            // 注册成功，提交事务
            $this->commit();
            return array(
                'status' => 1,
                'result' => array(
                    'id' => $id,
                    'phone' => $phone,
                    'username' => null,
                    'real_name' => null,
                    'avatar' => null,
                    'sex' => 0,
                    'remark' => null,
                    'register_time' => date("Y-m-d H:i:s", $register_time),
                    'last_time' => null
                )
            );
        } else {
            // 注册失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'result' => '未知错误'
            );
        }
    }

    /**
     * 用户备注
     *
     * @param int $user_id
     *            用户ID
     * @param string $remark
     *            用户备注
     * @return array
     */
    public function remark($user_id, $remark) {
        $remark = strlen($remark) ? $remark : null;
        if ($this->where(array(
            'id' => $user_id
        ))->save(array(
            'remark' => $remark
        ))) {
            return array(
                'status' => true,
                'msg' => '备注成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '备注失败'
            );
        }
    }

    /**
     * 更新用户
     *
     * @param int $id
     *            用户ID
     * @param string $username
     *            用户名
     * @param string $real_name
     *            真实姓名
     * @param string $avatar
     *            头像
     * @param int $sex
     *            性别（0：保密，1：男，2：女）
     * @return array
     */
    public function updateMember($id, $username, $real_name, $avatar, $sex) {
        $getResult = function () use($id) {
            $result = $this->field(array(
                'id',
                'phone',
                'username',
                'real_name',
                'avatar',
                'sex',
                'register_time',
                'last_time'
            ))->where(array(
                'id' => $id
            ))->find();
            $result['register_time'] = date("Y-m-d H:i:s", $result['register_time']);
            $result['last_time'] = $result['last_time'] ? date("Y-m-d H:i:s", $result['last_time']) : $result['last_time'];
            return $result;
        };
        $data = array();
        $username && $data['username'] = $username;
        $real_name && $data['real_name'] = $real_name;
        $avatar && $data['avatar'] = base64Code2Image($avatar);
        !is_null($sex) && in_array($sex, array(
            0,
            1,
            2
        )) && $data['sex'] = $sex;
        if (empty($data)) {
            return array(
                'status' => 1,
                'result' => $getResult()
            );
        }
        // 修改前后没有变化
        $where = $data['id'] = $id;
        if ($this->where($where)->count()) {
            return array(
                'status' => 1,
                'result' => $getResult()
            );
        }
        if ($this->where(array(
            'id' => $id
        ))->save($data)) {
            return array(
                'status' => 1,
                'result' => $getResult()
            );
        } else {
            return array(
                'status' => 0,
                'result' => '未知错误'
            );
        }
    }

}