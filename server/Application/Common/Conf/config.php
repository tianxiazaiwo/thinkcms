<?php
return array(
	//URL配置
    'URL_CASE_INSENSITIVE' => true,     //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 1,        //URL模式
    'VAR_URL_PARAMS'       => '',       // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/',      //PATHINFO URL分割符

    //数据库配置
    'DB_TYPE'   => 'mysql',
    'DB_HOST'   => '127.0.0.1',
    'DB_NAME'   => 'think_cms',
    'DB_USER'   => 'root',
    'DB_PWD'    => 'root',
    'DB_PORT'   => '3306',
    'DB_PREFIX' => 'ad_', // 数据库表前缀

    //Redis配置
    'REDIS_HOST'    => 'redis.zy.com',
    'REDIS_PORT'    => '6381',
    'REDIS_PASS'    => '72c765208cc5c27',

    //数据缓存设置
    'DATA_CACHE_PREFIX'    => 'tcms_',
    'DATA_CACHE_TYPE'      => 'Redis',
    'DATA_CACHE_TIME'   => 300,

    //其他配置
    'DATA_AUTH_KEY' => 'ye4*$D"JYC3auSZM]!lqz{>[hIc^A8f90FrkU&(.',  //系统数据加密设置
);