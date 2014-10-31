<?php

/**
 * tea_goods_comment表模型
*
* @author lzjjie
* @version 1.0.0
* @since 1.0.0
*/
class GoodsCommentModel extends Model {

    /**
     * 获取商品评论列表
     *
     * @param int $goods_id
     *            商品ID
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     */
    public function getGoodsCommentList($goods_id, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    gc.user_id, gc.goods_id, gc.content,
                    gc.add_time AS comment_time, m.account, m.phone, m.wechat,
                    m.avatar, m.sex, m.email, m.register_time, m.last_time
                FROM
                    " . $this->getTableName() . " AS gc
                INNER JOIN
                    " . M('Member')->getTableName() . " AS m
                ON
                    gc.user_id = m.id
                WHERE
                    gc.goods_id = {$goods_id}
                ORDER BY
                    gc.add_time DESC
                LIMIT
                    {$pageSize}
                OFFSET
                    {$offset}";
        return $this->query($sql);
    }

}