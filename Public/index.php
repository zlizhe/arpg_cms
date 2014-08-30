<?php
/*!
 *
 * 	Powered by ARPG TEAM.
 * 	http://cms.zlizhe.com
 * 	@author lizhe
 * 	mailto: m@zlizhe.com 
 *
 * 	Web目录 /Public
 * 	THINKPHP 3.2.2 目录 /Vendor
 */

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', false);

// 定义应用目录
define('APP_PATH', '../Application/');

//关闭 INDEX.HTML 空文件生成
//define('BUILD_DIR_SECURE', false);

// 引入ThinkPHP入口文件
require '../Vendor/ThinkPHP.php';