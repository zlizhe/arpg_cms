<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta name="force-rendering" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ($seoSetting['title']); ?> - <?php echo ($seoSetting['siteName']); ?></title>

    <link href="/css/bs.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="/css/html5shiv.min.js"></script>
      <script src="/css/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/css/common.css">
    
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bs.js"></script>
    <script src="/js/common.js"></script>

    <meta name="generator" content="ARPG TEAM." />
    <meta name="author" content="lizhe" />
    

    </head>
<body>

    <div id="ajax_loading">&nbsp;&nbsp;Loading ...&nbsp;</div>  


<link href="/css/arpg.css" rel="stylesheet">
<script src="/js/arpg.js"></script>

<link rel="stylesheet" href="<?php echo ($_themeUrl); ?>/Common/extend.css">


<div class="container">

	

<header>
	
	<div class="row top_logo">
		<div class="col-md-3 col-sm-4">
			<a href="<?php echo ($_Gset['SITE_URL']); ?>" title="<?php echo ($_Gset['SITE_NAME']); ?>">
				<img src="/img/logo.png" />
			</a>
		</div>
		<div class="col-md-6 col-sm-4">
			<div class="form-group input-group col-sm-10 navbar-right main_search">
				<input type="text" class="form-control" placeholder="搜索从这里开始..." name="query" id="query" autocomplate="off" onkeydown="event.keyCode==13 && $('#arpg_search').click();" value="<?php echo ($query); ?>">
				<span class="input-group-btn">
					<a class="btn btn-default" href="javascript:;" id="arpg_search"><span class="glyphicon glyphicon-search"></span></a>
				</span>
			</div>
			<input type="hidden" value="<?php echo U($searchUrl, array('query'=>NULL));?>/query/" name="search_url" id="search_url" />
		</div>

		<div class="col-md-3 col-sm-4">
			<?php if($_Guser): ?><ul class="nav navbar-nav navbar-right userList">
		        	<li class="dropdown">
		          		<a href="#" class="dropdown-toggle main_user" data-toggle="dropdown"><img src="<?php echo ($_Gset['IMG_AVATAR']); echo ($_Guser['uid']); ?>/<?php echo ($_Guser['uid']); ?>_small.jpg" class="img-rounded" id="top_avatar" /> <?php echo ($_Guser['realname']); ?> <span class="caret"></span></a>
		          		<ul class="dropdown-menu" role="menu">
		          			<li><a href="/space/<?php echo ($_Guser['uid']); ?>">我的主页</a></li>
		            		<li><a href="<?php echo U('/space/setup');?>">账号设置</a></li>
		            		<?php if($isAdmin): ?><li><a href="<?php echo U('/set');?>">管理中心</a></li><?php endif; ?>
		            		<li class="divider"></li>
		            		<li><a href="javascript:;" data-dialog="<?php echo U('dialog/dialog/logout');?>?ref=<?php echo ($ref); ?>" id="showWindow">登出</a></li>
		          		</ul>
		        	</li>
		      	</ul>
			<?php else: ?>
	      		<ul class="nav navbar-nav navbar-right">
		        	<li><a href="<?php echo U('/member/login', array('ref'=>$ref));?>" data-dialog="<?php echo U('dialog/dialog/login');?>?ref=<?php echo ($ref); ?>" id="showWindow"><span class="glyphicon glyphicon-user"></span> 登录</a></li>
		        	<li><a href="<?php echo U('/member/register', array('ref'=>$ref));?>">免费注册</a></li>
		      	</ul><?php endif; ?>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<?php if($navArr): ?><div class="row top_nav">
		<nav class="navbar navbar-default" role="navigation">
		  	<div class="container-fluid">
			    <div class="navbar-header">
			      	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				        <span class="sr-only">菜单</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
			      	</button>
			      	<a class="navbar-brand" href="<?php echo ($_Gset['SITE_URL']); ?>"><span class="glyphicon glyphicon-home"></span></a>
			    </div>

			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			      	<ul class="nav navbar-nav">
			      		<?php if(is_array($navArr)): foreach($navArr as $key=>$value): ?><li <?php if($value['select'] == 1): ?>class="active"<?php endif; ?>>
				          		<a <?php if($value['childArr']): ?>class="dropdown-toggle" data-toggle="dropdown"<?php endif; ?> href="<?php echo ($value['link']); ?>" title="<?php echo ($value['title']); ?>" <?php if($value['target'] == 1): ?>target="_blank"<?php endif; ?>><?php echo ($value['value']); ?>
					        		<?php if($value['childArr']): ?><span class="caret"></span><?php endif; ?>
				        		</a>
				        		<?php if($value['childArr']): ?><ul class="dropdown-menu" role="menu">
										<?php if($value['link'] && $value['link'] != '#' && $value['link'] !='javascript:;'): ?><li>
												<a href="<?php echo ($value['link']); ?>" title="<?php echo ($value['title']); ?>" <?php if($value['target'] == 1): ?>target="_blank"<?php endif; ?>><?php echo ($value['value']); ?></a>
											</li><?php endif; ?>
										<?php if(is_array($value['childArr'])): foreach($value['childArr'] as $key=>$value2): ?><li>
												<a href="<?php echo ($value2['link']); ?>" title="<?php echo ($value2['title']); ?>" <?php if($value2['target'] == 1): ?>target="_blank"<?php endif; ?>><?php echo ($value2['value']); ?></a>
											</li><?php endforeach; endif; ?>
									</ul><?php endif; ?>
				        	</li><?php endforeach; endif; ?>
			      	</ul>
			    </div>
		  	</div>
		</nav>
	</div><?php endif; ?>

