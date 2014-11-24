<?php

/**
 * fruit_custom 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CustomModel extends Model {

    /**
     * 获取定制列表（API）
     *
     * @param int $user_id
     *            用户ID
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     * @return array
     */
    public function _getCustomList($user_id, $offset, $pagesize) {
        return array_map(function ($value) {
            $value['stuff_list'] = M('CustomStuff')->table(M('CustomStuff')->getTableName() . " AS cs ")->join(array(
                " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON g.id = cs.goods_id ",
                " LEFT JOIN " . M('Package')->getTableName() . " AS p ON p.id = cs.package_id "
            ))->field(array(
                'cs.goods_id',
                'cs.package_id',
                'cs.quantity',
                'g.name' => 'goods_name',
                'g.price' => 'good_price',
                'g.thumb' => 'good_thumb',
                'p.name' => 'package_name',
                'p.price' => 'package_price',
                'p.thumb' => 'package_thumb'
            ))->where(array(
                'cs.custom_id' => $value['custom_id']
            ))->select();
            return $value;
        }, $this->where(array(
            'user_id' => $user_id
        ))->limit($offset, $pagesize)->select());
    }

    /**
     * 添加定制
     *
     * @param int $user_id
     *            用户ID
     * @param string $name
     *            定制名称
     * @return array
     */
    public function addCustom($user_id, $name) {
        if ($this->add(array(
            'user_id' => $user_id,
            'name' => $name,
            'create_time' => time()
        ))) {
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
     * 删除定制
     *
     * @param array $custom_id
     *            定制ID
     * @return array
     */
    public function deleteCustom(array $custom_id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'custom_id' => array(
                'in',
                $custom_id
            )
        ))->delete()) {
            if (D('CustomStuff')->deleteCustomStuffByCustomId((array) $custom_id)) {
                // 删除成功，提交事务
                $this->commit();
                return array(
                    'status' => 1,
                    'result' => '删除成功'
                );
            } else {
                // 删除失败，回滚事务
                $this->rollback();
                return array(
                    'status' => 0,
                    'result' => '删除失败'
                );
            }
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => 0,
                'result' => '删除失败'
            );
        }
    }

}