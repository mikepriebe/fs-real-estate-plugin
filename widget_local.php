<?php
class FSREP_Location_Widget extends WP_Widget {
	function __construct() {
		parent::WP_Widget( 'fsrep_location_widget', 'FireStorm Real Estate Locations', array( 'description' => 'Display your FireStorm Real Estate locations.' ) );
	}

	function widget( $args, $instance ) {
		global $wpdb,$ListingHomeURL;
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['fsrepwltitle'] );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 
		
			echo '<ul>';
			$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
			foreach ($Countries as $Countries) {
				$CountryListingCount = $wpdb->get_results("SELECT listing_address_country FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_country = $Countries->country_id");
				if (count($CountryListingCount) > 0) {
					echo '<li><a href="'.get_option('home').'/'.$ListingHomeURL.'/'.$Countries->country_url.'/" class="fsrepl-country">'.$Countries->country_name.'</a></li>';
					$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
					if (count($Provinces) > 0) {
						echo '<ul>';
						foreach ($Provinces as $Provinces) {
							$ProvincesListingCount = $wpdb->get_results("SELECT listing_address_province FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_province = $Provinces->province_id");
							if (count($ProvincesListingCount) > 0) {
								echo '<li><a href="'.get_option('home').'/'.$ListingHomeURL.'/'.$Countries->country_url.'/'.$Provinces->province_url.'/" class="fsrepl-province">'.$Provinces->province_name.'</a></li>';
								if ($instance['fsrepwlhidecities'] != 'Yes') {
									$Cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $Provinces->province_id ORDER BY city_name");
									if (count($Cities) > 0) {
										echo '<ul>';
										foreach ($Cities as $Cities) {
											$CitiesListingCount = $wpdb->get_results("SELECT listing_address_city FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_city = $Cities->city_id");
											if (count($CitiesListingCount) > 0) {
												echo '<li><a href="'.get_option('home').'/'.$ListingHomeURL.'/'.$Countries->country_url.'/'.$Provinces->province_url.'/'.$Cities->city_url.'/" class="fsrepl-city">'.$Cities->city_name.'</a></li>';
											}
										}
										echo '</ul>';
									}
								}
							}
						}
						echo '</ul>';
					}
				}
			}
			echo '</ul>';
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['fsrepwltitle'] = strip_tags($new_instance['fsrepwltitle']);
		$instance['fsrepwlhidecities'] = strip_tags($new_instance['fsrepwlhidecities']);
		return $instance;
	}

	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'fsrepwltitle' ] );
			$hidecities = esc_attr( $instance[ 'fsrepwlhidecities' ] );
		}
		else {
			$title = __( 'Search by Location', 'text_domain' );
			$hidecitieschecked = '';
		}
		
		$hidecitieschecked = ''; if ($hidecities == 'Yes') { $hidecitieschecked = 'checked'; }
		?>
		<p>
		<label for="<?php echo $this->get_field_id('fsrepwltitle'); ?>"><?php _e('Title:'); ?></label><input class="widefat" id="<?php echo $this->get_field_id('fsrepwltitle'); ?>" name="<?php echo $this->get_field_name('fsrepwltitle'); ?>" type="text" value="<?php echo $title; ?>" /><br />
		<label for="<?php echo $this->get_field_id('fsrepwlhidecities'); ?>"><?php _e('Hide Cities:'); ?></label><input id="<?php echo $this->get_field_id('fsrepwlhidecities'); ?>" name="<?php echo $this->get_field_name('fsrepwlhidecities'); ?>" type="checkbox" value="Yes" <?php echo $hidecitieschecked; ?> /><br />
		</p>
		<?php 
	}

} 

add_action( 'widgets_init', create_function( '', 'register_widget("FSREP_Location_Widget");' ) );
?>