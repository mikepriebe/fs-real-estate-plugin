<?php

$ShowMap = $FSREPconfig['GoogleMap'];
$fsrepsearchwidgetoptions = get_option('fsrep_search_widget');
if ($fsrepsearchwidgetoptions['fsrep-search-map'] == 'Yes') {
	$ShowMap = FALSE;
}
if ($FSREPconfig['SearchHeader'] == 'Map and Search' || $FSREPconfig['SearchHeader'] == 'Just a Map') {
	$ShowMap = TRUE;
} else {
	$ShowMap = FALSE;
}
if ($FSREPconfig['SearchHeader'] == 'Map and Search' || $FSREPconfig['SearchHeader'] == 'Just the Search') {
	if (isset($_POST['EnableAdvancedSearch'])) {	
		if ($_POST['EnableAdvancedSearch'] == 'Yes') {
			echo fsrep_search_box();
		}
	}
}

$AdvSearchError = '';
$FoundListings = 0;
if (isset($_POST['fsrep-search-submit'])){
	$FSREPPostName = 'fsrep-search-';
} elseif (isset($_POST['fsrepw-widget-search-submit'])){
	$FSREPPostName = 'fsrepw-search-';
}


if (isset($FSREPPostName)){
	// RUN SEARCH
	$SearchSQL = 'SELECT * FROM '.$wpdb->prefix.'fsrep_listings, '.$wpdb->prefix.'fsrep_listings_to_fields WHERE ';
	if ($_POST[$FSREPPostName.'country'] != '') {
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_country = '.$_POST[$FSREPPostName.'country'].' AND '; // COUNTRY
	}
	if ($_POST[$FSREPPostName.'province'] != '') {
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_province = '.$_POST[$FSREPPostName.'province'].' AND '; // PROVINCE
	}
	if ($_POST[$FSREPPostName.'city'] != '') {
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_address_city = '.$_POST[$FSREPPostName.'city'].' AND '; // CITY
	}
	if ($_POST[$FSREPPostName.'price-range2'] != '0' && $_POST[$FSREPPostName.'price-range2'] != '' && $_POST[$FSREPPostName.'price-range'] != '') {
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_price >= '.$_POST[$FSREPPostName.'price-range'].' AND '; // PRICE LOW
		$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings.listing_price <= '.$_POST[$FSREPPostName.'price-range2'].' AND '; // PRICE HIGH
	}
	
	$SearchSQL .= ' '.$wpdb->prefix.'fsrep_listings_to_fields.listing_id = '.$wpdb->prefix.'fsrep_listings.listing_id AND '; // PRICE LOW

	$SLFields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields WHERE field_search = 1 ORDER BY field_order");
	$FieldSearchSQL = '';
	foreach($SLFields as $SLFields) {
		if (isset($_POST['field-'.$SLFields->field_id])) {
			if ($_POST['field-'.$SLFields->field_id] != '') {
				$FieldSearchSQL .= '"'.$_POST['field-'.$SLFields->field_id].'", '; // PRICE LOW
			}
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
	echo fsrep_listings_display(0, $SearchSQL, 'search', 0, 0, 0, $ShowMap, 0);
}

?>