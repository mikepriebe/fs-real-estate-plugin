<?php
add_action('admin_menu', 'fsrep_admin_pages');
function fsrep_admin_pages() {
	global $user_ID,$FSREPconfig,$FSREPMembers,$FSREPAdminPermissions,$FSREPMemberPermissions,$FSREPCurrentPermission,$FSREPExtensions;
	if ($FSREPCurrentPermission == $FSREPAdminPermissions) { $PageName = 'All Listings'; } else { $PageName = 'My Listings'; }
	add_menu_page('Real Estate', 'Real Estate', $FSREPAdminPermissions, __FILE__, 'fsrep_admin_home');
	if ($FSREPExtensions['Membership'] == TRUE) {
		add_submenu_page(__FILE__, 'My Profile', 'My Profile', $FSREPCurrentPermission, 'fsrep_profile', 'fsrep_profile');
		add_submenu_page(__FILE__, $PageName, $PageName, $FSREPCurrentPermission, 'fsrep_listings', 'fsrep_listings');
	} else {
		add_submenu_page(__FILE__, $PageName, $PageName, $FSREPAdminPermissions, 'fsrep_listings', 'fsrep_listings');
	}
	add_submenu_page(__FILE__, 'Custom Fields', 'Custom Fields', $FSREPAdminPermissions, 'fsrep_fields', 'fsrep_fields');
	add_submenu_page(__FILE__, 'Filters', 'Filters', $FSREPAdminPermissions, 'fsrep_filters', 'fsrep_filters');
	add_submenu_page(__FILE__, 'Locations', 'Locations', $FSREPAdminPermissions, 'fsrep_local', 'fsrep_local');
	if (isset($FSREPconfig['EnableListingPlans'])) {
		if ($FSREPExtensions['Membership'] == TRUE) {
			if ($FSREPconfig['EnableListingPlans'] == 'Yes') {
				add_submenu_page(__FILE__, 'Plan Pricing', 'Plan Pricing', $FSREPAdminPermissions, 'fsrep_plans', 'fsrep_plans');
			}
		}
	}
	add_submenu_page(__FILE__, 'Settings', 'Settings', $FSREPAdminPermissions, 'fsrep_settings', 'fsrep_settings');
}

add_action('admin_bar_menu', 'fsrep_admin_bar', 100);
function fsrep_admin_bar() {
	global $wp_admin_bar, $FSREPCurrentPermission, $FSREPAdminPermissions;
	if ($FSREPCurrentPermission == $FSREPAdminPermissions) {
		$wp_admin_bar->add_menu( array(
		'id' => 'fsrep_admin_bar',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'FireStorm Real Estate'),
		'href' => admin_url('admin.php?page=fs-real-estate-plugin/hooks.php'),
		));
		$wp_admin_bar->add_menu( array(
		'parent' => 'fsrep_admin_bar',
		'id' => 'fsrep_admin_all',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'View All Listings'),
		'href' => admin_url('admin.php?page=fsrep_listings'),
		));
		$wp_admin_bar->add_menu( array(
		'parent' => 'fsrep_admin_bar',
		'id' => 'fsrep_admin_fields',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'Custom Fields'),
		'href' => admin_url('admin.php?page=fsrep_fields'),
		));
		$wp_admin_bar->add_menu( array(
		'parent' => 'fsrep_admin_bar',
		'id' => 'fsrep_admin_filters',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'Filters & Short Codes'),
		'href' => admin_url('admin.php?page=fsrep_filters'),
		));
		$wp_admin_bar->add_menu( array(
		'parent' => 'fsrep_admin_bar',
		'id' => 'fsrep_admin_locations',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'Locations'),
		'href' => admin_url('admin.php?page=fsrep_local'),
		));
		$wp_admin_bar->add_menu( array(
		'parent' => 'fsrep_admin_bar',
		'id' => 'fsrep_admin_settings',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'Settings'),
		'href' => admin_url('admin.php?page=fsrep_settings'),
		));
		$wp_admin_bar->add_menu( array(
		'parent' => 'fsrep_admin_bar',
		'id' => 'fsrep_admin_support',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'Support'),
		'href' => 'http://www.firestormplugins.com/',
		'meta' => array('target' => '_blank',),
		));
		$wp_admin_bar->add_menu( array(
		'parent' => 'fsrep_admin_support',
		'id' => 'fsrep_admin_support_forum',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'Discussion Forum'),
		'href' => 'http://www.firestormplugins.com/forums/',
		'meta' => array('target' => '_blank',),
		));
		$wp_admin_bar->add_menu( array(
		'parent' => 'fsrep_admin_support',
		'id' => 'fsrep_admin_support_custom',
		'title' => fsrep_text_translator('FireStorm Real Estate Plugin', 'Admin Bar Label', 'Customization'),
		'href' => 'http://www.firestormplugins.com/wordpress-customization/',
		'meta' => array('target' => '_blank',),
		));
	}
}


