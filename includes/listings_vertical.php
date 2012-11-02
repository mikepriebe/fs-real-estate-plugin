<?php
	$PageContent .= '<div id="listings"';
	if ($Listings->listing_featured == 1) {
		$PageContent .= ' class="featured"';
	} else {
		$PageContent .= ' class="standard"';
	}
	$PageContent .= '>';
	if ($FSREPconfig['EnableCompare'] == 'Yes') { $PageContent .= '<div id="fsrep-compare-checkbox"><input type="checkbox" name="'.$Listings->listing_id.'" value="'.$Listings->listing_id.'"></div>'; }
	$PageContent .= '<h3><a href="'.fsrep_listing_url_gen($Listings->listing_id).'">'.fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay']);
	if ($Listings->listing_address_city != '' && $Listings->listing_address_province != '') {
		$PageContent .= ' - '.fsrep_get_address_name($Listings->listing_address_city, 'city').', '.fsrep_get_address_name($Listings->listing_address_province, 'province');
	}
	$PageContent .= '</a></h3>';
	if (file_exists(ABSPATH.'/wp-content/uploads/fsrep/houses/small/'.$Listings->listing_id.'.jpg')) { 
		$PageContent .= '<div id="listingimage"><a href="'.fsrep_listing_url_gen($Listings->listing_id).'" title="Listing"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$Listings->listing_id.'.jpg" border="0" alt="'.strip_tags(fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay'])).' - '.fsrep_get_address_name($Listings->listing_address_city, 'city').', '.fsrep_get_address_name($Listings->listing_address_province, 'province').'" style="border: 1px solid #999999;" /></a></div>';
	}
	if ($FSREPconfig['DisplayCurrency'] == 'Yes') { $CurrencyDisplay = ' '.$FSREPconfig['CurrencyType']; } else { $CurrencyDisplay = ''; }
	$PageContent .= '<p><span class="listingprice">';
	if ($FSREPconfig['ListingPriceID'] != '') { $PageContent .= $FSREPconfig['ListingPriceID'].' '; }
	$PageContent .= $FSREPconfig['Currency'].fsrep_currency_format($Listings->listing_price_num).$CurrencyDisplay.'</span><br />';
	$PageContent .= $Listings->listing_address_number.' '.$Listings->listing_address_street.' '.fsrep_get_address_name($Listings->listing_address_city, 'city').' '.fsrep_get_address_name($Listings->listing_address_province, 'province').' '.$Listings->listing_address_postal.'</p>';
	$PageContent .= '<p>'.substr(strip_tags(stripslashes($Listings->listing_description)), 0 ,130).'... ';
	$PageContent .= '<a href="'.fsrep_listing_url_gen($Listings->listing_id).'">View Listing</a></p>';
	$PageContent .= '</div>';
?>