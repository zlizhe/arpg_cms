arpg_cms
========

ARPG CMS内容管理系统


V0.1 BETA (开发者预览版)


此版本为测试版，不建议在正式环境中使用。


适用于 LAMP/LNMP等 LINUX环境，需要PHP5.3以上、MYSQL5以上的开发环境即可


### 官方测试地址 http://cms.zlizhe.com ###

#### 基于 Thinkphp 3.2 框架开发 ####


# 安装 #


>1. Web服务器 指向 /ARPGCMS根目录/Public 目录


>2. 给予 Application/Runtime 、 Public/uploads 、 Public/plugin 、 Public/Template 目录 0777 可写权限 (sudo chmod -R 777 dirname)


>3. 调整 Application/Common/Conf/config.php 中 数据库设置 DB_HOST DB_NAME DB_USER DB_PWD 设置


>4. 在MYSQL中导入 _db_bak 中的 sql 文件 数据库默认名称为 arpg_cms，默认编码为 utf8_general_ci


>5. 测试版中需要强制使用 Rewrite 来隐藏默认的 index.php，Public/nginx.conf 中为 NGINX 隐藏 index.php 的方法，APACHE 请添加 .htaccess 文件


>6. 如果安装中出现错误，请调整 Public/index.php 中 Debug 选项为 true



## 完成后即可访问 ARPG CMS ##

### 安装步骤会在正式版中使用可视化的操作方式来代替 ###

#### 访问出现 403错误的 是第1项 没有指向 /arpg_cms/Public 目录，访问目录并非根目录，分类和文章如果一直停留在首页 则是 第5项 Rewrite 没有配置好 ####

### 喜欢请 Star, 谢谢 @lizhe 2014.8.30 http://zlizhe.com ###