(function($) {
	$(document).ready(function(){
		$('#btn_sub').click(function(){
			if($('#content').val() == ''){
				alert('请输入你的评论内容！');
				return false;
			}
		});
		$('#comment_form').ajaxForm(function(data) { 
	        if(data.status == 1){
	            alert(data.info);
	            window.location.reload();
	        }else if(data.status == 2){
	            alert(data.info);
	        }else{
	        	alert(data.info);
	        }
	    }); 
	});
})(jQuery)