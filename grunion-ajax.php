<?php
/*
Plugin Name: Grunion Ajax
Plugin URI: http://wordpress.org/extend/plugins/grunion-ajax/
Description: Using Grunion Contact Form? Make form submission slick with Grunion Ajax.
Author: Brent Shepherd
Version: 1.2
Author URI: http://find.brentshepherd.com
License: GPLv2 or later
*/

Grunion_Ajax::init();

class Grunion_Ajax {

	/**
	 * URL to the directory housing Grunion Ajax files.
	 */
	public static $grunion_dir_url;

	/**
	 * Setup the class variables & hook functions.
	 */
	public static function init() {

		self::$grunion_dir_url    = plugins_url( '', __FILE__ );

		add_action( 'wp_print_scripts', __CLASS__ . '::maybe_enqueue_scripts' );

		add_action( 'wp_ajax_grunion-ajax', __CLASS__ . '::handle_form_submission' );
		add_action( 'wp_ajax_nopriv_grunion-ajax', __CLASS__ . '::handle_form_submission' );
	}


	/**
	 * If the post/page contains a Grunion Contact Form, enqueue the ajax submission script.
	 */
	public static function maybe_enqueue_scripts() {

		if ( self::contains_grunion_shortcode() ){
			$grunion_handle = 'grunion-ajax';

			wp_enqueue_script( $grunion_handle, self::$grunion_dir_url . '/grunion-ajax.js', array( 'jquery' ) );
			$object_name    = 'grunionAjax';
			$script_data    = array( 
				'loadingImageUri' => self::$grunion_dir_url . '/loader.gif',
				'ajaxUri'         => admin_url( 'admin-ajax.php' )
			);

			if( function_exists( 'wp_add_script_data' ) ) // WordPress 3.3 and newer
				wp_add_script_data( $grunion_handle, $object_name, $script_data );
			else
				wp_localize_script( $grunion_handle, $object_name, $script_data );
		}
	}

	/**
	 * Handles the submission of the grunion contact form, in lieu of there being a function within Grunion Contact Form
	 * that saves the data (it's done as part of the shortcode).
	 */
	public static function handle_form_submission(){
		global $post;

		// Setup $_POST & $_REQUEST to appear as if the form had been submitted in the traditional way... yep, all sorts of fudge
		parse_str( $_POST['data'], $data );
		$_POST = array_merge( $_POST, $data );
		unset( $_POST['action'] );
		unset( $_POST['data'] );
		$_REQUEST['_wpnonce'] = $_POST['_wpnonce'];

		// Setup the post global for Grunion
		$post = get_post( $_POST['contact-form-id'] );

		// Dirty dirty hack to work around Grunion's style printing that breaks ajax
		$old_request = $_REQUEST['action'];
		$_REQUEST['action'] = 'grunion_shortcode_to_json';

		$content = do_shortcode( wpautop( $post->post_content ) );

		// Now we can restore the real action
		$_REQUEST['action'] = $old_request;

		// We have an error in the contact form
		if ( strpos( $content, 'form-errors' ) !== false ) {
			$result   = 'error';
			preg_match( '%((\<ul class=\'form-errors)(.|' . PHP_EOL . ')*?\</ul\>)%', $content, $matches );
			$content  = "<div class='form-error'><h3>" . __( 'Error!' ) . "</h3>";
			$content .= $matches[0];
			$content .= "</div>";
		} else {
			$result   = 'success';
			preg_match( '%((\<div id=\'contact-form)(.|' . PHP_EOL . ')*?\</div\>)%', $content, $matches );
			$content = $matches[0];
		}

		$content = apply_filters( 'grunion_ajax_confirmation', $content );

		$response = array( 'result' => $result, 'html' => $content );

		$response = json_encode( $response );

		header( "Content-Type: application/json" );
		echo $response;

		exit();
	}

	/**
	 * Checks the post content to see if it contains a select element. 
	 */
	private static function contains_grunion_shortcode( $content = '' ){
		global $post;

		if ( empty( $content ) && is_object( $post ) ) {
			$content = $post->post_content;
		}

		if ( strpos( $content, '[contact-form' ) !== false ) {
			return true;
		} else {
			return false;
		}
	}
}