$('.flip').each(function() {
	var height = $(this).find("img").height();
	$(this).height(height);
});

$('.flip')
	.click(function() {		
        $(this).find('.card').toggleClass('flipped');
        return false;
	})
	.hover(function() {
		$(this).closest("li").find(".flippy").show();
	},function() {
		$(this).closest("li").find(".flippy").hide();		
	});