<?php  

header("Content-type: text/xml; charset=utf-8");

require("../../../../wp-config.php"); 

// BACKEND FUNCTIONS
require_once("../common_functions.php");
//  SPAM CHECK
if (isset($_POST)) { if (fsrep_spam_check($_POST) == TRUE) { unset($_POST); } }
if (isset($_GET)) { if (fsrep_spam_check($_GET) == TRUE) { unset($_GET); } }

if (isset($_GET['id'])) {
	if (is_numeric($_GET['id'])) {
		$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_long != '' AND listing_lat != '' AND listing_id = ".$_GET['id'];
	}
} elseif (isset($_GET['search'])) {
	if (is_numeric($_GET['search'])) {
		$query = $wpdb->get_var("SELECT query_value FROM ".$table_prefix."fsrep_search_queries WHERE query_id = ".$_GET['search']);
		//$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_search_queries WHERE query_id = ".$_GET['search']);
	}
} elseif (isset($_GET['filter'])) {
	if (is_numeric($_GET['filter'])) {
		$query = "SELECT * FROM ".$wpdb->prefix."fsrep_listings";
		if ($_GET['filter'] != 0) {
			$FieldSQL = "SELECT DISTINCT t1.listing_id FROM ".$wpdb->prefix."fsrep_listings_to_fields t1, ".$wpdb->prefix."fsrep_listings WHERE EXISTS ";
			$Filters = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_filters_details WHERE filter_id = ".$_GET['filter']);
			$FieldID = '';
			$ListingValue = '';
			$FieldValueSQL = '';
			foreach ($Filters as $Filters) {
				$FieldID .= "'$Filters->field_id', ";
				$ListingValue .= "'$Filters->field_values', ";
				$FieldValueSQL .= "(SELECT * FROM wp_fsrep_listings_to_fields WHERE field_id = '$Filters->field_id' AND listing_value = '$Filters->field_values' AND listing_id = t1.listing_id) AND EXISTS ";
			}
			$FieldID = substr($FieldID, 0, -2);
			$ListingValue = substr($ListingValue, 0, -2);
			$FieldSQL .= substr($FieldValueSQL, 0, -11);
			$MatchingListings = $wpdb->get_results($FieldSQL);
			$MatchingListingsID = '';
			foreach ($MatchingListings as $MatchingListings) {
				$MatchingListingsID .= "'".$MatchingListings->listing_id."', ";
			}
			$MatchingListingsID = substr($MatchingListingsID, 0, -2);
			$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_long != '' AND listing_lat != '' AND listing_id IN ($MatchingListingsID)";
		}
	}
} elseif (isset($_GET['cityid'])) {
	if (is_numeric($_GET['cityid'])) {
		$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_long != '' AND listing_lat != '' AND listing_address_city = ".$_GET['cityid'];
	}
} elseif (isset($_GET['provinceid'])) {
	if (is_numeric($_GET['provinceid'])) {
		$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_long != '' AND listing_lat != '' AND listing_address_province = ".$_GET['provinceid'];
	}
} elseif (isset($_GET['countryid'])) {
	if (is_numeric($_GET['countryid'])) {
		$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_long != '' AND listing_lat != '' AND listing_address_country = ".$_GET['countryid'];
	}
} else {
	if (isset($_SESSION['FSREPSearch']) && $_SESSION['FSREPSearch'] != '') { 
		$query = $_SESSION['FSREPSearch']; 
	} else {
		$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_long != '' AND listing_lat != ''";
	}
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
	echo '<LABEL>'.htmlspecialchars($row['listing_address_number'].' '.$row['listing_address_street'].' '.$row['listing_address_city'].' '.$row['listing_address_province'].' '.$row['listing_address_postal']).'</LABEL>';
	echo '<ID>'.$row['listing_id'].'</ID>';
	echo '<NAME>'.htmlspecialchars(fsrep_listing_name_gen($row['listing_id'], '')).'</NAME>';
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
	$HTML .= '<strong>'.fsrep_listing_name_gen($row['listing_id'], '').'</strong><br />';
	if ($FSREPconfig['ListingPriceID'] != '') { $HTML .= $FSREPconfig['ListingPriceID'].' '; }
	$HTML .= $FSREPconfig['Currency'].$row['listing_price'].'<br />';
	$HTML .= '<a href="'.fsrep_listing_url_gen($row['listing_id']).'">view listing</a><br />';
	$HTML .= '</div>';

?>
