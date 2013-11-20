<?php
/**
 * File Name initiate.php
 * @package WordPress
 * @subpackage ParentTheme_VC
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 00.00.13
 **/
#################################################################################################### */


if ( ! defined('TwitterAjax_INIT') ) {
	
	class TwitterSettings {
		
		/**
		 * oauth_param
		 * 
		 * @access public
		 * @var object
		 **/
		var $oauth_param = 'start_twitter_oauth';
		
		
		
		/**
		 * option_name_prefix
		 * 
		 * @access public
		 * @var string
		 **/
		var $option_name_prefix = '_twitter';
		
		
		
		/**
		 * transient_timeout
		 * 
		 * @access public
		 * @var string
		 **/
		var $transient_timeout = 180; // 3min
		
		
		
		/**
		 * transient_name
		 * 
		 * @access public
		 * @var string
		 **/
		var $transient_name = 'twitter-ajax-widget';
		
	};
	
	// Widget Classes
	require_once( "TwitterOptionsPageVCWP.php" );
	require_once( "TwitterAjaxVCWP.php" );
	require_once( "TwitterWidgetVCWP.php" );
	require_once( "TwitterDoAjaxVCWP.php" );
	
	define( 'AjaxSearch_INIT', true );
	
} // end if ( ! defined('TwitterAjax_INIT') )