<?php
return array(
	// 数据库
    /*'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '124.173.156.2', // 服务器地址
    'DB_NAME' => 'tea', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => '123456', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'tea_', // 数据库表前缀*/

	//模板
	'DEFAULT_THEME' => 'default', // 默认模板主题名称
	'TMPL_ACTION_SUCCESS'=>'Jump/success',
    'TMPL_ACTION_ERROR'=>'Jump/success',

	//日志
	'LOG_RECORD' => true, // 默认不记录日志
	'LOG_EXCEPTION_RECORD' => true, // 是否记录异常信息日志

	//URL
	'URL_CASE_INSENSITIVE'  => true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'             => 2,

	//WEB KEY 用于用户COKIE的盐值。
	'WEB_KEY_FOR_COKIE'   => 'teamall',

	'UPLOAD_TYPE'  => 'jpg|png|jpeg|gif', //允许上传的图片格式

	'OUTPUT_ENCODE'         =>  false, // 页面压缩输出

	//修改定界符
	"TMPL_L_DELIM" => '{',
    "TMPL_R_DELIM" => '}',
);
?>
