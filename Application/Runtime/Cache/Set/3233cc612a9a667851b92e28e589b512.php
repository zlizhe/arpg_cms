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


<link href="/css/set.css" rel="stylesheet">
<script src="/js/set.js"></script>

<header>
	<nav class="navbar navbar-default" role="navigation">
	  	<div class="container-fluid">
		    <div class="navbar-header">
		      	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			        <span class="sr-only">菜单</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
		      	</button>
		      	<a class="navbar-brand" href="#">管理中心</a>
		    </div>

		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      	<ul class="nav navbar-nav">
		      		<?php if(isPurview('setPart1', false, false)): ?><li class="dropdown">
		          		<a href="#" class="dropdown-toggle" data-toggle="dropdown">全局 <span class="caret"></span></a>
		          		<ul class="dropdown-menu" role="menu">
	                        <?php if(isPurview('Set_Set_setting', false, false)): ?><li><a href="<?php echo U('/set/setting');?>">网站设置</a></li><?php endif; ?>
	                        <?php if(isPurview('Set_Set_reginfo', false, false)): ?><li><a href="<?php echo U('/set/reginfo');?>">注册控制</a></li><?php endif; ?>
	                        <?php if(isPurview('Set_Set_user', false, false)): ?><li><a href="<?php echo U('/set/user');?>">会员管理</a></li><?php endif; ?>
	                        <?php if(isPurview('Set_Set_competence', false, false)): ?><li><a href="<?php echo U('/set/competence');?>">权限设置</a></li><?php endif; ?>
	                        <?php if(isPurview('Set_Set_group', false, false)): ?><li><a href="<?php echo U('/set/group');?>">角色管理</a></li><?php endif; ?>
		          		</ul>
		        	</li><?php endif; ?>

		        	<?php if(isPurview('setPart2', false, false)): ?><li class="dropdown">
		          		<a href="#" class="dropdown-toggle" data-toggle="dropdown">界面 <span class="caret"></span></a>
		          		<ul class="dropdown-menu" role="menu">
			          		<?php if(isPurview('Set_Set_slide', false, false)): ?><li><a href="<?php echo U('/set/slide');?>">首页滚动图片</a></li><?php endif; ?>
	                        <?php if(isPurview('Set_Set_nav', false, false)): ?><li><a href="<?php echo U('/set/nav');?>">导航设置</a></li><?php endif; ?>
	                        <?php if(isPurview('Set_Set_themes', false, false)): ?><li><a href="<?php echo U('/set/themes');?>">主题管理</a></li><?php endif; ?>
		          		</ul>
		        	</li><?php endif; ?>
					
					<?php if(isPurview('setPart3', false, false)): ?><li class="dropdown">
		          		<a href="#" class="dropdown-toggle" data-toggle="dropdown">内容 <span class="caret"></span></a>
		          		<ul class="dropdown-menu" role="menu">
	                        <?php if(isPurview('Set_Set_category', false, false)): ?><li><a href="<?php echo U('/set/category');?>">分类管理</a></li><?php endif; ?>
	                        <?php if(isPurview('Set_Set_article', false, false)): ?><li><a href="<?php echo U('/set/article');?>">文章管理</a></li><?php endif; ?>
	                        <?php if(isPurview('Set_Set_comment', false, false)): ?><li><a href="<?php echo U('/set/comment');?>">评论管理</a></li><?php endif; ?>
		          		</ul>
		        	</li><?php endif; ?>
					
<!-- 					<?php if(isPurview('setPart4', false, false)): ?><li class="dropdown">
		          		<a href="#" class="dropdown-toggle" data-toggle="dropdown">接口 <span class="caret"></span></a>
		          		<ul class="dropdown-menu" role="menu">
		            		<li><a href="<?php echo U('/set/oss');?>">OSS上传</a></li>
		            		<li><a href="#">短信接口</a></li>
		            		<li><a href="#">支付接口</a></li>
		            		<li><a href="#">第三方登录</a></li>
		          		</ul>
		        	</li><?php endif; ?> -->
					
					<?php if(isPurview('setPart5', false, false)): ?><li class="dropdown">
		          		<a href="#" class="dropdown-toggle" data-toggle="dropdown">工具 <span class="caret"></span></a>
		          		<ul class="dropdown-menu" role="menu">
		            		<li><a href="<?php echo U('/set/dialog/rmCache', array('type'=>'Cache', 'ref'=>$ref));?>" id="showWindow">清理模板缓存</a></li>
		            		<li><a href="<?php echo U('/set/dialog/rmCache', array('type'=>'Data', 'ref'=>$ref));?>" id="showWindow">清理Data缓存</a></li>
		            		<li><a href="<?php echo U('/set/dialog/rmCache', array('type'=>'Temp', 'ref'=>$ref));?>" id="showWindow">清理Temp缓存</a></li>
		            		<li role="presentation" class="divider"></li>
		            		<li><a href="<?php echo U('/set/dialog/rmCache', array('type'=>'all', 'ref'=>$ref));?>" id="showWindow">清理全部缓存</a></li>
		          		</ul>
		        	</li><?php endif; ?>

		      	</ul>
		      	
