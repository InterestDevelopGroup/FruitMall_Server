<?php

/**
 * tea_publish表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class PublishModel extends Model {

    /**
     * 审核发布
     *
     * @param int $id
     *            发布ID
     * @return array
     */
    public function allowPublish($id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => $id
        ))->save(array(
            'status' => 1
        ))) {
            // 更新成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '审核成功'
            );
        } else {
            // 更新失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '审核失败，请稍后再试'
            );
        }
    }

    /**
     * 删除发布信息
     *
     * @param array $id
     *            发布ID
     * @return array
     */
    public function deletePublish(array $id) {
        // 开启事务
        $this->startTrans();
        if ($this->where(array(
            'id' => array(
                'in',
                $id
            )
        ))->delete()) {
            // 删除成功，提交事务
            $this->commit();
            return array(
                'status' => true,
                'msg' => '删除发布信息成功'
            );
        } else {
            // 删除失败，回滚事务
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '删除发布信息失败'
            );
        }
    }

    /**
     * 按照收藏ID获取发布收藏
     *
     * @param int $id
     *            收藏ID
     * @return array
     */
    public function getPublishCollectionById($id) {
        return $this->field(array(
            'id',
            'user_id',
            'brand_name',
            'name',
            'amount',
            'unit',
            'price',
            'business_number',
            'batch',
            'is_buy',
            'is_distribute',
            'status',
            'image_1',
            'image_2',
            'image_3',
            'details',
            'supply',
            'publish_time'
        ))->where(array(
            'id' => $id
        ))->select();
    }

    /**
     * 获取买/卖茶发布总数
     *
     * @param string $keyword
     *            关键字
     * @param int $is_buy
     *            是否卖茶（0：卖茶，1：买茶）
     * @return int
     */
    public function getPublishCount($keyword, $is_buy) {
        $condition = array(
            'p.is_buy' => $is_buy
        );
        empty($keyword) || $condition['_complex'] = array(
            'm.account' => array(
                'like',
                "%{$keyword}%"
            ),
            'p.name' => array(
                'like',
                "%{$keyword}%"
            ),
            '_logic' => 'OR'
        );
        return (int) $this->table($this->getTableName() . " AS p ")->join(array(
            "LEFT JOIN " . M('Member')->getTableName() . " AS m ON p.user_id = m.id"
        ))->where($condition)->count();
    }

    /**
     * 获取买/卖茶发布列表
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param string $order
     *            排序字段
     * @param string $sort
     *            排序方式
     * @param string $keyword
     *            关键字
     * @param int $is_buy
     *            是否卖茶（0：卖茶，1：买茶）
     * @return array
     */
    public function getPublishList($page, $pageSize, $order, $sort, $keyword, $is_buy) {
        $offset = ($page - 1) * $pageSize;
        $condition = array(
            'p.is_buy' => $is_buy
        );
        empty($keyword) || $condition['_complex'] = array(
            'm.account' => array(
                'like',
                "%{$keyword}%"
            ),
            'p.name' => array(
                'like',
                "%{$keyword}%"
            ),
            '_logic' => 'OR'
        );
        return $this->table($this->getTableName() . " AS p ")->join(array(
            " LEFT JOIN " . M('Member')->getTableName() . " AS m ON p.user_id = m.id "
        ))->field(array(
            'p.id',
            'p.user_id',
            'p.brand_name',
            'p.name',
            'p.amount',
            'p.unit',
            'p.price',
            'p.business_number',
            'p.batch',
            'p.is_buy',
            'p.is_distribute',
            'p.status',
            'p.image_1',
            'p.image_2',
            'p.image_3',
            'p.details',
            'p.supply',
            'p.publish_time',
            'm.account'
        ))->where($condition)->order("p." . $order . " " . $sort)->limit($offset, $pageSize)->select();
    }

    /**
     * API获取用户发布列表及用户详细资料
     *
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @param int|null $user_id
     *            用户ID
     * @param string|null $keyword
     *            搜索关键词
     */
    public function publishApiGetPublishList($page, $pageSize, $user_id = null, $keyword) {
        $offset = ($page - 1) * $pageSize;
        $condition = " p.status = 1 ";
        $user_id && $condition .= " AND p.user_id = {$user_id} ";
        $keyword && $condition .= " AND p.name LIKE \"%{$keyword}%\"";
        $sql = "SELECT
                    p.id, p.user_id, p.brand_name, p.name, p.amount, p.unit,
                    p.price, p.business_number, p.batch, p.is_buy,
                    p.is_distribute, p.status, p.image_1, p.image_2, p.image_3,
                    p.details, p.supply, p.publish_time, m.account, m.phone,
                    m.avatar, m.sex, m.level, m.register_time, m.last_time
                FROM
                    " . $this->getTableName() . " AS p
                LEFT JOIN
                    tea_member AS m
                ON
                    p.user_id = m.id
                WHERE
                    {$condition}
                ORDER BY
                    p.id DESC
                LIMIT
                    {$offset}, {$pageSize}";
        return $this->query($sql);
    }

}