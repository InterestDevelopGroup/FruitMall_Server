(function($) {
	$(document).ready(function(){
		$('.market_nav ul li').each(function(index){
			$(this).mouseover(function(){
				$('.market_nav ul li .item:eq('+index+')').show();
			}).mouseout(function(){
				$('.market_nav ul li .item').hide();
			});
		});

		$('.market_nav ul li .item').each(function(){
			$(this).mouseover(function(){
				$(this).show();
			}).mouseout(function(){
				$(this).hide();
			});
		});
	});
})(jQuery)