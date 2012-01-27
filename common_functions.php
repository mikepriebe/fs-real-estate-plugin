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
	global $wpdb,$wp_rewrite,$LPageID,$LOPageID,$FSREPconfig;
	$ListingHomeURL = $wpdb->get_var("SELECT post_name FROM ".$wpdb->prefix."posts WHERE ID = ".$FSREPconfig['ListingsPageID']);
	$new_rules = array($ListingHomeURL.'/(.+)' => 'index.php?page_id='.$LPageID.'&LPage='.$wp_rewrite->preg_index(1));
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
function fssc_add_listing_page() {
	global $wpdb;
	if ($wpdb->get_var("SELECT COUNT(post_content) FROM ".$wpdb->prefix."posts WHERE post_content = '[fsrep-listings]' AND post_status = 'publish'") == 0) {
		$ListingsPageID = wp_insert_post(array(
		'post_title' => 'Listings',
		'post_content' => '[fsrep-listings]',
		'post_name' => 'listings',
		'post_type' => 'page',
		'post_status' => 'publish',
		'comment_status' => 'closed', 
		'ping_status' => 'closed', 
		'post_author' => 1
		));
	} else {
		$ListingsPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content = '[fsrep-listings]' AND post_status = 'publish'");
	}
	$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = $ListingsPageID WHERE config_name = 'ListingsPageID'");
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
function fsrep_print_admin_radio($label, $name, $selvalue, $options, $onchange, $description) {
	echo '<tr><td>'.$label.'</td><td>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'checked'; }
		echo '<label><input type="radio" id="'.$name.'" name="'.$name.'" value="'.$value.'" '.$selected.'>'.$key.' &nbsp; &nbsp; </label>';
	}
	echo '</select>';
	echo '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_checkbox($label, $name, $selvalue, $options, $onchange, $description) {
	echo '<tr><td>'.$label.'</td><td>';
	$number = 0;
	$selvalue = str_replace(', ',',',$selvalue);
	$selvalue = explode(',',$selvalue);
	foreach ($options as $key=>$value) {
		$number++;
		$selected = '';
		if (in_array($value, $selvalue)) { $selected = 'checked'; }
		echo '<label><input type="checkbox" id="'.$name.'" name="'.$number.$name.'" value="'.$value.'" '.$selected.'>'.$key.' &nbsp; &nbsp; </label>';
	}
	echo '</select>';
	echo '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_return_admin_file_input($label, $name, $value, $length, $description) {
	return '<tr><td>'.$label.'</td><td><input type="file" name="'.$name.'" value="'.$value.'" size="'.$length.'"></td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_return_admin_input($label, $name, $value, $length, $description) {
	return '<tr><td>'.$label.'</td><td><input type="text" name="'.$name.'" value="'.$value.'" size="'.$length.'"></td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_return_admin_textarea($label, $name, $value, $rows, $cols, $description) {
	return '<tr><td>'.$label.'</td><td><textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea></td></tr>';
}
function fsrep_return_admin_selectbox($label, $name, $selvalue, $options, $onchange, $description) {
	$PageContent = '<tr><td>'.$label.'</td>';
	$PageContent .= '<td><select id="'.$name.'" name="'.$name.'"'; if ($onchange != '') { echo 'onchange="'.$onchange.'"'; } $PageContent .= '>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'selected'; }
		$PageContent .= '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
	}
	$PageContent .= '</select>';
	$PageContent .= '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
	return $PageContent;
}
function fsrep_return_admin_radio($label, $name, $selvalue, $options, $onchange, $description) {
	$PageContent = '<tr><td>'.$label.'</td><td>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'checked'; }
		$PageContent .= '<label><input type="radio" id="'.$name.'" name="'.$name.'" value="'.$value.'" '.$selected.'>'.$key.' &nbsp; &nbsp; </label>';
	}
	$PageContent .= '</select>';
	$PageContent .= '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
	return $PageContent;
}
function fsrep_return_admin_checkbox($label, $name, $selvalue, $options, $onchange, $description) {
	$PageContent .= '<tr><td>'.$label.'</td><td>';
	$number = 0;
	$selvalue = str_replace(', ',',',$selvalue);
	$selvalue = explode(',',$selvalue);
	foreach ($options as $key=>$value) {
		$number++;
		$selected = '';
		if (in_array($value, $selvalue)) { $selected = 'checked'; }
		$PageContent .= '<label><input type="checkbox" id="'.$name.'" name="'.$number.$name.'" value="'.$value.'" '.$selected.'>'.$key.' &nbsp; &nbsp; </label>';
	}
	$PageContent .= '</select>';
	$PageContent .= '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
	return $PageContent;
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
		
		imagejpeg($tmp,$destination_pic,80);
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
	
	if ($Listing->listing_label == '') {
		$ListingName = 'Listing #'.$ListingID;
	} else {
		$ListingName = $Listing->listing_label;
	}
	
	if ($Listing->listing_sold == 1) {
		$ListingName = '<span style="color: #B80000">SOLD!</span> '.$ListingName;
	}
	if (substr($ListingName, 0, 3) == ' - ') {
		$ListingName = substr($ListingName, 3); 
	}
	
	
	return $ListingName;
}
function fsrep_breadcrumbs() {
	global $FSREPconfig,$post,$wpdb,$ListingID,$CityID,$ProvinceID,$CountryID,$ListingHomeURL;
	$Breadcrumbs = '';
	if ($CountryID != 0) {
		$CountryInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
		$Breadcrumbs .= '> <a href="'.get_bloginfo('home').'/'.$ListingHomeURL.'/'.$CountryInfo->country_url.'/">'.$CountryInfo->country_name.'</a> ';
	}
	if ($ProvinceID != 0) {
		$ProvinceInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
		$Breadcrumbs .= '> <a href="'.get_bloginfo('home').'/'.$ListingHomeURL.'/'.$CountryInfo->country_url.'/'.$ProvinceInfo->province_url.'/">'.$ProvinceInfo->province_name.'</a> ';
	}
	if ($CityID != 0) {
		$CityInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID");
		$Breadcrumbs .= '> <a href="'.get_bloginfo('home').'/'.$ListingHomeURL.'/'.$CountryInfo->country_url.'/'.$ProvinceInfo->province_url.'/'.$CityInfo->city_url.'/">'.$CityInfo->city_name.'</a> ';
	}
	if ($ListingID != 0) {
		$ListingLabel = $wpdb->get_var("SELECT listing_label FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID");
		$Breadcrumbs .= '> <a href="'.fsrep_listing_url_gen($ListingID).'">'.$ListingLabel.'</a> ';
	}
	if ($Breadcrumbs != '') {
		return '<div id="fsrep-breadcrumbs"><a href="'.get_bloginfo('home').'/'.$ListingHomeURL.'/">'.get_the_title($post->ID).'</a> '.$Breadcrumbs.'</div>';
	}
}
function fsrep_listing_url_gen($ListingID) {
	global $wpdb,$FSREPconfig;
	$Listing = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID);
	$ListingHomeURL = $wpdb->get_var("SELECT post_name FROM ".$wpdb->prefix."posts WHERE ID = ".$FSREPconfig['ListingsPageID']);
	$URL = get_option('home').'/'.$ListingHomeURL.'/'.$Listing->listing_id.'-'.fsrep_url_generator(substr($Listing->listing_label,0,30)).'-'.fsrep_url_generator(fsrep_get_address_name($Listing->listing_address_city, 'city')).'-'.fsrep_url_generator(fsrep_get_address_name($Listing->listing_address_province, 'province')).'/';
	$URL = str_replace(' ', '-', $URL);
	return $URL;
}
function fsrep_price_range_print($Type) {
	global $FSREPconfig;
	$Options = '<option value="0">'.$FSREPconfig['Currency'].'0</option>';
	$Array = array($FSREPconfig['Currency'].'0' => 0);
	for($i=10000;$i<=100000;$i=$i+10000) {
		$Options .= '<option value="'.$i.'">'.$FSREPconfig['Currency'].number_format($i, ',').'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].number_format($i, ',') => $i));
	}
	for($i=125000;$i<=500000;$i=$i+25000) {
		$Options .= '<option value="'.$i.'">'.$FSREPconfig['Currency'].number_format($i, ',').'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].number_format($i, ',') => $i));
	}
	for($i=550000;$i<=1000000;$i=$i+50000) {
		$Options .= '<option value="'.$i.'">'.$FSREPconfig['Currency'].number_format($i, ',').'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].number_format($i, ',') => $i));
	}
		$Array = array_merge($Array, array($FSREPconfig['Currency'].'1,000,001+' => '1000001+'));
	$Options .= '<option value="1000000+">'.$FSREPconfig['Currency'].'1,000,001+</option>';
	if ($Type == 'options') {
		return $Options;
	} elseif ($Type == 'array') {
		return $Array;
	}
}
function fsrep_get_countries($Type) {
	global $wpdb,$FSREPconfig;
	$Options = '<option value="">Select '.$FSREPconfig['CountryLabel'].'</option>';
	$Array = array('Select '.$FSREPconfig['CountryLabel'] => '');
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
function fsrep_roundUp( $value, $precision=0 ) {
	if ( $precision == 0 ) {
		$precisionFactor = 1;
	}	else {
		$precisionFactor = pow( 10, $precision );
	}
	return ceil( $value * $precisionFactor )/$precisionFactor;
} 


// LISTINGS FUNCTION
function fsrep_listings_display($category_id, $value, $type, $fpagination, $hpagination, $CategoryPE, $GoogleMap, $Filter) {
	global $post,$wpdb,$FSREPconfig,$GMapLat,$GMapLong,$GMapZoom,$CountryID,$ProvinceID,$CityID,$ListingHomeURL;

	$PageContent = '';
	$Overview = '';
	$PS = 0;
	$PE = 0;
	if (!isset($_SESSION['Order'])) {
		$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_id DESC';
	}
	if (isset($_GET['ps'])) {
		$PS = $_GET['ps'];
	}
	if (isset($_GET['pe'])) {
		$PE = $_GET['pe'];
	}
	if (isset($_GET['order'])) {
		$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.'.$_GET['order'];
		if ($_GET['order'] == 'listing_id') {
			$_SESSION['Order'] .= ' DESC';
		}
	}
	$ModSQL = '';
	if ($FSREPconfig['ListingModeration'] == 'Yes'){
		$ModSQL .= " AND listing_visibility = 1 ";
	}
	if ($type == 'search') {
		$Listings = $wpdb->get_results($value);
	} elseif ($type == 'country') {
		if ($CountryID != 0) { 
			$CountryDetails = $wpdb->get_row("SELECT country_overview, country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
			$Overview = stripslashes($CountryDetails->country_overview);
			$OverviewTitle = $CountryDetails->country_name;
		} else {
			$CountryID = $value; 
		}
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_country = $CountryID ".$ModSQL." ORDER BY ".$wpdb->prefix."fsrep_listings.listing_featured DESC, ".$_SESSION['Order']);
	} elseif ($type == 'province' || $type == 'state') {
		if ($ProvinceID != 0) { 
			$ProvinceDetails = $wpdb->get_row("SELECT province_overview, province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
			$Overview = stripslashes($ProvinceDetails->province_overview);
			$OverviewTitle = $ProvinceDetails->province_name;
		} else {
			$ProvinceID = $value; 
		}
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_province = $ProvinceID ".$ModSQL." ORDER BY ".$wpdb->prefix."fsrep_listings.listing_featured DESC, ".$_SESSION['Order']);
	} elseif ($type == 'city') {
		if ($CityID != 0) { 
			$CityDetails = $wpdb->get_row("SELECT city_overview, city_name FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID");
			$Overview = stripslashes($CityDetails->city_overview);
			$OverviewTitle = $CityDetails->city_name;
		} else {
			$CityID = $value; 
		}
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_city = $CityID ".$ModSQL." ORDER BY ".$wpdb->prefix."fsrep_listings.listing_featured DESC, ".$_SESSION['Order']);
	} else {
		$ListingsSQL = "SELECT * FROM ".$wpdb->prefix."fsrep_listings";
		if ($Filter != 0) {
			$ListingsSQL .= ", ".$wpdb->prefix."fsrep_listings_to_fields WHERE ";
			$Filters = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_filters_details WHERE filter_id = ".$Filter);
			foreach ($Filters as $Filters) {
				if ($wpdb->get_var("SELECT field_type FROM ".$wpdb->prefix."fsrep_fields WHERE field_id = ".$Filters->field_id) == 'checkbox') {
					$ListingsSQL .= " ".$wpdb->prefix."fsrep_listings_to_fields.listing_id = ".$wpdb->prefix."fsrep_listings.listing_id AND  ".$wpdb->prefix."fsrep_listings_to_fields.field_id = ".$Filters->field_id ." AND ".$wpdb->prefix."fsrep_listings_to_fields.listing_value LIKE '%".$Filters->field_values."%' AND ";
				} else {
					$ListingsSQL .= " ".$wpdb->prefix."fsrep_listings_to_fields.listing_id = ".$wpdb->prefix."fsrep_listings.listing_id AND  ".$wpdb->prefix."fsrep_listings_to_fields.field_id = ".$Filters->field_id ." AND ".$wpdb->prefix."fsrep_listings_to_fields.listing_value = '".$Filters->field_values."' AND ";
				}
			}
			if ($FSREPconfig['ListingModeration'] == 'Yes'){
				$ListingsSQL .= " ".$wpdb->prefix."fsrep_listings.listing_visibility = 1 AND ";
			}
			$ListingsSQL = substr($ListingsSQL, 0, -4);
		} else {
			if ($FSREPconfig['ListingModeration'] == 'Yes'){
				$ListingsSQL .= " WHERE ".$wpdb->prefix."fsrep_listings.listing_visibility = 1 ";
			}
		}
		$SQLExtra = '';
		if ($_SESSION['Order'] == 'listing_id') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_id';
			$SQLExtra = ' DESC';
		} elseif ($_SESSION['Order'] == 'listing_priced') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_price';
			$SQLExtra = ' DESC';
		}
		$ListingsSQL .= " ORDER BY ".$wpdb->prefix."fsrep_listings.listing_featured DESC, ".$_SESSION['Order'].$SQLExtra;
		$Listings = $wpdb->get_results($ListingsSQL);
	}
	$ListingCount = count($Listings);
	
	if ($GoogleMap == TRUE) {
		$PageContent .= '<div id="listings_map" style="width: 100%; height: 300px; border: 1px solid #999999; margin-bottom: 12px;"></div>';
	} else {
		$PageContent .= '';
	}
		
	$TotalPages = 1;		
	$PageContent .= '<div id="fs-category-options">';
	$PageContent .= '<form action="./" method="GET">';
	$PageContent .= '<div style="text-align: right; float: right; width: 34%;">Sort by: <select name="order" onchange="this.form.submit();" class="sortby" style="width: 130px;">';
	$PageContent .= '<option value="listing_id" '; if ($_SESSION['Order'] == "listing_id" || $_SESSION['Order'] == "wp_fsrep_listings.listing_id") { $PageContent .= 'selected'; } $PageContent .= '>Recently Added</option>';
	$PageContent .= '<option value="listing_price" '; if ($_SESSION['Order'] == "listing_price" || $_SESSION['Order'] == "wp_fsrep_listings.listing_price") { $PageContent .= 'selected'; } $PageContent .= '>Price (low to high)</option>';
	$PageContent .= '<option value="listing_price DESC" '; if ($_SESSION['Order'] == "listing_price DESC" || $_SESSION['Order'] == "wp_fsrep_listings.listing_price DESC") { $PageContent .= 'selected'; } $PageContent .= '>Price (high to low)</option>';
	$PageContent .= '</select></div>';
	$PageContent .= '<div id="fs-page-select">Page: <select name="ps" onchange="this.form.submit();" class="numerical">';
	$PageContent .= '<option value="0">1</option>';
	if ($PE != 0) {
		$TotalPages = $ListingCount / $PE;
	} else {
		$TotalPages = 1;
	}
	$Pages = FALSE;
	$TotalPages = fsrep_roundUp( $TotalPages, $precision=0 );
	for ($i=1;$i<$TotalPages;$i++) {
		$select = '';
		if ($PS == $i * $PE) {
			$select = 'selected';
		}
		$PageNumber = $i + 1;
		$PageContent .= '<option value="'.$i * $PE.'" '.$select.'>'.$PageNumber.'</option>';
		$Pages = TRUE;
	}
	if ($Pages == FALSE && $TotalPages != 1) {
		$PageContent .= '<option value="'. 1 * $CategoryPE.'">1</option>';
	}
	$PageContent .= '</select></div> ';
	$PageContent .= '<div id="fs-results-per-page"><select name="pe" onchange="this.form.submit();" class="numerical">';
	$PageContent .= '<option '; if ($CategoryPE == 10) { $PageContent .= 'selected'; } $PageContent .= '>10</option>';
	$PageContent .= '<option '; if ($CategoryPE == 25) { $PageContent .= 'selected'; } $PageContent .= '>25</option>';
	$PageContent .= '<option '; if ($CategoryPE == 50) { $PageContent .= 'selected'; } $PageContent .= '>50</option>';
	$PageContent .= '<option '; if ($CategoryPE == 100) { $PageContent .= 'selected'; } $PageContent .= '>100</option>';
	$PageContent .= '</select> per page</div>';
	$PageContent .= '&nbsp;</form></div>';

	if (count($Listings) > 0) {
		if ($FSREPconfig['EnableCompare'] == 'Yes') { 
			$PageContent .= '<form id="fsrep-compare-form" name="fsrep-compare-form" action="'.get_option('home').'/'.$ListingHomeURL.'/compare/" METHOD="POST">';
			$PageContent .= '<div id="fsrep-compare-submit"><input type="submit" name="submit" value="Compare"></div>';
		}
		$PageContent .= '<br />';
		foreach ($Listings as $Listings) {
			if (is_numeric($Listings->listing_price)) { $Listings->listing_price = number_format($Listings->listing_price, 2, '.', ','); }
			include('includes/listings_'.$FSREPconfig['ListingsOrientation'].'.php');			
		}
		if ($FSREPconfig['EnableCompare'] == 'Yes') { $PageContent .= '</form>'; }
		$PageContent .= '<div style="clear: both;"></div>';
		if (!$_SESSION['Order']) {
			$_SESSION['Order'] = 'listing_id';
		}
		if (isset($_GET['order'])) {
			$_SESSION['Order'] = $_GET['order'];
		}
		$PageContent .= '<div id="fsrep-page-numbers"><a href="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'">1</a>';
		for ($i=1;$i<$TotalPages;$i++) {
			$PageNumber = $i + 1;
			$PS = $i * $CategoryPE;
			$PageContent .= ' | <a href="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'?order='.$_SESSION['Order'].'&ps='.$PS.'&pe='.$CategoryPE.'">'.$PageNumber.'</a>';
		}
		$PageContent .= '</div>';
	} else {
		$PageContent .= 'No listings were found.';
	}
	
	if ($Overview != '') { $PageContent .= '<div id="fsrep-overview"><h2>About '.$OverviewTitle.'</h2>'.$Overview.'</div>'; }
	if ($FSREPconfig['DisplaySubLocations'] != '') { 
		$SubLocCount = 0;
		if ($ProvinceID != 0) {
			$SubLocCount = $wpdb->get_var("SELECT COUNT(city_id) FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $ProvinceID");
			$SubLocations = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $ProvinceID ORDER BY city_name");
			$CountryURL = $wpdb->get_var("SELECT country_url FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
			$ProvinceURL = $wpdb->get_var("SELECT province_url FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
			$SearchWithinTitle = $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
		} elseif($CountryID != 0) {
			$SubLocCount = $wpdb->get_var("SELECT COUNT(province_id) FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $CountryID");
			$SubLocations = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $CountryID ORDER BY province_name");
			$CountryURL = $wpdb->get_var("SELECT country_url FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
			$SearchWithinTitle = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
		}
		if ($SubLocCount > 0 && $FSREPconfig['EnableSearchWithin'] == 'Yes') {
			$PageContent .= '<div id="fsrep-sublocations"><h2>Search Within '.$SearchWithinTitle.'</h2>';
			$SubLocCount = 0;
			$LCounter = 0;
			$PageContent .= '<table width="100%" border="0"><tr>'."\n";
			foreach ($SubLocations as $SubLocations) {
				if ($ProvinceID != 0) {
					if ($wpdb->get_var("SELECT count(listing_address_city) FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_city = ".$SubLocations->city_id) > 0) {
						$PageContent .= '<td><a href="'.get_option('home').'/'.$ListingHomeURL.'/'.$CountryURL.'/'.$ProvinceURL.'/'.$SubLocations->city_url.'/">'.$SubLocations->city_name.'</a></td>'."\n";
						$LCounter++;
					}
				} elseif($CountryID != 0) {
					if ($wpdb->get_var("SELECT count(listing_address_province) FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_province = ".$SubLocations->province_id) > 0) {
						$PageContent .= '<td><a href="'.get_option('home').'/'.$ListingHomeURL.'/'.$CountryURL.'/'.$SubLocations->province_url.'/">'.$SubLocations->province_name.'</a></td>'."\n";
						$LCounter++;
					}
			}
				if ($LCounter == 4) {
					$LCounter = 0;
					$PageContent .= '</tr><tr>'."\n";
				}
			}
			$PageContent .= '</tr></table></div>'."\n";
		} 
		
	}
	
	
	return $PageContent;
}

// SEARCH BOX
function fsrep_search_box() {
	global $wpdb,$ListingHomeURL,$FSREPconfig;
	$PageContent = '<div id="search-box">';
	$PageContent .= '<form id="fsrep_search_form" name="fsrep_search_form" action="'.get_option('home').'/'.$ListingHomeURL.'/search/" method="POST">';
	$PageContent .= '<div id="search-left">';
	$PageContent .= '<strong>Country:</strong><br />';
	$PageContent .= '<select id="fsrep-search-country" name="fsrep-search-country"  onchange="getFSREPlist(this, \'fsrep-search-province\', \'CountryID\', \'\')">';
	$PageContent .= '<option value="">Select '.$FSREPconfig['CountryLabel'].'</option>';
	$FSREPCountries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
	foreach ($FSREPCountries as $FSREPCountries) {
		$PageContent .= '<option value="'.$FSREPCountries->country_id.'">'.$FSREPCountries->country_name.'</option>';
	}
	$PageContent .= '</select><br /><br />';
	$PageContent .= '<strong>State/Prov.:</strong><br />';
	$PageContent .= '<select id="fsrep-search-province" name="fsrep-search-province"  onchange="getFSREPlist(this, \'fsrep-search-city\', \'ProvinceID\', \'\')">';
	$PageContent .= '<option value="">- - - - - -</option>';
	$PageContent .= '</select><br /><br />';
	$PageContent .= '<strong>City:</strong><br />';
	$PageContent .= '<select id="fsrep-search-city" name="fsrep-search-city">';
	$PageContent .= '<option value="">- - - - - -</option>';
	$PageContent .= '</select><br /><br />';
	$PageContent .= '<strong>Price:</strong><br />';
	$PageContent .= '<select name="fsrep-search-price-range">';
	$PageContent .= fsrep_price_range_print('options');
	$PageContent .= '</select><br />';
	$PageContent .= '<strong>to</strong><br />';
	$PageContent .= '<select name="fsrep-search-price-range2">';
	$PageContent .= fsrep_price_range_print('options');
	$PageContent .= '</select><br /><br />';
	$PageContent .= '</div>';
	$PageContent .= '<div id="search-right">';
	$SFields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields WHERE field_search = 1 ORDER BY field_order");
	foreach($SFields as $SFields) {
		$PageContent .= '<strong>'.$SFields->field_name.'</strong><br />';
		if ($SFields->field_type == 'selectbox') {
			$PageContent .= '<select name="field-'.$SFields->field_id.'">';
			$PageContent .= '<option value="">All</option>';
			$Array = explode(',',$SFields->field_value);
			for($i=0;$i<count($Array);$i++) {
				$PageContent .= '<option value="'.$Array[$i].'">'.$Array[$i].'</option>';
			}
			$PageContent .= '</select><br /><br />';
		} elseif ($SFields->field_type == 'radio') {
			$selected = '';
			$PageContent .= '<input type="radio" style="width: 25px;" name="field-'.$SFields->field_id.'" value="" '.$selected.'> All &nbsp; &nbsp;';
			$Array = explode(',',$SFields->field_value);
			for($i=0;$i<count($Array);$i++) {
				$selected = '';
				$PageContent .= '<input type="radio" style="width: 25px;" name="field-'.$SFields->field_id.'" value="'.$Array[$i].'" '.$selected.'> '.$Array[$i].' &nbsp; &nbsp;';
			}
			$PageContent .= '<br /><br />';
		} elseif ($SFields->field_type == 'checkbox') {
			$selected = '';
			$PageContent .= '<input type="checkbox" style="width: 25px;" name="field-'.$SFields->field_id.'" value="" '.$selected.'> All &nbsp; &nbsp;';
			$Array = explode(',',$SFields->field_value);
			for($i=0;$i<count($Array);$i++) {
				$selected = '';
				$PageContent .= '<input type="checkbox" style="width: 25px;" name="field-'.$SFields->field_id.'-'.$i.'" value="'.$Array[$i].'" '.$selected.'> '.$Array[$i].' &nbsp; &nbsp;';
			}
			$PageContent .= '<br /><br />';
		} else {
			$PageContent .= '<input type="text" name="field-'.$SFields->field_id.'" id="field-'.$SFields->field_id.'" value="">';
		}
	}
	$PageContent .= '</div>';
	$PageContent .= '<div id="fsrepws-submit"><input type="submit" name="fsrep-search-submit" id="fsrep-search-submit" value="Search Listings"></div>';
	$PageContent .= '</form>';
	$PageContent .= '</div>';	
	
	return $PageContent;
}
?>