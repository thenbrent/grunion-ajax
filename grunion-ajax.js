jQuery(document).ready(function($){
	console.log('Grunion Ajax loaded');

	/* ---------------------------------- */
	/* Set Image Background */
	$('.contact-form').submit(function(e){

		$(this).fadeTo( '200', 0.5 );

		console.log(e);
		console.log($(this).serialize());

		$.post( grunionAjax.ajaxUri, 
				{
					action  : 'grunion-ajax',
					data    : $(this).serialize()
//					_ajax_nonce	  : motion.nonce,
//					post_id		  : motion.post_id,
//					context		  : context,
//					attachment_id : attachment_id
				}, 
				function(response) {
					console.log('responded');
					console.log(response);
				}
		);
		return false;
	});
});