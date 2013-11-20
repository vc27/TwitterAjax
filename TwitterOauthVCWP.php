<?php
/**
 * File Name TwitterOauthVCWP.php
 * @package WordPress
 * @subpackage ParentTheme_VC
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 00.00.13
 **/
####################################################################################################





/**
 * TwitterOauthVCWP
 *
 * @version 1.0
 * @updated 00.00.13
 **/
class TwitterOauthVCWP {
	
	
	
	/**
	 * consumer_key
	 * 
	 * @access public
	 * @var string
	 **/
	var $consumer_key = 'SPQ5dJm4AyPbLMgIqKstew';
	
	
	
	/**
	 * consumer_secret
	 * 
	 * @access public
	 * @var string
	 **/
	var $consumer_secret = 'yglYXdESbabMgmgC8PMjO5nbUPEjOMH4SyVWdkhHic';
	
	
	
	/**
	 * consumer_secret
	 * 
	 * @access public
	 * @var string
	 **/
	var $oauth_callback = null;
	
	
	
	/**
	 * have_errors
	 * 
	 * @access public
	 * @var bool
	 **/
	var $have_errors = 0;
	
	
	
	/**
	 * errors
	 * 
	 * @access public
	 * @var array
	 **/
	var $errors = array();
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function __construct() {
		
		$this->set( 'settings', new TwitterSettings() );
		
	} // end function __construct
	
	
	
	
	
	
	/**
	 * init
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function init() {
		
		if ( $this->have_oauth_settings() ) {
			
			// print_r($this);
			
		} else {
			
			$this->append_error( 'OAuth settings are missing' );
			
		}
		
	} // end function init
	
	
	
	
	
	
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
	
	
	
	
	
	
	/**
	 * append_error
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function append_error( $text ) {
		
		if ( isset( $text ) AND ! empty( $text ) ) {
			$this->set( 'have_errors', 1 );
			$this->errors[] = $text;
		}
		
	} // end function append_error
	
	
	
	
	
	
	/**
	 * set__oauth_connection
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function set__oauth_connection() {
		
		if ( ! class_exists( 'TwitterOAuth' ) ) {
			require_once('twitteroauth/twitteroauth.php');
		}
		
		$this->connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret );
		
	} // end function set__oauth_connection 
	
	
	
	
	
	
	/**
	 * set__verifier_connection
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function set__verifier_connection() {
		
		if ( ! class_exists( 'TwitterOAuth' ) ) {
			require_once('twitteroauth/twitteroauth.php');
		}
		
		$this->connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $this->option__request_token['oauth_token'], $this->option__request_token['oauth_token_secret'] );
		
	} // end function set__verifier_connection
	
	
	
	
	
	
	/**
	 * connect
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function connect() {
		
		if ( ! class_exists( 'TwitterOAuth' ) ) {
			require_once('twitteroauth/twitteroauth.php');
		}
		
		if ( class_exists( 'TwitterOAuth' ) ) {
			
			$this->set( 'token', get_option( $this->settings->option_name_prefix . "-verified_token" ) );
			
			$this->twitter = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $this->token['oauth_token'], $this->token['oauth_token_secret'] );
			return $this->twitter;
			
		} else {
			
			return false;
			
		}
		
	} // end function connect
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Functionality
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * oauth
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function oauth() {
		
		if ( isset( $this->oauth_callback ) AND ! empty( $this->oauth_callback ) ) {
			$this->set__oauth_connection();
			
			$this->set( 'request_token', $this->connection->getRequestToken( $this->oauth_callback ) );
			
			switch ( $this->connection->http_code ) {
				case 200 : 
					update_option( $this->settings->option_name_prefix . "-request_token", $this->request_token );
					$this->set( 'url', $this->connection->getAuthorizeURL( $this->request_token['oauth_token'] ) );
			    	header( "Location: $this->url" );
					exit;
					break;
				default :
					$this->append_error( 'Could not connect to Twitter. Refresh the page or try again later.' );
					break;
			
			} // end switch ( $this->connection->http_code )
			
		} else {
			
			$this->append_error( 'oauth_callback is missing' );
			
		}
		
	} // end function oauth 
	
	
	
	
	
	
	/**
	 * verifier
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function verifier() {
		
		if ( isset( $this->option__request_token['oauth_token'] ) AND ! empty( $this->option__request_token['oauth_token'] ) AND isset( $this->option__request_token['oauth_token_secret'] ) AND ! empty( $this->option__request_token['oauth_token_secret'] ) ) {
			$this->set__verifier_connection();
			$this->set( 'verified_token', $this->connection->getAccessToken( $this->oauth_verifier ) );
			
			switch ( $this->connection->http_code ) {
				case 200 : 
					update_option( $this->settings->option_name_prefix . "-verified_token", $this->verified_token );
					break;
				default :
					$this->append_error( 'Twitter did not verify your account. Please try again, now or later.' );
					break;
			
			} // end switch ( $this->connection->http_code )
			
		} else {
			
			$this->append_error( 'oauth_callback is missing' );
			
		}
		
	} // end function verifier
	
	
	
	
	
	
	/**
	 * display_errors
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function display_errors() {
		
		if ( $this->have_errors ) {
			echo "<strong>Errors</strong>";
			echo "<ul><li>";
				echo implode( '</li><li>', $this->errors );
			echo "</li></ul>";
		}
		
	} // end function display_errors
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Conditionals
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * have_oauth_settings
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function have_oauth_settings() {
		
		if ( $this->have('consumer_key') AND $this->have('consumer_secret') AND $this->have('oauth_callback') ) {
			$this->set( 'have_oauth_settings', 1 );
		} else {
			$this->set( 'have_oauth_settings', 0 );
		}
		
		return $this->have_oauth_settings;
		
	} // end function have_oauth_settings
	
	
	
	
	
	
	/**
	 * have
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function have( $key ) {
		
		if ( $this->get( $key ) ) {
			$output = 1;
			$this->set( "have_$key", $output );
		} else {
			$output = 0;
			$this->set( "have_$key", $output );
		}
		
		return $output;
		
	} // end function have
	
	
	
} // end class TwitterOauthVCWP