<?php
add_action('admin_menu', 'fsrep_admin_pages');
function fsrep_admin_pages() {
	global $user_ID,$FSREPconfig,$FSREPMembers,$FSREPListingsPerm;
	if ($user_ID == 1) { $PageName = 'All Listings'; } else { $PageName = 'My Listings'; }
	add_menu_page('Real Estate', 'Real Estate', 10, __FILE__, 'fsrep_admin_home');
	if ($FSREPMembers == TRUE) {
		if ($FSREPconfig['EnableListingPlans'] == 'Yes') {
			add_submenu_page(__FILE__, 'My Profile', 'My Profile', $FSREPListingsPerm, __FILE__, 'fsrep_profile');
		}
	}
	add_submenu_page(__FILE__, $PageName, $PageName, $FSREPListingsPerm, 'fsrep_listings', 'fsrep_listings');
	add_submenu_page(__FILE__, 'Custom Fields', 'Custom Fields', 10, 'fsrep_fields', 'fsrep_fields');
	add_submenu_page(__FILE__, 'Filters', 'Filters', 10, 'fsrep_filters', 'fsrep_filters');
	add_submenu_page(__FILE__, 'Locations', 'Locations', 10, 'fsrep_local', 'fsrep_local');
	if ($FSREPMembers == TRUE) {
		if ($FSREPconfig['EnableListingPlans'] == 'Yes') {
			add_submenu_page(__FILE__, 'Plan Pricing', 'Plan Pricing', 10, 'fsrep_plans', 'fsrep_plans');
		}
	}
	add_submenu_page(__FILE__, 'Settings', 'Settings', 10, 'fsrep_settings', 'fsrep_settings');
}

add_action('admin_bar_menu', 'fsrep_admin_bar', 100);
function fsrep_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
	'id' => 'fsrep_admin_bar',
	'title' => 'FireStorm Real Estate',
	'href' => admin_url('admin.php?page=fs-real-estate-plugin/hooks.php'),
	));
	$wp_admin_bar->add_menu( array(
	'parent' => 'fsrep_admin_bar',
	'id' => 'fsrep_admin_all',
	'title' => 'View All Listings',
	'href' => admin_url('admin.php?page=fsrep_listings'),
	));
	$wp_admin_bar->add_menu( array(
	'parent' => 'fsrep_admin_bar',
	'id' => 'fsrep_admin_fields',
	'title' => 'Custom Fields',
	'href' => admin_url('admin.php?page=fsrep_fields'),
	));
	$wp_admin_bar->add_menu( array(
	'parent' => 'fsrep_admin_bar',
	'id' => 'fsrep_admin_filters',
	'title' => 'Filters & Short Codes',
	'href' => admin_url('admin.php?page=fsrep_filters'),
	));
	$wp_admin_bar->add_menu( array(
	'parent' => 'fsrep_admin_bar',
	'id' => 'fsrep_admin_locations',
	'title' => 'Locations',
	'href' => admin_url('admin.php?page=fsrep_local'),
	));
	$wp_admin_bar->add_menu( array(
	'parent' => 'fsrep_admin_bar',
	'id' => 'fsrep_admin_settings',
	'title' => 'Settings',
	'href' => admin_url('admin.php?page=fsrep_settings'),
	));
	$wp_admin_bar->add_menu( array(
	'parent' => 'fsrep_admin_bar',
	'id' => 'fsrep_admin_support',
	'title' => 'Support',
	'href' => 'http://www.firestormplugins.com/',
	'meta' => array('target' => '_blank',),
	));
	$wp_admin_bar->add_menu( array(
	'parent' => 'fsrep_admin_support',
	'id' => 'fsrep_admin_support_forum',
	'title' => 'Discussion Forum',
	'href' => 'http://www.firestormplugins.com/forums/',
	'meta' => array('target' => '_blank',),
	));
	$wp_admin_bar->add_menu( array(
	'parent' => 'fsrep_admin_support',
	'id' => 'fsrep_admin_support_custom',
	'title' => 'Customization',
	'href' => 'http://www.firestormplugins.com/wordpress-customization/',
	'meta' => array('target' => '_blank',),
	));
}


