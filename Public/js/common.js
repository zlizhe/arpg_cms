/**
 * 	Powered by ARPG TEAM.
 * 	http://web.zlizhe.com
 * 	全局 JS
 */
$(document).ready(function() {
	//AJAX 动作
	$(document).ajaxStart(function(){
		$("#ajax_loading").show();
	});
	$(document).ajaxComplete(function(){
		$("#ajax_loading").fadeOut();
	});

	/* 按钮状态显示  */
	$('button[data-loading-text], a[data-loading-text]').click(function () {
	    var btn = $(this).button('loading');
	    setTimeout(function () {
	        btn.button('reset');
	    }, 3000);
	});

	// A标签 TITLE 提示
    $('a').tooltip({
        animation: false,
        placement: 'bottom',
        trigger: 'hover'
    });

	//点击后 清除 INPUT HAS-ERROR 的状态
	$("input[type='password'],input[type='text'],input[type='email']").click(function(){
		hidePopOnly($(this));
	});

    //全局 改用DIALOG 打开弹出窗口
    $("a[id='showWindow']").on('click', function(){
    	// var olddata = $(this).attr('href');
    	// var controller = olddata.split('/');
    	// data = olddata.replace(controller[2], "dialog");
    	var data = $(this).attr('data-dialog');
    	if (!data) {
    		var data = $(this).attr('href');
    	}
    	showWindow(data);
    	return false;
    });

	//所有 a 标签可以用 ajax
	//$("a").bind('click', function(){
	//	var id = $(this).attr("id");
	//	var href = $(this).attr('href');
	//	var isajax = $(this).attr('data-ajax');
    //
	//	//不使用ajax
	//	if (isajax == 'false'){
	//		return true;
	//	}
    //
	//	//showwindow hook
	//	if (id == 'showWindow'){
	//		var data = $(this).attr('data-dialog');
	//		if (!data) {
	//			var data = href;
	//		}
	//		showWindow(data);
	//		return false;
	//	}
    //
	//	//ajax hook
	//	if (href == '#' || href == "javascript:;") {
	//		return true;
	//	}else{
	//		var title = $(this).text();
	//		//change click class
	//		var clickClass = $(this).parent().attr('click-class');
	//		if (clickClass){
	//			$(this).parent().siblings("li").removeClass(clickClass);
	//			$(this).parent().addClass(clickClass);
	//		}
	//		//is link
	//		ajaxGet(href, title);
	//	}
    //
	//	return false;
	//});


});
//ajax 浏览器后退
window.addEventListener('popstate', function(e){
	if (history.state){
		var state = e.state;
		//do something(state.url, state.title);
		ajaxGet(state.url, state.title);
	}
}, false);

//全部使用 ajax 打开的 链接
function ajaxLink(_this){
	var href = _this.attr('href');
	var title = _this.text();
	var target = _this.attr('target');
	//新窗口打开
	if (target == '_blank'){
		return true;
	}
	var clickClass = _this.parent().attr('click-class');
	if (clickClass){
		_this.parent().siblings("li").removeClass(clickClass);
		_this.parent().addClass(clickClass);
	}
	//is link
	return ajaxGet(href, title);
}

//ajax 打开页面
function ajaxGet(href, title){

	$.get(href, function(result){

		console.log(href);
		if (result){
			$('ajaxcontent').html(result);
			var stateObject = {
				"title": title,
				"url": href
			};
			window.history.pushState(stateObject,title,href);
			scrollTo("header");
			return false;
		}else{
			//失败
			return true;
		}
	});
}

function showCaptcha(str){
	//alert(str);
	if (str == 1){
		$(".login-captcha").show();
	}else{
		$(".login-captcha").hide();
	}	
}

//刷新验证码
function getCaptcha(){
	$('#captcha').html('<img src="/img/loading.gif" />');
	var time = new Date().getTime();
	$('#captcha img').attr('src', '/member/ajax/getCaptcha?' + Math.random());
	//清空框中内容
	$("input[name='verify']").val('');
}

//取消颜色 显示  表示可以输入
function hidePopOnly(_this) {
	_this.next('.help-block').remove();
	_this.parents('.form-group').removeClass('has-error');
	_this.parents('.form-group').removeClass('has-success');
	return true;
}

//销毁错误提示 并且显示 成功
function hidePopError(id){
	_this = $('input[name="'+id+'"]');
	_this.next('.help-block').remove();
	_this.parents('.form-group').removeClass('has-error');
	_this.parents('.form-group').addClass('has-success');
	return true;
}

//弹出错误
function showPopError(id, value){

 	hidePopError(id);
 	_this = $('input[name="'+id+'"]');
	_this.after("<div class='help-block'><span class=\"glyphicon glyphicon-exclamation-sign\"></span> " + value + "</div>");
    _this.parents('.form-group').removeClass('has-success');
    _this.parents('.form-group').addClass('has-error');
    // 移到最早的错误 点
    //scrollTo('.has-error');
    return false;
}

//ERROR 对话框
function errorMsg(res){
	$("#errorMsg p").html(res);
    $('#errorMsg').modal({
        backdrop: true,
        show: true
    });
}

//操作成功 对话框
function successMsg(res, url){
	var data = "/dialog/dialog/success/?res=" + encodeURI(res) + "&url=" + url;
	showWindow(data);
}


//SHOWWINDOW 调用
function showWindow(data){
	$("#dialog").html("<center class=\"loading\"><i></i></center>");
	$("#dialog").load(data);
	$('#dialog').modal({
        backdrop: true,
        show: true,
    });
	return true;
}

function scrollTo(go){
	//alert(go);
    //# 平滑滚动 a=#
    var targetOffset = $(go).offset().top - 50;
    $('html,body').animate({
        scrollTop: targetOffset
    },300);
}



function back_top(){
	$("html,body").animate({scrollTop: 0}, 800);
}
//未登陆  要求登陆弹出
function nowLogin(){
	var ref = $('input[name=ref]').val();
	var data = "/dialog/dialog/login/?ref=" + ref + '&isre=1';
	showWindow(data);
}

// 登出
function loginOut() {
	// body...
}


//浏览数、评论
function setPlus(aid, type){
	$.post("/home/ajax/setPlus",{aid:aid, type:type},function(result){
		//console.log(result);
	});
}


/**
 * 批量操作 提示
 * @return {[type]} [description]
 */
function arpgBatch(msg, name, action, type, ids) {
    //var ids = "";
    // 没有输入id时 取 选中值id
    if (!ids) {
	    $("input[name='"+name+"']").each(function(){
	        if(this.checked == true) {
	            if (ids == "") {
	                ids = this.value;
	            } else {
	                ids = ids+","+this.value;
	            }
	        }            
	    });
    }

    if (ids == "") {
    	errorMsg("您没有选择信息");  
    } else {  

    	//开始操作
    	if (window.confirm(msg + "?")){
			var ref = $('input[name=ref]').val();
			$.getJSON("/set/ajax/"+action, {ids:ids, type:type},function(result){   
				if(result.status == 'success'){
					successMsg("操作成功, 正在跳转...", ref);
				}else{
					errorMsg(result.msg);
				}
			});
    	}
    }
}