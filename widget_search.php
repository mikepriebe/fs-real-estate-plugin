<?php
class FSREP_Search_Widget extends WP_Widget {
	function __construct() {
		parent::WP_Widget( 'fsrep_search_widget', 'FireStorm Real Estate Search', array( 'description' => 'FireStorm Real Estate listing search form.' ) );
	}

	function widget( $args, $instance ) {
		global $wpdb,$FSREPconfig,$ListingHomeURL;
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['fsrepwstitle'] );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . fsrep_text_translator('FireStorm Real Estate Plugin', $title.' Label', $title) . $after_title; } 
		$FSREPSearchForm = new stdClass();
		$FSREPSearchForm->Name = 'fsrep_search_widget_form';
		$FSREPSearchForm->ID = 'fsrep_search_widget_form';
		$FSREPSearchForm->Action = get_option('home').'/'.$ListingHomeURL.'/search/';
		$FSREPSearchForm->Method = 'POST';
		$FSREPSearchForm->Abrv = 'fsrepw';
		
		echo fsrep_search_form($FSREPSearchForm);		
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['fsrepwstitle'] = strip_tags($new_instance['fsrepwstitle']);
		return $instance;
	}

	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'fsrepwstitle' ] );
		}
		else {
			$title = __( 'Search Listings', 'text_domain' );
		}
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id('fsrepwstitle'); ?>"><?php _e('Title:'); ?></label><input class="widefat" id="<?php echo $this->get_field_id('fsrepwstitle'); ?>" name="<?php echo $this->get_field_name('fsrepwstitle'); ?>" type="text" value="<?php echo $title; ?>" /><br />
		</p>
		<?php 
	}

} 

add_action( 'widgets_init', create_function( '', 'register_widget("FSREP_Search_Widget");' ) );
?>