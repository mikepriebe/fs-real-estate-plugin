<?php  

require("../../../../wp-config.php"); 

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node); 
$connection=mysql_connect (DB_HOST, DB_USER, DB_PASSWORD);
if (!$connection) {  die('Not connected : ' . mysql_error());} 
$db_selected = mysql_select_db(DB_NAME, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
} 
if (isset($_GET['id'])) {
	$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_long != '' AND listing_lat != '' AND listing_id = ".$_GET['id'];
} else {
	$query = "SELECT * FROM ".$table_prefix."fsrep_listings WHERE listing_long != '' AND listing_lat != ''";
}
$result = mysql_query($query);
if (!$result) {  
  die('Invalid query: ' . mysql_error());
} 
header("Content-type: text/xml"); 
while ($row = @mysql_fetch_assoc($result)){  
	if (is_numeric($row['listing_price'])) { $row['listing_price'] = number_format($row['listing_price'], 2, '.', ','); }
	$HTML = '<div style="text-align: center;">';
	if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$row['listing_id'].'.jpg')) {
		$HTML .= '<a href="'.fsrep_listing_url_gen($row['listing_id']).'"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$row['listing_id'].'.jpg" border="0" alt="" style="border: 1px solid #999999;" /></a><br /><br />';
	}
	$HTML .= '<strong>'.fsrep_listing_name_gen($row['listing_id'], $FSREPconfig['Listing Name Display']).'</strong><br />';
	if ($FSREPconfig['ListingPriceID'] != '') { $HTML .= $FSREPconfig['ListingPriceID'].' '; }
	$HTML .= '$'.$row['listing_price'].'<br />';
	$HTML .= '<a href="'.fsrep_listing_url_gen($row['listing_id']).'">view listing</a><br />';
	$HTML .= '</div>';
  $node = $dom->createElement("marker");  
  $newnode = $parnode->appendChild($node);   
  $newnode->setAttribute("html", $HTML);  
  $newnode->setAttribute("label", $row['listing_address_number'].' '.$row['listing_address_street'].' '.$row['listing_address_city'].' '.$row['listing_address_province'].' '.$row['listing_address_postal']);  
  $newnode->setAttribute("lat", $row['listing_lat']);  
  $newnode->setAttribute("lng", $row['listing_long']);  
} 
echo $dom->saveXML();
?>
