<?php

/**
 * fruit_purchase 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class PurchaseModel extends Model {

    public function addPurchase() {
        $orders = $this->order("add_time DESC")->find();
        $add_time = $orders['add_time'] ? $orders['add_time'] : 0;
        $sql = "";
    }

}