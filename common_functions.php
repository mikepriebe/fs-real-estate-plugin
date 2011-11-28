<?php
function fsrep_url_generator($url) {
	$url = str_replace(" ", "-", $url);
	$url = str_replace("_", "-", $url);
	$special = array('!','@','#','$','%','^','&','*','(',')','_','+','{','}','|','[',']',':',';','<','>','?',',','.','/','`','~','/','!','&','*');
	$url = str_replace(' ',' ',str_replace($special,'',$url));
	$url = str_replace("'", "", $url);
	$url = str_replace('"', '', $url);
	$url = str_replace("--", "-", $url);
	$url = strip_tags($url);
	$url = substr(strtolower($url), 0, 45);
	return $url;
}
function fsrep_flush_rewrite_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}
function fsrep_add_rewrite_rules($wp_rewrite) {
	global $wpdb,$wp_rewrite,$LPageID,$MAPageID,$LOPageID;
	$new_rules = array('listings/(.+)' => 'index.php?page_id='.$LPageID.'&LPage='.$wp_rewrite->preg_index(1), 'myaccount/(.+)' => 'index.php?page_id='.$MAPageID.'&MAPage='.$wp_rewrite->preg_index(1), 'local/(.+)' => 'index.php?page_id='.$LOPageID.'&LOPage='.$wp_rewrite->preg_index(1));
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
function fsrep_print_hidden_input($name, $value) {
	echo '<input type="hidden" name="'.$name.'" value="'.$value.'">';
}
function fsrep_print_file_input($label, $name, $value, $length) {
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div><input type="file" name="'.$name.'" value="'.$value.'" size="'.$length.'"></div>';
}
function fsrep_print_password_input($label, $name, $value, $length) {
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div><input type="password" name="'.$name.'" value="'.$value.'" size="'.$length.'"></div>';
}
function fsrep_print_input($label, $name, $value, $length) {
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div><input type="text" name="'.$name.'" value="'.$value.'" size="'.$length.'"></div>';
}
function fsrep_print_textarea($label, $name, $value, $rows, $cols) {
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div><textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea></div>';
}
function fsrep_print_selectbox($label, $name, $selvalue, $options, $onchange) {
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div>';
	echo '<select id="'.$name.'" name="'.$name.'"'; if ($onchange != '') { echo 'onchange="'.$onchange.'"'; } echo '>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'selected'; }
		echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
	}
	echo '</select>';
	echo '</div>';
}
function fsrep_print_checkbox($label, $name, $selvalue, $options) {
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'checked'; }
		echo '<input type="checkbox" name="'.$name.'" value="'.$value.'" '.$selected.'>'.$key.' ';
	}
	echo '</div>';
}
function fsrep_print_radio($label, $name, $selvalue, $options) {
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'checked'; }
		echo '<input type="radio" name="'.$name.'" value="'.$value.'" '.$selected.'>'.$key.' ';
	}
	echo '</div>';
}
function fsrep_print_admin_file_input($label, $name, $value, $length, $description) {
	echo '<tr><td>'.$label.'</td><td><input type="file" name="'.$name.'" value="'.$value.'" size="'.$length.'"></td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_input($label, $name, $value, $length, $description) {
	echo '<tr><td>'.$label.'</td><td><input type="text" name="'.$name.'" value="'.$value.'" size="'.$length.'"></td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_textarea($label, $name, $value, $rows, $cols, $description) {
	echo '<tr><td>'.$label.'</td><td><textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea></td></tr>';
}
function fsrep_print_admin_selectbox($label, $name, $selvalue, $options, $onchange, $description) {
	echo '<tr><td>'.$label.'</td>';
	echo '<td><select id="'.$name.'" name="'.$name.'"'; if ($onchange != '') { echo 'onchange="'.$onchange.'"'; } echo '>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'selected'; }
		echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
	}
	echo '</select>';
	echo '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_imageresizer($source_pic, $destination_pic, $max_width, $max_height) {
		
		$src = imagecreatefromjpeg($source_pic);
		list($width,$height)=getimagesize($source_pic);
		
		$x_ratio = $max_width / $width;
		$y_ratio = $max_height / $height;
		
		if( ($width <= $max_width) && ($height <= $max_height) ){
				$tn_width = $width;
				$tn_height = $height;
				}elseif (($x_ratio * $height) < $max_height){
						$tn_height = ceil($x_ratio * $height);
						$tn_width = $max_width;
				}else{
						$tn_width = ceil($y_ratio * $width);
						$tn_height = $max_height;
		}
		
		$tmp=imagecreatetruecolor($tn_width,$tn_height);
		imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);
		
		imagejpeg($tmp,$destination_pic,100);
		imagedestroy($src);
		imagedestroy($tmp);
}
function google_geocoder($address, $api) {
	$address = str_replace(' ', '+', $address);
	$XMLUrl = 'http://maps.google.com/maps/geo?q='.$address.'&key='.$api.'&sensor=false&output=xml&oe=utf8';
	
	$XMLContents = file_get_contents($XMLUrl);
	
	$XML = new SimpleXMLElement($XMLContents);
	
	$Coords = explode(',',$XML->Response->Placemark->Point->coordinates);
	
	return $Coords;
}
function fsrep_listing_name_gen($ListingID, $ListingName) {
	global $wpdb;
	$Listing = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID);
	$Fields = $wpdb->get_results("SHOW COLUMNS FROM ".$wpdb->prefix."fsrep_listings");
	foreach ($Fields as $Fields) {
		$Field = $Fields->Field;
		if ($Field == 'listing_address_country') {
			$ListingName = str_replace($Field, fsrep_get_address_name($Listing->$Field, 'country'), $ListingName);
		} elseif ($Field == 'listing_address_province') {
			$ListingName = str_replace($Field, fsrep_get_address_name($Listing->$Field, 'province'), $ListingName);
		} elseif ($Field == 'listing_address_city') {
			$ListingName = str_replace($Field, fsrep_get_address_name($Listing->$Field, 'city'), $ListingName);
		} else {
			$ListingName = str_replace($Field, $Listing->$Field, $ListingName);
		}
		$ListingName = str_replace($Field, $Listing->$Field, $ListingName);
	}
	if ($Listing->listing_sold == 1) {
		$ListingName = '<span style="color: #B80000">SOLD!</span> '.$ListingName;
	}
	if (substr($ListingName, 0, 3) == ' - ') {
		$ListingName = substr($ListingName, 3); 
	}
	
	
	return $ListingName;
}
function fsrep_listing_url_gen($ListingID) {
	global $wpdb;
	$Listing = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID);
	$URL = get_option('home').'/listings/'.$Listing->listing_id.'-'.$Listing->listing_address_number.'-'.$Listing->listing_address_street.'-'.$Listing->listing_address_city.'-'.$Listing->listing_address_province.'/';
	$URL = str_replace(' ', '-', $URL);
	return $URL;
}
function fsrep_price_range_print($Type) {
	$Options = '<option value="0">$0</option>';
	$Array = array('$0' => 0);
	for($i=10000;$i<=100000;$i=$i+10000) {
		$Options .= '<option value="'.$i.'">$'.number_format($i, ',').'</option>';
		$Array = array_merge($Array, array('$'.number_format($i, ',') => $i));
	}
	for($i=125000;$i<=500000;$i=$i+25000) {
		$Options .= '<option value="'.$i.'">$'.number_format($i, ',').'</option>';
		$Array = array_merge($Array, array('$'.number_format($i, ',') => $i));
	}
	for($i=550000;$i<=1000000;$i=$i+50000) {
		$Options .= '<option value="'.$i.'">$'.number_format($i, ',').'</option>';
		$Array = array_merge($Array, array('$'.number_format($i, ',') => $i));
	}
		$Array = array_merge($Array, array('$1,000,001+' => '1000001+'));
	$Options .= '<option value="1000000+">$1,000,001+</option>';
	if ($Type == 'options') {
		return $Options;
	} elseif ($Type == 'array') {
		return $Array;
	}
}
function fsrep_get_countries($Type) {
	global $wpdb;
	$Options = '<option value="">Select Country</option>';
	$Array = array('Select Country' => '');
	$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
	foreach($Countries as $Countries) {
		$Options .= '<option value="'.$Countries->country_id.'">'.$Countries->country_name.'</option>';
		$Array = array_merge($Array, array($Countries->country_name => $Countries->country_id));
	}
	if ($Type == 'options') {
		return $Options;
	} elseif ($Type == 'array') {
		return $Array;
	}
}
function fsrep_get_address_name($ID, $Type) {
	global $wpdb;
	if ($Type == 'country') {
		$Name = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$ID);
	} elseif ($Type == 'province') {
		$Name = $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$ID);
	} elseif ($Type == 'city') {
		$Name = $wpdb->get_var("SELECT city_name FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = ".$ID);
	}
	return $Name;
}

