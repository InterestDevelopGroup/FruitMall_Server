(function($) {
	$(document).ready(function(){
		$('.nav ul li#brand').mouseover(function(){
			$('.nav ul li#brand .item').show();
			$(this).addClass('active');
		}).mouseout(function(){
			$('.nav ul li#brand .item').hide();
			$(this).removeClass('active');
		});

		$('.nav ul li#brand .item').mouseover(function(){
			$(this).show();
		}).mouseout(function(){
			$(this).hide();
		});
	});
})(jQuery)