<?php
/**
 * File Name TwitterWidgetVCWP.php
 * @package WordPress
 * @subpackage ParentTheme_VC
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 06.05.13
 **/
#################################################################################################### */






/**
 * TwitterWidgetVCWP
 **/
class TwitterWidgetVCWP extends WP_Widget {
	
	
	
	
	/**
	 * __construct
	 **/
	function __construct() {		
		
		$this->set( 'name', __( 'TweetsMC' ) );
		$this->set( 'id', 'tweets-mc' );
		$this->set( 'count', 1 );
		$this->set( 'count_option', 5 );
		// $this->set( 'username', 'metacake' );
		$this->set( 'loading_image', home_url() . "/wp-admin/images/loading.gif" );
		
		$this->set( 'control_ops', array(
			// 'width' => 400,
			// 'height' => 350,
			'id_base' => $this->id
			) );
		
		$this->set( 'widget_ops', array(
			'classname' => $this->id,
			'description' => __('Twitter Tweets.')
			) );
		
		
		$this->WP_Widget( $this->id, $this->name, $this->widget_ops, $this->control_ops );
		
		
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
	 * Widget 
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function widget( $args, $instance ) {
		
		if ( isset( $instance['count'] ) AND ! empty( $instance['count'] ) ) {
			echo $args['before_widget'];
				echo "<div id=\"twitter-mc-widget\" class=\"twitter-mc-widget\" data-tweet-count=\"" . $instance['count'] . "\" data-nonce=\"" . wp_create_nonce('twitter-ajax') . "\" data-switch_case=\"tweets\">";
					echo "<p class=\"loading\"><img src=\"$this->loading_image\" alt=\"\" /> Loading...</p>";
				echo "</div>";
			echo $args['after_widget'];
		}  	
		
	} // end function widget
	
	
	
	
	
	
	/**
	 * Update Widget data
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		// Update Instance
		$instance['count'] = $new_instance['count'];
		
		return $instance;
		
	} // end function update
	
	
	
	
	
	
	/**
	 * Widget Form
	 *
	 * @version 1.0
	 * @updated 00.00.13
	 **/
	function form( $instance ) {
		
		// Set form defaults
		$defaults = array(
			'count' => $this->count,
			// 'username' => $this->username,
		);
		
		$r = wp_parse_args( $instance, $defaults );
		extract( $r, EXTR_SKIP );
		
		
		/*
		<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
		</p>
		*/
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count:'); ?></label>
			<select style="width:125px;" name="<?php echo $this->get_field_name('count'); ?>">
			<?php
			
			for ( $i = 1; $i < $this->count_option; $i++  ) {
				
				if ( $i == $count )
					$sel = 'selected="selected"';
				else
					$sel = '';
					
				echo "<option $sel value=\"$i\">$i</option>";
			
			}
			
			?>
			</select>
		</p>
		
		<?php
	
	} // end function form



} // end class TwitterWidgetVCWP extends WP_Widget