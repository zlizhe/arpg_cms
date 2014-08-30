<?php
return array(
    //SET相关
    'URL_ROUTE_RULES'       =>  array(
    	'dialog/:action' => 'Dialog/:1',
    	'ajax/:action'   => 'Ajax/:1',
    	':action'        => 'Set/:1',
    ), // 默认路由规则 针对模块

);