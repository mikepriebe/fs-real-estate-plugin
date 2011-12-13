<?php
// fs_categories_page() displays the page content for the first submenu of the custom Shopping Cart menu
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

function missing_settings($title) {
		return '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column"><b>'.$title.'</b></th>
		</tr>
		</thead>
		<tbody>
		<td style="height: 300px;">This feature is currently disabled. To purchase this extended feature, visit <a href="http://www.firestorminteractive.com/wordpress/real-estate/" target="_blank">www.firestorminteractive.com/wordpress/real-estate/</a>.</td>
		</tbody></table><br />';
}

function fsrep_settings() {
	global $FSREPconfig,$wpdb;
	
	if (!isset($_GET['f'])) {
		$SettingsPage = 'general';
	} else {
		$SettingsPage = $_GET['f'];
	}
	
	echo '<div class="wrap">';
	echo '<form name="fsrep-settings" action="#" method="POST">';
	echo '<h2>FireStorm Real Estate Plugin Settings</h2>';
	echo '<div class="nav-tabs-nav">';
	echo '<div class="nav-tabs-arrow nav-tabs-arrow-left" style="display: none; "></div>';
	echo '<div class="nav-tabs-wrapper">';
	echo '<div class="nav-tabs" style="padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; margin-right: -148px; margin-left: 0px; ">';
	echo '<span class="nav-tab'; if ($SettingsPage == 'general') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'general') { echo ' font-weight: bold;'; } echo '">General</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'googlemaps') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=googlemaps" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'googlemaps') { echo ' font-weight: bold;'; } echo '">Google Map</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'mlsidx') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=mlsidx" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'mlsidx') { echo ' font-weight: bold;'; } echo '">MLS/IDX</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'memberships') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=memberships" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'memberships') { echo ' font-weight: bold;'; } echo '">Memberships</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'payments') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=payments" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'payments') { echo ' font-weight: bold;'; } echo '">Accepting Payments</a></span>';
	echo '</div>';
	echo '</div>';
	echo '<div class="nav-tabs-arrow nav-tabs-arrow-right" style="display: none; "></div>';
	echo '</div>';
	if ($SettingsPage == 'general') {
		include("admin_settings_general.php");
	} elseif ($SettingsPage == 'googlemaps') {
		include("admin_settings_google.php");
	} elseif ($SettingsPage == 'mlsidx') {
		if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/mlsidx/admin_settings_mlsidx.php')) { require_once("members/admin_settings_mlsidx.php"); } else { echo missing_settings('MLS/IDX Settings'); }
	} elseif ($SettingsPage == 'memberships') {
		if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/members/admin_settings_memberships.php')) { require_once("members/admin_settings_memberships.php"); } else { echo missing_settings('Membership Settings'); }
	} elseif ($SettingsPage == 'payments') {
		if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/members/admin_settings_payments.php')) { require_once("members/admin_settings_payments.php"); } else { echo missing_settings('Payment Settings'); }
	}

	echo '</form>';
	echo '</div>';
}
?>