<?php

/**
 * 整站主配置文件
 *
 * @author lzjjie
 */
return array(
    'APP_GROUP_LIST' => 'Home,admin',
    'DEFAULT_GROUP' => 'Home',
    "URL_MODEL" => 2,
    "URL_CASE_INSENSITIVE" => true,
    "TMPL_L_DELIM" => '<{',
    "TMPL_R_DELIM" => '}>',
    "DB_TYPE" => "mysql",
    "DB_HOST" => "127.0.0.1",
    "DB_NAME" => "tea",
    "DB_USER" => "tea_f",
    "DB_PWD" => "123456",
    "DB_PORT" => 3306,
    "DB_PREFIX" => "tea_",
    "URL_HTML_SUFFIX" => "",
    "SMS" => array(
        'host' => 'sandboxapp.cloopen.com',
        'port' => '8883',
        'version' => '2013-12-26',
        'accountSid' => 'aaf98f8947c493ab0147c81ccccc0470',
        'accountToken' => '5a324a2147af41f888541b3350bfc2db',
        'appId' => 'aaf98f89493ff1d301495562105c0d6c',
        'templateId' => 1
    )
);