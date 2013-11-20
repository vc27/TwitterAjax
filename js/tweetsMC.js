/**
 * File Name tweetsMC.js
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 07.26.13
 **/
jQuery(document).ready(function($) {

	tweetsMC.displayTweet();

});



/**
 * tweetsMC
 * @version 1.0
 * @updated 07.26.13
 **/
var tweetsMC = {
	
	
	
	/**
	 * params
	 **/
	targetElement : '.twitter-mc-widget',
	url : 'https://api.twitter.com/1/statuses/user_timeline',
	action : 'twitter-ajax',
	
	
	
	/**
	 * variables
	 **/
	response : false,
	
	
	
	/**
	 * displayTweet
	 * 
	 * version 1.0
	 * updated 00.00.13
	 **/
	displayTweet : function( params ) {
		
		tweetsMC.setParams( params );
		tweetsMC.retrieveTweets();
		
	}, // end displayTweet : function
	
	
	
	/**
	 * retrieveTweets
	 * 
	 * version 1.0
	 * updated 00.00.13
	 **/
	retrieveTweets : function() {
		
		jQuery('body').find(tweetsMC.targetElement).each(function(i) {
			var tweet = jQuery(this);
			
			jQuery.post( siteObject.ajaxurl, {
				action : tweetsMC.action,
				nonce : tweet.attr('data-nonce'),
				switch_case : tweet.attr('data-switch_case'),
				username : tweet.attr('data-username'),
				count : tweet.attr('data-tweet-count')
			}, function( response ) {
				
				if ( 'success' == response.status && response.have_tweets == 1 ) {
					tweet.html(response.html);
					tweetsMC.tweetShare();
				} else {
					tweet.parent().hide();
				}				

			}, 'json' );
		});
		
	}, // end retrieveTweets : function
	
	
	
	/**
	 * tweetShare
	 *
	 * @version 1.0
	 * @updated 01.04.13
	 **/
	tweetShare : function() {
		
		jQuery('.tweet-footer a').click(function(event) {
			tweetsMC._openWindow( jQuery(this).attr('href'), 400, 300 );
			event.preventDefault();
		});
		
	}, // end tweetShare : function
	
	
	
	/**
	 * Open Window
	 *
	 * @version 1.0
	 * @updated 01.04.13
	 **/
	_openWindow : function( href, w, h ) {
		
		window.open(
			href,
			'Share',
			'fullscreen=no,height='+h+',width='+w+',location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no',
			false );
		
	}, // end _openWindow : function
	
	
	
	// ##################################################
	/**
	 * Setters
	 **/
	// ##################################################
	
	
	
	/**
	 * setParams
	 * 
	 * version 1.0
	 * updated 00.00.13
	 **/
	setParams : function( params ) {
		
		if ( typeof params != 'undefined' ) {
			
			if ( typeof params.targetElement != 'undefined' ) {
				tweetsMC.targetElement = params.targetElement;
			}
			
		}
		
	},  // end setParams : function
	
	
	
	/**
	 * setUrl
	 * 
	 * version 1.0
	 * updated 00.00.13
	 **/
	setUrl : function(userName) {
		
		tweetsMC.url = tweetsMC.url + '/' + userName + '.json';
		
	}  // end setUrl : function
	
}; // end var tweetsMC