<?php if (!defined('THINK_PATH')) exit(); if($slideArr): ?><div class="row">
		
		
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		  	<ol class="carousel-indicators">
		  		<?php if(is_array($slideArr)): foreach($slideArr as $key=>$value): ?><li data-target="#carousel-example-generic" data-slide-to="<?php echo ($key); ?>" class="<?php if($key == 0): ?>active<?php endif; ?>"></li><?php endforeach; endif; ?>
		  	</ol>

		  	<div class="carousel-inner" role="listbox">
		  		<?php if(is_array($slideArr)): foreach($slideArr as $key=>$value): ?><div class="item <?php if($key == 0): ?>active<?php endif; ?>">
			    		<a onclick="ajaxLink($(this)); return false;" href="<?php echo ($value['link']); ?>" <?php if($value['target']): ?>target="_blank"<?php endif; ?>>
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
						<a onclick="ajaxLink($(this));return false;" href="/<?php echo ($value['path_name']); ?>"><?php echo ($value['value']); ?></a>
						<span class="pull-right"><a onclick="ajaxLink($(this));return false;" href="/<?php echo ($value['path_name']); ?>">更多 >></a></span>
					</div>
					
					<?php if($value['articles']): ?><ul class="list-group">
							<?php if(is_array($value['articles'])): foreach($value['articles'] as $key=>$value2): ?><li class="list-group-item"><a onclick="ajaxLink($(this));return false;" href="/article/<?php echo ($value2['id']); ?>/<?php echo ($value2['title']); ?>"><?php echo ($value2['title']); ?></a></li><?php endforeach; endif; ?>
						</ul>
					<?php else: ?>
						<div class="panel-body">该栏目还没有文章</div><?php endif; ?>
				</div>
			</li><?php endforeach; endif; ?>

	</ul>



</div>