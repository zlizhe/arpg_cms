<?php if (!defined('THINK_PATH')) exit();?>
<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title">操作成功！</h4>
		</div>
		<div class="modal-body">
			<?php echo ($res); ?>
		</div>
		<div class="modal-footer">
			<span class="pull-left"><em id="second">2</em> 秒后转到...</span>
	        <a href="<?php echo ($url); ?>" class="btn btn-success pull-right" data-loading-text="Loading...">立即跳转...</a>
	    </div>
	</div>
</div>
    
<script>

setTimeout(function(){
	window.open("<?= $url?>", "_self");
}, 2000);
setInterval(function(){
	var s = $('#second').text();
	s--;
	$('#second').text(s);
}, 1000);


/* 按钮状态显示  */
$('a[data-loading-text]').click(function () {
    var btn = $(this).button('loading');
    setTimeout(function () {
        btn.button('reset');
    }, 3000);
});


</script>