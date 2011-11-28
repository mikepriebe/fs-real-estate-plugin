<?php

// GET CONFIG VALUSE
$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
while($FSREPconfigsql = mysql_fetch_array($sql)) {
	$FSREPconfig[$FSREPconfigsql['config_name']] = $FSREPconfigsql['config_value'];
}

// FIND PAGE IDS
$LPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-listings]%' AND post_status = 'publish' LIMIT 1");
$LOPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-local]%' AND post_status = 'publish' LIMIT 1");

// NO GOOGLE MAPS API WARNING 
if ($FSREPconfig['Google Map API'] == '' && !isset($_POST['submit'])) {
	if(!function_exists('fsrep_google_api_warning')) {
	function fsrep_google_api_warning() {
		echo '<div class="updated fade"><p><strong>Google Maps API Key Required: </strong> <a href="admin.php?page=fsrep_settings">Click here</a> to enter your Google Maps API Key. An API Key can be obtained by logging into your account on <a href="http://code.google.com/apis/maps/signup.html" target="_blank">http://code.google.com/apis/maps/signup.html</a></p></div>';
	}
	}
	add_action('admin_notices', 'fsrep_google_api_warning');
	return;
}

// PERMALINKS WARNING
$FSREPPermalinkStructure = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = 'permalink_structure'");
if ($FSREPPermalinkStructure == '' && !isset($_POST['submit'])) {
	if(!function_exists('fsrep_permalink_warning')) {
	function fsrep_permalink_warning() {
		echo '<div class="updated fade"><p><strong>Real Estate Plugin Error: </strong> Permalinks cannot be set to default for the FireStorm Real Estate Plugin to function.</p></div>';
	}
	}
	add_action('admin_notices', 'fsrep_permalink_warning');
	return;
}
?>