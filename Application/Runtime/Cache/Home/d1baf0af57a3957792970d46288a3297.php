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
	
	<div class="row">
	<ol class="breadcrumb">
		<li><a href="<?php echo ($_Gset['SITE_URL']); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
		<li><a href="/<?php echo ($categoryArr['path_name']); ?>"><?php echo ($categoryArr['value']); ?></a></li>
		<li class="active"><?php echo ($articleArr['title']); ?></li>
	</ol>
</div>

<div class="row">
	
	<div class="col-md-8">
		<div class="page-header">
			<h1><?php echo ($articleArr['title']); ?></h1>
			<small>[ 浏览数: <?php echo ($articleArr['view_num']); ?> 评论数: <?php echo ($articleArr['comment_num']); ?> 发布时间: <?php echo (date("Y-m-d",$articleArr[created_at])); ?> 作者: <?php echo ($profileArr['realname']); ?>]</small>
		</div>

		<article>
<!-- 			<?php if($articleArr['cover_img']): ?><div class="text-center">
					<img src="<?php echo ($articleArr['cover_img']); ?>" class="img-thumbnail">
				</div><?php endif; ?> -->

			<?php echo (htmlspecialchars_decode($articleArr['content'])); ?>
		</article>

		
		
		
		<?php if($tagsArr): ?><blockquote>标签</blockquote>
			<ul class="list-unstyled list-inline tags">
				<?php if(is_array($tagsArr)): foreach($tagsArr as $key=>$value): ?><li><a href="<?php echo U('/search', array('query'=>$value));?>" target="_blank"><?php echo ($value); ?></a></li><?php endforeach; endif; ?>
			</ul><?php endif; ?>

		
		<ul class="pager">
			<?php if($beforeArr): ?><li class="previous"><a href="<?php echo U('/article', array($beforeArr['id']=>$beforeArr['title']));?>">&larr; <?php echo ($beforeArr['title']); ?></a></li><?php endif; ?>
			<?php if($afterArr): ?><li class="next"><a href="<?php echo U('/article', array($afterArr['id']=>$afterArr['title']));?>"><?php echo ($afterArr['title']); ?> &rarr;</a></li><?php endif; ?>
		</ul>


		
		<blockquote class="comment_top">评论 <?php if($articleArr['comment_num']): echo ($articleArr['comment_num']); ?> 人参与<?php endif; ?></blockquote>

<div class="row">

	<div class="col-md-12">
		<?php if($_Guser): ?><div class="view_comment">
				<form name="form" action="<?php echo U('home/ajax/setComment', array('op'=>'publish'));?>" class="form-horizontal" method="post" target="actionfrm" id="commentPublish">
					<div class="col-md-12">
						<textarea id="publish_text" name="comment" class="comment_area" placeholder="发表评论" rows="5" maxlength="120" onkeydown="event.keyCode==13 && $('#commentPublish').submit();"></textarea>
					</div>

					<div class="col-md-6">
						<small class="comment_num"><i>0</i>/120</small>
					</div>
					<div class="col-md-6 text-right">
						<button class="btn btn-default" data-loading-text="Loading...">发表评论</button>
					</div>
					<input type="hidden" name="aid" value="<?php echo ($articleArr['id']); ?>" />
				</form>
			</div>
		<?php else: ?>
			<blockquote>
				<small>您需要登录才能评论...&nbsp;
				<a href="<?php echo U('/member/login', array('ref'=>$ref));?>" data-dialog="<?php echo U('dialog/dialog/login');?>?ref=<?php echo ($ref); ?>" id="showWindow">登录</a>
				<a href="<?php echo U('/member/register', array('ref'=>$ref));?>" target="_blank">免费注册</a>
				</small>
			</blockquote><?php endif; ?>
	</div>

	<div class="clearfix"></div>


	<div class="col-md-12">
		<div class="comment_msgs">
			<hr />
		    <ul class="media-list" id="showMsg">

		    </ul>
		    <center class="hide" id="comment_loading"><img src="/img/loading.gif" /></center>
		</div>
	</div>
</div>



<div id="comment_replay" class="hide">

	<div class="clearfix"></div>
    <div class="comment_replay">
        <div class="col-md-12">
	        <form name="form" action="<?php echo U('home/ajax/setComment', array('op'=>'replay'));?>" class="form-horizontal" method="post" target="actionfrm" id="replayPublish">            
	            <div class="col-md-12">
	            	<textarea id="replay_text" class="comment_area" placeholder="发表回复" name="comment" rows="3" maxlength="120" onkeydown="event.keyCode==13 && $('#replayPublish').submit();"></textarea>
				</div>
				<div class="col-md-6">
				</div>
				<div class="col-md-6 text-right">
					<button class="btn btn-default btn-sm" data-loading-text="Loading...">发表回复</button>
				</div>
	            <input type="hidden" name="re_id" value="#re_id#" />
	            <input type="hidden" name="aid" value="<?php echo ($articleArr['id']); ?>" />
	        </form>
        </div>
        <div class="clearfix"></div>
    </div> 
