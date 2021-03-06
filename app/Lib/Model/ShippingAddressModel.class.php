<?php

/**
 * fruit_shipping_address 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class ShippingAddressModel extends Model {

    /**
     * 获取配送地址列表（API）
     *
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @return array
     */
    public function _getShippingAddressList($offset, $pagesize) {
        return $this->limit($offset, $pagesize)->select();
    }

    /**
     * 按运费分类配送地址
     *
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     */
    public function _getShippingAddressGroup($offset, $pagesize) {
        $shipping_fee_range = $this->field(array(
            'shipping_fee'
        ))->group("shipping_fee")->order("shipping_fee ASC")->limit($offset, $pagesize)->select();
        foreach ($shipping_fee_range as &$v) {
            $v['shipping_address_list'] = $this->field(array(
                'id',
                'province',
                'city',
                'district',
                'road_number',
                'community',
                'building',
                'discount'
            ))->where(array(
                'shipping_fee' => $v['shipping_fee']
            ))->select();
        }
        return $shipping_fee_range;
    }

    /**
     * 添加配送地址
     *
     * @param string $province
     *            省
     * @param string $city
     *            市
     * @param string|null $district
     *            区
     * @param string|null $road_number
     *            路牌号
     * @param string|null $community
     *            小区（社区）、建筑名
     * @param string|null $building
     *            栋、几期、座
     * @param float $shipping_fee
     *            送货费
     * @param string|null $discount
     *            价格调整比例
     * @return array
     */
    public function addShippingAddress($province, $city, $district, $road_number, $community, $building, $shipping_fee, $discount) {
        $data = array(
            'province' => $province,
            'city' => $city,
            'shipping_fee' => $shipping_fee,
            'add_time' => time()
        );
        strlen($district) && $data['district'] = $district;
        strlen($road_number) && $data['road_number'] = $road_number;
        strlen($community) && $data['community'] = $community;
        strlen($building) && $data['building'] = $building;
        strlen($discount) && $data['discount'] = $discount;
        if ($this->add($data)) {
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
     * 删除配送地址
     *
     * @param array $id
     *            地址ID
     * @return array
     */
    public function deleteShippingAddress(array $id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            if (!D('BranchShippingAddress')->deleteShippingAddressByShippingAddressId($id)) {
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
     * 获取地址总数
     *
     * @return int
     */
    public function getShippingAddressCount($keyword) {
        return (int) $this->count();
    }

    /**
     * 获取配送地址列表
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
    public function getShippingAddressList($page, $pageSize, $order, $sort) {
        $offset = ($page - 1) * $pageSize;
        return $this->table($this->getTableName() . " AS sa ")->field(array(
            'sa.*',
            "(SELECT b.name FROM " . M('BranchShippingAddress')->getTableName() . " AS bsa LEFT JOIN " . M('Branch')->getTableName() . " AS b ON b.id = bsa.branch_id WHERE bsa.shipping_address_id = sa.id)" => 'branch'
        ))->order($order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * 更新配送地址
     *
     * @param string $province
     *            省
     * @param int $id
     *            地址ID
     * @param string $city
     *            市
     * @param string|null $district
     *            区
     * @param string|null $road_number
     *            路牌号
     * @param string|null $community
     *            小区（社区）、建筑名
     * @param string|null $building
     *            栋、几期、座
     * @param float $shipping_fee
     *            送货费
     * @param string $discount
     *            价格调整比例
     * @return array
     */
    public function updateShippingAddress($id, $province, $city, $district, $road_number, $community, $building, $shipping_fee, $discount) {
        $data = array(
            'province' => $province,
            'city' => $city,
            'shipping_fee' => $shipping_fee,
            'update_time' => time()
        );
        strlen($district) && $data['district'] = $district;
        strlen($road_number) && $data['road_number'] = $road_number;
        strlen($community) && $data['community'] = $community;
        strlen($building) && $data['building'] = $building;
        strlen($discount) && $data['discount'] = $discount;
        if ($this->where(array(
            'id' => $id
        ))->save($data)) {
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