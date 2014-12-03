<?php

/**
 * fruit_branch 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class BranchModel extends Model {

    /**
     * 添加分店
     *
     * @param string $name
     *            分店名称
     * @param int $admin_id
     *            管理员ID
     * @param string $remark
     *            备注
     * @param array $shipping_addresses_id
     *            送货地址
     * @param array $courier_id
     *            送货员
     * @return array
     */
    public function addBranch($name, $admin_id, $remark, array $shipping_address_id, array $courier_id) {
        $data = array(
            'name' => $name,
            'admin_id' => $admin_id,
            'add_time' => time()
        );
        strlen($remark) && $data['remark'] = $remark;
        // 开启事务
        $this->startTrans();
        if ($this->add($data)) {
            $branch_id = $this->getLastInsID();
            if (!D('BranchShippingAddress')->addBranchShippingAddress($branch_id, $shipping_address_id)) {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '添加失败'
                );
            }
            if (!D('BranchCourier')->addBranchCourier($branch_id, $courier_id)) {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '添加失败'
                );
            }
            // 添加成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '添加成功'
            );
        } else {
            // 添加失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '添加失败'
            );
        }
    }

    /**
     * 删除分店
     *
     * @param array $id
     *            分店ID
     * @return array
     */
    public function deleteBranch(array $id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            if (!D('BranchShippingAddress')->deleteBranchShippingAddress($id)) {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除失败1'
                );
            }
            if (!D('BranchCourier')->deleteBranchCourier($id)) {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '删除失败2'
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
                'msg' => '删除失败3'
            );
        }
    }

    /**
     * 获取分店总数
     *
     * @param string $keyword
     *            关键字
     * @return int
     */
    public function getBranchCount($keyword, $adminInfo) {
        $where = array();
        $adminInfo['type'] || $where['admin_id'] = $adminInfo['id'];
        empty($keyword) || $where['name'] = array("like", "%{$keyword}%");
        empty($where) || $this->where($where);
        return (int) $this->count();
    }

    /**
     * 获取分店列表
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
     * @return array
     */
    public function getBranchList($page, $pageSize, $order, $sort, $keyword, $adminInfo) {
        $offset = ($page - 1) * $pageSize;
        $where = array();
        $adminInfo['type'] || $where['admin_id'] = $adminInfo['id'];
        empty($keyword) || $where['name'] = array("like", "%{$keyword}%");
        empty($where) || $this->where($where);
        return $this->table($this->getTableName() . " AS b ")->join(array(
            " LEFT JOIN " . M('AdminUser')->getTableName() . " AS au ON au.id = b.admin_id "
        ))->field(array(
            'b.*',
            'au.real_name' => 'admin'
        ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新分店
     *
     * @param int $id
     *            分店ID
     * @param string $name
     *            分店名称
     * @param int $admin_id
     *            管理员ID
     * @param string $remark
     *            备注
     * @param array $shipping_address_id
     *            送货地址ID
     * @param array $courier_id
     *            送货人员ID
     * @return array
     */
    public function updateBranch($id, $name, $admin_id, $remark, array $shipping_address_id, array $courier_id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'name' => $name,
            'admin_id' => $admin_id,
            'remark' => strlen($remark) ? $remark : null,
            'update_time' => time()
        ))) {
            if (!D('BranchShippingAddress')->addBranchShippingAddress($id, $shipping_address_id)) {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '更新失败'
                );
            }
            if (!D('BranchCourier')->addBranchCourier($id, $courier_id)) {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '更新失败'
                );
            }
            // 添加成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '更新成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '更新失败'
            );
        }
    }

}