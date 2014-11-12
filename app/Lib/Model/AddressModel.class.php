<?php

/**
 * fruit_address 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class AddressModel extends Model {

    /**
     * 获取用户地址列表（API）
     *
     * @param int $user_id
     *            用户ID
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @return array
     */
    public function _getAddressList($user_id, $offset, $pagesize) {
        return $this->table($this->getTableName() . " AS a ")->field(array(
            'a.*',
            "(SELECT COUNT(1) FROM " . M('DefaultAddress')->getTableName() . " WHERE address_id = a.address_id)" => 'is_default'
        ))->where(array(
            'user_id' => $user_id
        ))->limit($offset, $pagesize)->select();
    }

    /**
     * 添加地址
     *
     * @param int $user_id
     *            用户ID
     * @param string $consignee
     *            收货人
     * @param string $phone
     *            收货人手机
     * @param string $province
     *            省份
     * @param string $city
     *            城市
     * @param string $district
     *            区
     * @param string $address
     *            详细地址
     * @param string|null $_consignee
     *            备用收货人
     * @param string|null $_phone
     *            备用收货人手机
     * @return array
     */
    public function addAddress($user_id, $consignee, $phone, $province, $city, $district, $address, $_consignee, $_phone) {
        $data = array(
            'user_id' => $user_id,
            'consignee' => $consignee,
            'phone' => $phone,
            'province' => $province,
            'city' => $city,
            'address' => $address,
            'add_time' => time()
        );
        strlen($district) && $data['district'] = $district;
        $_consignee && $data['_consignee'] = $_consignee;
        $_phone && $data['_phone'] = $_phone;
        if ($this->add($data)) {
            return array(
                'status' => 1,
                'result' => '添加成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '添加失败'
            );
        }
    }

    /**
     * 删除地址
     *
     * @param array $address_id
     *            删除的地址ID数组
     * @return array
     */
    public function deleteAddress(array $address_id) {
        if ($this->where(array(
            'address_id' => array(
                'in',
                $address_id
            )
        ))->delete()) {
            return array(
                'status' => 1,
                'result' => '删除成功'
            );
        } else {
            return array(
                'status' => 1,
                'result' => '删除失败'
            );
        }
    }

    /**
     * 更新地址
     *
     * @param int $address_id
     *            地址ID
     * @param string|null $consignee
     *            收货人
     * @param string|null $phone
     *            收货人手机
     * @param string|null $province
     *            省份
     * @param string|null $city
     *            城市
     * @param string|null $district
     *            区
     * @param string|null $address
     *            详细地址
     * @param string|null $_consignee
     *            备用收货人
     * @param string|null $_phone
     *            备用收货人手机
     * @return array
     */
    public function updateAddress($address_id, $consignee, $phone, $province, $city, $district, $address, $_consignee, $_phone) {
        $data = array();
        $consignee && $data['consignee'] = $consignee;
        $phone && $data['phone'] = $phone;
        $province && $data['province'] = $province;
        $city && $data['city'] = $city;
        $district && $data['district'] = $district;
        $address && $data['address'] = $address;
        $_consignee && $data['_consignee'] = $_consignee;
        $_phone && $data['_phone'] = $_phone;
        if (empty($data)) {
            return array(
                'status' => 0,
                'result' => '参数错误'
            );
        }
        $data['update_time'] = time();
        if ($this->where(array(
            'address_id' => $address_id
        ))->save($data)) {
            return array(
                'status' => 1,
                'result' => '更新成功'
            );
        } else {
            return array(
                'status' => 0,
                'result' => '更新失败'
            );
        }
    }

}