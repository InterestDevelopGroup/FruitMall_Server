<?php

/**
 * fruit_advertisement 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class AdvertisementModel extends Model {

    /**
     * 获取广告列表
     *
     * @param int $offset
     *            偏移量
     * @param int $pagesize
     *            条数
     */
    public function _getAdvertisement($offset, $pagesize) {
        $result = $this->table($this->getTableName() . " AS a ")->field(array(
            'advertisement_id',
            'goods_id',
            'package_id',
            'a.add_time' => 'advertisement_add_time'
        ))->join(array(
            " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON a.goods_id = g.id ",
            " LEFT JOIN " . M('Package')->getTableName() . " AS p ON a.package_id = p.id "
        ))->where(array(
            "_string" => "(g.is_delete = 0 AND g.status = 1) OR (p.is_delete = 0)"
        ))->limit($offset, $pagesize)->select();
        foreach ($result as $k => &$v) {
            if ($v['goods_id']) {
                if (M('Goods')->where(array(
                    'id' => $v['goods_id'],
                    'is_delete' => 0
                ))->count()) {
                    $v = array_merge($v, M('Goods')->table(M('Goods')->getTableName() . " AS g ")->join(array(
                        " LEFT JOIN " . M('ParentCategory')->getTableName() . " AS pc ON pc.id = g.p_cate_id ",
                        " LEFT JOIN " . M('ChildCategory')->getTableName() . " AS cc ON cc.id = g.c_cate_id ",
                        " LEFT JOIN " . M('Tag')->getTableName() . " AS t ON t.id = g.tag "
                    ))->field(array(
                        'g.p_cate_id',
                        'g.c_cate_id',
                        'g.name',
                        'g.price',
                        'g._price',
                        'g.unit',
                        'g.tag',
                        'g.amount',
                        'g.weight',
                        'g.thumb',
                        'g.image_1',
                        'g.image_2',
                        'g.image_3',
                        'g.image_4',
                        'g.image_5',
                        'g.description',
                        'g.add_time' => 'goods_add_time',
                        'g.update_time' => 'goods_update_time',
                        'pc.name' => 'parent_category',
                        'cc.name' => 'child_category',
                        't.name' => 'tag_name'
                    ))->where(array(
                        'g.id' => $v['goods_id'],
                        'g.is_delete' => 0
                    ))->find());
                } else {
                    array_splice($result, $k, 1);
                }
            }
            if ($v['package_id']) {
                if (M('Package')->where(array(
                    'id' => $v['package_id'],
                    'is_delete' => 0
                ))->count()) {
                    $v = array_merge($v, M('Package')->field(array(
                        'name',
                        'price',
                        '_price',
                        'thumb',
                        'image_1',
                        'image_2',
                        'image_3',
                        'image_4',
                        'image_5',
                        'description',
                        'add_time' => 'package_add_time',
                        'update_time' => 'package_update_time'
                    ))->where(array(
                        'id' => $v['package_id'],
                        'is_delete' => 0
                    ))->find());
                    $v['goods_list'] = M('PackageGoods')->table(M('PackageGoods')->getTableName() . " AS pg ")->join(array(
                        " LEFT JOIN " . M('Goods')->getTableName() . " AS g ON g.id = pg.goods_id "
                    ))->field(array(
                        'pg.goods_id',
                        'pg.amount',
                        'g.name',
                        'g.thumb'
                    ))->where(array(
                        'pg.package_id' => $v['package_id'],
                        'g.is_delete' => 0
                    ))->select();
                } else {
                    array_splice($result, $k, 1);
                }
            }
        }
        return $result;
    }

    /**
     * 添加广告
     *
     * @param array $data
     *            设为广告的商品或套餐
     * @return array
     */
    public function addAdvertisement(array $data) {
        $data['add_time'] = time();
        if ($this->add($data)) {
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

    /**
     * 取消广告
     *
     * @param array $data
     *            取消广告的商品或套餐
     * @return array
     */
    public function deleteAdvertisement(array $data) {
        if ($this->where($data)->delete()) {
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