</header>
	
	<?php if($slideArr): ?><div class="row">
		
		
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		  	<ol class="carousel-indicators">
		  		<?php if(is_array($slideArr)): foreach($slideArr as $key=>$value): ?><li data-target="#carousel-example-generic" data-slide-to="<?php echo ($key); ?>" class="<?php if($key == 0): ?>active<?php endif; ?>"></li><?php endforeach; endif; ?>
		  	</ol>

		  	<div class="carousel-inner" role="listbox">
		  		<?php if(is_array($slideArr)): foreach($slideArr as $key=>$value): ?><div class="item <?php if($key == 0): ?>active<?php endif; ?>">
			    		<a href="<?php echo ($value['link']); ?>" <?php if($value['target']): ?>target="_blank"<?php endif; ?>>
			      			<img src="<?php echo ($value['img_url']); ?>" alt="正在加载图片" />
			      		</a>
			    	</div><?php endforeach; endif; ?>
		  	</div>

		  	<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
		    	<span class="glyphicon glyphicon-chevron-left"></span>
		    	<span class="sr-only">前一张</span>
		  	</a>
		  	<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
		    	<span class="glyphicon glyphicon-chevron-right"></span>
		    	<span class="sr-only">后一张</span>
		  	</a>
		</div>

	</div>

	<div class="clearfix"></div>
	<hr /><?php endif; ?>

<div class="row">
	
	
	<ul class="list-inline">

		<?php if(is_array($categoryArr)): foreach($categoryArr as $key=>$value): ?><li class="index_cat">
				<div class="panel panel-default">
					<div class="panel-heading">
						<a href="/<?php echo ($value['path_name']); ?>"><?php echo ($value['value']); ?></a>
						<span class="pull-right"><a href="/<?php echo ($value['path_name']); ?>">更多 >></a></span>
					</div>
					
					<?php if($value['articles']): ?><ul class="list-group">
							<?php if(is_array($value['articles'])): foreach($value['articles'] as $key=>$value2): ?><li class="list-group-item"><a href="/article/<?php echo ($value2['id']); ?>/<?php echo ($value2['title']); ?>"><?php echo ($value2['title']); ?></a></li><?php endforeach; endif; ?>
						</ul>
					<?php else: ?>
						<div class="panel-body">该栏目还没有文章</div><?php endif; ?>
				</div>
			</li><?php endforeach; endif; ?>

	</ul>



</div>
</div>

	
	<div class="clearfix"></div>
	<hr>

	<div class="copyright text-center">
		<p><a href="<?php echo ($_Gset['SITE_URL']); ?>"><?php echo ($_Gset['SITE_NAME']); ?></a> <?php echo (htmlspecialchars_decode($_Gset['ANALYTICS'])); ?></p>
		<small>Powered by <a href="http://cms.zlizhe.com" target="_blank">ARPG CMS</a> <?php echo ($_Gver); ?> &copy 2010 - <?= date('Y')?> <a href="http://web.zlizhe.com" target="_blank">ARPG TEAM.</a></small>
	</div>

	<input type="hidden" value="<?php echo ($ref); ?>" name="ref" />

	<div id="errorMsg" class="modal fade bs-example-modal-sm" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        			<h4 class="modal-title">提示</h4>
				</div>
				<div class="modal-body"><p></p></div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">好的</button>
			    </div>
			</div>
		</div>
	</div>

	<div id="dialog" class="modal fade" tabindex="-1" aria-hidden="true">
    	
    </div>

	<iframe id="actionfrm" src=""  width="0" height="0" name="actionfrm"  frameborder="0"></iframe>
	
	</body>
</html>