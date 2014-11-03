<?php

/**
 * fruit_admin_priv 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class AdminPrivModel extends Model {

    /**
     * 设置权限
     *
     * @param int $admin_id
     *            管理员ID
     * @param string $priv
     *            权限
     * @return 设置权限
     */
    public function setPriv($admin_id, $priv) {
        if ($this->where(array(
            'admin_id' => $admin_id
        ))->count()) {
            if ($this->where(array(
                'admin_id' => $admin_id
            ))->save(array(
                'priv' => $priv
            ))) {
                return array(
                    'status' => true,
                    'msg' => '设置成功'
                );
            } else {
                return array(
                    'status' => false,
                    'msg' => '设置失败'
                );
            }
        } else {
            if ($this->add(array(
                'admin_id' => $$admin_id,
                'priv' => $priv
            ))) {
                return array(
                    'status' => true,
                    'msg' => '设置成功'
                );
            } else {
                return array(
                    'status' => false,
                    'msg' => '设置失败'
                );
            }
        }
    }

}