<?php
/**
 * File Name TwitterDoAjaxVCWP.php
 * @package WordPress
 * @subpackage ParentTheme_VC
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 00.00.13
 **/
####################################################################################################




/**
 * TwitterDoAjaxVCWP
 *
 * @version 1.0
 * @updated 00.00.13
 **/
$TwitterDoAjaxVCWP = new TwitterDoAjaxVCWP();
class TwitterDoAjaxVCWP {
	
	
	
	/**
	 * msg__default_error
	 * 
	 * @access public
	 * @var string
	 **/
	var $msg__error_default = 'Invalid ajax call';
	
	
	
	/**
	 * msg__default_error
	 * 
	 * @access public
	 * @var string
	 **/
	var $msg__error_nonce = 'Invalid nonce';
	
	
	
	/**
	 * action
	 * 
	 * @access public
	 * @var string
	 **/
	var $action = 'twitter-ajax';
	
	
	
	/**
	 * url_pattern
	 * 
	 * @access public
	 * @var string
	 **/
	var $url_pattern = '/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/';
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function __construct() {
		
		$this->set( 'settings', new TwitterSettings() );
		add_action( "wp_ajax_$this->action", array( &$this, 'do_ajax' ) );
		add_action( "wp_ajax_nopriv_$this->action", array( &$this, 'do_ajax' ) );

	} // end function __construct
	
	
	
	
	
	
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
	 * set__response
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 *
	 * Description:
	 * This function is used to add a new key=value
	 * pair to the response variable. The response variable
	 * is echoed at the end of the process with json_encode.
	 * Any key=value pair added to the response will be available
	 * in the jQuery response.
	 **/
	function set__response( $key, $val = false ) {
		
		if ( isset( $key ) AND ! empty( $key ) ) {
			$this->response[$key] = $val;
		}
		
	} // end function set__response
	
	
	
	
	
	
	/**
	 * set__response_html
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function set__response_html( $val = false ) {
		
		if ( isset( $val ) AND ! empty( $val ) ) {
			$this->response['html'] .= $val;
		}
		
	} // end function set__response_html
	
	
	
	
	
	
	/**
	 * set__case
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function set__case() {
		
		if ( isset( $_REQUEST['switch_case'] ) AND ! empty( $_REQUEST['switch_case'] ) ) {
			$this->set( 'case', $_REQUEST['switch_case'] );
		}
		
	} // end function set__case
	
	
	
	
	
	
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

			$this->set( 'oauth', new TwitterOauthVCWP() );
			
		} else {
			
			$this->set( 'oauth', false );
			
		}

	} // end function set_oauth
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Functionality
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * do_ajax
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function do_ajax() {
		
		$this->set__response( 'status', 'error' );
		$this->set__response( 'message', $this->msg__error_default );
		
		if ( $this->is_doing_ajax() ) {
			
			$this->set__response( 'message', $this->msg__error_nonce );
			
			if ( $this->have_switch_case() AND $this->have_nonce() ) {
				$this->set__case();
				$this->set( '_request', $_POST );
				
				switch ( $this->case ) {
					
					case "tweets" :
						$this->twitter();
						break;
					
				} // end switch ( $_POST['switch_case'] )
			
			} // end if varify
			
			header( 'Content: application/json' );
			echo json_encode( $this->response );

			die();
		
		} // end if DOING_AJAX
		
	} // end function do_ajax
	
	
	
	
	
	
	/**
	 * twitter
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function twitter() {
		
		$response = get_transient( $this->settings->transient_name );
		if ( isset( $response ) AND ! empty( $response ) ) {
			
			$this->set( 'response', $response );
			$this->set__response( 'is_transient', 1 );
			
		} else {
			
			$this->set__response( 'is_transient', 0 );
			$this->set_oauth();
			if ( $this->oauth != false ) {

				$this->set( 'twitter', $this->oauth->connect() );
				$this->twitter->get( 'statuses/user_timeline', array( 
					'screen_name' => $this->oauth->token['screen_name'],
					'count' => $this->_request['count'],
					'exclude_replies' => true
					) );

			} else {

				$this->set( 'twitter', false );

			} // end if ( $this->oauth != false )
			
			
			
			if ( $this->have_twitter() ) {

				$this->set__response( 'html', '' );

				$twitter = fetch__data(
					'get', // $type
					$this->twitter->url, // $url 
					array(),
					false, // $this->settings->transient_name, // $transient_name
					false // true // $reset_transient
				);

				if ( isset( $twitter->data ) AND is_array( $twitter->data ) AND ! empty( $twitter->data ) ) {
					
					$this->set( 'tweets', $twitter->data );
					$this->set( 'have_tweets', 1 );
					$this->set( 'userdata', $twitter->data[0]->user );
					$this->set( 'tweet_count', count( $this->tweets ) );
					$this->set__response( 'have_tweets', 1 );

					if ( file_exists( get_stylesheet_directory() . "/addons/TwitterAjax/images/" . $this->userdata->screen_name . ".png" ) ) {
						$this->set( 'avatar', get_stylesheet_directory_uri() . "/addons/TwitterAjax/images/" . $this->userdata->screen_name . ".png" );
					} else {
						$this->set( 'avatar', str_replace( '_normal', '', $this->userdata->profile_image_url ) );
					}

				} else {
					
					$this->set( 'have_tweets', 0 );
					$this->set__response( 'have_tweets', 0 );
					
				} // end if ( isset( $twitter->data ) )

				if ( $this->have_tweets ) {

					$this->set__response( 'status', 'success' );
					$this->set__response( 'message', '<h3>Results found</h3>' );

					$this->set__response_html("<div class=\"tweet-header\">");
						$this->set__response_html("<a href=\"http://twitter.com/" . $this->userdata->screen_name . "\">");
							$this->set__response_html("<span class=\"profile-image-url\"><img src=\"" . $this->avatar . "\" alt=\"\" /></span>");
							$this->set__response_html("<span class=\"screen-name\">@" . $this->userdata->screen_name . "</span>");
						$this->set__response_html("</a>");
					$this->set__response_html("</div>");

					$this->set__response_html("<ul>");

					foreach ( $this->tweets as $tweet ) {

						$tweet->time_stamp = "<span class=\"time-stamp\"><img class=\"img-comment-tip-left\" src=\"" . get_stylesheet_directory_uri() . "/images/img-comment-tip-left.png\" alt=\"\" /><img class=\"img-ribbon-right\" src=\"" . get_stylesheet_directory_uri() . "/images/img-ribbon-right.png\" alt=\"\" />" . date( 'n-j', strtotime( $tweet->created_at ) ) . "</span>";

						// $tweet->text = htmlentities( $tweet->text, ENT_QUOTES );
						$tweet->text = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '<a href="http://$1" target="_blank">http://$1</a>', $tweet->text );
						$tweet->text = preg_replace('/https:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '<a href="https://$1" target="_blank">https://$1</a>', $tweet->text );
						$tweet->text = preg_replace('/@([a-z0-9_]+)/i', '<a href="http://twitter.com/$1" target="_blank">@$1</a>', $tweet->text );
						$tweet->text = preg_replace('/#([a-z0-9_]+)/i', '<a href="http://twitter.com/search?q=%23$1" target="_blank">#$1</a>', $tweet->text );

						$this->set__response_html( "<li>" . $tweet->text . " " . $tweet->time_stamp . "</li>" );

					} // end foreach ( tweet )

					$this->set__response_html( "</ul>" );

					if ( $this->tweet_count > 0 AND $this->tweet_count < 2 ) {

						$this->set__response_html( "<div class=\"tweet-footer\">" );
							$this->set__response_html( "<a target=\"_blank\" class=\"icon-reply tweet-reply\" href=\"https://twitter.com/intent/tweet?in_reply_to=$tweet->id\"></a>" );
							$this->set__response_html( "<a target=\"_blank\" class=\"icon-retweet tweet-retweet\" href=\"https://twitter.com/intent/retweet?tweet_id=$tweet->id\"></a>" );
							$this->set__response_html( "<a target=\"_blank\" class=\"icon-star tweet-favorite\" href=\"https://twitter.com/intent/favorite?tweet_id=$tweet->id\"></a>" );
						$this->set__response_html( "</div>" );

					}

					$this->set__response_html( "<div class=\"tweet-follow\">" );
						$this->set__response_html( "<a target=\"_blank\" href=\"https://twitter.com/" . $this->userdata->screen_name . "\"><span class=\"icon-twitter\"></span> Follow Me</a>" );
					$this->set__response_html( "</div>" );

					set_transient( $this->settings->transient_name, $this->response, $this->settings->transient_timeout );

				} else {

					$this->set__response( 'status', 'success' );
					$this->set__response( 'message', '<h3>No tweets</h3>' );

				} // end if ( $this->have_tweets )

			} else {

				$this->set__response( 'status', 'error' );
				$this->set__response( 'message', 'put your special message here' );

			} // end if ( $this->have_twitter() )
			
		}
		
	} // end function twitter
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Conditionals
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * have_twitter
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function have_twitter() {
		
		if ( isset( $this->twitter ) AND ! empty( $this->twitter ) ) {
			$this->set( 'have_twitter', 1 );
		} else {
			$this->set( 'have_twitter', 0 );
		}
		
		return $this->have_twitter;
	
	} // end function have_search
	
	
	
	
	
	
	/**
	 * is_doing_ajax
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function is_doing_ajax() {
		
		if ( defined( 'DOING_AJAX') AND DOING_AJAX ) {
			$this->set( 'is_doing_ajax', 1 );
		} else {
			$this->set( 'is_doing_ajax', 0 );
		}
		
		return $this->is_doing_ajax;
	
	} // end function is_doing_ajax 
	
	
	
	
	
	
	/**
	 * have_switch_case
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function have_switch_case() {
		
		if ( isset( $_POST['switch_case'] ) AND ! empty( $_POST['switch_case'] ) ) {
			$this->set( 'have_switch_case', 1 );
		} else {
			$this->set( 'have_switch_case', 0 );
		}
		
		return $this->have_switch_case;
	
	} // end function have_switch_case
	
	
	
	
	
	
	/**
	 * have_nonce
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function have_nonce() {
		
		if ( isset( $_POST['nonce'] ) AND ! empty( $_POST['nonce'] ) AND wp_verify_nonce( $_POST['nonce'], $this->action ) ) {
			$this->set( 'have_nonce', 1 );
		} else {
			$this->set( 'have_nonce', 0 );
		}
		
		return $this->have_nonce;
	
	} // end function have_nonce
	
	
	
} // end class TwitterDoAjaxVCWP