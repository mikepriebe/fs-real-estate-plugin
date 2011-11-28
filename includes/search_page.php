<?php
$AdvSearchError = '';
$FoundListings = 0;
if (isset($_POST['fsrepw-widget-search-submit'])){
	// RUN SEARCH
	$SearchSQL = 'SELECT * FROM '.$wpdb->prefix.'fsrep_listings, '.$wpdb->prefix.'fsrep_listings_to_fields WHERE ';
	if ($_POST['fsrepw-search-country'] != '') {
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_country = '.$_POST['fsrepw-search-country'].' AND '; // COUNTRY
	}
	if ($_POST['fsrepw-search-province'] != '') {
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_province = '.$_POST['fsrepw-search-province'].' AND '; // PROVINCE
	}
	if ($_POST['fsrepw-search-city'] != '') {
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_city = '.$_POST['fsrepw-search-city'].' AND '; // CITY
	}
	if ($_POST['fsrepw-search-price-range2'] != '0' && $_POST['fsrepw-search-price-range2'] != '' && $_POST['fsrepw-search-price-range'] != '') {
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_price >= '.$_POST['fsrepw-search-price-range'].' AND '; // PRICE LOW
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_price <= '.$_POST['fsrepw-search-price-range2'].' AND '; // PRICE HIGH
	}
	
	$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings_to_fields.listing_id = '.$wpdb->prefix.'fsrep_listings.listing_id AND '; // PRICE LOW

	$SLFields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields WHERE field_search = 1 ORDER BY field_order");
	$FieldSearchSQL = '';
	foreach($SLFields as $SLFields) {
		if ($_POST['field-'.$SLFields->field_id] != '') {
			$FieldSearchSQL .= '"'.$_POST['field-'.$SLFields->field_id].'", '; // PRICE LOW
		}
	}
	if ($FieldSearchSQL != '') {
		$FieldSearchSQL = substr($FieldSearchSQL, 0, -2);
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings_to_fields.listing_value IN('.$FieldSearchSQL.') '; // PRICE LOW
	}
	
	if (substr($SearchSQL, -4) == ' OR ') {
		$SearchSQL = substr($SearchSQL, 0, -4);
	}
	if (substr($SearchSQL, -5) == ' AND ') {
		$SearchSQL = substr($SearchSQL, 0, -5);
	}
	if (substr($SearchSQL, -7) == ' WHERE ') {
		$SearchSQL = substr($SearchSQL, 0, -7);
	}
	$SearchSQL .= ' GROUP BY '.$wpdb->prefix.'fsrep_listings.listing_id ORDER BY '.$wpdb->prefix.'fsrep_listings.listing_id DESC';
}

// PRINT LISTINGS
if (isset($SearchSQL)) {
	//   fsrep_listings($category_id, $value, $type, $fpagination, $hpagination, $CategoryPE)
	echo fsrep_listings(0, $SearchSQL, 'search', 0, 0, 0, TRUE);
	//echo $SearchSQL;
}

?>