(function($) {
	$(document).ready(function(){
		$('.trade_list ul li').each(function(){
			$(this).hover(function(){
				$(this).find('.contact_us').addClass('on');
			},function(){
				$(this).find('.contact_us').removeClass('on');
			});
		});
	});
})(jQuery)