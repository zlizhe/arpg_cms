<?php if (!defined('THINK_PATH')) exit();?><form role="form" method="post" target="actionfrm" action="<?php echo U('Set/Ajax/setEditCategory');?>" enctype="multipart/form-data">

<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title">
				<?php if($ac == 'add'): ?>新增分类
				<?php else: ?>
				编辑分类 "<?php echo ($catArr['value']); ?>"<?php endif; ?>
			</h4>
		</div>
		<div class="modal-body">

				<div class="form-group">
			    	<label for="InputValue1"><em>*</em> 分类名称</label>
			    	<input type="text" class="form-control" id="InputValue1" placeholder="分类名称" value="<?php echo ($catArr['value']); ?>" name="value" maxlength="15">
				</div>

				<div class="form-group">
			    	<label for="InputNote1"><em>*</em> 自定义目录,无需加/</label>
			    	<input type="text" class="form-control" id="InputNote1" placeholder="自定义目录" value="<?php echo ($catArr['path_name']); ?>" name="path_name" maxlength="25">
				</div>


				<div class="form-group">
			    	<label for="InputCategory1">上级分类</label>
			    	<select name="upid" id="InputCategory1" class="form-control">
			    		<option value="0">最高级</option>
			    		<?php if(is_array($catList)): foreach($catList as $key=>$value): ?><option value="<?php echo ($value['id']); ?>" <?php if($value['id'] == $catArr['upid']): ?>selected<?php endif; ?> ><?php echo ($value['value']); ?></option><?php endforeach; endif; ?>
			    	</select>
				</div>

				<div class="form-group">
					<div class="checkbox">
					  	<label>
					    	<input type="checkbox" name="onNav" value="1" />同时添加至主导航
					  	</label>
					</div>
				</div>



				<input type="hidden" value="<?php echo ($catArr['id']); ?>" name="cid" />
				<input type="hidden" value="<?php echo ($ref); ?>" name="ref" />
				<input type="hidden" value="<?php echo ($ac); ?>" name="ac" />
			

		</div>
		<div class="modal-footer">

			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        	<button type="submit" class="btn btn-primary" data-loading-text="Loading...">保存</button>
	    </div>
	</div>
</div>
</form>

<script>

	//点击后 清除 INPUT HAS-ERROR 的状态
	$("input[type='password'],input[type='text'],input[type='email']").click(function(){
		hidePopOnly($(this));
	});	

	
	/* 按钮状态显示  */
	$('button[data-loading-text]').click(function () {
	    var btn = $(this).button('loading');
	    setTimeout(function () {
	        btn.button('reset');
	    }, 3000);
	});
	
</script>