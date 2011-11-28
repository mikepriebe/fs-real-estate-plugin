<?php
function fsrep_local_widget_init() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ) {
		return;
	}
	function fsrep_local_widget($args) {
		global $wpdb;
		extract($args);
		$options = get_option('fsrep_local_widget');
		$fsrep_title = empty($options['title']) ? $options['fsrep-local-title'] : $options['title'];
		if ($fsrep_title  == '') { $fsrep_title = 'Search by Location'; }
		echo $before_widget;
		echo $before_title;
		echo $fsrep_title;
		echo $after_title;
    echo '<ul>';
		$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
    foreach ($Countries as $Countries) {
			$CountryListingCount = $wpdb->get_results("SELECT listing_address_country FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_country = $Countries->country_id");
			if (count($CountryListingCount) > 0) {
				echo '<li><a href="'.get_option('home').'/listings/'.$Countries->country_url.'/" class="fsrepl-country">'.$Countries->country_name.'</a></li>';
				$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
				if (count($Provinces) > 0) {
					echo '<ul>';
					foreach ($Provinces as $Provinces) {
						$ProvincesListingCount = $wpdb->get_results("SELECT listing_address_province FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_province = $Provinces->province_id");
						if (count($ProvincesListingCount) > 0) {
							echo '<li><a href="'.get_option('home').'/listings/'.$Countries->country_url.'/'.$Provinces->province_url.'/" class="fsrepl-province">'.$Provinces->province_name.'</a></li>';
							if ($options['fsrephidecities'] != 'Yes') {
								$Cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $Provinces->province_id ORDER BY city_name");
								if (count($Cities) > 0) {
									echo '<ul>';
									foreach ($Cities as $Cities) {
										$CitiesListingCount = $wpdb->get_results("SELECT listing_address_city FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_city = $Cities->city_id");
										if (count($CitiesListingCount) > 0) {
											echo '<li><a href="'.get_option('home').'/listings/'.$Countries->country_url.'/'.$Provinces->province_url.'/'.$Cities->city_url.'/" class="fsrepl-city">'.$Cities->city_name.'</a></li>';
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
	function fsrep_local_widget_control() {
		$options = get_option('fsrep_local_widget');
		if (isset($options['title'])) {
			$fsrep_title = htmlspecialchars($options['title'], ENT_QUOTES);
		}
		$Checked = ''; if ($options['fsrephidecities'] == 'Yes') { $Checked = ' checked'; } 
    echo '<p><label for="fsrep-local-title">Title:<br /><input id="fsrep-local-title" name="fsrep-local-title" type="text" value="'.$options['fsrep-local-title'].'" style="width: 200px" /></label></p>';
		echo '<p>Don\'t Display Cities: <input type="checkbox" name="fsrephidecities" value="Yes"'.$Checked.' /></p>';
		if (isset($_POST['fsrep-local-title'])){
			if (!isset($_POST['fsrephidecities'])) { $_POST['fsrephidecities'] = 'No'; }
			$options['fsrephidecities'] = attribute_escape($_POST['fsrephidecities']);
			$options['fsrep-local-title'] = attribute_escape($_POST['fsrep-local-title']);
			update_option('fsrep_local_widget', $options);
		}
	}
	register_sidebar_widget('FSREP Location Categories Widget', 'fsrep_local_widget');
	register_widget_control('FSREP Location Categories Widget', 'fsrep_local_widget_control');
}
add_action('plugins_loaded', 'fsrep_local_widget_init');
?>