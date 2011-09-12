<?php
	$ListingDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID[0]);
	echo '<div id="listings_map" style="width: 100%; height: 300px; border: 1px solid #999999; margin-bottom: 12px;"></div>';
	echo '<div id="fsrep-images">';
	if (file_exists(ABSPATH.'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails->listing_id.'.jpg')) { 
		echo '<div id="fsrep-main-image"><a href="'.get_option('home').'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails->listing_id.'.jpg" title="View Slideshow" rel="lightbox[slideshow]"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/'.$ListingDetails->listing_id.'.jpg" /></a></div>';
		$AdditionalImages = '';
		for ($i=1;$i<=10;$i++) {
			if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails->listing_id.'-'.$i.'.jpg')) {
				$AdditionalImages .= '<a href="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/large/'.$ListingDetails->listing_id.'-'.$i.'.jpg" title="View Slideshow" rel="lightbox[slideshow]"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails->listing_id.'-'.$i.'.jpg" class="full" /></a>';
			}
		}
		if ($AdditionalImages != '') {
			echo '<div id="fsrep-add-image">'.$AdditionalImages.'</div>';
		}
	}
	echo '</div>';
	echo '<h1>'.fsrep_listing_name_gen($ListingDetails->listing_id, $FSREPconfig['Listing Name Display']).'</h1>';
	echo '<p><strong>Asking Price: </strong>$'.number_format($ListingDetails->listing_price, 2, '.', ',').'</p>';
	echo '<p>'.$ListingDetails->listing_address_number.' '.$ListingDetails->listing_address_street.' '.fsrep_get_address_name($ListingDetails->listing_address_city, 'city').' '.fsrep_get_address_name($ListingDetails->listing_address_province, 'province').' '.$ListingDetails->listing_address_postal.'</p>';
	echo '<p>&nbsp;</p>';
	echo '<p>'.stripslashes(nl2br($ListingDetails->listing_description)).'</p>';
	
	$OpenHouse = $wpdb->get_row("SELECT *, DATE_FORMAT(open_house_time_start, '%W %M %D, %Y') as ohdate, DATE_FORMAT(open_house_time_start, '%l:%i%p') as ohstart, DATE_FORMAT(open_house_time_end, '%l:%i%p') as ohend FROM ".$wpdb->prefix."fsrep_open_houses WHERE listing_id = ".$ListingDetails->listing_id);
	if (count($OpenHouse) > 0) {
		echo '<h2>Upcoming Open House</h2>';
		echo '<p>'.$OpenHouse->ohstart.' to '.$OpenHouse->ohend.' on '.$OpenHouse->ohdate.'</p>';
		echo '<p>&nbsp;</p>';
	}
	echo '<p>&nbsp;</p>';

	echo '<h2>Additional Details</h2>';
	echo '<p><strong>Listing #: </strong>'.$ListingDetails->listing_id.'</p>';
	$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
	foreach ($Fields as $Fields) {
		$HouseFieldInfo = $wpdb->get_var("SELECT listing_value FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = $Fields->field_id AND listing_id = $ListingDetails->listing_id");
		if ($HouseFieldInfo != '') {
			echo '<p><strong>'.$Fields->field_name.': </strong>'.$HouseFieldInfo.'</p>';
		}
	}
	echo '<p>&nbsp;</p>';
	
	if ($ListingDetails->listing_contact_display == 'Display Contact Information') {
		echo '<h2>Contact Details</h2>';
		if ($ListingDetails->listing_contact_name != '') { echo '<p><strong>Contact Name: </strong>'.$ListingDetails->listing_contact_name.'</p>'; }
		if ($ListingDetails->listing_contact_email != '') { echo '<p><strong>Contact Email: </strong><a href="mailto:'.$ListingDetails->listing_contact_email.'">'.$ListingDetails->listing_contact_email.'</a></p>'; }
		if ($ListingDetails->listing_contact_home_phone != '') { echo '<p><strong>Contact Phone: </strong>'.$ListingDetails->listing_contact_home_phone.'</p>'; }
		if ($ListingDetails->listing_contact_cell_phone != '') { echo '<p><strong>Contact Cell Phone: </strong>'.$ListingDetails->listing_contact_cell_phone.'</p>'; }
		if ($ListingDetails->listing_contact_special_instructions != '') { echo '<p><strong>Special Instructions: </strong>'.$ListingDetails->listing_contact_special_instructions.'</p>'; }
	} elseif ($ListingDetails->listing_contact_display == 'Display Contact Form') {
		echo '<h2>Contact Form</h2>';
		include("listing_contact_form.php");
	}
	
?>