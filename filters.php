<?php
// FSREP CONTENT FILTER
add_filter('the_content', 'fsrep_content');
add_filter('wp_title', 'fsrep_title'); 
function fsrep_content($content) {
	global $LPageID,$LOPageID,$MAPageID,$MembersPageID,$post,$wpdb,$wp_rewrite,$user_ID,$current_user,$FSREPconfig,$CityID,$ProvinceID,$CountryID,$RequestURI,$FSREPMembers,$FSREPExtensions;
	$PageContent = '';
	$WPUploadDir = wp_upload_dir();
	if ($post->ID == $LPageID) {
		$ViewListings = 'yes';
		if (function_exists('fsrep_pro_lview_login')) {
			$ViewListings = fsrep_pro_lview_login();
		}
		if ($ViewListings == 'yes') {
			if (is_numeric(substr($RequestURI[1], 0, 1))) {
				$ListingID = explode('-', $RequestURI[1]);
				if ($FSREPconfig['EnableBreadcrumbs'] == 'Yes') {
					$PageContent .= fsrep_breadcrumbs();
				}
				include('themes/'.$FSREPconfig['Theme'].'/listing_details.php');
			} elseif (substr($RequestURI[1],0,7) == 'compare') {
				include('includes/compare.php');
			} elseif (substr($RequestURI[1], 0, 6) == 'search') {
				include('includes/search_page.php');
			} else {
				$Type = '';
				$Value = '';
				if ($RequestURI[1] != '') {
					$CP = explode('/', $RequestURI[1]);
					if ($CityID != 0) {
						$Type = 'city';
						$Value = $wpdb->get_var("SELECT city_id FROM ".$wpdb->prefix."fsrep_cities WHERE city_url = '".$CP[2]."'");
					} elseif($ProvinceID != 0) {
						$Type = 'province';
						$Value = $wpdb->get_var("SELECT province_id FROM ".$wpdb->prefix."fsrep_provinces WHERE province_url = '".$CP[1]."'");
					} elseif($CountryID != 0) {
						$Type = 'country';
						$Value = $wpdb->get_var("SELECT country_id FROM ".$wpdb->prefix."fsrep_countries WHERE country_url = '".$CP[0]."'");
					}
				}
				if ($FSREPconfig['EnableBreadcrumbs'] == 'Yes') {
					$PageContent .= fsrep_breadcrumbs();
				}
				$PageContent .= fsrep_listings_display(0, $Value, $Type, 0, 0, 0, $FSREPconfig['GoogleMap'], 0);
			}
		} else {
			$PageContent .= 'Please login to view listings.';
		}
	} elseif ($post->ID == $MAPageID && $FSREPExtensions['Membership'] == TRUE) {
		if (function_exists('fsrep_pro_my_account')) { $PageContent .= fsrep_pro_my_account(); }
	} elseif ($post->ID == $MembersPageID && $FSREPExtensions['Membership'] == TRUE) {
		if (function_exists('fsrep_pro_members_page')) { $PageContent .= fsrep_pro_members_page(); }
	} else {
		$ViewListings = 'yes';
		if (function_exists('fsrep_pro_lview_login')) {
			$ViewListings = fsrep_pro_lview_login();
		}
		if (function_exists('fsrep_pro_shortcodes')) {
			$content = fsrep_pro_shortcodes($content);
		}		
		if (preg_match("/fsrep-filter-/i", $content)) {
			if (function_exists('fsrep_pro_custom_filter_replace')) {
				$content = fsrep_pro_custom_filter_replace($content,$ViewListings);
			} else {
				$content = fsrep_custom_filter_replace($content,$ViewListings);
			}
		} elseif (preg_match("/fsrep-filter /i", $content)) {
			$FSREPFilter = explode('[fsrep-filter ', $content);
			$FSREPFilterStart =  $FSREPFilter[0];
			$FSREPFilter = explode(']', $FSREPFilter[1]);
			$FSREPFilterEnd =  $FSREPFilter[1];
			$FSREPFilter = explode(' ', $FSREPFilter[0]);
			$FSREPType = str_replace('"','',str_replace('type=','',$FSREPFilter[0]));
			$FSREPMap = str_replace('"','',str_replace('map=','',$FSREPFilter[1]));
			if ($FSREPMap == 'no' || $FSREPMap == '0') { $FSREPMap = FALSE; } elseif ($FSREPMap == 'yes' || $FSREPMap == '1') { $FSREPMap = TRUE; }
			if ($FSREPconfig['GoogleMap'] == 0) { $FSREPMap = FALSE; }
			if(isset($FSREPFilter[2])) { $FSREPFilterID = str_replace('"','',str_replace('value=','',$FSREPFilter[2])); } else { $FSREPFilterID = 0; }
			if ($FSREPType == 'listing') {
				$ListingID[0] = $FSREPFilterID;
				include('themes/'.$FSREPconfig['Theme'].'/listing_details.php');
				$content = $FSREPFilterStart.$PageContent.$FSREPFilterEnd;
			} elseif ($FSREPType == 'all') {
				$content = $FSREPFilterStart.fsrep_listings_display('', '', '', '', '', '', $FSREPconfig['GoogleMap'], 0).$FSREPFilterEnd;
			} elseif ($FSREPType == 'sold') {
				$content = $FSREPFilterStart.fsrep_listings_display('', '', '', '', '', '', $FSREPconfig['GoogleMap'], 0).$FSREPFilterEnd;
			} else {
				$content = $FSREPFilterStart.fsrep_listings_display('', $FSREPFilterID, $FSREPType, '', '', '', $FSREPMap, $FSREPFilter[0]).$FSREPFilterEnd;
			}
			
		}
		//if (preg_match("/fsrep-search/i", $content)) {
		//	$content = str_replace('[fsrep-search]',fsrep_search_box(),$content);
		//}
		$PageContent .= $content;
	}
	return $PageContent;
}
function fsrep_title() {
	global $post,$wpdb,$wp_rewrite,$user_ID,$current_user,$FSREPconfig,$ListingID,$CityID,$ProvinceID,$CountryID;
	$ListingHomeURL = $wpdb->get_var("SELECT post_name FROM ".$wpdb->prefix."posts WHERE ID = ".$FSREPconfig['ListingsPageID']);
	if (isset($post)) {
		if (preg_match('/fsrep-listings/i', $post->post_content)) {
			$RequestURI = explode('/'.$ListingHomeURL.'/', $_SERVER['REQUEST_URI']);
			if (substr($RequestURI[1],0,-1) == 'search') {
				return ' Listing Search ';
			} elseif (substr($RequestURI[1],0,7) == 'compare') {
				return ' Compare Listings ';
			} elseif (substr($RequestURI[1],0,6) == '?order') {
				return ' Listings ';
			} elseif (is_numeric($ListingID)) {
				$ListingTitle = strip_tags(fsrep_listing_name_gen($ListingID, $FSREPconfig['ListingNameDisplay']));
				if ($CityID != 0) {
					$ListingTitle .= ' - '.$wpdb->get_var("SELECT city_name FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID");
				}
				if ($ProvinceID != 0) {
					$ListingTitle .= ', '.$wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID").' ';
				}
				return $ListingTitle.' ';
			} elseif ($RequestURI[1] != '') {
				$Title = '';
				if ($CityID != 0) {
					$Title .= $wpdb->get_var("SELECT city_name FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID").' ';
				}
				if ($ProvinceID != 0) {
					$Title .= $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID").' ';
				}
				if ($CountryID != 0) {
					$Title .= $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID").' ';
				}
				return $Title.' ';
			} else {
				return $post->post_title.' ';
			}
			
		} else {
			return $post->post_title.' ';
		}
	}
}
?>