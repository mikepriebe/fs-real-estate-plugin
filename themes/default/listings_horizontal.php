<?php
	$PageContent .= '<div id="vlistings"';
	//if ($Listings->listing_featured == 1) {
		//$PageContent .= ' class="featured"';
	//} else {
		$PageContent .= ' class="standard"';
	//}
	$PageContent .= ' style="width: '.$FSREPconfig['ListingsPerLine'].'%;">';
	if (file_exists(ABSPATH.'/wp-content/uploads/fsrep/houses/medium/'.$Listings->listing_id.'.jpg')) { 
		$PageContent .= '<div id="listingimagev"><a href="'.fsrep_listing_url_gen($Listings->listing_id).'" title="Listing"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/medium/'.$Listings->listing_id.'.jpg" border="0" alt="'.strip_tags(fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay'])).' - '.fsrep_get_address_name($Listings->listing_address_city, 'city').', '.fsrep_get_address_name($Listings->listing_address_province, 'province').'" style="border: 1px solid #999999;" /></a></div>';
	}
	$PageContent .= '<h3><a href="'.fsrep_listing_url_gen($Listings->listing_id).'">'.fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay']).'</a></h3>';
	if ($FSREPconfig['DisplayCurrency'] == 'Yes') { $CurrencyDisplay = ' '.$FSREPconfig['CurrencyType']; } else { $CurrencyDisplay = ''; }
	if ($Listings->listing_price_num != '0.00') {
		$PageContent .= '<p><span class="listingprice">';
		if ($FSREPconfig['ListingPriceID'] != '') { $PageContent .= $FSREPconfig['ListingPriceID'].' '; }
		$PageContent .= $FSREPconfig['Currency'].fsrep_currency_format($Listings->listing_price_num).$CurrencyDisplay.'</span><br />';
	}
	if ($FSREPconfig['EnableCompare'] == 'Yes') { $PageContent .= 'Compare <input type="checkbox" name="'.$Listings->listing_id.'" value="'.$Listings->listing_id.'">'; }
	$PageContent .= '</div>';
?>