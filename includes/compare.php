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


$TDWidth = 100 / $count;
echo '<div id="fsrep-compare">';
echo '<table width="100%">';

// LISTING ID
echo '<tr>'; for($i=1;$i<=$count;$i++) { echo '<td width="'.$TDWidth.'">Listing ID # '.$ListingDetails[$i]->listing_id.'</td>'; } echo '</tr>';

// LISTING LINK
echo '<tr>'; for($i=1;$i<=$count;$i++) { echo '<td width="'.$TDWidth.'"><a href="'.fsrep_listing_url_gen($ListingDetails[$i]->listing_id).'">view listing</a></td>'; } echo '</tr>';

// MAIN IMAGE + SLIDESHOW
echo '<tr>'; for($i=1;$i<=$count;$i++) { 
echo '<td width="'.$TDWidth.'">';
if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$ListingDetails[$i]->listing_id.'.jpg')) {
	//echo '<a id="fsrep-main-image-a" href="'.get_option('home').'/wp-content/uploads/fsrep/houses/large/'.$ListingDetails[$i]->listing_id.'.jpg" title="View Slideshow" rel="lightbox[slideshow'.$ListingDetails[$i]->listing_id.']"><img id="fsrep-main-image-img" src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$ListingDetails[$i]->listing_id.'.jpg" /></a>';
	echo '<a href="'.fsrep_listing_url_gen($ListingDetails[$i]->listing_id).'" title="View Listing"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$ListingDetails[$i]->listing_id.'.jpg" /></a>';
} else {
	echo 'No Image';
}
echo '</td>'; 
} echo '</tr>';
/*
for($i=1;$i<=$count;$i++) {
	$AdditionalImages = '';
	for ($i=1;$i<=10;$i++) {
		if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails[$i]->listing_id.'-'.$i.'.jpg')) {
			$AdditionalImages .= '<a href="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/large/'.$ListingDetails[$i]->listing_id.'-'.$i.'.jpg" title="View Slideshow" rel="lightbox[slideshow'.$ListingDetails[$i]->listing_id.']" onmouseover="document.getElementById(\'fsrep-main-image-img\').src=\''.get_option('home').'/wp-content/uploads/fsrep/houses/additional/'.$ListingDetails[$i]->listing_id.'-'.$i.'.jpg\'; document.getElementById(\'fsrep-main-image-a\').rel=\'lightbox[slideshow]\'"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/small/'.$ListingDetails[$i]->listing_id.'-'.$i.'.jpg" class="full" /></a>';
		}
	}
	if ($AdditionalImages != '') {
		echo '<tr><td class="fsrep-compare-hide">'.$AdditionalImages.'</td></tr>';
	}
}
*/

// LISTING LABEL
echo '<tr>'; for($i=1;$i<=$count;$i++) { echo '<td width="'.$TDWidth.'">'.$ListingDetails[$i]->listing_label.'</td>'; } echo '</tr>';

// LISTING PRICE

echo '<tr>'; for($i=1;$i<=$count;$i++) { if (is_numeric($ListingDetails[$i]->listing_price)) { $ListingDetails[$i]->listing_price = number_format($ListingDetails[$i]->listing_price, 2, '.', ','); } echo '<td width="'.$TDWidth.'">'.$FSREPconfig['Currency'].$ListingDetails[$i]->listing_price.'</td>'; } echo '</tr>';

// LISTING ADDRESS
echo '<tr>'; for($i=1;$i<=$count;$i++) { echo '<td width="'.$TDWidth.'">'.$ListingDetails[$i]->listing_address_number.' '.$ListingDetails[$i]->listing_address_street.'<br />'.fsrep_get_address_name($ListingDetails[$i]->listing_address_city, 'city').', '.fsrep_get_address_name($ListingDetails[$i]->listing_address_province, 'province').' '.$ListingDetails[$i]->listing_address_postal.'</td>'; } echo '</tr>';

// LISTING DESCRIPTION
echo '<tr>'; for($i=1;$i<=$count;$i++) { 
$ListingDescription = substr(stripslashes(nl2br($ListingDetails[$i]->listing_description)), 0, 300);
if (strlen($ListingDetails[$i]->listing_description) > 300) { $ListingDescription .= '...'; }
echo '<td width="'.$TDWidth.'">'.$ListingDescription.'</td>'; 
} echo '</tr>';

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
		echo $FieldValues;
	}
}

// LISTING LINK
echo '<tr>'; for($i=1;$i<=$count;$i++) { echo '<td width="'.$TDWidth.'"><a href="'.fsrep_listing_url_gen($ListingDetails[$i]->listing_id).'">view listing</a></td>'; } echo '</tr>';

echo '</table>';
echo '</div>';

?>