// LISTINGS FUNCTION
function fsrep_listings($category_id, $value, $type, $fpagination, $hpagination, $CategoryPE, $GoogleMap) {
	global $wpdb,$FSREPconfig;
	$PageContent = '';
	
	// GET LISTING TYPE
	if ($type == 'search') {
		$Listings = $wpdb->get_results($value);
	} elseif ($type == 'country') {
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_country = $value ORDER BY listing_id DESC");
	} elseif ($type == 'province') {
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_province = $value ORDER BY listing_id DESC");
	} elseif ($type == 'city') {
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_city = $value ORDER BY listing_id DESC");
	} else {
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings ORDER BY listing_id DESC");
	}
	
	if ($GoogleMap == TRUE) {
		include('includes/listing_map.php');
	}
	
	foreach ($Listings as $Listings) {
		echo '<div style="height: 145px; width: 100%;">';
		echo '<h3><a href="'.fsrep_listing_url_gen($Listings->listing_id).'">'.fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['Listing Name Display']).'</a></h3>';
		if (file_exists(ABSPATH.'/wp-content/uploads/fsrep/houses/small/'.$Listings->listing_id.'.jpg')) { 
			echo '<div style="float: left; padding-right: 12px; height: 110px;"><a href="'.fsrep_listing_url_gen($Listings->listing_id).'" title="Listing"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$Listings->listing_id.'.jpg" border="0" alt="" style="border: 1px solid #999999;" /></a></div>';
		}
		echo '<p><strong>Asking Price: $'.number_format($Listings->listing_price, 2, '.', ',').'</strong><br />';
		echo $Listings->listing_address_number.' '.$Listings->listing_address_street.' '.fsrep_get_address_name($Listings->listing_address_city, 'city').' '.fsrep_get_address_name($Listings->listing_address_province, 'province').' '.$Listings->listing_address_postal.'</p>';
		echo '<p>'.substr(strip_tags(stripslashes($Listings->listing_description)), 0 ,190).'... ';
		echo '<a href="'.fsrep_listing_url_gen($Listings->listing_id).'">View more details of this listing</a></p>';
		echo '</div>';
		
	}
	$PageContent .= '<div style="clear: both;"></div>';
	
	return $PageContent;
}
?>