<!-- 				<div class="navbar-form  navbar-left" role="search" >
			        <div class="form-group has-feedback">
			          	<input type="text" class="form-control" placeholder="关键词..." name="query" id="query" autocomplate="off" onkeydown="event.keyCode==13 && $('#set_search').click();" value="<?php echo ($query); ?>">
			          	<a href="javascript:;" id="set_search"><span class="glyphicon glyphicon-search form-control-feedback"></span></a>
			        </div>
			        <input type="hidden" value="<?php echo U($urlName, array('query'=>NULL));?>/query/" name="set_search_url" id="set_search_url" />
		  		</div> -->

		      	<ul class="nav navbar-nav navbar-right">
		        	<li class="dropdown">
		          	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo ($_Guser['realname']); ?> <span class="caret"></span></a>
		          	<ul class="dropdown-menu" role="menu">
		            		<li><a href="<?php echo U('/space/setup');?>">账号设置</a></li>
		            		<li><a href="/">网站首页</a></li>
		            		<li class="divider"></li>
		            		<li><a href="javascript:;" data-dialog="<?php echo U('Dialog/Dialog/logout');?>" id="showWindow">登出</a></li>
		          		</ul>
		        	</li>
		      	</ul>
		    </div>
	  	</div>
	</nav>

</header>

	<div class="container-fluid">

		<div class="row">
			
			<div class="tabs-left col-md-2">
				
<div class="panel-group" id="accordion">

    <?php if(isPurview('setPart1', false, false)): ?><div class="panel panel-default">
            <div class="panel-heading">
                
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <h4 class="panel-title">
                        全局
                    </h4>
                </a>
                
            </div>
            <div id="collapseOne" class="panel-collapse collapse <?php if($setPart == 1): ?>in<?php endif; ?>">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        <?php if(isPurview('Set_Set_setting', false, false)): ?><li <?php if ($action == 'setting'){?>class="active"<?php }?>><a href="<?php echo U('/set/setting');?>">网站设置</a></li><?php endif; ?>
                        <?php if(isPurview('Set_Set_reginfo', false, false)): ?><li <?php if ($action == 'reginfo'){?>class="active"<?php }?>><a href="<?php echo U('/set/reginfo');?>">注册控制</a></li><?php endif; ?>
                        <?php if(isPurview('Set_Set_user', false, false)): ?><li <?php if ($action == 'user'){?>class="active"<?php }?>><a href="<?php echo U('/set/user');?>">会员管理</a></li><?php endif; ?>
                        <?php if(isPurview('Set_Set_competence', false, false)): ?><li <?php if ($action == 'competence'){?>class="active"<?php }?>><a href="<?php echo U('/set/competence');?>">权限设置</a></li><?php endif; ?>
                        <?php if(isPurview('Set_Set_group', false, false)): ?><li <?php if ($action == 'group'){?>class="active"<?php }?>><a href="<?php echo U('/set/group');?>">角色管理</a></li><?php endif; ?>
                    </ul>
                </div>
            </div>
        </div><?php endif; ?>
    
    <?php if(isPurview('setPart2', false, false)): ?><div class="panel panel-default">
            <div class="panel-heading">
                
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    <h4 class="panel-title">
                        界面
                    </h4>
                </a>
                
            </div>
            <div id="collapseTwo" class="panel-collapse collapse <?php if($setPart == 2): ?>in<?php endif; ?>">
                <div class="panel-body">

                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        <?php if(isPurview('Set_Set_slide', false, false)): ?><li <?php if ($action == 'slide'){?>class="active"<?php }?>><a href="<?php echo U('/set/slide');?>">首页滚动图片</a></li><?php endif; ?>
                        <?php if(isPurview('Set_Set_nav', false, false)): ?><li <?php if ($action == 'nav'){?>class="active"<?php }?>><a href="<?php echo U('/set/nav');?>">导航设置</a></li><?php endif; ?>
                        <?php if(isPurview('Set_Set_themes', false, false)): ?><li <?php if ($action == 'themes'){?>class="active"<?php }?>><a href="<?php echo U('/set/themes');?>">主题管理</a></li><?php endif; ?>

                    </ul>
                </div>
            </div>
        </div><?php endif; ?>
    
    <?php if(isPurview('setPart3', false, false)): ?><div class="panel panel-default">
            <div class="panel-heading">
                
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                    <h4 class="panel-title">
                        内容
                    </h4>
                </a>
                
            </div>
            <div id="collapseThree" class="panel-collapse collapse <?php if($setPart == 3): ?>in<?php endif; ?>">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        <?php if(isPurview('Set_Set_category', false, false)): ?><li <?php if ($action == 'category'){?>class="active"<?php }?>><a href="<?php echo U('/set/category');?>">分类管理</a></li><?php endif; ?>
                        <?php if(isPurview('Set_Set_article', false, false)): ?><li <?php if ($action == 'article'){?>class="active"<?php }?>><a href="<?php echo U('/set/article');?>">文章管理</a></li><?php endif; ?>
                        <?php if(isPurview('Set_Set_comment', false, false)): ?><li <?php if ($action == 'comment'){?>class="active"<?php }?>><a href="<?php echo U('/set/comment');?>">评论管理</a></li><?php endif; ?>

                    </ul>

                </div>
            </div>
        </div><?php endif; ?>
