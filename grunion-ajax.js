jQuery(document).ready(function($){

	$('.contact-form').submit(function(e){

		var $form = $(this);

		$(this).fadeTo('200', 0.5, function (){
			$(':submit', $(this)).fadeOut('200', function(){
				$(this).replaceWith('<img id="ga-loader" src="'+grunionAjax.loadingImageUri+'"/>');
				$('#ga-loader').fadeIn('200');

				$.post( grunionAjax.ajaxUri, 
						{
							action  : 'grunion-ajax',
							data    : $form.serialize()
						},
						function(response) {
							$(response.html).hide();
							$('#'+$(response.html).attr('id')).fadeOut('200', function(){
								$('#'+$(response.html).attr('id')).html($(response.html));
								$('#'+$(response.html).attr('id')).fadeIn('200');
							});
						}
				);

			});
		});

		return false;
	});
});