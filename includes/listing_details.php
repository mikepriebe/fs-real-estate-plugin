<?php
	$PageContent = '';
	$FSREPShowMap = $FSREPconfig['GoogleMap'];
	if (isset($FSREPMap)) {
		if ($FSREPMap == FALSE) {
			$FSREPShowMap = FALSE;
		}
	}
	
	$ListingDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID[0]);
	if ($FSREPconfig['DisplayCurrency'] == 'Yes') { $CurrencyDisplay = ' '.$FSREPconfig['CurrencyType']; } else { $CurrencyDisplay = ''; }


	
	$PageContent .= '<h1>'.fsrep_listing_name_gen($ListingDetails->listing_id, $FSREPconfig['ListingNameDisplay']).'</h1>';
	$PageContent .= '<span class="listingprice">'; if ($FSREPconfig['ListingPriceID'] != '') { $PageContent .= $FSREPconfig['ListingPriceID'].' '; } $PageContent .= $FSREPconfig['Currency'].$ListingDetails->listing_price.$CurrencyDisplay.'</span><br />';
	$PageContent .= '<div class="listingaddress">'.$ListingDetails->listing_address_number.' '.$ListingDetails->listing_address_street.' '.fsrep_get_address_name($ListingDetails->listing_address_city, 'city').', '.fsrep_get_address_name($ListingDetails->listing_address_province, 'province').' '.$ListingDetails->listing_address_postal.'</div>';


	if (is_numeric($ListingDetails->listing_price)) { $ListingDetails->listing_price = number_format($ListingDetails->listing_price, 2, '.', ','); }
	$PageContent .= '<div id="fsrep-images">';
	if (file_exists(ABSPATH.'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails->listing_id.'.jpg')) { 
		$PageContent .= '<div id="fsrep-main-image"><a id="fsrep-main-image-a" href="'.get_option('home').'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails->listing_id.'.jpg" title="View Slideshow" rel="lightbox[slideshow]"><img id="fsrep-main-image-img" src="'.get_option('home').'/wp-content/uploads/fsrep/houses/'.$ListingDetails->listing_id.'.jpg" alt="'.strip_tags(fsrep_listing_name_gen($ListingDetails->listing_id, $FSREPconfig['ListingNameDisplay'])).'" /></a></div>';
	}
	$PageContent .= '<div id="fsrep-aimages">';
	for ($i=1;$i<=50;$i++) {
		if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails->listing_id.'-'.$i.'.jpg')) {
			$PageContent .= '<div class="fsrep-aimage"><a href="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/large/'.$ListingDetails->listing_id.'-'.$i.'.jpg" title="View Slideshow" rel="lightbox[slideshow]" onmouseover="document.getElementById(\'fsrep-main-image-img\').src=\''.get_option('home').'/wp-content/uploads/fsrep/houses/additional/'.$ListingDetails->listing_id.'-'.$i.'.jpg\'; document.getElementById(\'fsrep-main-image-a\').rel=\'lightbox[slideshow]\'"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails->listing_id.'-'.$i.'.jpg" class="full" /></a></div>';
		}
	}
	if (file_exists(ABSPATH.'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails->listing_id.'.jpg')) { 
		$PageContent .= '<div class="fsrep-aimage"><a href="'.get_option('home').'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails->listing_id.'.jpg" title="View Slideshow" rel="lightbox[slideshow]" onmouseover="document.getElementById(\'fsrep-main-image-img\').src=\''.get_option('home').'/wp-content/uploads/fsrep/houses/'.$ListingDetails->listing_id.'.jpg\'; document.getElementById(\'fsrep-main-image-a\').rel=\'lightbox[slideshow]\'"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$ListingDetails->listing_id.'.jpg" class="full" /></a></div>';
	}
	$PageContent .= '</div>';
	$PageContent .= '</div>';
	
		
	if ($FSREPShowMap == TRUE) {
		$PageContent .= '<h2>Located in '.fsrep_get_address_name($ListingDetails->listing_address_city, 'city').', '.fsrep_get_address_name($ListingDetails->listing_address_province, 'province').'</h2>';
		$PageContent .= '<div id="listings_map" style="width: 100%; height: 300px; border: 1px solid #999999; margin-bottom: 12px;"></div>';
		$PageContent .= '<br />';
	}

	/*
	$AdditionalImages = '';
	for ($i=1;$i<=10;$i++) {
		if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails->listing_id.'-'.$i.'.jpg')) {
			$AdditionalImages .= '<td align="center" valign="center"><a href="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/large/'.$ListingDetails->listing_id.'-'.$i.'.jpg" title="View Slideshow" rel="lightbox[slideshow]" onmouseover="document.getElementById(\'fsrep-main-image-img\').src=\''.get_option('home').'/wp-content/uploads/fsrep/houses/additional/'.$ListingDetails->listing_id.'-'.$i.'.jpg\'; document.getElementById(\'fsrep-main-image-a\').rel=\'lightbox[slideshow]\'"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails->listing_id.'-'.$i.'.jpg" class="full" /></a></td>';
			if ($i == 4) {
				$AdditionalImages .= '</tr><tr>';
			}
		}
	}
	if ($AdditionalImages != '') {
		$PageContent .= '<h2>Photo Gallery</h2>';
		$PageContent .= '<table><tr><td><a href="'.get_option('home').'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails->listing_id.'.jpg" title="View Slideshow" rel="lightbox[slideshow]" onmouseover="document.getElementById(\'fsrep-main-image-img\').src=\''.get_option('home').'/wp-content/uploads/fsrep/houses/'.$ListingDetails->listing_id.'.jpg\'; document.getElementById(\'fsrep-main-image-a\').rel=\'lightbox[slideshow]\'"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$ListingDetails->listing_id.'.jpg" class="full" /></a></td>'.$AdditionalImages.'</tr></table>';
		$PageContent .= '<p>&nbsp;</p>';
	}
	*/
	if ($ListingDetails->listing_description != '') {
		$PageContent .= '<h2>Description</h2>';
		$PageContent .= '<p>'.stripslashes(nl2br($ListingDetails->listing_description)).'</p>';
	}
	$PageContent .= '<p>&nbsp;</p>';

	$Documents = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings_docs WHERE listing_id = ".$ListingDetails->listing_id);
	if (count($Documents) > 0) {
		$PageContent .= '<h2>Documents and Support Material</h2>';
		$PageContent .= '<p>';
		foreach ($Documents as $Documents) {
			$PageContent .= '<a href="'.get_bloginfo('home').'/wp-content/uploads/fsrep/houses/docs/'.$Documents->document_name.'" target="_blank">'.str_replace($ListingDetails->listing_id,'',$Documents->document_name).'</a><br />';
		}
		$PageContent .= '</p>';
		$PageContent .= '<p>&nbsp;</p>';
	}

	if ($ListingDetails->listing_virtual_tour != '' || $ListingDetails->listing_slideshow != '' || $ListingDetails->listing_video != '') {
		$PageContent .= '<h2>Videos and Slideshows</h2>';
		$PageContent .= '<p>';
		if ($ListingDetails->listing_virtual_tour != '') {
			$PageContent .= '<a href="'.$ListingDetails->listing_virtual_tour.'" rel="nofollow" target="_blank">Virtual Tour</a><br />';
		}
		if ($ListingDetails->listing_slideshow != '') {
			$PageContent .= '<a href="'.$ListingDetails->listing_slideshow.'" rel="nofollow" target="_blank">Slideshow</a><br />';
		}
		if ($ListingDetails->listing_video != '') {
			$PageContent .= '<a href="'.$ListingDetails->listing_video.'" rel="nofollow" target="_blank">Video</a><br />';
		}
		$PageContent .= '</p>';
		$PageContent .= '<p>&nbsp;</p>';
	}

	$PageContent .= '<h2>Additional Details</h2>';
	$PageContent .= '<p><strong>Listing #: </strong>'.$ListingDetails->listing_id.'</p>';
	$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
	foreach ($Fields as $Fields) {
		$HouseFieldInfo = $wpdb->get_var("SELECT listing_value FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = $Fields->field_id AND listing_id = $ListingDetails->listing_id");
		if ($HouseFieldInfo != '') {
			$PageContent .= '<p><strong>'.$Fields->field_name.': </strong>'.$HouseFieldInfo.'</p>';
		}
	}
	$PageContent .= '<p>&nbsp;</p>';
	
	if ($ListingDetails->listing_contact_display == 'Display Contact Information') {
		$PageContent .= '<h2>Contact Details</h2>';
		if ($ListingDetails->listing_contact_name != '') { $PageContent .= '<p><strong>Contact Name: </strong>'.$ListingDetails->listing_contact_name.'</p>'; }
		if ($ListingDetails->listing_contact_email != '') { $PageContent .= '<p><strong>Contact Email: </strong><a href="mailto:'.$ListingDetails->listing_contact_email.'">'.$ListingDetails->listing_contact_email.'</a></p>'; }
		if ($ListingDetails->listing_contact_home_phone != '') { $PageContent .= '<p><strong>Contact Phone: </strong>'.$ListingDetails->listing_contact_home_phone.'</p>'; }
		if ($ListingDetails->listing_contact_cell_phone != '') { $PageContent .= '<p><strong>Contact Cell Phone: </strong>'.$ListingDetails->listing_contact_cell_phone.'</p>'; }
		if ($ListingDetails->listing_contact_special_instructions != '') { $PageContent .= '<p><strong>Special Instructions: </strong>'.$ListingDetails->listing_contact_special_instructions.'</p>'; }
	} elseif ($ListingDetails->listing_contact_display == 'Display Contact Form') {
		$PageContent .= '<h2>Contact Form</h2>';
		include("listing_contact_form.php");
	}
	if ($FSREPconfig['ContactInfoNote'] != '') { $PageContent .= '<p><strong>'.$FSREPconfig['ContactInfoNote'].'</strong></p>'; }
	
?>