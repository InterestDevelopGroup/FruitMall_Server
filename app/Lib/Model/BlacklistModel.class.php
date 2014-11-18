<?php

/**
 * fruit_blacklist 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class BlacklistModel extends Model {

    /**
     * 拉入黑名单
     *
     * @param array $user_id
     *            用户ID
     * @return array
     */
    public function addBlacklist(array $user_id) {
        $length = count($user_id);
        $dataList = array();
        for ($i = 0; $i < $length; $i++) {
            $dataList[] = array(
                'user_id' => $user_id[$i],
                'add_time' => time()
            );
        }
        if ($this->addAll($dataList)) {
            return array(
                'status' => true,
                'msg' => '拉入成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '拉入失败'
            );
        }
    }

    /**
     * 移出黑名单
     *
     * @param array $user_id
     *            用户ID
     * @return array
     */
    public function deleteBlacklist(array $user_id) {
        if ($this->where(array(
            'user_id' => array(
                'in',
                $user_id
            )
        ))->delete()) {
            return array(
                'status' => true,
                'msg' => '移出成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '移出失败'
            );
        }
    }

}