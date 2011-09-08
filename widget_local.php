<?php
/*
Plugin Name: FSREP Location Categories
Plugin URI: http://www.firestorminteractive.com/wordpress/real-estate/
Description: This is a widget for the real estate plugin created by Wes Fernley @ FireStorm Interactive.
Author: Wes Fernley
Version: 1.100
Author URI: http://www.firestorminteractive.com/
*/

function fsrep_local_widget_init() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ) {
		return;
	}
	function fsrep_local_widget($args) {
		global $wpdb;
		extract($args);
		$options = get_option('fsrep_local_widget');
		$fsrep_title = empty($options['title']) ? 'Search By Location' : $options['title'];
		echo $before_widget;
		echo $before_title;
		echo $fsrep_title;
		echo $after_title;
    echo '<ul>';
		$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
    foreach ($Countries as $Countries) {
			$CountryListingCount = $wpdb->get_results("SELECT listing_address_country FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_country = $Countries->country_id");
			if (count($CountryListingCount) > 0) {
				echo '<li><a href="'.get_option('home').'/local/'.$Countries->country_url.'/">'.$Countries->country_name.'</a></li>';
				$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
				if (count($Provinces) > 0) {
					echo '<ul>';
					foreach ($Provinces as $Provinces) {
						$ProvincesListingCount = $wpdb->get_results("SELECT listing_address_province FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_province = $Provinces->province_id");
						if (count($ProvincesListingCount) > 0) {
							echo '<li><a href="'.get_option('home').'/local/'.$Provinces->province_url.'/">'.$Provinces->province_name.'</a></li>';
							$Cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $Provinces->province_id ORDER BY city_name");
							if (count($Cities) > 0) {
								echo '<ul>';
								foreach ($Cities as $Cities) {
									$CitiesListingCount = $wpdb->get_results("SELECT listing_address_city FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_city = $Cities->city_id");
									if (count($CitiesListingCount) > 0) {
										echo '<li><a href="'.get_option('home').'/local/'.$Cities->city_url.'/">'.$Cities->city_name.'</a></li>';
									}
								}
								echo '</ul>';
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
	function fsrep_local_widget_control() {
		$options = get_option('fsrep_local_widget');
		$fsrep_title = htmlspecialchars($options['title'], ENT_QUOTES);
		echo '<div>There are no options for this widget.</div>';
	}
	register_sidebar_widget('FSREP Location Categories Widget', 'fsrep_local_widget');
	register_widget_control('FSREP Location Categories Widget', 'fsrep_local_widget_control');
}
add_action('plugins_loaded', 'fsrep_local_widget_init');
?>