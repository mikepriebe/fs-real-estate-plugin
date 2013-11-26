<?php

// GET CONFIG VALUSE
$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
if ($sql) {
	while($FSREPconfigsql = mysql_fetch_array($sql)) {
		$FSREPconfig[$FSREPconfigsql['config_name']] = $FSREPconfigsql['config_value'];
	}
}
if (!isset($FSREPconfig['Theme']) || $FSREPconfig['Theme'] == '') { $FSREPconfig['Theme'] = 'default'; }

// SET THEME
if ($FSREPconfig['Theme'] == 'default') {
	$FSREPThemeDir = ABSPATH.'wp-content/plugins/fs-real-estate-plugin/themes/default/';	
} else {
	$FSREPThemeDir = ABSPATH.'wp-content/uploads/fsrep/themes/'.$FSREPconfig['Theme'].'/';
}


// SET PERMISSION LEVEL
if (!function_exists('wp_get_current_user')) {
	include(ABSPATH.'wp-includes/pluggable.php'); 
}
$FSREPCurrentUser = wp_get_current_user();
if (isset($FSREPCurrentUser->roles[0])) { $FSREPCurrentPermission = $FSREPCurrentUser->roles[0]; } else { $FSREPCurrentPermission = ''; }
$FSREPAdminPermissions = 'administrator';
$FSREPMemberPermissions = 'subscriber'; if (function_exists('fsrep_member_role')) { $FSREPMemberPermissions = fsrep_member_role(); }

// FIND PAGE IDS
$LPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-listings]%' AND post_status = 'publish' LIMIT 1");
if ($FSREPExtensions['Membership'] == TRUE) { 
	$MAPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-myaccount]%' AND post_status = 'publish' LIMIT 1"); 
	$MembersPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-members]%' AND post_status = 'publish' LIMIT 1"); 
} else { 
	$MAPageID = 0; 
	$MembersPageID = 0; 
}

// CHECK REWRITE RULES
add_action('save_post', 'fsrep_check_rules');
add_action('init', 'add_fsrep_rules');

// GET LISTING CREDENTIALS
$ListingID = 0;
$CityID = 0;
$ProvinceID = 0;
$CountryID = 0;
$ShowGoogleMap = FALSE;
if (isset($FSREPconfig['ListingsPageID'])) {
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
}

$ListingPageID = get_page($FSREPconfig['ListingsPageID']);

// LABELS
if (!isset($FSREPconfig['CountryLabel'])) { $FSREPconfig['CountryLabel'] = 'Country'; }
if (!isset($FSREPconfig['ProvinceLabel'])) { $FSREPconfig['ProvinceLabel'] = 'State/Prov.'; }
if (!isset($FSREPconfig['CityLabel'])) { $FSREPconfig['CityLabel'] = 'City'; }
if (!isset($FSREPconfig['PriceRangeLabel'])) { $FSREPconfig['PriceRangeLabel'] = 'Price'; }

