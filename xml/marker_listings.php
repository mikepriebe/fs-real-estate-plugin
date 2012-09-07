<?php  

header("Content-type: text/xml; charset=utf-8");

require("../../../../wp-config.php"); 

if (isset($_GET['id'])) {
	$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_visibility = 1 AND listing_long != '' AND listing_lat != '' AND listing_id = ".$_GET['id'];
} elseif (isset($_GET['filter'])) {
	$query = "SELECT * FROM ".$wpdb->prefix."fsrep_listings";
	if ($_GET['filter'] != 0) {
		$query .= ", ".$wpdb->prefix."fsrep_listings_to_fields WHERE ";
		$Filters = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_filters_details WHERE filter_id = ".$_GET['filter']);
		foreach ($Filters as $Filters) {
			$query .= " ".$wpdb->prefix."fsrep_listings_to_fields.listing_id = ".$wpdb->prefix."fsrep_listings.listing_id AND  ".$wpdb->prefix."fsrep_listings_to_fields.field_id = ".$Filters->field_id ." AND ".$wpdb->prefix."fsrep_listings_to_fields.listing_value = '".$Filters->field_values."' AND ";
		}
		if ($FSREPconfig['ListingModeration'] == 'Yes'){
			$query .= " ".$wpdb->prefix."fsrep_listings.listing_visibility = 1 AND ";
		}
		$query = substr($query, 0, -4);
	}
} elseif (isset($_GET['cityid'])) {
	$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_visibility = 1 AND listing_long != '' AND listing_lat != '' AND listing_address_city = ".$_GET['cityid'];
} elseif (isset($_GET['provinceid'])) {
	$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_visibility = 1 AND listing_long != '' AND listing_lat != '' AND listing_address_province = ".$_GET['provinceid'];
} elseif (isset($_GET['countryid'])) {
	$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_visibility = 1 AND listing_long != '' AND listing_lat != '' AND listing_address_country = ".$_GET['countryid'];
} else {
	$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_visibility = 1 AND listing_long != '' AND listing_lat != ''";
}
$result = mysql_query($query);
if (!$result) {  
  die('Invalid query: ' . mysql_error());
} 
echo '<MARKERS>';
while ($row = @mysql_fetch_assoc($result)){  
	if (is_numeric($row['listing_price'])) { $row['listing_price'] = number_format($row['listing_price'], 2, '.', ','); }
	$Image = 'None';
	if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$row['listing_id'].'.jpg')) {
		$Image = get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$row['listing_id'].'.jpg';
	}
	echo '<LISTING>';
	echo '<LABEL>'.$row['listing_address_number'].' '.$row['listing_address_street'].' '.$row['listing_address_city'].' '.$row['listing_address_province'].' '.$row['listing_address_postal'].'</LABEL>';
	echo '<ID>'.$row['listing_id'].'</ID>';
	echo '<NAME>'.fsrep_listing_name_gen($row['listing_id'], $FSREPconfig['Listing Name Display']).'</NAME>';
	echo '<PRICE>'.$FSREPconfig['Currency'].$row['listing_price'].'</PRICE>';
	echo '<LAT>'.$row['listing_lat'].'</LAT>';
	echo '<LONG>'.$row['listing_long'].'</LONG>';
	echo '<IMAGE>'.$Image.'</IMAGE>';
	echo '<URL>'.fsrep_listing_url_gen($row['listing_id']).'</URL>';
	echo '</LISTING>';
} 
echo '</MARKERS>';





	$HTML = '<div style="text-align: center;">';
	if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$row['listing_id'].'.jpg')) {
		$HTML .= '<a href="'.fsrep_listing_url_gen($row['listing_id']).'"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$row['listing_id'].'.jpg" border="0" alt="" style="border: 1px solid #999999;" /></a><br /><br />';
	}
	$HTML .= '<strong>'.fsrep_listing_name_gen($row['listing_id'], $FSREPconfig['Listing Name Display']).'</strong><br />';
	if ($FSREPconfig['ListingPriceID'] != '') { $HTML .= $FSREPconfig['ListingPriceID'].' '; }
	$HTML .= $FSREPconfig['Currency'].$row['listing_price'].'<br />';
	$HTML .= '<a href="'.fsrep_listing_url_gen($row['listing_id']).'">view listing</a><br />';
	$HTML .= '</div>';

?>
