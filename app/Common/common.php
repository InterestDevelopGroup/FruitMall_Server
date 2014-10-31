<?php
// 截取字符串
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr")) {
        if ($suffix && strlen($str) > $length)
            return mb_substr($str, $start, $length, $charset) . "...";
        else
            return mb_substr($str, $start, $length, $charset);
    } elseif (function_exists('iconv_substr')) {
        if ($suffix && strlen($str) > $length)
            return iconv_substr($str, $start, $length, $charset) . "...";
        else
            return iconv_substr($str, $start, $length, $charset);
    }
    $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("", array_slice($match[0], $start, $length));
    if ($suffix)
        return $slice . "…";
    return $slice;
}
// 更新用户session信息
function update_user_session() {
    $member_id = $_SESSION['member_info']['id'];
    if (!$member_id) {
        return;
    }
    $m = M('member');
    $where['id'] = $member_id;
    $member = $m->where($where)->find();
    unset($_SESSION['member_info']);
    $_SESSION['member_info'] = $member;
}

// 上传
function upload($params = array('size'=>'','type'=>'','path'=>'','name'=>'','fix'=>'','width'=>'','height'=>'','remove'=>false,)) {
    import('ORG.Net.UploadFile');
    $upload = new UploadFile(); // 实例化上传类
    $upload->maxSize = empty($params['size']) ? C('UPLOAD_SIZE') : $params['size']; // 设置附件上传大小
    $type = empty($params['type']) ? C('UPLOAD_TYPE') : $params['type'];
    $types = explode('|', $type);
    $upload->allowExts = $types; // 设置附件上传类型
    $upload->savePath = empty($params['path']) ? C('DATA_PATH') : $params['path']; // 设置附件上传目录
    $upload->saveRule = empty($params['name']) ? time() : $params['name'];

    if (!empty($params['width'])) {
        $upload->thumb = true; // 是否生成缩略图
        $upload->thumbMaxWidth = $params['width']; // 缩略图的最大宽度，多个使用逗号分隔
    }
    if (!empty($params['height'])) {
        $upload->thumb = true; // 是否生成缩略图
        $upload->thumbMaxHeight = $params['height'];
    }
    $upload->thumbPrefix = empty($params['fix']) ? C('PIC_PREFIX') : $params['fix']; // 缩略图前缀
    $upload->thumbRemoveOrigin = $params['remove'];
    // $upload->saveRule=time();//保存规则
    if (!$upload->upload()) { // 上传错误提示错误信息
        $info['error'] = $upload->getErrorMsg();
    } else { // 上传成功 获取上传文件信息
        $info = $upload->getUploadFileInfo();
        $info = current($info);
    }
    return $info;
}

/*
 * 上传图片 $file-上传文件,$path上传路径，$_sys系统配置,$thumb-是否缩略图,$logo-水印 return $arr-原始图和缩略图
 */
