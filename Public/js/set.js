/**
 * 	Powered by ARPG TEAM.
 * 	http://web.zlizhe.com
 * 	SET 专用JS
 */
$(document).ready(function() {

    //搜索 
    $("#set_search").click(function(){
		var query = encodeURI($("#query").val());
		var s_url = $("#set_search_url").val();
		if (query != ""){
			window.open(s_url+query, "_self");	
		}
    });

	//全选
    $("#chk_all").click(function(){
        if (this.checked == true) {
            $("input[id='checkOn']").each(function(){this.checked=true;}); 
        } else {
            $("input[id='checkOn']").each(function(){this.checked=false;}); 			
        }        
    });

	//选择栏变色
	$('input[id="checkOn"]').click(function(){
		var _this = $(this);
		if(this.checked == true){
			_this.parents('tr').addClass("table-checked");
		}else{
			_this.parents('tr').removeClass("table-checked");
		};
	});

});

/**
 * 删除各种
 * @param  {[type]} id     删除ID
 * @param  {[type]} name   标题
 * @param  {[type]} action 操作名
 * @return {[type]}        BOOLE
 */
function arpgDel(id, name, action) {
	var ref = $('input[name=ref]').val();
	if (window.confirm("确定要删除 \""+name+"\" ?")){
		$.getJSON("/index.php/set/ajax/"+action, {id:id},function(result){   
			if(result.status == 'success'){
				successMsg("操作成功, 正在跳转...", ref);
			}else{
				errorMsg(result.msg);
			}
		});
	}
}

/**
 * 禁止用户
 * @param  {[type]} uid [description]
 * @return {[type]}     [description]
 */
function ban(uid, name) {
	var ref = $('input[name=ref]').val();
	if (window.confirm("确定禁止 \""+name+"\" 的账号使用吗?")){
		$.getJSON("/index.php/set/ajax/setBan",{uid:uid},function(result){   
			if(result.status == 'success'){
				successMsg("操作成功, 正在跳转...", ref);
			}else{
				errorMsg(result.msg);
			}
		});
	}
}

/**
 * 恢复用户
 * @param  {[type]} uid [description]
 * @return {[type]}     [description]
 */
function reban(uid, name) {
	var ref = $('input[name=ref]').val();
	if (window.confirm("确定恢复 \""+name+"\" 的账号继续使用吗?")){
		$.getJSON("/index.php/set/ajax/setBan",{uid:uid, type:1},function(result){   
			if(result.status == 'success'){
				successMsg("操作成功, 正在跳转...", ref);
			}else{
				errorMsg(result.msg);
			}
		});
	}
}
