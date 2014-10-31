(function($) {
	$(document).ready(function(){
		$('.list_main').cycle({
	    	fx:     'scrollHorz', 
	        prev:   '#prev', 
	        next:   '#next', 
	        timeout: 0, 
	        rev: true 
		});
	});
})(jQuery)