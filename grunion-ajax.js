jQuery(document).ready(function($){

	$('.contact-form').submit(function(e){

		var $form  = $(this),
			img_id = 'ga-loader',
			div_id = $(this).parent().attr('id');

		$(this).fadeTo('200', 0.5, function (){
			$(':submit', $(this)).fadeOut('200', function(){
				$(this).replaceWith('<img id="'+img_id+'" src="'+grunionAjax.loadingImageUri+'"/>');
				$('#'+img_id).fadeIn('200');

				$.post( grunionAjax.ajaxUri, 
						{
							action  : 'grunion-ajax',
							data    : $form.serialize()
						},
						function(response) {
							$(response.html).hide();
							$('#'+div_id).fadeOut('200', function(){
								$('#'+div_id).html($(response.html));
								$('#'+div_id).fadeIn('200');
							});
						}
				);

			});
		});

		return false;
	});
});