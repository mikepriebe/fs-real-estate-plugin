<?php
	$ListingTarget = ''; if (function_exists('fsrep_pro_listing_target')) { $ListingTarget = fsrep_pro_listing_target(); }
	$PageContent .= '<a href="'.fsrep_listing_url_gen($Listings->listing_id).'"'.$ListingTarget.' style="display: block; color: inherit; text-decoration: none;"><div id="listings"';
	//if ($Listings->listing_featured == 1) {
		//$PageContent .= ' class="featured"';
	//} else {
		$PageContent .= ' class="standard"';
	//}
	$PageContent .= '>';
	if (function_exists('fsrep_pro_compare_checkbox') && $FSREPconfig['EnableCompare'] == 'Yes') { $PageContent .= fsrep_pro_compare_checkbox ($Listings->listing_id); }
	$PageContent .= '<h3>'.fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay']);
	if ($Listings->listing_address_city != '' && $Listings->listing_address_province != '') {
		$PageContent .= ' - '.fsrep_get_address_name($Listings->listing_address_city, 'city').', '.fsrep_get_address_name($Listings->listing_address_province, 'province');
	}
	$PageContent .= '</h3>';
	if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/small/'.$Listings->listing_id.'.jpg')) { 
		$PageContent .= '<div id="listingimage"><img src="'.$WPUploadDir['baseurl'].'/fsrep/houses/small/'.$Listings->listing_id.'.jpg" border="0" alt="'.strip_tags(fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay'])).' - '.fsrep_get_address_name($Listings->listing_address_city, 'city').', '.fsrep_get_address_name($Listings->listing_address_province, 'province').'" style="border: 1px solid #999999;" /></div>';
	}
	if ($FSREPconfig['DisplayCurrency'] == 'Yes') { $CurrencyDisplay = ' '.$FSREPconfig['CurrencyType']; } else { $CurrencyDisplay = ''; }
	if ($Listings->listing_price_num != '0.00') {
		$PageContent .= '<p><span class="listingprice">';
		if ($FSREPconfig['ListingPriceID'] != '') { $PageContent .= fsrep_text_translator('FireStorm Real Estate Plugin', $FSREPconfig['ListingPriceID'].' Label', $FSREPconfig['ListingPriceID']).' '; }
		$PageContent .= $FSREPconfig['Currency'].fsrep_currency_format($Listings->listing_price_num).$CurrencyDisplay.'</span><br />';
	}
	$PageContent .= $Listings->listing_address_number.' '.$Listings->listing_address_street.' '.fsrep_get_address_name($Listings->listing_address_city, 'city').' '.fsrep_get_address_name($Listings->listing_address_province, 'province').' '.$Listings->listing_address_postal.'</p>';
	if ($Listings->listing_description != '') { $PageContent .= '<p>'.substr(strip_tags(stripslashes($Listings->listing_description)), 0 ,130); if (strlen($Listings->listing_description) > 130 ) { $PageContent .= '...'; } $PageContent .= '</p>'; }
	if (function_exists('fsrep_pro_listing_fields')) { fsrep_pro_listing_fields($Listings->listing_id); }
	$PageContent .= '</div></a>';
?>