function up_img($file, $path, $_sys, $thumb = 0, $logo = 1, $image_width = '', $image_height = '') {
    if (is_uploaded_file($file['tmp_name'])) {
        if ($file['size'] > $_sys['upload_size'] * 1000000) {
            $infos['error'] = '图片超过' . $_sys['upload_size'] . 'M大小';
            return $infos;
        }
        $pic_name = pathinfo($file['name']); // 图片信息
                                             // $file_type=$file['type'];
                                             // echo $file_type;
        $type = explode('|', $_sys['upload_type']);
        if (!in_array(strtolower($pic_name['extension']), $type)) {
            $infos['error'] = '上传图片格式不正确';
            return $infos;
        }
        if (!file_exists($path)) {
            mkdir($path);
        }
        $up_file_name = date('YmdHis') . rand(1, 10000);
        $file_name = $path . $up_file_name . '.' . $pic_name['extension'];
        $return_name['up_pic_size'] = $file['size']; // 上传图片大小
        $return_name['up_pic_ext'] = $pic_name['extension']; // 上传文件扩展名
        $return_name['up_pic_name'] = $up_file_name; // 上传图片名
        $return_name['up_pic_path'] = $path; // 上传图片路径
        $return_name['up_pic_time'] = time(); // 上传时间
        unset($pic_name);
        // 开始上传
        if (!move_uploaded_file($file['tmp_name'], $file_name)) {
            $infos['error'] = '图片上传失败';
            return $infos;
        }
        $file_info = getimagesize($file_name);

        switch ($file_info[2]) {
            case 1 :
                $php_file = imagecreatefromgif($file_name);
                break;
            case 2 :
                $php_file = imagecreatefromjpeg($file_name);
                break;
            case 3 :
                $php_file = imagecreatefrompng($file_name);
                break;
        }
        // return $php_file;
        // 生成水印
        if ($_sys['watermark'] && $logo) {
            // 文字
            if (!$_sys['mark_type']) {
                $mark_img = $php_file;
                $t_color = empty($_sys['font_color']) ? array(
                    "255",
                    "255",
                    "255"
                ) : explode(',', $_sys['font_color']);
                $text_color = imagecolorallocate($php_file, $t_color[0], $t_color[1], $t_color[2]);
                $text_content = iconv("UTF-8", "UTF-8", empty($_sys['font']) ? 'ZSNET' : $_sys['font']);
                $text_size = empty($_sys['font_size']) ? "12" : $_sys['font_size'];
                $font = "data/font/arial.ttf";
                $text_arr = @imagettfbbox($text_size, 0, $font, $text_content);
                $text_width = max($text_arr[2], $text_arr[4]) - min($text_arr[0], $text_arr[6]);
                $text_height = max($text_arr[1], $text_arr[3]) - min($text_arr[5], $text_arr[7]);
                switch ($_sys['mark_place']) {
                    case '1' :
                        $position = array(
                            "5",
                            "5"
                        );
                        break;
                    case '2' :
                        $position = array(
                            ($file_info[0] - $text_width) / 2,
                            "5"
                        );
                        break;
                    case '3' :
                        $position = array(
                            $file_info[0] - $text_width - 5,
                            "5"
                        );
                        break;
                    case '4' :
                        $position = array(
                            "5",
                            ($file_info[1] - $text_height) / 2
                        );
                        break;
                    case 5 :
                        $position = array(
                            ($file_info[0] - $text_width) / 2,
                            ($file_info[1] - $text_height) / 2
                        );
                        break;
                    case '6' :
                        $position = array(
                            $file_info[0] - $text_width - 5,
                            ($file_info[1] - $text_height) / 2
                        );
                        break;
                    case '7' :
                        $position = array(
                            "3",
                            $file_info[1] - $text_height - 5
                        );
                        break;
                    case '8' :
                        $position = array(
                            ($file_info[0] - $text_width) / 2,
                            $file_info[1] - $text_height - 5
                        );
                        break;
                    case '9' :
                        $position = array(
                            $file_info[0] - $text_width - 10,
                            $file_info[1] - $text_height - 10
                        );
                        break;
                }
                imagettftext($mark_img, $text_size, 0, ($position[0] + $text_size), ($position[1] + $text_size), $text_color, $font, $text_content);
                switch ($file_info[2]) {
                    case 1 :
                        imagegif($mark_img, $file_name);
                        break;
                    case 2 :
                        imagejpeg($mark_img, $file_name);
                        break;
                    case 3 :
                        imagepng($mark_img, $file_name);
                        break;
                }
            }
            // 图片

            if ($_sys['mark_type']) {
                // $logo=GB_PATH.'upload/'.$_sys['mark_pic'];
                $logo = $_sys['mark_pic'];
                $logo_info = getimagesize($logo);
                switch ($logo_info[2]) {
                    case 1 :
                        $logo_file = imagecreatefromgif($logo);
                        break;
                    case 2 :
                        $logo_file = imagecreatefromjpeg($logo);
                        break;
                    case 3 :
                        $logo_file = imagecreatefrompng($logo);
                        break;
                }
                switch ($_sys['mark_place']) {
                    case '1' :
                        $position = array(
                            "5",
                            "5"
                        );
                        break;
                    case '2' :
                        $position = array(
                            ($file_info[0] - $logo_info[0]) / 2,
                            "5"
                        );
                        break;
                    case '3' :
                        $position = array(
                            $file_info[0] - $logo_info[0] - 5,
                            "5"
                        );
                        break;
                    case '4' :
                        $position = array(
                            "5",
                            ($file_info[1] - $logo_info[1]) / 2
                        );
                        break;
                    case '5' :
                        $position = array(
                            ($file_info[0] - $logo_info[0]) / 2,
                            ($file_info[1] - $logo_info[1]) / 2
                        );
                        break;
                    case 6 :
                        $position = array(
                            $file_info[0] - $logo_info[0] - 5,
                            ($file_info[1] - $logo_info[1]) / 2
                        );
                        break;
                    case 7 :
                        $position = array(
                            "3",
                            $file_info[1] - $logo_info[1] - 5
                        );
                        break;
                    case 8 :
                        $position = array(
                            ($file_info[0] - $logo_info[0]) / 2,
                            $file_info[1] - $logo_info[1] - 5
                        );
                        break;
                    case 9 :
                        $position = array(
                            $file_info[0] - $logo_info[0] - 10,
                            $file_info[1] - $logo_info[1] - 10
                        );
                        break;
                }
                $logo_img = $php_file;
                imagecopy($logo_img, $logo_file, $position[0], $position[1], 0, 0, $logo_info[0], $logo_info[1]);
                switch ($file_info[2]) {
                    case 1 :
                        imagegif($logo_img, $file_name);
                        break;
                    case 2 :
                        imagejpeg($logo_img, $file_name);
                        break;
                    case 3 :
                        imagepng($logo_img, $file_name);
                        break;
                }
            }
        }
        // 生成缩略图

        if ($thumb) {
            $src_img = $php_file;

            $thumb = resize($file_name, '_thumb', $_sys['thum_width'], $_sys['thum_height']);

            // $return_name['thumb']=str_replace(GB_PATH,"",$thumb);
            $return_name['thumb'] = $thumb;
        }
        if ($image_width || $image_height) {
            $file_name = resize($file_name, '', $image_width, $image_height);
        }
        // $return_name['pic']=str_replace(GB_PATH,"",$file_name);
        $return_name['pic'] = $file_name;
    }
    return $return_name;
}

