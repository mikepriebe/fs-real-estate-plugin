<?php
	if (isset($_POST['submit'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['GoogleMapAPI'])."' WHERE config_name = 'GoogleMapAPI'");
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
		<th scope="col" class="manage-column" width="200"><b>Google Map Settings</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column"><input type="submit" name="submit" class="button-primary" value="Update Google Map Settings" style="padding: 3px 8px;"></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>Google Map Settings</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column"><input type="submit" name="submit" class="button-primary" value="Update Google Map Settings" style="padding: 3px 8px;"></th>
		</tr>
		</tfoot>
		<tbody>';
	fsrep_print_admin_input('Google Map API', 'GoogleMapAPI', $FSREPconfig['GoogleMapAPI'], 30, 'An API Key can be obtained by logging into your account on <a href="http://code.google.com/apis/maps/signup.html" target="_blank">http://code.google.com/apis/maps/signup.html</a>');
	fsrep_print_admin_selectbox('Default Map Location', 'DefaultMapLocation', $FSREPconfig['DefaultMapLocation'], fsrep_get_map_locations(), '', 'Enter a default map location or select "Use Custom Longitude and Latitude" to use the coordinates below.');
	fsrep_print_admin_input('Google Map Default Zoom', 'MapCenterZoom', $FSREPconfig['MapCenterZoom'], 30, '');
	fsrep_print_admin_input('Google Map Default Lat', 'MapCenterLat', $FSREPconfig['MapCenterLat'], 30, 'To obtain Google Map Coordinates, use this tool: <a href="http://www.gorissen.info/Pierre/maps/googleMapLocation.php" target="_blank">http://www.gorissen.info/Pierre/maps/googleMapLocation.php</a>');
	fsrep_print_admin_input('Google Map Default Long', 'MapCenterLong', $FSREPconfig['MapCenterLong'], 30, 'To obtain Google Map Coordinates, use this tool: <a href="http://www.gorissen.info/Pierre/maps/googleMapLocation.php" target="_blank">http://www.gorissen.info/Pierre/maps/googleMapLocation.php</a>');
	echo '</tbody></table><br />';
	
	// ABQIAAAAt8J5VWVWOjIan3ToZ6pQMBRSv4hzC16PZ1ly1sk3EtatFt35LxQ2Cbffc14nyw72cFiHdrV2WP2N1A
	
?>