add_action('wp_head', 'fsrep_head');
function fsrep_head() {
	global $FSREPconfig,$post,$wpdb,$ListingID,$CityID,$ProvinceID,$CountryID,$LPageID;
	echo '<link rel="stylesheet" href="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/style.css" type="text/css" media="screen" />';
	echo '<link rel="stylesheet" href="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/lightbox.css" type="text/css" media="screen" />';
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/prototype.js"></script>';
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/scriptaculous.js?load=effects,builder"></script>';
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/lightbox.js"></script>';
	echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/calendarDateInput.js"></script>';
	if (file_exists(ABSPATH.'/wp-content/plugins/fs-real-estate-plugin/js/ajax.js')) {
		echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/ajax.js"></script>';
	} else {
		echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/js/ajax.php"></script>';
	}
	$ShowGoogleMap = FALSE;
	if ($FSREPconfig['GoogleMapAPI'] != '' && $FSREPconfig['GoogleMap'] == 1) {
		if ($ListingID != 0 || $CityID != 0 || $ProvinceID != 0 || $CountryID != 0 || preg_match("/fsrep-filter-/i", $post->post_content) || preg_match("/fsrep-filter /i", $post->post_content) || $post->ID == $LPageID) {
			$ShowGoogleMap = TRUE;
			echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/gmaps.js"></script>';
			echo '<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;sensor=false&amp;key='.$FSREPconfig['GoogleMapAPI'].'" type="text/javascript"></script>';
		}
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('thickbox');	
	
	// META TAGS
	if (isset($LPageID)) {
		if ($post->ID == $LPageID) {
			$ShowGoogleMap = TRUE;
			$METADescription = '';
			if ($ListingID != 0) {
				$METADescription = substr($wpdb->get_var("SELECT listing_description FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID"), 0, 250);
			} elseif ($CityID != 0) {
				$METADescription = substr($wpdb->get_var("SELECT city_overview FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID"), 0, 250);
			} elseif ($ProvinceID != 0) {
				$METADescription = substr($wpdb->get_var("SELECT province_overview FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID"), 0, 250);
			} elseif ($CountryID != 0) {
				$METADescription = substr($wpdb->get_var("SELECT country_overview FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID"), 0, 250);
			}		
			if ($METADescription != '') { echo "<meta name=\"description\" content=\"".$METADescription."\" /> \n"; }
		}
	}
	
	// SHOW GOOGLE MAP
	$FilterID = 0;
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
	$MarkerListingURL = 'sort=all';
	
	if ($FSREPconfig['GoogleMap'] == 0) { $ShowGoogleMap = FALSE; }
	
	if ($ShowGoogleMap == TRUE) {
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
		} elseif ($ListingID != 0) {
			$MarkerListingURL = 'id='.$ListingID;
			$GMapDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID);
			$GMapLat = $GMapDetails->listing_lat;
			$GMapLong = $GMapDetails->listing_long;
			$GMapZoom = '16';
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
		if (!isset($GMapLat) || !isset($GMapLong) || !isset($GMapZoom)) {
			$GMapLat = $FSREPconfig['MapCenterLat'];
			$GMapLong = $FSREPconfig['MapCenterLong'];
			$GMapZoom = $FSREPconfig['MapCenterZoom'];
		}
	
		?>
		<link rel="stylesheet" href="<?= get_option('siteurl'); ?>/<?= WPINC; ?>/js/thickbox/thickbox.css" type="text/css" media="screen" />
		<script type="text/javascript">
		var tb_pathToImage = "<?= get_option('siteurl'); ?>/<?= WPINC; ?>/js/thickbox/loadingAnimation.gif";
		var tb_closeImage = "<?= get_option('siteurl'); ?>/<?= WPINC; ?>/js/thickbox/tb-close.png"
		</script>
		<script type="text/javascript">
			var map;
			function createMarker(point,name,html) {
				var marker = new GMarker(point);
				GEvent.addListener(marker, "click", function() {
					marker.openInfoWindowHtml(html);
				});
				return marker;
			}
		
		
			function myclick(i) {
				gmarkers[i].openInfoWindowHtml(htmls[i]);
			}
		
			function makeMap() {
				if (GBrowserIsCompatible()) {
				var m = document.getElementById("listings_map");
				map = new GMap(document.getElementById("listings_map"));
				map.addControl(new GLargeMapControl());
				map.addControl(new GMapTypeControl());
				map.setCenter(new GLatLng(<?php echo $GMapLat; ?>, <?php echo $GMapLong; ?>), <?php echo $GMapZoom; ?>);
				var request = GXmlHttp.create();
				var filename = "<?php echo get_option('home'); ?>/wp-content/plugins/fs-real-estate-plugin/xml/marker_listings.xml?<?php echo $MarkerListingURL; ?>"
				request.open("GET", filename, true);
				request.onreadystatechange = function() {
				if (request.readyState == 4) {
					if (request.status == 200) {
						var xmlDoc = request.responseXML;
						if (xmlDoc.documentElement) {
							var markers = xmlDoc.documentElement.getElementsByTagName("marker");
							for (var i = 0; i < markers.length; i++) {
								var lat = parseFloat(markers[i].getAttribute("lat"));
								var lng = parseFloat(markers[i].getAttribute("lng"));
								var point = new GPoint(lng,lat);
								var html = markers[i].getAttribute("html");
								var label = markers[i].getAttribute("label");
								var marker = createMarker(point,label,html);
								map.addOverlay(marker);
							}
						}
					}
				}
			}
			request.send(null);
		}
		}
		window.onload=makeMap; 
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