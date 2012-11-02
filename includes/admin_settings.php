<?php
function missing_settings($title) {
		return '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column"><b>'.$title.'</b></th>
		</tr>
		</thead>
		<tbody>
		<td style="height: 300px;">This feature is currently disabled. To purchase this extended feature, visit <a href="http://www.firestormplugins.com/extensions/real-estate/" target="_blank">www.firestormplugins.com/extensions/real-estate/</a>.</td>
		</tbody></table><br />';
}

function fsrep_settings() {
	global $FSREPconfig,$wpdb;
	
	echo '<div class="wrap">';
	echo '<form name="fsrep-settings" action="#" method="POST">';
	echo '<h2>FireStorm Real Estate Plugin Settings</h2>';
	
	if (isset($_GET['listingpagefix'])) {
		fssc_add_listing_page();
		$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
		while($dbfsrepconfig = mysql_fetch_array($sql)) {
			$FSREPconfig[$dbfsrepconfig['config_name']] = $dbfsrepconfig['config_value'];
		}
		echo '<div id="message" class="updated fade"><p><strong>Your listings page has been corrected.</strong></p></div>';
	}
	
	if (!isset($_GET['f'])) {
		$SettingsPage = 'general';
	} else {
		$SettingsPage = $_GET['f'];
	}
	
	echo '<div class="nav-tabs-nav">';
	echo '<div class="nav-tabs-arrow nav-tabs-arrow-left" style="display: none; "></div>';
	echo '<div class="nav-tabs-wrapper">';
	echo '<div class="nav-tabs" style="padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; margin-right: -148px; margin-left: 0px; ">';
	echo '<span class="nav-tab'; if ($SettingsPage == 'general') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'general') { echo ' font-weight: bold;'; } echo '">General</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'googlemaps') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=googlemaps" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'googlemaps') { echo ' font-weight: bold;'; } echo '">Google Map</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'styling') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=styling" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'styling') { echo ' font-weight: bold;'; } echo '">Styling</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'memberships') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=memberships" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'memberships') { echo ' font-weight: bold;'; } echo '">Memberships</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'payments') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=payments" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'payments') { echo ' font-weight: bold;'; } echo '">Accepting Payments</a></span>';
	echo '<span class="nav-tab'; if ($SettingsPage == 'mlsidx') { echo ' nav-tab-active'; } echo '"><a href="admin.php?page=fsrep_settings&f=mlsidx" style="text-decoration: none; color: #333333;'; if ($SettingsPage == 'mlsidx') { echo ' font-weight: bold;'; } echo '">MLS/IDX</a></span>';
	echo '</div>';
	echo '</div>';
	echo '<div class="nav-tabs-arrow nav-tabs-arrow-right" style="display: none; "></div>';
	echo '</div>';
	if ($SettingsPage == 'general') {
		include("admin_settings_general.php");
	} elseif ($SettingsPage == 'googlemaps') {
		include("admin_settings_google.php");
	} elseif ($SettingsPage == 'styling') {
		if (function_exists('fsrep_pro_styling_settings')) { fsrep_pro_styling_settings(); } else { echo missing_settings('Styling'); }
	} elseif ($SettingsPage == 'mlsidx') {
		if (function_exists('fsrep_mls_settings')) { fsrep_mls_settings(); } else { echo missing_settings('MLS / IDX'); }
	} elseif ($SettingsPage == 'memberships') {
		if (function_exists('fsrep_membership_settings')) { fsrep_membership_settings(); } else { echo missing_settings('Membership'); }
	} elseif ($SettingsPage == 'payments') {
		if (function_exists('fsrep_payment_settings')) { fsrep_payment_settings(); } else { echo missing_settings('Payments'); }
	}

	echo '</form>';
	echo '</div>';
}
?>