<?php

/**
 * tea_collection表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class CollectionModel extends Model {

    /**
     * 获取用户收藏列表
     *
     * @param int $user_id
     *            用户ID
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     * @return array
     */
    public function getCollectionList($user_id, $page, $pageSize) {
        $sql = "SELECT
                    c.*, m.avatar
                FROM
                    " . $this->getTableName() . " AS c
                INNER JOIN
                    tea_member AS m
                ON
                    m.id = c.user_id
                WHERE
                    c.user_id = {$user_id}
                ORDER BY
                    c.add_time DESC
                LIMIT
                    " . ($page - 1) * $pageSize . ", {$pageSize}";
        return $this->query($sql);
    }

}