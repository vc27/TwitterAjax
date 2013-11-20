<?php
/**
 * File Name TwitterAjaxVCWP.php
 * @package WordPress
 * @subpackage ParentTheme_VC
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 00.00.13
 **/
####################################################################################################





/**
 * TwitterAjaxVCWP
 *
 * @version 1.0
 * @updated 00.00.13
 **/
$TwitterAjaxVCWP = new TwitterAjaxVCWP();
class TwitterAjaxVCWP {
	
	
	
	/**
	 * Option name
	 * 
	 * @access public
	 * @var string
	 * Description:
	 * Used for various purposes when an import may be adding content to an option.
	 **/
	var $option_name = false;
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function __construct() {
		
		$this->set( 'template_directory', get_stylesheet_directory_uri() . "/addons/" . basename(__DIR__) );

		// add_action( 'after_setup_theme', array( &$this, 'after_setup_theme' ) );
        
		add_action( 'init', array( &$this, 'init' ) );

		// add_action( 'admin_init', array( &$this, 'admin_init' ) );
		
		add_action( 'widgets_init', array( &$this, 'widgets_init' ) );

	} // end function __construct
	
	
	
	
	
	
	/**
	 * after_setup_theme
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 *
	 * @codex http://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
	 **/
	function after_setup_theme() {
		
		// 
		
	} // end function after_setup_theme
	
	
	
	
	
	
	/**
	 * init
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 * @codex http://codex.wordpress.org/Plugin_API/Action_Reference/init
	 * 
	 * Description:
	 * Runs after WordPress has finished loading but before any headers are sent.
	 **/
	function init() {
		
		$this->register_style_and_scripts();
		
		add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ) );
		
	} // end function init
	
	
	
	
	
	
	/**
	 * admin_init
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 * @codex http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
	 * 
	 * Description:
	 * admin_init is triggered before any other hook when a user access the admin area.
	 * This hook doesn't provide any parameters, so it can only be used to callback a 
	 * specified function.
	 **/
	function admin_init() {
		
		// 
		
	} // end function admin_init
	
	
	
	
	
	
	/**
	 * Widgets Initiate
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function widgets_init() {
		
		register_widget( 'TwitterWidgetVCWP' );
		
	} // end function widgets_init
	
	
	
	
	
	
	/**
	 * set
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function set( $key, $val = false ) {
		
		if ( isset( $key ) AND ! empty( $key ) ) {
			$this->$key = $val;
		}
		
	} // end function set
	
	
	
	
	
	
	/**
	 * get
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function get( $key ) {
		
		if ( isset( $key ) AND ! empty( $key ) AND isset( $this->$key ) AND ! empty( $this->$key ) ) {
			return $this->$key;
		} else {
			return false;
		}
		
	} // end function get
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Functionality
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * Register Styles and Scripts
	 *
	 * @version 1.6
	 * @updated 02.11.13
	 **/
	function register_style_and_scripts() {
		
		wp_register_script( 'tweetsMC', "$this->template_directory/js/tweetsMC.js" );
		
	} // end function register_style_and_scripts
	
	
	
	
	
	
	/**
	 * Enqueue Scripts
	 *
	 * @version 1.4
	 * @updated 11.18.12
	 **/
	function wp_enqueue_scripts() {
		
		wp_enqueue_script( 'tweetsMC' );
		
	} // function wp_enqueue_scripts
	
	
	
} // end class TwitterAjaxVCWP