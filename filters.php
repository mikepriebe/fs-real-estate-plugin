<?php
// FSREP CONTENT FILTER
add_filter('the_content', 'fsrep_content');
add_filter('wp_title', 'fsrep_title'); 
function fsrep_content($content) {
	global $LPageID,$LOPageID,$MAPageID,$post,$wpdb,$wp_rewrite,$user_ID,$current_user,$FSREPconfig,$CityID,$ProvinceID,$CountryID,$RequestURI,$FSREPMembers,$FSREPExtensions;
	if ($post->ID == $LPageID) {
		if (is_numeric(substr($RequestURI[1], 0, 1))) {
			$ListingID = explode('-', $RequestURI[1]);
			if ($FSREPconfig['EnableBreadcrumbs'] == 'Yes') {
				echo fsrep_breadcrumbs();
			}
			include('includes/listing_details.php');
			echo $PageContent;
		} elseif (substr($RequestURI[1],0,7) == 'compare') {
			include('includes/compare.php');
		} elseif ($RequestURI[1] == 'search') {
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
				echo fsrep_breadcrumbs();
			}
			echo fsrep_listings_display(0, $Value, $Type, 0, 0, 0, $FSREPconfig['GoogleMap'], 0);
		}
		
		
		
	} elseif ($post->ID == $MAPageID && $FSREPExtensions['Membership'] == TRUE) {
		if (function_exists('fsrep_pro_my_account')) { fsrep_pro_my_account(); }
	} else {
		if (preg_match("/fsrep-filter-/i", $content)) {
			$FilterID = explode('[fsrep-filter-', $content);
			$FilterID = explode(']', $FilterID[1]);
			$content = str_replace('[fsrep-filter-'.$FilterID[0].']',fsrep_listings_display('', '', '', '', '', '', $FSREPconfig['GoogleMap'], $FilterID[0]),$content);
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
				include('includes/listing_details.php');
				$content = $FSREPFilterStart.$PageContent.$FSREPFilterEnd;
			} else {
				$content = $FSREPFilterStart.fsrep_listings_display('', $FSREPFilterID, $FSREPType, '', '', '', $FSREPMap, $FSREPFilter[0]).$FSREPFilterEnd;
			}
			
		}
		if (preg_match("/fsrep-search/i", $content)) {
			$content = str_replace('[fsrep-search]',fsrep_search_box(),$content);
		}

		return($content);
	}
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
			} elseif (is_numeric($ListingID[0])) {
				$ListingTitle = strip_tags(fsrep_listing_name_gen($ListingID[0], $FSREPconfig['ListingNameDisplay']));
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