function getImageInfo($src) {
    return getimagesize($src);
}

/**
 * 创建图片，返回资源类型
 *
 * @param string $src
 *            图片路径
 * @return resource $im 返回资源类型
 *         *
 */
function create($src) {
    $info = getImageInfo($src);
    switch ($info[2]) {
        case 1 :
            $im = imagecreatefromgif($src);
            break;
        case 2 :
            $im = imagecreatefromjpeg($src);
            break;
        case 3 :
            $im = imagecreatefrompng($src);
            break;
    }
    return $im;
}

/**
 * 缩略图主函数
 *
 * @param string $src
 *            图片路径
 * @param int $w
 *            缩略图宽度
 * @param int $h
 *            缩略图高度
 * @return mixed 返回缩略图路径
 *         *
 */
function resize($src, $rename, $w, $h) {
    $temp = pathinfo($src);
    // $name=$temp["basename"];//文件名
    $name = $temp["filename"] . $rename . '.' . $temp["extension"];
    $dir = $temp["dirname"]; // 文件所在的文件夹
    $extension = $temp["extension"]; // 文件扩展名
    $savepath = "{$dir}/{$name}"; // 缩略图保存路径,新的文件名为*.thumb.jpg

    // 获取图片的基本信息
    $info = getImageInfo($src);
    $width = $info[0]; // 获取图片宽度
    $height = $info[1]; // 获取图片高度
    $per1 = round($width / $height, 2); // 计算原图长宽比
    $per2 = round($w / $h, 2); // 计算缩略图长宽比

    // 计算缩放比例
    if ($per1 > $per2 || $per1 == $per2) {
        // 原图长宽比大于或者等于缩略图长宽比，则按照宽度优先
        $per = $w / $width;
    }
    if ($per1 < $per2) {
        // 原图长宽比小于缩略图长宽比，则按照高度优先
        $per = $h / $height;
    }
    $temp_w = intval($width * $per); // 计算原图缩放后的宽度
    $temp_h = intval($height * $per); // 计算原图缩放后的高度
    $temp_img = imagecreatetruecolor($temp_w, $temp_h); // 创建画布
    $color = imagecolorAllocate($temp_img, 245, 245, 245);
    imagefill($temp_img, 0, 0, $color);
    $im = create($src);
    imagecopyresampled($temp_img, $im, 0, 0, 0, 0, $temp_w, $temp_h, $width, $height);
    if ($per1 > $per2) {
        imagejpeg($temp_img, $savepath, 100);
        imagedestroy($im);
        return addBg($savepath, $w, $h, "w");
        // 宽度优先，在缩放之后高度不足的情况下补上背景
    }
    if ($per1 == $per2) {
        imagejpeg($temp_img, $savepath, 100);
        imagedestroy($im);
        return $savepath;
        // 等比缩放
    }
    if ($per1 < $per2) {
        imagejpeg($temp_img, $savepath, 100);
        imagedestroy($im);
        return addBg($savepath, $w, $h, "h");
        // 高度优先，在缩放之后宽度不足的情况下补上背景
    }
}

