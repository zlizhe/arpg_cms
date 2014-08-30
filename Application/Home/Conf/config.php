<?php
return array(
    //HOME
    'URL_ROUTE_RULES'       =>  array(
    	//文章阅读页面
    	'article/:id\d'	=> 'Index/article',
    	//允许AJAX完整请求
    	'ajax/:action'	=> 'Ajax/:1',
    	//用户空间
    	'space/:action'	=> 'Space/:1',
    	//其他转为 目录列表/分类
    	':action' => 'Index/category',

    ), // 默认路由规则 针对模块
);