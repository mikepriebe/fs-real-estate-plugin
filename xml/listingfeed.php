<?php  

error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Content-type:text/xml; charset=utf-8");
require("../../../../wp-load.php"); 
	
if ($FSREPconfig['AllowXMLFeed'] == '1') {
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_visibility = 1");
	foreach ($Listings as $Listings) {
	echo "<url>\n";
	echo "<loc>".fsrep_listing_url_gen($Listings->listing_id)."</loc>\n";
	echo "</url>\n";
	}
	echo "</urlset>\n";
}
?>