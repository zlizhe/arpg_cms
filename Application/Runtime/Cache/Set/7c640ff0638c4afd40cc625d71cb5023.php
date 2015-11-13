<?php if (!defined('THINK_PATH')) exit();?><form role="form" method="post" target="actionfrm" action="<?php echo U('Set/Ajax/setEditArticle');?>" enctype="multipart/form-data">

<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<a class="close" href="<?php echo U('/set/article');?>" title="取消"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></a>
			<h4 class="modal-title">
				<?php echo ($title); ?>
			</h4>
		</div>
		<div class="modal-body">

				<div class="form-group">
			    	<label for="InputTitle1"><font color="red">*</font> 标题</label>
			    	<input type="text" class="form-control" id="InputTitle1" placeholder="标题" value="<?php echo ($articleArr['title']); ?>" name="title" maxlength="40">
				</div>

				<div class="form-group">
					<label for="InputCategory1"><font color="red">*</font> 分类</label>
					<select name="category" class="form-control" id="InputCategory1">
						<?php if(is_array($catList)): foreach($catList as $key=>$value): ?><option value="<?php echo ($value['id']); ?>" <?php if($articleArr['cat_id'] == $value['id']): ?>selected<?php endif; ?>><?php echo ($value['value']); ?></option><?php endforeach; endif; ?>
					</select>
			    </div>

				<div class="form-group">
			    	<label>封面图片</label>
			    	<div class="clearfix"></div>

					<div id="coverArea">
						<?php if($articleArr['cover_img']): ?><img src="<?php echo ($articleArr['cover_img']); ?>" class="img-thumbnail" /><?php endif; ?>
					</div>

					<div class="clearfix"></div>
					<input type="hidden" value="<?php echo ($articleArr['cover_img']); ?>" name="coverImg" id="coverImg" />
					<a href="javascript:showUpload();" class="btn btn-default btn-sm">浏览图片...</a>
					<a href="javascript:removeUpload();" class="btn btn-danger btn-sm <?php if(!$articleArr['cover_img']): ?>hide<?php endif; ?>" id="removeUpbtn"><span class="glyphicon glyphicon-trash"></span> 移除图片</a>
					
				</div>

				<div class="form-group">
			    	<label for="arpgEditor"><font color="red">*</font> 内容</label>
			    	<div class="clearfix"></div>
		        	<textarea name="content" id="arpgEditor"><?php echo htmlspecialchars_decode($articleArr['content']);?></textarea>
				</div>

				<div class="form-group">
					<label>标签</label>
					<div class="clearfix"></div>
	                <select multiple name="tags_arr[]" data-role="tagsinput" placeholder="Enter 键分割多个标签                ">
	                	<?php if(is_array($tags)): foreach($tags as $key=>$value): ?><option value="<?php echo ($value); ?>" selected="selected"><?php echo ($value); ?></option>
							<?php echo ($value); endforeach; endif; ?>
	                </select>
	                <span class="help-block">Enter 键分割多个标签</span>   
			    </div>

				<input type="hidden" value="<?php echo ($ac); ?>" name="ac" />
				<input type="hidden" value="<?php echo ($articleArr['id']); ?>" name="aid" />
			

		</div>
		<div class="modal-footer">
        	<button type="submit" class="btn btn-primary" data-loading-text="Loading..."><?php if($ac == 'add'): ?>发布<?php else: ?>更新<?php endif; ?></button>
	    </div>
	</div>
</div>

</form>

<script type="text/javascript" src="/plugin/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/plugin/ueditor/ueditor.all.js"></script>
<script type="text/javascript">
    var editor = new UE.ui.Editor();
    editor.render("arpgEditor");
</script>

<link rel="stylesheet" href="/css/bs-tagsinput.css" />
<script type="text/javascript" src="/js/bs-tagsinput.js"></script>


<form action="<?php echo U('/set/ajax/uploadImg');?>" class="form-horizontal hide" method="post" target="actionfrm" id="cover_form" name="cover_form" enctype="multipart/form-data">
	<input type="file" id="cover_start" name="cover_img" />
</form>
<script>
	//打开普通 上传窗口	
	function showUpload() {
		$("#cover_start").click();
	}
	//开始上传动作
	$('input#cover_start').change(function(){
		//ON LOADING
    	showWindow('/index.php/dialog/dialog/uploading');
		$("#cover_form").submit();
	});

	//移除已上传的图片
	function removeUpload() {
		$("#coverArea").find('img').remove();
        $("#coverImg").val('');
        $("#removeUpbtn").hide();
	}
    // 设置封面图片
    function successCut(img) {
        $('#dialog').modal('hide');
        $("#coverArea").html('<img src="' + img + '" class="img-thumbnail" />');
        $("#coverImg").val(img);
        $("#removeUpbtn").show();
    }
</script>