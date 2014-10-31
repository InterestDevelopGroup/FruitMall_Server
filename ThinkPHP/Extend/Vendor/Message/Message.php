<?php
/**
 * message class
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class Message {

    /**
     * message box without jump
     *
     * @param $msg message
     *            to be show
     */
    static function onShowMsg($msg) {
        header("content-Type: text/html; charset=utf-8");
        $pages = '<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Transitional//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\'>';
        $pages .= '<html><head><title></title>';
        $pages .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        $pages .= '<style type="text/css">';
        $pages .= 'body{_text-align:center;font-family:微软雅黑;}';
        $pages .= '.message{border:1px solid #E5E5E5; overflow:hidden; background:#fff; margin:100px auto auto auto;border-radius:10px;box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);width: 450px;}';
        $pages .= '.top{width:auto; line-height:30px; padding:5px 10px; background:#eeeeee;text-align:left;color: rgb(102, 102, 102);}';
        $pages .= '.con{width:auto; line-height:20px; padding:10px; text-align:center; min-height:60px;color: sienna}';
        $pages .= '.end{width:auto; line-height:30px; padding:5px 10px; text-align:right; background:#eeeeee}';
        $pages .= '.end a{ color:#036; font-size:12px;text-decoration: none;}';
        $pages .= '</style></head><body>';
        $pages .= '<div class="message">
            <div class="top">Tips</div>
                <div class="con">' . $msg . '</div>
            </div></body></html>';
        exit($pages);
    }

    /**
     * message box with jump
     * back to the referrer page just let the param url empty
     *
     * @param string $msg
     *            message to be show
     * @param string $url
     *            url to jump
     * @param string $tit
     *            message title
     * @param string $info
     *            additional message
     * @param string $time
     *            wait time
     */
    static function showMsg($msg, $url = '', $til = 'Tips', $info = 'Click here to jump', $time = '1') {
        header("content-Type: text/html; charset=utf-8");
        $pages = '<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Transitional//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\'>';
        $pages .= '<html><head><title>' . $til . '</title>';
        $pages .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        $url && $pages .= '<meta http-equiv=\'Refresh\' content=\'' . $time . ';URL=' . $url . '\'>';
        $pages .= '<style type="text/css">';
        $pages .= 'body{_text-align:center;font-family:微软雅黑;}';
        $pages .= '.message{border:1px solid #E5E5E5;overflow:hidden;background:#fff;margin:100px auto auto auto;border-radius:10px;box-shadow:0px 4px 10px -1px rgba(200, 200, 200, 0.7);width:450px;}';
        $pages .= '.top{width:auto;line-height:30px;padding:5px 10px;background:#eeeeee;text-align:left;color:rgb(102, 102, 102);}';
        $pages .= '.con{width:auto;line-height:20px;padding:10px;text-align:center;min-height:60px;color:sienna}';
        $pages .= '.end{width:auto;line-height:30px;padding:5px 10px;text-align:right;background:#eeeeee}';
        $pages .= '.end a{color:#036;font-size:12px;text-decoration:none;}';
        $pages .= '</style></head><body>';
        $pages .= '<div class="message">
            <div class="top">' . $til . '</div>
                <div class="con">' . $msg . '</div>
                <div class="end" ><a href="' . $url . '">' . $info . '</a></div>
            </div></body></html>';
        if (empty($url)) {
            $pages .= "<script type=\"text/javascript\">setTimeout(history.back(-1), " . ($time * 1000) . ");</script>";
        }
        exit($pages);
    }

}