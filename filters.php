<?php
// FSREP CONTENT FILTER
add_filter('the_content', 'fsrep_content', 1);
add_filter('wp_title', 'fsrep_title'); 
function fsrep_content($content) {
	global $post,$wpdb,$wp_rewrite,$user_ID,$current_user,$FSREPconfig;
	if (preg_match('/fsrep-listings/i', $post->post_content)) {
		$RequestURI = explode('/listings/', $_SERVER['REQUEST_URI']);
		$ListingID = explode('-', $RequestURI[1]);
		
		// DISPLAY SEARCH RESULTS
		if (substr($RequestURI[1],0,-1) == 'search') {
			include('includes/search_page.php');
			
		// DISPLAY LISTING
		} elseif (is_numeric($ListingID[0])) {
			include('includes/listing_details.php');
			
		// DISPLAY CITY / PROVINCE LISTINGS
		} elseif ($RequestURI[1] != '') {
			include('includes/cityprov.php');
			
		// DISPLAY HOME PAGE LISTING MAP / FEATURED / NEW
		} else {
			include('includes/listing_featured.php');
		}
		
	// LOCAL PAGE
	} elseif (preg_match('/fsrep-local/i', $post->post_content)) {
		$RequestURI = explode('/local/', $_SERVER['REQUEST_URI']);
		$LocalURL = str_replace('/','',$RequestURI[1]);
		$CountryID = $wpdb->get_var("SELECT country_id FROM ".$wpdb->prefix."fsrep_countries WHERE country_url = '$LocalURL'");
		$ProvinceID = $wpdb->get_var("SELECT province_id FROM ".$wpdb->prefix."fsrep_provinces WHERE province_url = '$LocalURL'");
		$CityID = $wpdb->get_var("SELECT city_id FROM ".$wpdb->prefix."fsrep_cities WHERE city_url = '$LocalURL'");
		if (isset($CountryID)) {
			$Type = 'country';
			$Value = $CountryID;
		} elseif (isset($ProvinceID)) {
			$Type = 'province';
			$Value = $ProvinceID;
		} elseif (isset($CityID)) {
			$Type = 'city';
			$Value = $CityID;
		}
		include('includes/listing_local.php');
	} else {
		return($content);
	}
}
function fsrep_title() {
	global $post,$wpdb,$wp_rewrite,$user_ID,$current_user,$FSREPconfig;
	if (preg_match('/fsrep-listings/i', $post->post_content)) {
		$RequestURI = explode('/listings/', $_SERVER['REQUEST_URI']);
		$ListingID = explode('-', $RequestURI[1]);
		
		// DISPLAY SEARCH RESULTS
		if (substr($RequestURI[1],0,-1) == 'search') {
			return 'Listing Search - ';
			
		// DISPLAY LISTING
		} elseif (is_numeric($ListingID[0])) {
			return strip_tags(fsrep_listing_name_gen($ListingID[0], $FSREPconfig['Listing Name Display']));
			
		// DISPLAY CITY / PROVINCE LISTINGS
		} elseif ($RequestURI[1] != '') {
			return 'City Listings - ';
			
		// DISPLAY HOME PAGE LISTING MAP / FEATURED / NEW
		} else {
			return 'Listings - ';
		}
		
	} else {
		return $post->post_title;
	}
}
?>