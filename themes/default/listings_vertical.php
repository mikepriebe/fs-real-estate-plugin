<?php
	$PageContent .= '<div id="listings"';
	//if ($Listings->listing_featured == 1) {
		//$PageContent .= ' class="featured"';
	//} else {
		$PageContent .= ' class="standard"';
	//}
	$PageContent .= '>';
	if ($FSREPconfig['EnableCompare'] == 'Yes') { $PageContent .= '<div id="fsrep-compare-checkbox"><input type="checkbox" name="'.$Listings->listing_id.'" value="'.$Listings->listing_id.'"></div>'; }
	$ListingTarget = ''; if (function_exists('fsrep_pro_listing_target')) { $ListingTarget = fsrep_pro_listing_target(); }
	$PageContent .= '<h3><a href="'.fsrep_listing_url_gen($Listings->listing_id).'"'.$ListingTarget.'>'.fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay']);
	if ($Listings->listing_address_city != '' && $Listings->listing_address_province != '') {
		$PageContent .= ' - '.fsrep_get_address_name($Listings->listing_address_city, 'city').', '.fsrep_get_address_name($Listings->listing_address_province, 'province');
	}
	$PageContent .= '</a></h3>';
	if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/small/'.$Listings->listing_id.'.jpg')) { 
		$PageContent .= '<div id="listingimage"><a href="'.fsrep_listing_url_gen($Listings->listing_id).'"'.$ListingTarget.' title="Listing"><img src="'.$WPUploadDir['baseurl'].'/fsrep/houses/small/'.$Listings->listing_id.'.jpg" border="0" alt="'.strip_tags(fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay'])).' - '.fsrep_get_address_name($Listings->listing_address_city, 'city').', '.fsrep_get_address_name($Listings->listing_address_province, 'province').'" style="border: 1px solid #999999;" /></a></div>';
	}
	if ($FSREPconfig['DisplayCurrency'] == 'Yes') { $CurrencyDisplay = ' '.$FSREPconfig['CurrencyType']; } else { $CurrencyDisplay = ''; }
	if ($Listings->listing_price_num != '0.00') {
		$PageContent .= '<p><span class="listingprice">';
		if ($FSREPconfig['ListingPriceID'] != '') { $PageContent .= fsrep_text_translator('FireStorm Real Estate Plugin', $FSREPconfig['ListingPriceID'].' Label', $FSREPconfig['ListingPriceID']).' '; }
		$PageContent .= $FSREPconfig['Currency'].fsrep_currency_format($Listings->listing_price_num).$CurrencyDisplay.'</span><br />';
	}
	$PageContent .= $Listings->listing_address_number.' '.$Listings->listing_address_street.' '.fsrep_get_address_name($Listings->listing_address_city, 'city').' '.fsrep_get_address_name($Listings->listing_address_province, 'province').' '.$Listings->listing_address_postal.'</p>';
	$PageContent .= '<p>'.substr(strip_tags(stripslashes($Listings->listing_description)), 0 ,130).'...</p>';
	if (function_exists('fsrep_pro_listing_fields')) { fsrep_pro_listing_fields($Listings->listing_id); }
	$PageContent .= '<a href="'.fsrep_listing_url_gen($Listings->listing_id).'"'.$ListingTarget.'>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'View Listings Label', 'View Listing').'</a>';
	$PageContent .= '</div>';
?>