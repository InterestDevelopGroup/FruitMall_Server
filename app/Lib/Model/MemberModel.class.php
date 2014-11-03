<?php

/**
 * tea_member表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class MemberModel extends Model {

    /**
     * 删除会员
     *
     * @param array $id
     *            会员ID
     * @return multitype:boolean string
     */
    public function deleteMember(array $id) {
        $images = $this->field('avatar')->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->select();
        foreach ($images as $v) {
            if (empty($v['avatar'])) {
                continue;
            } else {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $v['avatar'])) {
                    if (unlink($_SERVER['DOCUMENT_ROOT'] . $v['avatar'])) {
                        continue;
                    } else {
                        return array(
                            'status' => false,
                            'msg' => '删除用户头像失败'
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
                'msg' => '删除会员成功'
            );
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '删除会员失败'
            );
        }
    }

    /**
     * 获取会员总数
     *
     * @param string $keyword
     *            查询关键字
     * @return number
     */
    public function getMemberCount($keyword) {
        return (int) (empty($keyword) ? $this->count() : $this->where(array(
            'account' => array(
                'like',
                "%{$keyword}%"
            ),
            'email' => array(
                'like',
                "%{$keyword}%"
            ),
            '_logic' => 'OR'
        ))->count());
    }

    /**
     * 获取会员列表
     *
     * @param string $keyword
     *            查询关键字
     */
    public function getMemberList($page, $pageSize, $order, $sort, $keyword) {
        $offset = ($page - 1) * $pageSize;
        empty($keyword) || $this->where(array(
            'account' => array(
                'like',
                "%{$keyword}%"
            ),
            'email' => array(
                'like',
                "%{$keyword}%"
            ),
            '_logic' => 'OR'
        ));
        $result = $this->limit($offset, $pageSize)->order($order . " " . $sort)->select();
        foreach ($result as &$value) {
            $value['register_time'] = date("Y-m-d H:i:s");
            $value['last_time'] = $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : $value['last_time'];
            $value['avatar'] = $value['avatar'] ? "http://{$_SERVER['HTTP_HOST']}{$value['avatar']}" : $value['avatar'];
        }
        return $result;
    }

    /**
     * 更新用户等级
     *
     * @param int $id
     *            用户ID
     * @param int $level
     *            用户等级
     * @return array
     */
    public function updateUserLevel($id, $level) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'level' => $level
        ))) {
            // 更新成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '更新用户等级成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '更新用户等级失败'
            );
        }
    }

}