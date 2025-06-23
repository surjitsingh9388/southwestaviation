$(document).ready(function() {
	$('.panel-collapse').on('show.bs.collapse', function () {
		$(this).parents('.faq-box').addClass('active');

	});

	$('.panel-collapse').on('hide.bs.collapse', function () {
		$(this).parents('.faq-box').removeClass('active');

	});
});