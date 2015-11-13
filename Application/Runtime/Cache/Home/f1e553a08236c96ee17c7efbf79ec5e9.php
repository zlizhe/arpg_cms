<?php if (!defined('THINK_PATH')) exit();?>

<div class="row">
	<ol class="breadcrumb">
		<li><a onclick="ajaxLink($(this));return false;" href="<?php echo ($_Gset['SITE_URL']); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active"><?php echo ($categoryArr['value']); ?></li>
	</ol>
</div>

<div class="row">

	<div class="col-md-12">
		<div class="page-header">
			
			<blockquote><h4><?php echo ($categoryArr['value']); ?></h4> 
				<?php if($articleArr['how']): ?><small>共 <?php echo ($articleArr['how']); ?> 篇文章</small>
				<?php else: ?>
					<small>该栏目还没有文章</small><?php endif; ?>
			<?php if($query): ?><small>
				<span class="label label-primary">
				关键词: "<?php echo ($query); ?>" <a onclick="ajaxLink($(this));return false;" href="<?php echo U($urlName, array('query'=>NULL));?>" title="清除" class="del"><span class="glyphicon glyphicon-remove"></span></a>
				</span>
				</small><?php endif; ?>
			</blockquote>

		</div>
	</div>
	
	<div class="col-md-8">

	
		
		<article>
			<ul class="media-list">
				<?php if(is_array($articleArr['row'])): foreach($articleArr['row'] as $key=>$value): ?><li class="media">
	                	<?php if($value['cover_img']): ?><a onclick="ajaxLink($(this));return false;" class="pull-left" href="/article/<?php echo ($value['id']); ?>/<?php echo ($value['title']); ?>">
		                        <img class="media-object img-rounded" src="<?php echo ($value['cover_img']); ?>" />
		                    </a><?php endif; ?>
	                    <div class="media-body">
	                        <h3 class="media-heading">
	                            <a onclick="ajaxLink($(this));return false;" href="/article/<?php echo ($value['id']); ?>/<?php echo ($value['title']); ?>"><?php echo ($value['title']); ?></a>
	                        </h3>
	                        <small>[ 浏览数: <?php echo ($value['view_num']); ?> 评论数: <?php echo ($value['comment_num']); ?> 发布时间: <?php echo (date("Y-m-d H:i:s",$value[created_at])); ?> 作者: <?php echo ($value['realname']); ?>]</small>
							<p class="summary">
								<a onclick="ajaxLink($(this));return false;" href="/article/<?php echo ($value['id']); ?>/<?php echo ($value['title']); ?>">
		                        <?php echo ($value['summary']); ?>
		                        </a>
	                        </p>
	                    </div>
						<?php if($value['tags_arr']): ?><div class="pull-right">
								<ul class="list-unstyled list-inline tags">
									<?php if(is_array($value['tags_arr'])): foreach($value['tags_arr'] as $key=>$value2): ?><li><a onclick="ajaxLink($(this));return false;" href="<?php echo U($urlName);?>/query/<?php echo ($value2); ?>"><?php echo ($value2); ?></a></li><?php endforeach; endif; ?>
								</ul>
		                    </div><?php endif; ?>
	                </li><?php endforeach; endif; ?>
			</ul>
		</article>


		<div class="pagination-centered">
			<ul class="pagination pagination-sm">
				<?php if($articleArr['mulitpage']): echo ($articleArr['mulitpage']); ?>
				<?php else: ?>
					<li>&nbsp;</li><?php endif; ?>
			</ul>
		</div>


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