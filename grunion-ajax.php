<?php
/*
Plugin Name: Grunion Ajax
Plugin URI: http://wordpress.org/extend/plugins/grunion-ajax/
Description: Using Grunion Contact Form? Make form submissions slick with Grunion Ajax.
Author: Brent Shepherd
Version: 0.1
Author URI: http://find.brentshepherd.com
License: GPLv2 or later
*/

Grunion_Ajax::init();

class Grunion_Ajax {

	/**
	 * URL to the directory housing Chosen Javascript files.
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
	 * If the post/page contains a select element, enqueue the chosen & jquery scripts.
	 */
	public static function maybe_enqueue_scripts() {

		$grunion_handle = 'grunion-ajax';

		if( self::contains_grunion_shortcode() ) {
			wp_enqueue_script( $grunion_handle, self::$grunion_dir_url . '/grunion-ajax.js', array( 'jquery' ) );
		}

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


	/**
	 * Handles the submission of the grunion contact form, in lieu of there being a function within Grunion Contact Form
	 * that saves the data (it's done as part of the shortcode).
	 */
	public static function handle_form_submission(){
		global $post;

		// Setup $_POST to appear as if the form had been submitted in the traditional way... all sorts of fudging
		parse_str( $_POST['data'], $data );
		$_POST = array_merge( $_POST, $data );
		unset( $_POST['action'] );
		unset( $_POST['data'] );
		$_REQUEST['_wpnonce'] = $_POST['_wpnonce'];

		// Setup the post global for Grunion
		$post = get_post( $_POST['contact-form-id'] );

		$content = do_shortcode( $post->post_content );

		error_log('content = ' . print_r( $content, true ) );

		$response = array( 'success' => 'true', 'html' => $content );

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

		if( empty( $content ) && is_object( $post ) )
			$content = $post->post_content;

		if( strpos( $content, '[contact-form' ) )
			return true;
		else
			return false;
	}

}