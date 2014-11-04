<?php

/**
 * 图片上传
 * @param array $file 图片文件数组
 * @param int $maxSize 允许最大尺寸
 * @param array $allowExtension 允许上传图片的扩展名数组
 * @param int $is_thumbnail 是否生成缩略图
 * @param array $thumbnail_size 缩略图的尺寸和后缀（该参数和$is_thumbnail同时出现同时消失）
 * @return array
 */
function upload(array $file, $maxSize, array $allowExtension, $is_thumbnail = 0, array $thumbnail_size) {
    $year = date("Y");
    $month = date("m");
    $day = date("d");
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/{$year}/{$month}/{$day}";
    if (!file_exists($targetPath)) {
        mkdir($targetPath, 0755, true);
    }
    if ($file['files']['size'][0] > $maxSize) {
        return array(
            'status' => false,
            'msg' => '图片文件过大，请选择另外的图片'
        );
    } else {
        $fileParts = pathinfo($file['files']['name'][0]);
        $tempFile = $file['files']['tmp_name'][0];
        if (in_array(strtolower($fileParts['extension']), $allowExtension)) {
            $uploadFileName = generateTargetFileName($fileParts['extension']);
            $targetFile = rtrim($targetPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $uploadFileName;
            move_uploaded_file($tempFile, $targetFile);
            if ($is_thumbnail) {
                foreach ($thumbnail_size as $v) {
                    thumbnail($targetFile, $v[0], $v[1], $v[2]);
                }
            }
            return array(
                'status' => true,
                'src' => 'http://' . $_SERVER['HTTP_HOST'] . "/uploads/{$year}/{$month}/{$day}/" . $uploadFileName,
                'filename' => "/uploads/{$year}/{$month}/{$day}/" . $uploadFileName
            );
        } else {
            return array(
                'status' => false,
                'msg' => '不支持的图片格式'
            );
        }
    }
}

/**
 * 生成上传文件名
 *
 * @param string $extension
 *            扩展名
 * @return string
 */
function generateTargetFileName($extension) {
    return sha1(time() . rand(1000, 9999)) . "." . $extension;
}

function thumbnail($image, $width, $height, $extension) {
    $data = getImageSize($image);
    $imageInfo["width"] = $data[0];
    $imageInfo["height"] = $data[1];
    $imageInfo["type"] = $data[2];
    switch ($imageInfo["type"]) {
        case 1 :
            $img = imagecreatefromgif($image);
            break;
        case 2 :
            $img = imageCreatefromjpeg($image);
            break;
        case 3 :
            $img = imageCreatefrompng($image);
            break;
        default :
            return false;
    }
    $size["width"] = $imageInfo["width"];
    $size["height"] = $imageInfo["height"];
    if ($width < $imageInfo["width"]) {
        $size["width"] = $width;
    }
    if ($height < $imageInfo["height"]) {
        $size["height"] = $height;
    }
    if ($imageInfo["width"] * $size["width"] > $imageInfo["height"] * $size["height"]) {
        $size["height"] = round($imageInfo["height"] * $size["width"] / $imageInfo["width"]);
    } else {
        $size["width"] = round($imageInfo["width"] * $size["height"] / $imageInfo["height"]);
    }
    $newImg = imagecreatetruecolor($size["width"], $size["height"]);
    $otsc = imagecolortransparent($img);
    if ($otsc >= 0 && $otsc <= imagecolorstotal($img)) {
        $tran = imagecolorsforindex($img, $otsc);
        $newt = imagecolorallocate($newImg, $tran["red"], $tran["green"], $tran["blue"]);
        imagefill($newImg, 0, 0, $newt);
        imagecolortransparent($newImg, $newt);
    }
    imagecopyresized($newImg, $img, 0, 0, 0, 0, $size["width"], $size["height"], $imageInfo["width"], $imageInfo["height"]);
    imagedestroy($img);
    $newName = str_replace('.', $extension . '.', $image);
    switch ($imageInfo["type"]) {
        case 1 :
            $result = imageGif($newImg, $newName);
            break;
        case 2 :
            $result = imageJPEG($newImg, $newName);
            break;
        case 3 :
            $result = imagepng($newImg, $newName);
            break;
    }
    imagedestroy($newImg);
}