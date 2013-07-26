<?php
function fsrep_get_map_locations() {
	global $wpdb;
	$Array = array('Use Custom Longitude and Latitude' => '');
	$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
	foreach($Countries as $Countries) {
		$Array = array_merge($Array, array($Countries->country_name => 'country-'.$Countries->country_id));
		$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
		if (count($Provinces) > 0) {
			foreach ($Provinces as $Provinces) {
				$Array = array_merge($Array, array(' - '.$Provinces->province_name => 'province-'.$Provinces->province_id));
				$Cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $Provinces->province_id ORDER BY city_name");
				if (count($Cities) > 0) {
					foreach ($Cities as $Cities) {
						$Array = array_merge($Array, array(' --- '.$Cities->city_name => 'city-'.$Cities->city_id));
					}
				}
			}
		}
	}
	return $Array;
}

	if (isset($_POST['submit'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['GoogleMap'])."' WHERE config_name = 'GoogleMap'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['MapCenterLat'])."' WHERE config_name = 'MapCenterLat'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['MapCenterLong'])."' WHERE config_name = 'MapCenterLong'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['MapCenterZoom'])."' WHERE config_name = 'MapCenterZoom'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['DefaultMapLocation'])."' WHERE config_name = 'DefaultMapLocation'");

		$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
		while($dbfsrepconfig = mysql_fetch_array($sql)) {
			$FSREPconfig[$dbfsrepconfig['config_name']] = $dbfsrepconfig['config_value'];
		}
	}
	

	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Google Map Settings Label', 'Google Map Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column"><input type="submit" name="submit" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Settings Label', 'Update Settings').'"></th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_selectbox('Enable Google Map', 'GoogleMap', $FSREPconfig['GoogleMap'], array('Yes' => '1', 'No' => '0'), '', '');
	fsrep_print_admin_selectbox('Default Map Location', 'DefaultMapLocation', $FSREPconfig['DefaultMapLocation'], fsrep_get_map_locations(), '', 'Enter a default map location or select "Use Custom Longitude and Latitude" to use the coordinates below.');
	fsrep_print_admin_input('Google Map Default Zoom', 'MapCenterZoom', $FSREPconfig['MapCenterZoom'], 30, '');
	fsrep_print_admin_input('Google Map Default Lat', 'MapCenterLat', $FSREPconfig['MapCenterLat'], 30, 'To obtain Google Map Coordinates, use this tool: <a href="http://www.gorissen.info/Pierre/maps/googleMapLocation.php" target="_blank">http://www.gorissen.info/Pierre/maps/googleMapLocation.php</a>');
	fsrep_print_admin_input('Google Map Default Long', 'MapCenterLong', $FSREPconfig['MapCenterLong'], 30, 'To obtain Google Map Coordinates, use this tool: <a href="http://www.gorissen.info/Pierre/maps/googleMapLocation.php" target="_blank">http://www.gorissen.info/Pierre/maps/googleMapLocation.php</a>');
	echo '</tbody></table>';
	if (function_exists('fsrep_pro_gmap_settings')) { fsrep_pro_gmap_settings(); } else {
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'ID Label', 'ID').'Google Map Settings</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column"><input type="submit" name="submit" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Google Map Settings Label', 'Update Google Map Settings').'"></th>
		</tr>
		</thead></table>';
	}
	
?>