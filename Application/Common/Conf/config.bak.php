<?php
/*!
 * 	APPLICATION 级别配置
 */
return array(

    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '{DB_HOST}', // 服务器地址
    'DB_NAME'               =>  '{DB_NAME}',          // 数据库名
    'DB_USER'               =>  '{DB_USER}',      // 用户名
    'DB_PWD'                =>  '{DB_PWD}',          // 密码
    'DB_PORT'               =>  '{DB_PORT}',        // 端口
    'DB_PREFIX'             =>  'arpg_',    // 数据库表前缀

    'APP_SUB_DOMAIN_DEPLOY' =>  false,   // 是否开启子域名部署
    'APP_SUB_DOMAIN_RULES'  =>  array(), // 子域名部署规则
	'APP_DOMAIN_SUFFIX'     =>  '', // 域名后缀 二级域名 设置子域名 需设定 如果是com.cn net.cn 之类的后缀必须设置

    /* NAMESPACE 前缀 */
    'COOKIE_PREFIX'         =>  '{NAMESPACE}',
    'DATA_CACHE_PREFIX'     =>  '{NAMESPACE}',
    'SESSION_PREFIX'        =>  '{NAMESPACE}',

    /* 允许访问模块 */
    'MODULE_ALLOW_LIST'     =>  array('Home','Member','Set','Dialog','Service', 'Install'),

    /* 默认设定 */
    'DEFAULT_THEME'         =>  'default',	// 默认模板主题名称
    //'TMPL_DETECT_THEME'     =>  true,       // 自动侦测模板主题
    'DEFAULT_MODULE'        =>  'Home',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称

    #### 正式环境 TRUE ####
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_SQL_BUILD_CACHE'    =>  true, // 数据库查询的SQL创建缓存
    'DB_SQL_BUILD_QUEUE'    =>  'file',   // SQL缓存队列的缓存方式 支持 file xcache和apc

    /* URL */
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'URL_HTML_SUFFIX'       =>  '',  // URL伪静态后缀设置
    'URL_PARAMS_BIND'       =>  true, // URL变量绑定到Action方法参数
	'URL_PARAMS_BIND_TYPE'  =>  1, // URL变量绑定的类型 0 按变量名绑定 1 按变量顺序绑定
    'URL_ROUTER_ON'         =>  true,   // 是否开启URL路由
    'URL_ROUTE_RULES'       =>  array(
        //放置在 各模块下
    ), // 默认路由规则 针对模块
    'URL_MAP_RULES'         =>  array(), // URL映射定义规则

    //'URL_MODULE_MAP'        =>  array('admin'=>'set'),
    // 设置禁止访问的模块列表
    //'MODULE_DENY_LIST'      =>  array('Template'),


    // 显示页面Trace信息 #### 正式环境 FALSE ####
	'SHOW_PAGE_TRACE'       =>  false,
    
    //模板位置 重新定义 多模块合并
    'VIEW_PATH'=>'./Template/',
    //其他主题没有该文件时 使用 默认主题文件
    'TMPL_LOAD_DEFAULTTHEME'=>  true,

    //启用 LAYOUT
    //'LAYOUT_ON'             =>  true,

    //如果 将INDEX.PHP 移出 PUBLIC 目录 这里需要设置 为 /Public
    'TMPL_PARSE_STRING'     =>  array(
         '__PUBLIC__' => '', // 更改默认的/Public 替换规则
    ),

    'TMPL_L_DELIM'          =>  '{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '}',            // 模板引擎普通标签结束标记
    //模板引擎
    //'TMPL_ENGINE_TYPE' =>'PHP'

    'DEFAULT_FILTER'        =>  'htmlspecialchars,trim', // 默认参数过滤方法 用于I函数...
    'URL_CASE_INSENSITIVE'  =>  false, //关闭URL小写
    //'ACTION_BIND_CLASS'     =>  true,

);