add_action('wp_head', 'fsrep_head');
function fsrep_head() {
	global $FSREPconfig,$post,$wpdb,$ListingID,$CityID,$ProvinceID,$CountryID,$LPageID,$FSREPSearch;
	echo '<link rel="stylesheet" href="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/style.css" type="text/css" media="screen" />';
	echo '<link rel="stylesheet" href="'.get_option('siteurl').'/'.WPINC.'/js/thickbox/thickbox.css" type="text/css" media="screen" />';
	echo '<script type="text/javascript">';
	echo 'var tb_pathToImage = "'.get_option('siteurl').'/'.WPINC.'/js/thickbox/loadingAnimation.gif";';
	echo 'var tb_closeImage = "'.get_option('siteurl').'/'.WPINC.'/js/thickbox/tb-close.png"';
	echo '</script>';
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/prototype.js"></script>';
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/scriptaculous.js?load=effects,builder"></script>';
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/calendarDateInput.js"></script>';
	if (file_exists(ABSPATH.'/wp-content/plugins/fs-real-estate-plugin/js/ajax.js')) {
		echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/ajax.js"></script>';
	} else {
		echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/ajax.php"></script>';
	}
	$ShowGoogleMap = FALSE;
	if ($FSREPconfig['GoogleMap'] == 1) {
		if (isset($post)) {
			if ($ListingID != 0 || $CityID != 0 || $ProvinceID != 0 || $CountryID != 0 || preg_match("/fsrep-filter-/i", $post->post_content) || preg_match("/fsrep-filter /i", $post->post_content) || $post->ID == $LPageID) {
				$ShowGoogleMap = TRUE;
				echo '<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>';
			}
		}
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('thickbox');	
	
	// META TAGS
	$METADescription = '';
	if (isset($LPageID) && isset($post)) {
		if ($post->ID == $LPageID) {
			$ShowGoogleMap = TRUE;
			$METADescription = '';
			if ($ListingID != 0) {
				$METADescription = substr($wpdb->get_var("SELECT listing_description FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID"), 0, 250);
			} elseif ($CityID != 0) {
				//$METADescription = substr($wpdb->get_var("SELECT city_overview FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID"), 0, 250);
			} elseif ($ProvinceID != 0) {
				//$METADescription = substr($wpdb->get_var("SELECT province_overview FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID"), 0, 250);
			} elseif ($CountryID != 0) {
				//$METADescription = substr($wpdb->get_var("SELECT country_overview FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID"), 0, 250);
			}		
			if ($METADescription != '') { echo "<meta name=\"description\" content=\"".strip_tags($METADescription)."\" /> \n"; }
		}
	}
	
	// SHOW GOOGLE MAP
	$FilterID = 0;
	if (isset($post)) {
		if ($post->ID == $LPageID) {
			if (preg_match("/fsrep-filter-/i", $post->post_content)) {
				$FilterID = explode('[fsrep-filter-', $post->post_content);
				$FilterID = explode(']', $FilterID[1]);
				$FilterID = $FilterID[0];
				$ShowGoogleMap = TRUE;
			} elseif (preg_match("/fsrep-filter /i", $post->post_content)) {
				$FSREPFilter = explode('[fsrep-filter ', $post->post_content);
				$FSREPFilter = explode(']', $FSREPFilter[1]);
				$FSREPFilter = explode(' ', $FSREPFilter[0]);
				$FSREPType = str_replace('"','',str_replace('type=','',$FSREPFilter[0]));
				$FSREPMap = str_replace('"','',str_replace('map=','',$FSREPFilter[1]));
				if(isset($FSREPFilter[2])) { $FSREPFilterID = str_replace('"','',str_replace('value=','',$FSREPFilter[2])); } else { $FSREPFilterID = 0; }
				if ($FSREPType == 'listing') {
					$ListingID = $FSREPFilterID;
				} elseif ($FSREPType == 'country') {
					$CountryID = $FSREPFilterID;
				} elseif ($FSREPType == 'state' || $FSREPType == 'province') {
					$ProvinceID = $FSREPFilterID;
				} elseif ($FSREPType == 'city') {
					$CityID = $FSREPFilterID;
				}
				$ShowGoogleMap = TRUE;
			}
		}
	}
	$MarkerListingURL = 'sort=all';
	
	if ($FSREPconfig['GoogleMap'] == 0) { $ShowGoogleMap = FALSE; }
	
	if ($ShowGoogleMap == TRUE) {
		$GBounds = FALSE;
		if ($FSREPconfig['DefaultMapLocation'] != '') {
			$DefaultCoords = explode('-',$FSREPconfig['DefaultMapLocation']);
			if ($DefaultCoords[0] == 'country') {
				$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$DefaultCoords[1]);
				if ($GoogleCoords->country_lat == '' || $GoogleCoords->country_long == '') {
					$Coords = google_geocoder($GoogleCoords->country_name, $FSREPconfig['Google Map API']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_countries SET country_long = '".$Coords[0]."', country_lat = '".$Coords[1]."', country_zoom = 3 WHERE country_id = ".$GoogleCoords->country_id);
					$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$DefaultCoords[1]);
				}
				$GMapLat = $GoogleCoords->country_lat;
				$GMapLong = $GoogleCoords->country_long;
				$GMapZoom = $GoogleCoords->country_zoom;
			} elseif($DefaultCoords[0] == 'province') {
				$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$DefaultCoords[1]."");
				if ($GoogleCoords->province_lat == '' || $GoogleCoords->province_long == '') {
					$CoordCountryName = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$GoogleCoords->country_id);
					$Coords = google_geocoder($GoogleCoords->province_name.' '.$CoordCountryName, $FSREPconfig['Google Map API']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_provinces SET province_long = '".$Coords[0]."', province_lat = '".$Coords[1]."', province_zoom = 5 WHERE province_id = ".$GoogleCoords->province_id);
					$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$DefaultCoords[1]);
				}
				$GMapLat = $GoogleCoords->province_lat;
				$GMapLong = $GoogleCoords->province_long;
				$GMapZoom = $GoogleCoords->province_zoom;
		} elseif($DefaultCoords[0] == 'city') {
				$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = ".$DefaultCoords[1]);
				if ($GoogleCoords->city_lat == '' || $GoogleCoords->city_long == '') {
					$CoordCountryName = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$GoogleCoords->country_id);
					$CoordProvinceName = $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$GoogleCoords->province_id);
					$Coords = google_geocoder($GoogleCoords->city_name.' '.$CoordProvinceName.' '.$CoordCountryName, $FSREPconfig['Google Map API']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_cities SET city_long = '".$Coords[0]."', city_lat = '".$Coords[1]."', city_zoom = 11 WHERE city_id = ".$GoogleCoords->city_id);
					$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = ".$DefaultCoords[1]);
				}
				$GMapLat = $GoogleCoords->city_lat;
				$GMapLong = $GoogleCoords->city_long;
				$GMapZoom = $GoogleCoords->city_zoom;
			}
		}
    if ($FilterID != 0) {
			$MarkerListingURL = 'filter='.$FilterID;
			$GMapLat = $FSREPconfig['MapCenterLat'];
			$GMapLong = $FSREPconfig['MapCenterLong'];
			$GMapZoom = $FSREPconfig['MapCenterZoom'];
		} elseif ($FSREPSearch != '') {
			global $SearchQueryID,$GMapSLat,$GMapSLong,$GMapSZoom;
			$MarkerListingURL = 'search='.$SearchQueryID;
			if (isset($GMapSLat)) { $GMapLat = $GMapSLat; }
			if (isset($GMapSLong)) { $GMapLong = $GMapSLong; }
			if (isset($GMapSZoom)) { $GMapZoom = $GMapSZoom; }
			$GBounds = TRUE;
		} elseif ($ListingID != 0) {
			$MarkerListingURL = 'id='.$ListingID;
			$GMapDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID);
			$GMapLat = $GMapDetails->listing_lat;
			$GMapLong = $GMapDetails->listing_long;
			$GMapZoom = $GMapDetails->listing_zoom;
		} elseif ($CityID != 0) {
			$MarkerListingURL = 'cityid='.$CityID;
			$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID");
			$ProvinceName = $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$GoogleCoords->province_id);
			$CountryName = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$GoogleCoords->country_id);
			if ($GoogleCoords->city_long == '') {
				$Coords = google_geocoder($GoogleCoords->city_name.' '.$ProvinceName.' '.$CountryName, $FSREPconfig['GoogleMapAPI']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_cities SET city_long = '".$Coords[0]."', city_lat = '".$Coords[1]."', city_zoom = '11' WHERE city_id = $CityID");
				$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID");
			}
			$GMapLat = $GoogleCoords->city_lat;
			$GMapLong = $GoogleCoords->city_long;
			$GMapZoom = $GoogleCoords->city_zoom;
		} elseif ($ProvinceID != 0) {
			$MarkerListingURL = 'provinceid='.$ProvinceID;
			$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
			$CountryName = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$GoogleCoords->country_id);
			if ($GoogleCoords->province_long == '') {
				$Coords = google_geocoder($GoogleCoords->province_name.' '.$CountryName, $FSREPconfig['GoogleMapAPI']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_provinces SET province_long = '".$Coords[0]."', province_lat = '".$Coords[1]."', province_zoom = '5' WHERE province_id = $ProvinceID");
				$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
			}
			$GMapLat = $GoogleCoords->province_lat;
			$GMapLong = $GoogleCoords->province_long;
			$GMapZoom = $GoogleCoords->province_zoom;
		} elseif ($CountryID != 0) {
			$MarkerListingURL = 'countryid='.$CountryID;
			$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
			if ($GoogleCoords->country_long == '') {
				$Coords = google_geocoder($GoogleCoords->country_name, $FSREPconfig['GoogleMapAPI']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_countries SET country_long = '".$Coords[0]."', country_lat = '".$Coords[1]."', country_zoom = '3' WHERE country_id = $CountryID");
				$GoogleCoords = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
			}
			$GMapLat = $GoogleCoords->country_lat;
			$GMapLong = $GoogleCoords->country_long;
			$GMapZoom = $GoogleCoords->country_zoom;
		}
		if (!isset($GMapLat) || $GMapLat == '') {
			$GMapLat = $FSREPconfig['MapCenterLat'];
		}
		if (!isset($GMapLong) || $GMapLong == '') {
			$GMapLong = $FSREPconfig['MapCenterLong'];
		}
		if (!isset($GMapZoom) || $GMapZoom == '') {
			$GMapZoom = $FSREPconfig['MapCenterZoom'];
		}
		
		$GMapType = 'ROADMAP';
		if (isset($FSREPconfig['MapTypeId'])) { $GMapType = $FSREPconfig['MapTypeId']; }
		
		
		
		?>
    <script type="text/javascript">
      function FSREPMap() {
				function listinginfo(infowindow, marker) { 
					return function() {
						infowindow.open(map, marker);
					};
				}
       var myOptions = {
          center: new google.maps.LatLng(<?php echo $GMapLat; ?>, <?php echo $GMapLong; ?>),
          zoom: <?php echo $GMapZoom; ?>,
          mapTypeId: google.maps.MapTypeId.<?php echo $GMapType; ?>
        };
        var map = new google.maps.Map(document.getElementById("listings_map"),
            myOptions);
						

				if (window.XMLHttpRequest) {
					// FireFox, Opera, Safari, Chrome, IE7
					xmlhttp = new XMLHttpRequest();
				} else {
					// IE6
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET","<?php echo get_option('home'); ?>/wp-content/plugins/fs-real-estate-plugin/xml/marker_listings.php?<?php echo $MarkerListingURL; ?>",false);
				xmlhttp.send();
				xmlDoc = xmlhttp.responseXML; 
				listing = xmlDoc.getElementsByTagName("LISTING");
				var bounds = new google.maps.LatLngBounds ();

				for (var i=0;i<listing.length;i++) { 
					var lat 	= listing[i].getElementsByTagName("LAT")[0].childNodes[0].nodeValue;
					var lng 	= listing[i].getElementsByTagName("LONG")[0].childNodes[0].nodeValue;
					var latlngset;
					latlngset = new google.maps.LatLng(lat, lng);
					bounds.extend (latlngset);
					var marker = new google.maps.Marker({  
						position: new google.maps.LatLng(listing[i].getElementsByTagName("LAT")[0].childNodes[0].nodeValue, listing[i].getElementsByTagName("LONG")[0].childNodes[0].nodeValue),
						map: map,
						title: listing[i].getElementsByTagName("LABEL")[0].childNodes[0].nodeValue
					});
					if (listing[i].getElementsByTagName("IMAGE")[0].childNodes[0].nodeValue != 'None') {
						var content = '<div style="text-align: center;"><a href="' + listing[i].getElementsByTagName("URL")[0].childNodes[0].nodeValue + '"><img src="' + listing[i].getElementsByTagName("IMAGE")[0].childNodes[0].nodeValue + '" border="0" alt="" style="border: 1px solid #999999;"/></a><br/><strong>' + listing[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue + '</strong><br/>Asking Price: ' + listing[i].getElementsByTagName("PRICE")[0].childNodes[0].nodeValue + '<br/><a href="' + listing[i].getElementsByTagName("URL")[0].childNodes[0].nodeValue + '">view listing</a><br/></div>';
					} else {
						var content = '<div style="text-align: center;"><strong>' + listing[i].getElementsByTagName("NAME")[0].childNodes[0].nodeValue + '</strong><br/>Asking Price: ' + listing[i].getElementsByTagName("PRICE")[0].childNodes[0].nodeValue + '<br/><a href="' + listing[i].getElementsByTagName("URL")[0].childNodes[0].nodeValue + '">view listing</a><br/></div>';
					}
					var infowindow = new google.maps.InfoWindow();
						infowindow.setContent(content);
						google.maps.event.addListener(
							marker, 
							'click', 
							listinginfo(infowindow, marker)
					);		
				}
				<?php if ($GBounds == TRUE) { echo 'map.fitBounds (bounds);'; } ?>
      }
			window.onload=FSREPMap; 
    </script>    
		<?php 
	}
}
add_action('admin_head', 'fsrep_ahead');
function fsrep_ahead() {
	global $FSREPconfig;
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/gmaps.js"></script>';
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/calendarDateInput.js"></script>';
	if (file_exists(ABSPATH.'/wp-content/plugins/fs-real-estate-plugin/js/ajax.js')) {
		echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/ajax.js"></script>';
	} else {
		echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/ajax.php"></script>';
	}
}
?>