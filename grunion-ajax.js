jQuery(document).ready(function($){

	$('body').on('submit', '.contact-form', function (e) {

		var $form  = $(this),
			img_id = 'ga-loader',
			div_id = $(this).parent().attr('id');

		$(this).fadeTo('200', 0.5, function (){
			$(':submit', $(this)).fadeOut('200', function(){
				var $submitButton = $(this);
				$(this).replaceWith('<img id="'+img_id+'" src="'+grunionAjax.loadingImageUri+'"/>');
				$('#'+img_id).fadeIn('200');

				$.post( grunionAjax.ajaxUri, {
						action  : 'grunion-ajax',
						data    : $form.serialize()
					},
					function(response) {
						$(response.html).hide();
						if(response.result == 'success'){
							$('#'+div_id).slideUp('400', function(){
								$('#'+div_id).html($(response.html)).slideDown('400');
							});
						} else {
							if($('div.form-error').length == 0)
								$(response.html).prependTo($('#'+div_id)).hide().slideDown();
							else
								$('div.form-error').slideUp('400', function(){
									$(this).html($(response.html)).slideDown();
								});

							$('#'+img_id).fadeOut('200',function(){
								$(this).replaceWith($submitButton);
							})
							$submitButton.fadeIn('200');
							$form.fadeTo('200',1);
						}
					}
				);

			});
		});

		return false;
	});
});