</div>

<script>
$(document).ready(function() {
	//点击清除提示
	// $('textarea[name="comment"]').click(function(){
	// 	var _this = $(this);
	// 	hideCommentErr(_this);
	// });

	// 评论框字数
	$(".comment_area").bind('focus keyup input paste',function(){
		var _this = $(this);
		getTextNum(_this, '.comment_num i');
	});

	//加载评论内容
    window.commentOffset = 0;
    $(window).scroll(function() {
        //滚动条事件
        
        //显示评论内容
        try {
            var nowHeight = $(this).height() - $(".comment_top").height();
            if (($(".comment_top").offset().top - ($(this).scrollTop())) < nowHeight){
                if (window.commentOffset != '-1'){
                    //get comment
                    getCommentMsg(<?php echo ($articleArr['id']); ?>, window.commentOffset);
                    //offset = 5
                    window.commentOffset = window.commentOffset + 5;
                }
                
            }
        } catch (err) {

        }
    });
});

//评论操作 提示
function showCommentMsg(status, id, value) {
 	//alert(id);
	_this = $('textarea[id="'+id+'"]');
	//评论错误
	if (status == 'error') {
		_this.after("<div class='help-block'><span class=\"glyphicon glyphicon-exclamation-sign\"></span> " + value + "</div>");
	    _this.parents('.col-md-12').removeClass('has-success');
	    _this.parents('.col-md-12').addClass('has-error');
	}

	//评论成功
	if (status == 'success') {
		//操作成功 刷新评论内容
		_this.after("<div class='help-block'><span class=\"glyphicon glyphicon-ok-circle\"></span> " + value + "</div>");
		_this.parents('.col-md-12').removeClass('has-error');
	    _this.parents('.col-md-12').addClass('has-success');
	    //刷新
	    window.commentOffset = 0;
	    getCommentMsg(<?php echo ($articleArr['id']); ?>, 0);
	}

	//隐藏提示
	hideCommentErr(_this);
	_this.val('');
	$('.comment_num i').text('0');
}

//隐藏错误 提示
function hideCommentErr(target) {
	setTimeout(function () {
		target.next('.help-block').remove();
		target.parents('.col-md-12').removeClass('has-error');
	}, 3000);
	
}

//VIEW 统计字数
function getTextNum (_this, target){

	var num = _this.val().length;
	// if(num > 120){
	// 	var numtext = "<font color=\"#FF0000\">" + num + "</font>";
	// }else{
	// 	var numtext = num;
	// }
	// 设置了 MAXLENGTH 不会超过了
	var numtext = num;
	$(target).html(numtext);
}

//清空评论内容
function clearComment() {
	$('#showMsg').children().remove();
}


//取评论
function getCommentMsg(aid, offset) {
	
	//var page ? page : 1;
	if (aid) {
		$.getJSON("/index.php/home/ajax/getComment", {'aid': aid, 'offset': offset},function(result){   
			//console.log(result.res);
			if(result.status == 'success'){
				if (offset == 0) {
					//第一页 清除所有
					clearComment();
				}
				$('#showMsg').append(result.res);
			}else{
				window.commentOffset = -1;
			}
		});
	}

}

//创建回复框
function replay(re_id) {

	<?php if ($_Guser) {?>
	var dataopen = $("#replay_" + re_id).attr('data-open');
	//移除已有评论框
	$('#showMsg .comment_replay').remove();

	if (dataopen != 'true') {
		//创建回复评论框
		var replayhtml = $('#comment_replay').html();
		//id
		replayhtml = replayhtml.replace(/#re_id#/, re_id);
		$("#replay_" + re_id).append(replayhtml);
		$("#replay_" + re_id + ' .comment_replay').fadeIn();
		//设置所有为FALSE
		$(".comment_msgs .media").attr('data-open', 'false');
		$("#replay_" + re_id).attr('data-open', 'true');
	}else{
		$("#replay_" + re_id).attr('data-open', 'false');
	}
	<?php }else{ ?>

		nowLogin();
	<?php }?>

}

</script>
	</div>

	<div class="col-md-4">
		<aside>
			<?php if($sameCatArr): ?><div class="panel panel-default">
		<div class="panel-heading">
			热门文章
		</div>
		
		<ul class="list-group">
			<?php if(is_array($sameCatArr)): foreach($sameCatArr as $key=>$value): ?><li class="list-group-item"><a href="/article/<?php echo ($value['id']); ?>/<?php echo ($value['title']); ?>"><?php echo ($value['title']); ?></a></li><?php endforeach; endif; ?>
		</ul>
		
	</div><?php endif; ?>
		</aside>
	</div>
</div>


<script>
$(document).ready(function() {
	//页面载入后 记录历史和点击数
    $("article").after(function(){
    	//setHistory();
    	//CLICK
    	setPlus(<?= $articleArr['id']?>, 'view_num');
    });
});
</script>
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