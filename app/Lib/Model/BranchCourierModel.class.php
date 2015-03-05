<?php

/**
 * fruit_branch_courier 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class BranchCourierModel extends Model {

    /**
     * 添加分店送货人员
     *
     * @param int $branch_id
     *            分店ID
     * @param array $courier_id
     *            送货人员ID
     * @return boolean
     */
    public function addBranchCourier($branch_id, array $courier_id) {
        $this->where(array(
            'branch_id' => $branch_id
        ))->delete();
        $dataList = array();
        $now = time();
        for ($i = 0; $i < count($courier_id); $i++) {
            $dataList[] = array(
                'branch_id' => $branch_id,
                'courier_id' => $courier_id[$i],
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
     * 删除送货人员
     *
     * @param array $courier_id
     *            送货人员ID
     * @return boolean
     */
    public function deleteBranchCourier(array $branch_id) {
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
     * 根据送货员ID删除分店送货员
     *
     * @param array $courier_id
     *            送货员ID
     * @return boolean
     */
    public function deleteCourierByCourierId(array $courier_id) {
        if (!$this->where(array(
            'courier_id' => array(
                'in',
                $courier_id
            )
        ))->count()) {
            return true;
        }
        if ($this->where(array(
            'courier_id' => array(
                'in',
                $courier_id
            )
        ))->delete()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据分店ID获取分店送货人员
     *
     * @param int $branch_id
     *            分店ID
     */
    public function getBranchCourierList($branch_id) {
        return $this->table($this->getTableName() . " AS bc ")->join(array(
            " LEFT JOIN " . M('Courier')->getTableName() . " AS c ON bc.courier_id = c.id "
        ))->field(array(
            'c.*'
        ))->where(array(
            'bc.branch_id' => $branch_id
        ))->select();
    }

}