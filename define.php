<?php

// GET CONFIG VALUSE
$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
while($FSREPconfigsql = mysql_fetch_array($sql)) {
	$FSREPconfig[$FSREPconfigsql['config_name']] = $FSREPconfigsql['config_value'];
}

// SET PERMISSION LEVEL
$FSREPListingsPerm = 10;

// FIND PAGE IDS
$LPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-listings]%' AND post_status = 'publish' LIMIT 1");
$LOPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-local]%' AND post_status = 'publish' LIMIT 1");
if ($FSREPMembers == TRUE) { $MAPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-my-account]%' AND post_status = 'publish' LIMIT 1"); } else { $MAPageID = 0; }

// GET LISTING CREDENTIALS
$ListingID = 0;
$CityID = 0;
$ProvinceID = 0;
$CountryID = 0;
$ShowGoogleMap = FALSE;
$ListingHomeURL = $wpdb->get_var("SELECT post_name FROM ".$wpdb->prefix."posts WHERE ID = ".$FSREPconfig['ListingsPageID']);
$RequestURI = explode('/'.$ListingHomeURL.'/', substr($_SERVER['REQUEST_URI'], 0, -1));
if (!isset($RequestURI[1])) { $RequestURI[1] = ''; }
if (isset($RequestURI[1]) || substr($_SERVER['REQUEST_URI'],-9) == 'listings/' || substr($_SERVER['REQUEST_URI'],-9) == '/listings') {
	$ShowGoogleMap = TRUE;
	if (is_numeric(substr($RequestURI[1], 0, 1))) {
		$ListingID = explode('-', $RequestURI[1]);
		$ListingID = $ListingID[0];
		$CityID = $wpdb->get_var("SELECT listing_address_city FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID");
		$ProvinceID = $wpdb->get_var("SELECT listing_address_province FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID");
		$CountryID = $wpdb->get_var("SELECT listing_address_country FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID");
	} elseif (substr($RequestURI[1],0,7) != 'compare' && $RequestURI[1] != 'search') {
		if ($RequestURI[1] != '') {
			$CP = explode('/', $RequestURI[1]);
			if (isset($CP[2])) {
				$CityID = $wpdb->get_var("SELECT city_id FROM ".$wpdb->prefix."fsrep_cities WHERE city_url = '".$CP[2]."'");
			}
			if (isset($CP[1])) {
				$ProvinceID = $wpdb->get_var("SELECT province_id FROM ".$wpdb->prefix."fsrep_provinces WHERE province_url = '".$CP[1]."'");
			}
			if (isset($CP[0])) {
				$CountryID = $wpdb->get_var("SELECT country_id FROM ".$wpdb->prefix."fsrep_countries WHERE country_url = '".$CP[0]."'");
			}
		}
	}
}

$ListingPageID = get_page($FSREPconfig['ListingsPageID']);
if ($ListingPageID->post_content != '[fsrep-listings]' && !isset($_POST['submit']) && !isset($_GET['listingpagefix']) && !isset($_GET['pagecheck'])) {
	if(!function_exists('fsrep_listing_page_warning')) {
	function fsrep_listing_page_warning() {
		echo '<div class="updated fade"><p><strong>The FireStorm Real Estate Plugin Listing Page Must Contain The Following Shortcode: [fsrep-listings]</strong> <a href="admin.php?page=fsrep_settings&listingpagefix">Click here to automatically update the listing to contain the correct shortcode</a>.</p></div>';
	}
	}
	add_action('admin_notices', 'fsrep_listing_page_warning');
	return;
}

if ($FSREPconfig['GoogleMapAPI'] == '' && $FSREPconfig['GoogleMap'] == 1 && !isset($_POST['submit'])) {
	if(!function_exists('fsrep_google_api_warning')) {
	function fsrep_google_api_warning() {
		echo '<div class="updated fade"><p><strong>Google Maps API Key Required: </strong> <a href="admin.php?page=fsrep_settings&f=googlemaps">Click here</a> to enter your Google Maps API Key. An API Key can be obtained by logging into your account on <a href="http://code.google.com/apis/maps/signup.html" target="_blank">http://code.google.com/apis/maps/signup.html</a></p></div>';
	}
	}
	add_action('admin_notices', 'fsrep_google_api_warning');
	return;
}

$FSREPPermalinkStructure = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = 'permalink_structure'");
if ($FSREPPermalinkStructure == '' && !isset($_POST['submit']) && !isset($_GET['permalinks'])) {
	if(!function_exists('fsrep_permalink_warning')) {
	function fsrep_permalink_warning() {
		echo '<div class="updated fade"><p><strong>Real Estate Plugin Error: </strong> Permalinks cannot be set to default for the FireStorm Real Estate Plugin to function.</p></div>';
	}
	}
	add_action('admin_notices', 'fsrep_permalink_warning');
	return;
}

$FSREPFOpen = ini_get('allow_url_fopen');
if ($FSREPFOpen != 1) {
	if(!function_exists('fsrep_fopen_warning')) {
	function fsrep_fopen_warning() {
		echo '<div class="updated fade"><p><strong>Real Estate Plugin Error: </strong> The plugin requires allow_url_fopen to be turned ON however your server has this function turned OFF. This function is used to obtain listing coordinates for Google Maps. To turn this function on, add "allow_url_fopen=ON" to your php.ini file or contact your hosting provider.</p></div>';
	}
	}
	add_action('admin_notices', 'fsrep_fopen_warning');
	return;
}

// DIRECTORIES & PERMISSIONS
if (!file_exists(ABSPATH.'wp-content/uploads/') ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/agents") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/agents/temp") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/small") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/large") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/temp") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/additional") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/additional/small") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/additional/large") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/additional/temp")
	 ) {
	if(!function_exists('fsrep_directory_warning')) {
	function fsrep_directory_warning() {
		echo '<div class="updated fade"><p><strong>Real Estate Plugin Error: </strong> 
		The plugin was unable to setup needed directories for listing images. Please create the following directories and CHMOD them to 777:<br />
		'.ABSPATH.'wp-content/uploads/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/agents/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/agents/temp/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/houses/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/houses/small/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/houses/large/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/houses/temp/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/houses/additional/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/houses/additional/temp/</p></div>';
	}
	}
	add_action('admin_notices', 'fsrep_directory_warning');
	return;
}

?>