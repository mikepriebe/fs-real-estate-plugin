<?php
// fs_categories_page() displays the page content for the first submenu of the custom Shopping Cart menu
function fsrep_settings() {
	global $FSREPconfig,$wpdb;
	
	if (isset($_POST['submit'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['GoogleMapAPI'])."' WHERE config_name = 'Google Map API'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['MapCenterLat'])."' WHERE config_name = 'Map Center Lat'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['MapCenterLong'])."' WHERE config_name = 'Map Center Long'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['MapCenterZoom'])."' WHERE config_name = 'Map Center Zoom'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingNameDisplay'])."' WHERE config_name = 'Listing Name Display'");
		$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
		while($dbfsrepconfig = mysql_fetch_array($sql)) {
			$FSREPconfig[$dbfsrepconfig['config_name']] = $dbfsrepconfig['config_value'];
		}
	}
	echo '<div class="wrap">';
	echo '<form name="fsrep-settings" action="#" method="POST">';
	echo '<h2>FireStorm Real Estate Plugin Settings</h2>';
	echo '<p>&nbsp;</p>';
	echo '<h3>Settings</h3>';
	echo '<p>';
	fsrep_print_input('Google Map API', 'GoogleMapAPI', $FSREPconfig['Google Map API'], 40);
	fsrep_print_input('Google Map Center Lat', 'MapCenterLat', $FSREPconfig['Map Center Lat'], 20);
	fsrep_print_input('Google Map Center Long', 'MapCenterLong', $FSREPconfig['Map Center Long'], 20);
	fsrep_print_input('Google Map Center Zoom', 'MapCenterZoom', $FSREPconfig['Map Center Zoom'], 20);
	echo '</p>';
	echo '<p>To obtain Google Map Coordinates, use this tool: <a href="http://www.gorissen.info/Pierre/maps/googleMapLocation.php" target="_blank">http://www.gorissen.info/Pierre/maps/googleMapLocation.php</a></p>';
	echo '<p>&nbsp;</p>';
	echo '<h3>Listings Displays</h3>';
	echo '<p>';
	fsrep_print_input('Listing Name Display', 'ListingNameDisplay', $FSREPconfig['Listing Name Display'], 60);
	echo '</p>';
	echo '<p><strong>Examples include:</strong><br />listing_label, listing_price, listing_address_number, listing_address_street, listing_address_city, listing_address_province, listing_address_country, listing_address_postal, listing_bedrooms, listing_bathrooms, listing_kitchens, listing_property_type, and text. </p>';
	echo '<p>&nbsp;</p>';
	echo '<input type="submit" name="submit" value="Update Settings">';
	echo '</form>';
	echo '</div>';
}
?>