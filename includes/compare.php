<?php
if(isset($_POST)) {
	$count = 0;
	unset($_POST['submit']);
	foreach($_POST as $Listings) {
		if (is_numeric($Listings)) {
			$count++;
			$ListingDetails[$count] = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$Listings);
		}
	}
}

if ($count == 0) {
	$PageContent .= '<p>No listings were selected to compare.</p>';
} else {
	if ($FSREPconfig['DisplayCurrency'] == 'Yes') { $CurrencyDisplay = ' '.$FSREPconfig['CurrencyType']; } else { $CurrencyDisplay = ''; }
	
	
	$TDWidth = 100 / $count;
	$PageContent .= '<div id="fsrep-compare">';
	$PageContent .= '<table width="100%">';
	
	// LISTING ID
	$PageContent .= '<tr>'; for($i=1;$i<=$count;$i++) { $PageContent .= '<td width="'.$TDWidth.'">Listing ID # '.$ListingDetails[$i]->listing_id.'</td>'; } $PageContent .= '</tr>';
	
	// LISTING LINK
	$PageContent .= '<tr>'; for($i=1;$i<=$count;$i++) { $PageContent .= '<td width="'.$TDWidth.'"><a href="'.fsrep_listing_url_gen($ListingDetails[$i]->listing_id).'">view listing</a></td>'; } $PageContent .= '</tr>';
	
	// MAIN IMAGE + SLIDESHOW
	$PageContent .= '<tr>'; for($i=1;$i<=$count;$i++) { 
	$PageContent .= '<td width="'.$TDWidth.'">';
	if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$ListingDetails[$i]->listing_id.'.jpg')) {
		//$PageContent .= '<a id="fsrep-main-image-a" href="'.get_option('home').'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails[$i]->listing_id.'.jpg" title="View Slideshow" rel="lightbox[slideshow'.$ListingDetails[$i]->listing_id.']"><img id="fsrep-main-image-img" src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$ListingDetails[$i]->listing_id.'.jpg" /></a>';
		$PageContent .= '<a href="'.fsrep_listing_url_gen($ListingDetails[$i]->listing_id).'" title="View Listing"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$ListingDetails[$i]->listing_id.'.jpg" /></a>';
	} else {
		$PageContent .= 'No Image';
	}
	$PageContent .= '</td>'; 
	} $PageContent .= '</tr>';
	/*
	for($i=1;$i<=$count;$i++) {
		$AdditionalImages = '';
		for ($i=1;$i<=10;$i++) {
			if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails[$i]->listing_id.'-'.$i.'.jpg')) {
				$AdditionalImages .= '<a href="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/large/'.$ListingDetails[$i]->listing_id.'-'.$i.'.jpg" title="View Slideshow" rel="lightbox[slideshow'.$ListingDetails[$i]->listing_id.']" onmouseover="document.getElementById(\'fsrep-main-image-img\').src=\''.get_option('home').'/wp-content/uploads/fsrep/houses/additional/'.$ListingDetails[$i]->listing_id.'-'.$i.'.jpg\'; document.getElementById(\'fsrep-main-image-a\').rel=\'lightbox[slideshow]\'"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails[$i]->listing_id.'-'.$i.'.jpg" class="full" /></a>';
			}
		}
		if ($AdditionalImages != '') {
			$PageContent .= '<tr><td class="fsrep-compare-hide">'.$AdditionalImages.'</td></tr>';
		}
	}
	*/
	
	// LISTING LABEL
	$PageContent .= '<tr>'; for($i=1;$i<=$count;$i++) { $PageContent .= '<td width="'.$TDWidth.'">'.$ListingDetails[$i]->listing_label.'</td>'; } $PageContent .= '</tr>';
	
	// LISTING PRICE
	
	$PageContent .= '<tr>'; for($i=1;$i<=$count;$i++) { $ListingPrice = ''; if ($ListingDetails[$i]->listing_price_num != '0.00') { $ListingPrice = $FSREPconfig['Currency'].fsrep_currency_format($ListingDetails[$i]->listing_price_num).$CurrencyDisplay; } $PageContent .= '<td width="'.$TDWidth.'">'.$ListingPrice.'</td>'; } $PageContent .= '</tr>';
	
	// LISTING ADDRESS
	$PageContent .= '<tr>'; for($i=1;$i<=$count;$i++) { $PageContent .= '<td width="'.$TDWidth.'">'.$ListingDetails[$i]->listing_address_number.' '.$ListingDetails[$i]->listing_address_street.'<br />'.fsrep_get_address_name($ListingDetails[$i]->listing_address_city, 'city').', '.fsrep_get_address_name($ListingDetails[$i]->listing_address_province, 'province').' '.$ListingDetails[$i]->listing_address_postal.'</td>'; } $PageContent .= '</tr>';
	
	// LISTING DESCRIPTION
	$PageContent .= '<tr>'; for($i=1;$i<=$count;$i++) { 
	$ListingDescription = substr(stripslashes(nl2br($ListingDetails[$i]->listing_description)), 0, 300);
	if (strlen($ListingDetails[$i]->listing_description) > 300) { $ListingDescription .= '...'; }
	$PageContent .= '<td width="'.$TDWidth.'">'.$ListingDescription.'</td>'; 
	} $PageContent .= '</tr>';
	
	// CUSTOM FIELDS
	$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
	foreach ($Fields as $Fields) {
		$FieldDisplay = FALSE;
		$FieldValues = '<tr>';
		for($i=1;$i<=$count;$i++) { 
			$HouseFieldInfo = $wpdb->get_var("SELECT listing_value FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = ".$Fields->field_id." AND listing_id = ".$ListingDetails[$i]->listing_id);
			if ($HouseFieldInfo != '') {
				$FieldDisplay = TRUE;
				$FieldValues .= '<td width="'.$TDWidth.'">'.$Fields->field_name.': '.$HouseFieldInfo.'</td>';
			} else {
				$FieldValues .= '<td width="'.$TDWidth.'">&nbsp;</td>';
			}
		}
		$FieldValues .= '</tr>';
		if ($FieldDisplay == TRUE) {
			$PageContent .= $FieldValues;
		}
	}
	
	// LISTING LINK
	$PageContent .= '<tr>'; for($i=1;$i<=$count;$i++) { $PageContent .= '<td width="'.$TDWidth.'"><a href="'.fsrep_listing_url_gen($ListingDetails[$i]->listing_id).'">view listing</a></td>'; } $PageContent .= '</tr>';
	
	$PageContent .= '</table>';
	$PageContent .= '</div>';
}
?>