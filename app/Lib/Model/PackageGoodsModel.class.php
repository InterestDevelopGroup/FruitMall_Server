<?php

/**
 * fruit_package_goods 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class PackageGoodsModel extends Model {

    /**
     * 添加套餐商品
     *
     * @param int $package_id
     *            套餐ID
     * @param array $package_goods
     *            套餐商品数组
     * @return boolean
     */
    public function addPackageGoods($package_id, array $package_goods) {
        $this->where(array(
            'package_id' => $package_id
        ))->delete();
        $dataList = array();
        foreach ($package_goods as $v) {
            $dataList[] = array(
                'package_id' => $package_id,
                'goods_id' => $v['goods_id'],
                'amount' => $v['goods_amount']
            );
        }
        if ($this->addAll($dataList)) {
            return true;
        } else {
            return false;
        }
    }

}