<!-- 
    <?php if(isPurview('setPart4', false, false)): ?><div class="panel panel-default">
            <div class="panel-heading">
                
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                    <h4 class="panel-title">
                        接口
                    </h4>
                </a>
                
            </div>
            <div id="collapseFour" class="panel-collapse collapse <?php if($setPart == 4): ?>in<?php endif; ?>">
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        <?php if(isPurview('Set_Set_oss', false, false)): ?><li <?php if ($action == 'oss'){?>class="active"<?php }?>><a href="<?php echo U('/set/oss');?>">OSS上传</a></li><?php endif; ?>

                    </ul>

                </div>
            </div>
        </div><?php endif; ?> -->

    
</div>
			</div>
	
			<div class="col-md-10">
				
					
	<ul class="nav nav-tabs" role="tablist">

		<?php if($action == 'setting'): ?><li class="active"><a href="">网站设置</a></li><?php endif; ?>

		<?php if($action == 'reginfo'): ?><li class="active"><a href="">注册控制</a></li><?php endif; ?>

		<?php if($action == 'user'): ?><li <?php if($app == 1): ?>class="active"<?php endif; ?>><a href="<?php echo U('/set/user/app/1');?>">全部</a></li>
	  		<li <?php if($app == 2): ?>class="active"<?php endif; ?>><a href="<?php echo U('/set/user/app/2');?>">小黑屋</a></li>
			<a href="<?php echo U('/set/dialog/addUser', array('ref'=>$ref));?>" id="showWindow" class="btn btn-success">添加用户</a><?php endif; ?>

		<?php if($action == 'group'): ?><li class="active"><a href="">角色管理</a></li>
	  		<a href="<?php echo U('/set/dialog/editGroup', array('ref'=>$ref,'ac'=>'add'));?>" id="showWindow" class="btn btn-success">添加角色</a><?php endif; ?>

		<?php if($action == 'competence'): ?><li class="active"><a href="">权限设置</a></li>
	  		<a href="<?php echo U('/set/dialog/editCompetence', array('ref'=>$ref,'ac'=>'add'));?>" id="showWindow" class="btn btn-success">添加权限</a><?php endif; ?>

		<?php if($action == 'nav'): ?><li class="active"><a href="">主导航</a></li>
	  		<a href="<?php echo U('/set/dialog/editNav', array('ref'=>$ref,'ac'=>'add'));?>" id="showWindow" class="btn btn-success">添加主导航</a><?php endif; ?>

		<?php if($action == 'themes'): ?><li class="active"><a href="">主题设置</a></li>
	  		<a href="<?php echo U('/set/dialog/addThemes', array('ref'=>$ref));?>" id="showWindow" class="btn btn-success">添加主题</a><?php endif; ?>

		<?php if($action == 'category'): ?><li class="active"><a href="">分类管理</a></li>
	  		<a href="<?php echo U('/set/dialog/editCategory', array('ref'=>$ref));?>" id="showWindow" class="btn btn-success">添加分类</a><?php endif; ?>

		<?php if($action == 'article'): ?><li class="<?php if($app == 1): ?>active<?php endif; ?>"><a href="<?php echo U('/set/article', array('app'=>1));?>">显示中</a></li>
	  		<li class="<?php if($app == 2): ?>active<?php endif; ?>"><a href="<?php echo U('/set/article', array('app'=>2));?>">已删除</a></li>
	  		<li class="<?php if($app == 3): ?>active<?php endif; ?>"><a href="<?php echo U('/set/article', array('app'=>3));?>">审核中</a></li>
			<div class="btn-group">
	  			<a href="<?php echo U('/set/editArticle', array('ac'=>'add'));?>" class="btn btn-success">添加文章</a>
			</div><?php endif; ?>

		<?php if($action == 'comment'): ?><li class="<?php if($app == 1): ?>active<?php endif; ?>"><a href="<?php echo U('/set/comment', array('app'=>1));?>">显示中</a></li>
	  		<li class="<?php if($app == 2): ?>active<?php endif; ?>"><a href="<?php echo U('/set/comment', array('app'=>2));?>">已删除</a></li>
	  		<li class="<?php if($app == 3): ?>active<?php endif; ?>"><a href="<?php echo U('/set/comment', array('app'=>3));?>">审核中</a></li><?php endif; ?>

		<?php if($action == 'slide'): ?><li class="active"><a href="<?php echo U('/set/slide');?>">首页滚动图片</a></li>
	  		<div class="btn-group">
	  			<a href="javascript:showUpload();" class="btn btn-success" title="建议大小 1200 x 250像素">添加新图片</a>
			</div><?php endif; ?>

