<?php

/**
 * 新闻资讯 Action
 */
class NewsAction extends CommonAction {

    /**
     * 资讯详情
     */
    public function detail() {
        // 渲染分类
        $this->render_category();
        $news_id = $_GET['news_id'];
        $details = M('News')->table('tea_news n')->join('tea_news_type nt on nt.id = n.type_id')->field('n.*,nt.name as type_name')->where('n.id = ' . $news_id)->find();
        $details['update_time'] = date('Y-m-d H:i', $details['update_time']);
        // 商品评论内容
        $comments = M('news_comment')->table('tea_news_comment nc')->join('tea_member m on m.id = nc.user_id')->field('m.account,m.avatar,nc.add_time,nc.content')->where('nc.news_id = ' . $news_id)->select();
        foreach ($comments as $key => $val) {
            $comments[$key]['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
        }
        $this->assign('comments', $comments);
        $this->assign('details', $details);
        $this->display();
    }

    /**
     * 资讯首页
     */
    public function index() {
        // 渲染分类
        $this->render_category();
        // 渲染顶部头条
        $this->render_top_news();
        // 推荐头条
        $recommend_news = M('News')->where('is_top = 0')->order('update_time desc')->limit(4)->select();
        // 资讯分类
        $news_type = M('News_type')->select();
        // 各资讯分类下的资讯
        $news_arr = array();
        foreach ($news_type as $key => $val) {
            $type_id = $val['id'];
            $news_list = M('News')->where('type_id = ' . $type_id)->order('update_time desc')->select();
            foreach ($news_list as $k => $v) {
                $news_list[$k]['add_time'] = date('m-d', $v['add_time']);
                $news_list[$k]['type_name'] = $val['name'];
            }
            $news_arr[$type_id] = $news_list;
        }
        $this->assign('news_arr', $news_arr);
        $this->assign('news_type', $news_type);
        $this->assign('recommend_news', $recommend_news);
        $this->display();
    }

    /**
     * 评论
     */
    public function send_comment() {
        $user_id = $_POST['user_id'];
        if (!$_SESSION['member_info']['id']) {
            $result['info'] = '对不起，登陆后才能评论，请先登录！';
            $result['status'] = 2;
            $this->ajaxReturn($result);
        }
        $news_id = $_POST['news_id'];
        $exists = M('News')->where('id = ' . $news_id)->find();
        if (!$exists) {
            $result['info'] = '对不起，您要评论的资讯不存在！';
            $result['status'] = 0;
            $this->ajaxReturn($result);
        }
        if (!$_POST['content']) {
            $result['info'] = '留言内容不能为空';
            $result['status'] = 0;
            $this->ajaxReturn($result);
        }
        $data = array(
            'user_id' => $user_id,
            'news_id' => $news_id,
            'content' => $_POST['content'],
            'add_time' => time()
        );
        if (M('news_comment')->add($data)) {
            $result['info'] = '评论该资讯成功';
            $result['status'] = 1;
        } else {
            $result['info'] = '评论该资讯失败';
            $result['status'] = 0;
        }
        $this->ajaxReturn($result);
    }

}
?>