// SEARCH RESULTS
$FSREPSearch = '';
if (isset($_POST['fsrep-search-submit'])){
	$FSREPPostName = 'fsrep-search-';
} elseif (isset($_POST['fsrepw-widget-search-submit'])){
	$FSREPPostName = 'fsrepw-search-';
}
if (isset($FSREPPostName)){
	$ModSQL = '';
	if (function_exists('fsrep_pro_visibility')) { 
		if (fsrep_pro_visibility() == 1) {
			$ModSQL .= " listing_visibility = 1 ";
		}
	}
	if (function_exists('fsrep_member_moderation_check')) { 
		$ModSQL .= fsrep_member_moderation_check();
	}
	if (function_exists('fsrep_listing_hidden_mod')) { 
		$ModSQL .= fsrep_listing_hidden_mod();
	}
	if (function_exists('fsrep_listing_visibility_mod')) { 
		$ModSQL .= fsrep_listing_visibility_mod();
	}
	$FSREPSearch = 'SELECT * FROM '.$wpdb->prefix.'fsrep_listings WHERE ';
	if ($_POST[$FSREPPostName.'country'] != '') {
		$FSREPSearch .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_country = '.$_POST[$FSREPPostName.'country'].' AND '; // COUNTRY
		$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$_POST[$FSREPPostName.'country']);
		$GMapSLat = $GoogleCoords->country_lat;
		$GMapSLong = $GoogleCoords->country_long;
		$GMapSZoom = $GoogleCoords->country_zoom;
	}
	if (isset($_POST[$FSREPPostName.'province']) && $_POST[$FSREPPostName.'province'] != '') {
		$FSREPSearch .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_province = '.$_POST[$FSREPPostName.'province'].' AND '; // PROVINCE
		$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$_POST[$FSREPPostName.'province']);
		$GMapSLat = $GoogleCoords->province_lat;
		$GMapSLong = $GoogleCoords->province_long;
		$GMapSZoom = $GoogleCoords->province_zoom;
	}
	if (isset($_POST[$FSREPPostName.'city']) && $_POST[$FSREPPostName.'city'] != '') {
		$FSREPSearch .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_city = '.$_POST[$FSREPPostName.'city'].' AND '; // CITY
		$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = ".$_POST[$FSREPPostName.'city']);
		$GMapSLat = $GoogleCoords->city_lat;
		$GMapSLong = $GoogleCoords->city_long;
		$GMapSZoom = $GoogleCoords->city_zoom;
	}
	if (function_exists('fsrep_pro_regions_search')) { $FSREPSearch .= fsrep_pro_regions_search($FSREPPostName); }

	if ($_POST[$FSREPPostName.'price-range2'] != '0' && $_POST[$FSREPPostName.'price-range2'] != '' && $_POST[$FSREPPostName.'price-range'] != '') {
		$FSREPSearch .= ' '.$wpdb->prefix.'fsrep_listings.listing_price >= '.$_POST[$FSREPPostName.'price-range'].' AND '; // PRICE LOW
		$FSREPSearch .= ' '.$wpdb->prefix.'fsrep_listings.listing_price <= '.$_POST[$FSREPPostName.'price-range2'].' AND '; // PRICE HIGH
	}
	
	$FieldSet = FALSE;
	$SLFields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields WHERE field_search = 1 AND field_type != 'checkbox' ORDER BY field_order");
	$FieldSearchSQL = 'SELECT listing_id FROM '.$wpdb->prefix.'fsrep_listings_to_fields WHERE (';
	$FieldCount = 0;
	foreach($SLFields as $SLFields) {
		if (isset($_POST['field-'.$SLFields->field_id])) {
			if ($_POST['field-'.$SLFields->field_id] != '') {
				$FieldSet = TRUE;
				$FieldSearchSQL .= ' (field_id='.$SLFields->field_id.' and listing_value = "'.$_POST['field-'.$SLFields->field_id].'") or'; // PRICE LOW
				$FieldCount++;
			}
		}
	}

	if ($FieldCount > 0) {
		$FieldSearchSQL = substr($FieldSearchSQL, 0, -2);
		$FieldSearchSQL .= ') group by listing_id having count(field_id)>='.$FieldCount;
		$FieldSearchID = $wpdb->get_results($FieldSearchSQL);
		$FieldSearchIDs = array();
		foreach ($FieldSearchID as $FieldSearchID) {
			array_push($FieldSearchIDs, $FieldSearchID->listing_id);
		}
	}

	$SLFields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields WHERE field_search = 1 AND field_type = 'checkbox' ORDER BY field_order");
	$FieldSearchSQL = 'SELECT listing_id FROM '.$wpdb->prefix.'fsrep_listings_to_fields WHERE (';
	$FieldCount = 0;
	foreach($SLFields as $SLFields) {
		$SLFieldsArray = explode(',',$SLFields->field_value);
		$SLFieldValue = '';
		$SLFieldsArrayCount = count($SLFieldsArray);
		for($i=0;$i<$SLFieldsArrayCount;$i++) {
			if (isset($_POST['field-'.$SLFields->field_id.'-'.$i])) {
				if ($_POST['field-'.$SLFields->field_id.'-'.$i] == $SLFieldsArray[$i]) {
					$FieldSet = TRUE;
					$FieldSearchSQL .= " (field_id = ".$SLFields->field_id." AND listing_value = '".$_POST['field-'.$SLFields->field_id.'-'.$i]."') OR"; // PRICE LOW
					$FieldCount++;
				}
			}
		}
	}
	if ($FieldCount > 0) {
		$FieldSearchSQL = substr($FieldSearchSQL, 0, -2);
		$FieldSearchSQL .= ') group by listing_id';
		$FieldSearchID = $wpdb->get_results($FieldSearchSQL);
		$FieldSearchCBIDs = array();
		foreach ($FieldSearchID as $FieldSearchID) {
			array_push($FieldSearchCBIDs, $FieldSearchID->listing_id);
		}
	}
	
	
	
	if (count($FieldSearchIDs) > 0 & count($FieldSearchCBIDs) > 0) {
		$MatchedIDs = array_intersect($FieldSearchIDs, $FieldSearchCBIDs);
	} else {
		if (count($FieldSearchIDs) > 0) {
			$MatchedIDs = $FieldSearchIDs;
		} elseif (count($FieldSearchCBIDs) > 0) {
			$MatchedIDs = $FieldSearchCBIDs;
		} elseif (count($FieldSearchCBIDs) == 0 && count($FieldSearchIDs) == 0 && $FieldSet == FALSE) {
			// DO NOTHING
		} else {
			$MatchedIDs = array('99999999999999999');
		}
	}
	if (count($MatchedIDs) > 0) {
		$FieldSearchIDs = implode("', '", $MatchedIDs);
		$FSREPSearch .= " ".$wpdb->prefix."fsrep_listings.listing_id IN('".$FieldSearchIDs."') "; // PRICE LOW
	}
	
	if (substr($FSREPSearch, -4) == ' OR ') {
		$FSREPSearch = substr($FSREPSearch, 0, -4);
	}
	if (substr($FSREPSearch, -5) == ' AND ') {
		$FSREPSearch = substr($FSREPSearch, 0, -5);
	}
	if (substr($FSREPSearch, -7) == ' WHERE ') {
		$FSREPSearch = substr($FSREPSearch, 0, -7);
	}
	$FSREPSearch = $FSREPSearch.$ModSQL;
	$FSREPSearch .= ' GROUP BY '.$wpdb->prefix.'fsrep_listings.listing_id';
	if (function_exists('fsrep_pro_default_order')) {
		$FSREPSearch .= ' ORDER BY '.fsrep_pro_default_order();
	} else {
		$FSREPSearch .= ' ORDER BY '.$wpdb->prefix.'fsrep_listings.listing_id DESC';
	}
	$FSREPSearchQuery = fsrep_sql_clean($FSREPSearch);
	if (function_exists('fsrep_zip_search_results')) { 
		$FSREPSearch = fsrep_zip_search_results($FSREPSearch); 
		if (isset($FSREPSearch['glong']) && $FSREPSearch['glong'] != 'S') { $GMapSLong = $FSREPSearch['glong']; }
		if (isset($FSREPSearch['glat']) && $FSREPSearch['glat'] != 'S') { $GMapSLat = $FSREPSearch['glat']; }
		if (isset($FSREPSearch['gzoom']) && $FSREPSearch['gzoom'] != 'S') { $GMapSZoom = $FSREPSearch['gzoom']; }
		if (isset($FSREPSearch['query']) && $FSREPSearch['query'] != 'S') { $FSREPSearchQuery = $FSREPSearch['query']; }
	}
	if (function_exists('fsrep_custom_search_results')) { 
		$FSREPSearch = fsrep_custom_search_results($FSREPSearch); 
		if (isset($FSREPSearch['glong']) && $FSREPSearch['glong'] != 'S') { $GMapSLong = $FSREPSearch['glong']; }
		if (isset($FSREPSearch['glat']) && $FSREPSearch['glat'] != 'S') { $GMapSLat = $FSREPSearch['glat']; }
		if (isset($FSREPSearch['gzoom']) && $FSREPSearch['gzoom'] != 'S') { $GMapSZoom = $FSREPSearch['gzoom']; }
		if (isset($FSREPSearch['query']) && $FSREPSearch['query'] != 'S') { $FSREPSearchQuery = $FSREPSearch['query']; }
	}
	$wpdb->insert($wpdb->prefix.'fsrep_search_queries',  array('query_value' => $FSREPSearchQuery, 'glong' => $GMapSLong, 'glat' => $GMapSLat, 'gzoom' => $GMapSZoom));
	$SearchQueryID = $wpdb->insert_id;
} elseif(isset($_GET['searchid']) && is_numeric($_GET['searchid'])) {
	$SearchQueryID = $_GET['searchid'];
	$FSREPSearch = $wpdb->get_var("SELECT query_value FROM ".$wpdb->prefix."fsrep_search_queries WHERE query_id = ".$SearchQueryID);
}

