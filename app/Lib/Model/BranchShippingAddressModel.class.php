<?php

/**
 * fruit_branch_shipping_address 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class BranchShippingAddressModel extends Model {

    /**
     * 添加分店送货地址
     *
     * @param int $branch_id
     *            分店ID
     * @param array $shipping_addresses_id
     *            送货地址ID
     * @return boolean
     */
    public function addBranchShippingAddress($branch_id, array $shipping_address_id) {
        $this->where(array(
            'branch_id' => $branch_id
        ))->delete();
        $dataList = array();
        $now = time();
        for ($i = 0; $i < count($shipping_address_id); $i++) {
            $dataList[] = array(
                'branch_id' => $branch_id,
                'shipping_address_id' => $shipping_address_id[$i],
                'add_time' => $now
            );
        }
        if ($this->addAll($dataList)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除分店送货地址
     *
     * @param array $branch_id
     *            送货地址ID
     * @return boolean
     */
    public function deleteBranchShippingAddress(array $branch_id) {
        if (!$this->where(array(
            'branch_id' => array(
                'in',
                $branch_id
            )
        ))->count()) {
            return true;
        }
        if ($this->where(array(
            'branch_id' => array(
                'in',
                $branch_id
            )
        ))->delete()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据送货地址ID删除分店送货地址
     *
     * @param array $shipping_address_id
     *            送货地址ID
     * @return boolean
     */
    public function deleteShippingAddressByShippingAddressId(array $shipping_address_id) {
        if (!$this->where(array(
            'shipping_address_id' => array(
                'in',
                $shipping_address_id
            )
        ))->count()) {
            return true;
        }
        if ($this->where(array(
            'shipping_address_id' => array(
                'in',
                $shipping_address_id
            )
        ))->delete()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据分店ID获取送货地址列表
     *
     * @param int $branch_id
     *            分店ID
     */
    public function getBranchShippingAddressList($branch_id) {
        return $this->table($this->getTableName() . " AS bsa ")->join(array(
            " LEFT JOIN " . M('ShippingAddress')->getTableName() . " AS sa ON bsa.shipping_address_id = sa.id "
        ))->field(array(
            'sa.*'
        ))->where(array(
            'bsa.branch_id' => $branch_id
        ))->select();
    }

}