<!-- 		<?php if($action == 'oss'): ?><li class="active"><a href="<?php echo U('/set/oss');?>">OSS上传</a></li><?php endif; ?> -->

		
		<?php if($action == 'user' || $action == 'article'): ?><div class="navbar-form navbar-right set_rightsearch" role="search" >
		        <div class="form-group has-feedback">
		          	<input type="text" class="form-control" placeholder="关键词..." name="query" id="query" autocomplate="off" onkeydown="event.keyCode==13 && $('#set_search').click();" value="<?php echo ($query); ?>">
		          	<a href="javascript:;" id="set_search"><span class="glyphicon glyphicon-search form-control-feedback"></span></a>
		        </div>
		        <input type="hidden" value="<?php echo U($urlName, array('query'=>NULL));?>/query/" name="set_search_url" id="set_search_url" />
	  		</div><?php endif; ?>

	</ul>
				
				<div class="row set_main">
					<div class="col-md-12">
						
<?php if(!$catArr): ?><blockquote>没有找到信息</blockquote>
	
<?php else: ?>
	
	<form role="form" method="post" target="actionfrm" action="<?php echo U('Set/Ajax/setEditAllCat');?>" enctype="multipart/form-data">

	<table class="table table-hover">

		<thead>
			<tr>
				<th>#</th>
				<th>顺序</th>
				<th>名称</th>
				<th>目录</th>
				<th>链接</th>
				<th class="text-center">启用</th>
				<th>&nbsp;</th>
				
			</tr>
		</thead>

		<tbody>
			<?php if(is_array($catArr)): foreach($catArr as $key=>$value): ?><tr>
					<td><input type="checkbox" name="del[]" id="checkOn" value="<?php echo ($value[id]); ?>"></td>
					
					<td class="col-xs-1"><input type="text" class="form-control input-mini" value="<?php echo ($value['sort']); ?>" name="sort[<?php echo ($value[id]); ?>]" /></td>

					<td>
						<?php echo ($value['value']); ?>
					</td>
					<td>
					    <input type="text" class="form-control input-mini" value="<?php echo ($value['path_name']); ?>" name="path_name[<?php echo ($value['id']); ?>]" />
					</td>

					<td>
						<a href="<?php echo ($site_url); ?>/<?php echo ($value['path_name']); ?>" target="_blank"><?php echo ($site_url); ?>/<?php echo ($value['path_name']); ?></a>
					</td>
					<td class="text-center">
						<input type="checkbox" name="app[<?php echo ($value['id']); ?>]" value="1" <?php if($value['app']): ?>checked<?php endif; ?>>
					</td>

					<td>
						<a href="<?php echo U('/set/dialog/editCategory', array('cid'=>$value['id'],'ref'=>$ref,'ac'=>'edit'));?>" class="btn btn-default btn-xs" id="showWindow"><span class="glyphicon glyphicon-edit"></span> 高级</a>
					</td>
				</tr>
				<input type="hidden" value="<?php echo ($value['id']); ?>" name='id[]' ><?php endforeach; endif; ?>

		</tbody>

	</table>


	<hr>
	<div class="setBchange">
		<label for="chk_all" class="control-label">
			<input id="chk_all" class="pc vm" type="checkbox" name="chk_all" /> 删?
		</label>
		&nbsp;
		<input type="hidden" value="<?php echo ($ref); ?>" name="ref" />
		<button type="submit" class="btn btn-primary btn-sm" data-loading-text="Loading...">提交</button>
	</div>
	</form><?php endif; ?>
					</div>
				</div>


			</div>

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