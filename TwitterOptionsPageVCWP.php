<?php
/**
 * File Name TwitterOptionsPageVCWP.php
 * @package WordPress
 * @subpackage ParentTheme_VC
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 03.11.13
 **/
####################################################################################################





/**
 * TwitterOptionsPageVCWP
 *
 * @version 1.0
 * @updated 02.16.13
 **/
$TwitterOptionsPageVCWP = new TwitterOptionsPageVCWP();
class TwitterOptionsPageVCWP {
	
	
	
	/**
	 * option_name
	 * 
	 * @access public
	 * @var string
	 **/
	var $option_name = 'twitter_page_options';
	
	
	
	/**
	 * oauth
	 * 
	 * @access public
	 * @var object
	 **/
	var $oauth = null;
	
	
	
	
	
	
	/**
	 * parent_slug
	 * 
	 * @access public
	 * @var string
	 **/
	var $parent_slug = 'options-general.php';
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function __construct() {
		
		$this->set( 'settings', new TwitterSettings() );

		// hook method after_setup_theme
		add_action( 'after_setup_theme', array( &$this, 'after_setup_theme' ) );

		// hook method init
		// add_action( 'init', array( &$this, 'init' ) );

		// hook method admin_init
		// add_action( 'admin_init', array( &$this, 'admin_init' ) );

	} // end function __construct
	
	
	
	
	
	
	/**
	 * after_setup_theme
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 *
	 * @codex http://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
	 **/
	function after_setup_theme() {
		
		$this->redirect_twitter_oauth();
		
		$this->add_options_page();
		$this->add_actions_for_options();
		
		global $twitter_page_options;
		if ( ! isset( $twitter_page_options ) ) {
			$twitter_page_options = get_option("_$this->option_name");
		}
		
	} // end function after_setup_theme
	
	
	
	
	
	
	/**
	 * set
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function set( $key, $val = false ) {
		
		if ( isset( $key ) AND ! empty( $key ) ) {
			$this->$key = $val;
		}
		
	} // end function set
	
	
	
	
	
	
	/**
	 * set_oauth
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function set_oauth() {
        
		if ( ! class_exists( 'TwitterOauthVCWP' ) ) {
			require_once( "TwitterOauthVCWP.php" );
		}

		if ( class_exists( 'TwitterOauthVCWP' ) ) {

			$this->oauth = new TwitterOauthVCWP();
			
			$this->oauth->set( 'oauth_callback', home_url() . "/wp-admin/$this->parent_slug?page=$this->option_name-admin-page&recieve_twitter_oauth=1" );
			$this->oauth->set( 'oauth_connect_url', home_url() . "/wp-admin/$this->parent_slug?admin-page=$this->option_name-admin-page&" . $this->settings->oauth_param . "=1&oauth_callback=" . urlencode( $this->oauth->oauth_callback ) );
			
		}

	} // end function set_oauth
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Functionality
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * add_options_page
	 *
	 * @version 1.0
	 * @updated 03.18.13
	 **/
	function add_options_page() {
		
		$this->options_page = create__options_page( array(

			'version' => '1.0',

			'option_name' => "_$this->option_name",
			'option_group' => $this->option_name,

			'add_submenu_page' => array(
				'parent_slug' => $this->parent_slug,
				'page_title' => 'Twitter Options',
				'menu_title' => 'Twitter Options',
				'capability' => 'administrator',
				),

			// 'options_page_title' => false,
			// 'options_page_desc' => 'Options page description and general information here.',

			// Metaboxs and Optionns
			'options' => array(
                
				// Default Metabox and Options
				'oauth' => array(

					// Metabox
					'meta_box' => array(
						'title' => 'Twitter OAuth',
						'context' => 'normal',
						'priority' => 'core',
						'desc' => 'Twitter authentication is required for the use of this addon.',
						'callback' => array( &$this, 'custom_meta_box_option' ),
						'save_all_settings' => 'Save', // uses value as button text & sanitize_title_with_dashes(save_all_settings) for value
						),

					// settings and options
					'settings' => array(

						// Single setting and option
						'test' => array(
							'type' => 'blank',
							'validation' => 'blank',
							'title' => 'Blank',
							),
						'title' => array(
							'type' => 'text',
							'validation' => 'text',
							'title' => 'Title',
							),
						'content' => array(
							'type' => 'text_editor',
							'validation' => 'text_editor',
							'title' => 'Text Editor',
							),
						),
					), // end Default Metabox and Options
				
				
				
				// Capture Wine Experiences
				/*'general-two' => array(

					// Metabox
					'meta_box' => array(
						'title' => 'General Two',
						'context' => 'normal',
						'priority' => 'core',
						// 'desc' => 'Description.',
						// 'callback' => array( &$this, 'custom_meta_box_option' ),
						'save_all_settings' => 'Save', // uses value as button text & sanitize_title_with_dashes(save_all_settings) for value
						),

					// settings and options
					'settings' => array(

						// Single setting and option
						'title' => array(
							'type' => 'text',
							'validation' => 'text',
							'title' => 'Title',
							),
						'entry' => array(
							'type' => 'simple_text_editor',
							'validation' => 'text_editor',
							'title' => 'Content',
							),
						'image' => array(
							'type' => 'image',
							'validation' => 'text',
							'title' => 'Featured Image',
							'desc' => 'please pre-size your images, and be aware that the images are custom placed on the page. Custom placement will mean that swapping images will require you to use relatively sized canvas.',
							),
						),
					), // end Default Metabox and Options*/

				),

			) ); // end default_settings array
	} // end function add_options_page
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Functionality
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * Add Settings Field
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function add_actions_for_options() {
		
		add_action( "_$this->option_name-add_settings_field", array( &$this, 'add_settings_field' ), 10, 2 );
		add_action( "_$this->option_name-sanitize-option", array( &$this, 'sanitize_callback' ), 10, 2 );

	} // end function add_actions_for_options






