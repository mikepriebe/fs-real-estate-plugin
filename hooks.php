<?php
// ADMIN MENU HOOK
add_action('admin_menu', 'fsrep_admin_pages');
function fsrep_admin_pages() {
	add_menu_page('WP Real Estate', 'WP Real Estate', 8, __FILE__, 'fsrep_admin_home');
	add_submenu_page(__FILE__, 'Listings', 'Listings', 8, 'fsrep_house_listings', 'fsrep_house_listings');
	add_submenu_page(__FILE__, 'Custom Fields', 'Custom Fields', 8, 'fsrep_fields', 'fsrep_fields');
	add_submenu_page(__FILE__, 'Locations', 'Locations', 8, 'fsrep_local', 'fsrep_local');
	add_submenu_page(__FILE__, 'Settings', 'Settings', 8, 'fsrep_settings', 'fsrep_settings');
}

// HEAD TAG HOOK (CSS, JS)
add_action('wp_head', 'fsrep_head');
function fsrep_head() {
	global $FSREPconfig,$post,$wpdb;
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
	if ($FSREPconfig['Google Map API'] != '') {
		echo '<script type="text/javascript" src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/gmaps.js"></script>';
		echo '<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;sensor=false&amp;key='.$FSREPconfig['Google Map API'].'" type="text/javascript"></script>';
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('thickbox');	

	if (preg_match('/fsrep-listings/i', $post->post_content)) {
		$RequestURI = explode('/listings/', $_SERVER['REQUEST_URI']);
		$ListingID = explode('-', $RequestURI[1]);
		if (is_numeric($ListingID[0])) {
			$MarkerListingURL = 'id='.$ListingID[0];
			$GMapDetails = $wpdb->get_row("SELECT * FROM wp_fsrep_listings WHERE listing_id = ".$ListingID[0]);
			$GMapLat = $GMapDetails->listing_lat;
			$GMapLong = $GMapDetails->listing_long;
			$GMapZoom = '16';
		}
	}	
	if (!$MarkerListingURL) {
		$MarkerListingURL = 'sort=all';
		$GMapLat = $FSREPconfig['Map Center Lat'];
		$GMapLong = $FSREPconfig['Map Center Long'];
		$GMapZoom = $FSREPconfig['Map Center Zoom'];
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