/**
 * 添加背景
 *
 * @param string $src
 *            图片路径
 * @param int $w
 *            背景图像宽度
 * @param int $h
 *            背景图像高度
 * @param String $first
 *            决定图像最终位置的，w 宽度优先 h 高度优先 wh:等比
 * @return 返回加上背景的图片 *
 */
function addBg($src, $w, $h, $fisrt = "w") {
    $bg = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($bg, 255, 255, 255);
    imagefill($bg, 0, 0, $white); // 填充背景

    // 获取目标图片信息
    $info = getImageInfo($src);
    $width = $info[0]; // 目标图片宽度
    $height = $info[1]; // 目标图片高度
    $img = create($src);
    if ($fisrt == "wh") {
        // 等比缩放
        return $src;
    } else {
        if ($fisrt == "w") {
            $x = 0;
            $y = ($h - $height) / 2; // 垂直居中
        }
        if ($fisrt == "h") {
            $x = ($w - $width) / 2; // 水平居中
            $y = 0;
        }
        imagecopymerge($bg, $img, $x, $y, 0, 0, $width, $height, 100);
        imagejpeg($bg, $src, 100);
        imagedestroy($bg);
        imagedestroy($img);
        return $src;
    }
}

/**
 * 对象转数组
 *
 * @param unknown $obj
 * @author Zonkee
 * @version 1.0.1
 * @since 1.0.1
 * @return array
 */
function ob2ar($obj) {
    if (is_object($obj)) {
        $obj = (array) $obj;
        $obj = ob2ar($obj);
    } elseif (is_array($obj)) {
        foreach ($obj as $key => $value) {
            $obj[$key] = ob2ar($value);
        }
    }
    return $obj;
}

/**
 * 商品被拍下的短息提醒
 *
 * @param string $to
 *            收件人号码
 * @param array $datas
 *            发送给用户的数据
 * @param int $templateId
 *            短信模版ID
 */
function send_shopping_sms($to, array $datas, $templateId = 1) {
    vendor('SMS.CCPRestSDK');
    $sms_config = C('SMS');
    $rest = new REST($sms_config['host'], $sms_config['port'], $sms_config['version']);
    $rest->setAccount($sms_config['accountSid'], $sms_config['accountToken']);
    $rest->setAppId($sms_config['appId']);
    return $rest->sendTemplateSMS($to, $datas, $templateId);
}
?>