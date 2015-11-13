<?php if (!defined('THINK_PATH')) exit();?><div class="row">
	<ol class="breadcrumb">
		<li><a onclick="ajaxLink($(this));return false;" href="<?php echo ($_Gset['SITE_URL']); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
		<li><a onclick="ajaxLink($(this));return false;" href="/<?php echo ($categoryArr['path_name']); ?>"><?php echo ($categoryArr['value']); ?></a></li>
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
				<?php if(is_array($tagsArr)): foreach($tagsArr as $key=>$value): ?><li><a onclick="ajaxLink($(this));return false;" href="<?php echo U('/search');?>/query/<?php echo ($value); ?>" ><?php echo ($value); ?></a></li><?php endforeach; endif; ?>
			</ul><?php endif; ?>

		
		<ul class="pager">
			<?php if($beforeArr): ?><li class="previous"><a onclick="ajaxLink($(this));return false;" href="<?php echo U('/article', array($beforeArr['id']=>$beforeArr['title']));?>">&larr; <?php echo ($beforeArr['title']); ?></a></li><?php endif; ?>
			<?php if($afterArr): ?><li class="next"><a onclick="ajaxLink($(this));return false;" href="<?php echo U('/article', array($afterArr['id']=>$afterArr['title']));?>"><?php echo ($afterArr['title']); ?> &rarr;</a></li><?php endif; ?>
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
		$.getJSON("/home/ajax/getComment", {'aid': aid, 'offset': offset},function(result){   
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
			<?php if(is_array($sameCatArr)): foreach($sameCatArr as $key=>$value): ?><li class="list-group-item"><a onclick="ajaxLink($(this));return false;" href="/article/<?php echo ($value['id']); ?>/<?php echo ($value['title']); ?>"><?php echo ($value['title']); ?></a></li><?php endforeach; endif; ?>
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