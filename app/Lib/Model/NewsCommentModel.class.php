<?php

/**
 * tea_news_comment表模型
*
* @author lzjjie
* @version 1.0.0
* @since 1.0.0
*/
class NewsCommentModel extends Model {

    /**
     * 获取资讯评论列表
     *
     * @param int $news_id
     *            资讯ID
     * @param int $page
     *            当前页
     * @param int $pageSize
     *            每页显示条数
     */
    public function getNewsCommentList($news_id, $page, $pageSize) {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT
                    nc.user_id, nc.news_id, nc.content,
                    nc.add_time AS comment_time, m.account, m.phone, m.wechat,
                    m.avatar, m.sex, m.email, m.register_time, m.last_time
                FROM
                    " . $this->getTableName() . " AS nc
                INNER JOIN
                    " . M('Member')->getTableName() . " AS m
                ON
                    nc.user_id = m.id
                WHERE
                    nc.news_id = {$news_id}
                ORDER BY
                    nc.add_time DESC
                LIMIT
                    {$pageSize}
                OFFSET
                    {$offset}";
        return $this->query($sql);
    }

}