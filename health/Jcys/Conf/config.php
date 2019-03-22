<?php
return array(
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'localhost',
    'DB_HOST'=>'127.0.0.1',
    'DB_NAME'=>'yabao',
    'DB_USER'=>'root',
    'DB_PWD'=>'76503445',
    'DB_PORT'=>'3306',
    'DB_PREFIX'=>'yb_',
    'DB_CHARSET'=>'utf8',
    'URL_MODEL'=>2,
    'SHOW_PAGE_TRACE' =>true, // 显示页面Trace信息

    //设置默认目录
//    'URL_ROUTER_ON' => true,//开启路由
//    'URL_ROUTE_RULES'=> array(
//        'login' => 'Jcys/Login/index',
//    ),



	//'配置项'=>'配置值'
    "TMPL_PARSE_STRING"=>array(
        '__JQ__'=>'https://cdn.bootcss.com/jquery/3.3.1/jquery.js',
        "__CSS__"=>__ROOT__."/Public/".MODULE_NAME."/css",
        "__JS__"=>__ROOT__."/Public/".MODULE_NAME."/js",
        "__IMG__"=>__ROOT__."/Public/".MODULE_NAME."/img",
        "__LAYUICSS__"=>__ROOT__."/Public/".MODULE_NAME."/layui/css/layui.css",
        "__LAYUIJS__"=>__ROOT__."/Public/".MODULE_NAME."/layui/layui.js",
        "__FONTS__"=>__ROOT__."/Public/".MODULE_NAME."/fonts",
    )

);