	/**
	 * Options Version Update
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 *
	 * ToDo:
	 * Add switch case for version control
	 **/
	function options_version_update( $settings ) {

		// nothing here yet

	} // end function options_version_update






	/**
	 * Add Settings Field
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function add_settings_field( $field, $raw_option ) {
		
		if ( is_array( $field ) AND ! empty( $field ) ) {
			extract( $field, EXTR_SKIP );
		} else {
			return;
		}
		
		// Options
		if ( isset( $field['options'] ) AND ! empty( $field['options'] ) ) {
			$options = $field['options'];
		} else {
			$options = false;
		}
		
		// Desc
		if ( isset( $field['desc'] ) AND ! empty( $field['desc'] ) ) {
			$desc = $field['desc'];
		} else {
			$desc = false;
		}
		
		// Desc
		if ( isset( $field['val'] ) AND ! empty( $field['val'] ) ) {
			$val = $field['val'];
		} else {
			$val = false;
		}
		
		switch ( $type ) {

			case "blank" :
				echo "<input type=\"text\" name=\"$name\" value=\"$val\" id=\"$id\" class=\"large-text\">";
				if ( $desc ) echo "<p class=\"description\">$desc</p>";
				break;

		}

	} // end function add_settings_field






	/**
	 * Sanitize Callback
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function sanitize_callback( $new_option, $option_args ) {

		switch ( $option_args['validation'] ) {

			case "blank" :
				$new_option = "$new_option-blank";
				break;

		}

		return $new_option;

	} // end function sanitize_callback






	/**
	 * Create Post meta form, Meta box content
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function custom_meta_box_option( $options, $metabox ) {
		
		$this->set_oauth();
		if ( isset( $this->oauth ) AND ! empty( $this->oauth ) ) {
			
			$this->oauth->init();
			if ( $this->oauth->have_errors ) {
				
				 $this->oauth->display_errors();
				
			} else if ( $this->recieve_twitter_oauth() ) {
				
				$this->oauth->set( 'option__request_token', get_option( $this->settings->option_name_prefix . "-request_token" ) );				
				$this->oauth->set( 'oauth_verifier', $_REQUEST['oauth_verifier'] );
				
				if ( isset( $this->oauth->option__request_token ) AND ! empty( $this->oauth->option__request_token ) ) {
					
					$this->oauth->verifier();
					if ( $this->oauth->have_errors ) {
						
						$this->oauth->display_errors();
						
					} else {
						
						echo "Your account was verified.";
						
					}
					
				}
				
			} else if ( $this->have_verified_token() ) {
				
				echo "<p class=\"description\">Your account is verified and ready to use.</p>";
				
				/*
				$this->set( 'twitter', $this->oauth->connect() );
				$this->twitter->get( 'users/show', array( 'screen_name' => $this->oauth->token['screen_name'] ) );
				$this->set( 'twitter_account', fetch__data( 'get', $this->twitter->url ) );
				$this->set( 'statuses', $this->twitter_account->data['status'] );
				print_r($this->statuses);
				*/
				
			} else {
				
				echo "<a href=\"" . $this->oauth->oauth_connect_url . "\">Authenticate Twitter</a>";
				
			}
		
		}

	} // end function custom_meta_box
	
	
	
	
	
	
	/**
	 * redirect_twitter_oauth
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function redirect_twitter_oauth() {
		
		if ( is_admin() AND isset( $_GET[$this->settings->oauth_param] ) AND $_GET[$this->settings->oauth_param] == 1 ) {
			
			$this->set_oauth();
			if ( isset( $this->oauth ) AND ! empty( $this->oauth ) ) {
				
				$this->oauth->oauth();
				print_r($this); die();
				
			}
				
		}
		
	} // end function redirect_twitter_oauth 
	
	
	
	
	
	
	/**
	 * recieve_twitter_oauth
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function recieve_twitter_oauth() {
		
		if ( isset( $_GET['recieve_twitter_oauth'] ) AND $_GET['recieve_twitter_oauth'] == 1 AND isset( $_REQUEST['oauth_verifier'] ) AND ! empty( $_REQUEST['oauth_verifier'] ) ) {
			$output = 1;				
		} else {
			$output = 0;
		}
		
		return $output;
		
	} // end function recieve_twitter_oauth
	
	
	
	
	
	
	/**
	 * have_verified_token
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function have_verified_token() {
		
		$this->set( 'verified_token', get_option( $this->settings->option_name_prefix . "-verified_token" ) );
		
		if ( isset( $this->verified_token ) AND ! empty( $this->verified_token ) ) {
			$this->set( 'have_verified_token', 1 );
		} else {
			$this->set( 'have_verified_token', 0 );
		}
		
		return $this->have_verified_token;
		
	} // end function have_verified_token
	
	
	
} // end class TwitterOptionsPageVCWP