$FSREPPermalinkStructure = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = 'permalink_structure'");
if ($FSREPPermalinkStructure == '' && !isset($_POST['submit']) && !isset($_GET['permalinks']) && $FSREPconfig['ShowWelcome'] == 'No') {
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

if ($FSREPconfig['FooterLink'] == 1) {
	function fsrep_credits() {
		echo '<p style="text-align: center"><a href="http://www.firestormplugins.com/plugins/real-estate/" target="_blank" title="WordPress Real Estate">Powered by the FireStorm WordPress Real Estate Plugin</a></p>';
	}
	add_filter('wp_footer', 'fsrep_credits');
}

// EXTENSIONS
if ($FSREPconfig['ShowWelcome'] == 'Yes' && !isset($_GET['showwelcome'])) {
	function fsrep_show_welcome() {
		echo '<div class="updated fade">
					<p><strong>Thank you for installing the FireStorm Real Estate Plugin</strong></p>
					<p>The Professional and Membership extensions are now available for purchase!</p>
					<p><a href="http://www.firestormplugins.com/extensions/real-estate/" target="_blank">Click here to learn more and buy online.</a></p>
					<p style="text-align: right;"><a href="'.get_option('home').'/wp-admin/admin.php?page=fsrep_settings&showwelcome=no">hide this message</a></p>
					</div>';
	}
	add_action('admin_notices', 'fsrep_show_welcome');
	return;
}
if ($FSREPExtensions['MembershipL'] != '' || $FSREPExtensions['ProFeaturesL'] != '') {
	if ($FSREPconfig['ExtensionUpdateMsg'] == 'Yes' && !isset($_GET['ExtensionUpdateMsg'])) {
		function fsrep_ext_update_msg() {
			echo '<div class="updated fade">
						<p><strong>Please Redownload Extension File(s)</strong></p>
						<p>Extension files should now be located in the Uploads directory ('.ABSPATH.'wp-content/uploads/fsrep/extensions/).</p>
						<p><a href="http://www.firestormplugins.com/my-account/" target="_blank">Download the latest extension files here.</a></p>
						<p style="text-align: right;"><a href="'.get_option('home').'/wp-admin/admin.php?page=fsrep_settings&ExtensionUpdateMsg=no">hide this message</a></p>
						</div>';
		}
		add_action('admin_notices', 'fsrep_ext_update_msg');
		return;
	}
}

// DIRECTORIES & PERMISSIONS
if (!file_exists(ABSPATH.'wp-content/uploads/') ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/extensions") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/themes") ||
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
		The plugin was unable to setup needed directories for listing images. Please create the following directories and CHMOD them to 755:<br />
		'.ABSPATH.'wp-content/uploads/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/extensions/<br />
		'.ABSPATH.'wp-content/uploads/fsrep/themes/<br />
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