/* ARPG JS */
$(document).ready(function() {

	//全局 搜索
	$("#arpg_search").click(function(){
		//alert(1);
		var query = $("#query").val();
		var s_url = $("#search_url").val();
		if (query){
			window.open(s_url+query, "_self");	
		}
	});

});