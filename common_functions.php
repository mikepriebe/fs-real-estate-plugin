<?php


function fsrep_add_pages($Pages) {
	global $wpdb;
	foreach ($Pages as $Title => $Content) {
		if ($wpdb->get_var("SELECT COUNT(post_content) FROM ".$wpdb->prefix."posts WHERE post_content = '$Content' AND post_status IN ('publish', 'private')") == 0) {
			wp_insert_post(array(
			'post_title' => $Title,
			'post_content' => $Content,
			'post_type' => 'page',
			'post_status' => 'publish',
			'comment_status' => 'closed', 
			'ping_status' => 'closed', 
			'post_author' => 1
			));
		}
	}
}
function fsrep_feature_disabled($title) {
		return '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column"><b>'.$title.'</b></th>
		</tr>
		</thead>
		<tbody>
		<td style="height: 300px;">This feature is currently disabled. To purchase this extended feature, visit <a href="http://www.firestorminteractive.com/wordpress/ecommerce/" target="_blank">www.firestorminteractive.com/wordpress/ecommerce/</a>.</td>
		</tbody></table><br />';
}
function fsrep_feature_disabled_mini() {
	echo '<tr><td colspan="3">This feature is currently disabled. To purchase this extended feature, visit <a href="http://www.firestorminteractive.com/wordpress/ecommerce/" target="_blank">www.firestorminteractive.com/wordpress/ecommerce/</a>.</td></tr>';
}
function fsrep_extension_version($URL) {
	if (file_exists($URL)) {
		$FSREPThemeStyle = file($URL);
		return str_replace("\n", '', str_replace('// Version: ','',$FSREPThemeStyle[1]));
	}
}
function fsrep_sql_alter ($DBNAME, $TABLENAME, $COLUMNNAME, $TYPE) {
	global $wpdb;
	$AlterTable = TRUE;
	$tableFields = mysql_list_fields($DBNAME, $TABLENAME);
	for($i=0;$i<mysql_num_fields($tableFields);$i++) { 
		if(mysql_field_name($tableFields, $i) == $COLUMNNAME) {
			$AlterTable = FALSE;
		}
	}
	if ($AlterTable == TRUE) {
		$wpdb->query("ALTER TABLE $TABLENAME ADD $COLUMNNAME $TYPE");
	}
}
function fsrep_sql_insert($TableName, $ColumnName, $Value, $Columns, $Values) {
	global $wpdb;
	if ($wpdb->get_var("SELECT COUNT(*) FROM $TableName WHERE $ColumnName = '$Value'") == 0) {
		$wpdb->query("INSERT INTO $TableName ($Columns) VALUES ($Values)");
	}
}

function fsrep_spam_check($Var) {
	$SpamCheck = FALSE;
	foreach ($Var as $Var => $Value) {
		if (isset($Value)) {
			if ($Value != '' && !is_numeric($Value)) { 
				if (preg_match("/wp_users/i", $Value)) {
					$SpamCheck = TRUE;
				}
			}
		}
	}
	return $SpamCheck;
}
function fsrep_text_translator($context, $name, $value) {
	if (function_exists('fsrep_pro_text_translator')) {
		$text = fsrep_pro_text_translator($context, $name, $value);
	} else {
		$text = $value;
	}
	return $text;
}
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
function fsrep_add_listing_page() {
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
	if ($options != '') {
		foreach ($options as $key=>$value) {
			$selected = '';
			if ($selvalue == $value) { $selected = 'selected'; }
			echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
		}
	}
	echo '</select>';
	echo '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_field_selectbox($label, $name, $selvalue, $options, $onchange, $description) {
	echo '<tr><td>'.$label.'</td>';
	echo '<td><select id="'.$name.'" name="'.$name.'"'; if ($onchange != '') { echo 'onchange="'.$onchange.'"'; } echo '>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $key) { $selected = 'selected'; }
		echo '<option value="'.$key.'" '.$selected.'>'.$key.'</option>';
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
	global $wpdb,$FSREPconfig;

	if ($ListingID != '') {
		
		$Listing = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID);
		
		if ($Listing->listing_label == '') {
			$ListingName = 'Listing #'.$ListingID;
		} else {
			$ListingName = $Listing->listing_label;
		}
		
		if ($Listing->listing_sold == 1) {
			$ListingName = $FSREPconfig['SoldLabel'].' '.$ListingName;
		}
		if (substr($ListingName, 0, 3) == ' - ') {
			$ListingName = substr($ListingName, 3); 
		}
		
		return $ListingName;
	}
}
function fsrep_breadcrumbs() {
	global $FSREPconfig,$post,$wpdb,$ListingID,$CityID,$ProvinceID,$CountryID,$ListingHomeURL;
	$Breadcrumbs = '';
	if ($CountryID != 0) {
		$CountryInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
		$Breadcrumbs .= '> <a href="'.get_bloginfo('url').'/'.$ListingHomeURL.'/'.$CountryInfo->country_url.'/">'.$CountryInfo->country_name.'</a> ';
	}
	if ($ProvinceID != 0) {
		$ProvinceInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
		$Breadcrumbs .= '> <a href="'.get_bloginfo('url').'/'.$ListingHomeURL.'/'.$CountryInfo->country_url.'/'.$ProvinceInfo->province_url.'/">'.$ProvinceInfo->province_name.'</a> ';
	}
	if ($CityID != 0) {
		$CityInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID");
		$Breadcrumbs .= '> <a href="'.get_bloginfo('url').'/'.$ListingHomeURL.'/'.$CountryInfo->country_url.'/'.$ProvinceInfo->province_url.'/'.$CityInfo->city_url.'/">'.$CityInfo->city_name.'</a> ';
	}
	if ($ListingID != 0) {
		$ListingLabel = $wpdb->get_var("SELECT listing_label FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID");
		$Breadcrumbs .= '> <a href="'.fsrep_listing_url_gen($ListingID).'">'.$ListingLabel.'</a> ';
	}
	if ($Breadcrumbs != '') {
		return '<div id="fsrep-breadcrumbs"><a href="'.get_bloginfo('url').'/'.$ListingHomeURL.'/">'.get_the_title($post->ID).'</a> '.$Breadcrumbs.'</div>';
	}
}
function fsrep_listing_url_gen($ListingID) {
	global $wpdb,$FSREPconfig;
	if ($ListingID != '') {
		$Listing = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$ListingID);
		$ListingHomeURL = $wpdb->get_var("SELECT post_name FROM ".$wpdb->prefix."posts WHERE ID = ".$FSREPconfig['ListingsPageID']);
		if ($ListingHomeURL == '') { $ListingHomeURL = 'listings'; }
		$URL = get_option('home').'/'.$ListingHomeURL.'/'.$Listing->listing_id.'-'.fsrep_url_generator(substr($Listing->listing_label,0,30)).'-'.fsrep_url_generator(fsrep_get_address_name($Listing->listing_address_city, 'city')).'-'.fsrep_url_generator(fsrep_get_address_name($Listing->listing_address_province, 'province')).'/';
		$URL = str_replace(' ', '-', $URL);
		return $URL;
	}
}
function fsrep_price_range_print($Type) {
	global $FSREPconfig;
	$Options = '<option value="0">'.$FSREPconfig['Currency'].'0</option>';
	$Array = array($FSREPconfig['Currency'].'0' => 0);
	for($i=10000;$i<=100000;$i=$i+10000) {
		$Options .= '<option value="'.$i.'">'.$FSREPconfig['Currency'].fsrep_currency_format($i).'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].fsrep_currency_format($i) => $i));
	}
	for($i=125000;$i<=500000;$i=$i+25000) {
		$Options .= '<option value="'.$i.'">'.$FSREPconfig['Currency'].fsrep_currency_format($i).'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].fsrep_currency_format($i) => $i));
	}
	for($i=550000;$i<=1000000;$i=$i+50000) {
		$Options .= '<option value="'.$i.'">'.$FSREPconfig['Currency'].fsrep_currency_format($i).'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].fsrep_currency_format($i) => $i));
	}
		$Array = array_merge($Array, array($FSREPconfig['Currency'].'1,000,001+' => '1000001+'));
	$Options .= '<option value="1000000+">'.$FSREPconfig['Currency'].'1,000,001+</option>';
	if ($Type == 'options') {
		return $Options;
	} elseif ($Type == 'array') {
		return $Array;
	}
}
function fsrep_currency_format($Price) {
	global $FSREPconfig;
	$Price = number_format($Price, 2, ".", ",");
	if (!isset($FSREPconfig['PriceTSeparator'])) { $FSREPconfig['PriceTSeparator'] = ','; }
	if (!isset($FSREPconfig['PriceCSeparator'])) { $FSREPconfig['PriceCSeparator'] = '.'; }
	$Price = str_replace(',',$FSREPconfig['PriceTSeparator'],$Price);
	$Price = str_replace('.',$FSREPconfig['PriceCSeparator'],$Price);
	return $Price;
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
	$Name = '';
	if ($ID != '') {
		if ($Type == 'country') {
			$Name = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$ID);
		} elseif ($Type == 'province') {
			$Name = $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$ID);
		} elseif ($Type == 'city') {
			$Name = $wpdb->get_var("SELECT city_name FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = ".$ID);
		}
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
	$PE = 10;
	$Limit = ' LIMIT '.$PS.', '.$PE;
	if (!isset($_SESSION['Order'])) {
		if (function_exists('fsrep_pro_default_order')) {
			$_SESSION['Order'] = fsrep_pro_default_order();
		} else {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_id DESC';
		}
	}
	if (isset($_GET['ps'])) {
		if (is_numeric($_GET['ps'])) {
			$PS = $_GET['ps'];
		}
	}
	if (isset($_GET['pe'])) {
		if (is_numeric($_GET['pe'])) {
			$PE = $_GET['pe'];
		}
	}
	if (isset($_GET['order'])) {
		if ($_GET['order'] == 'listing_price_num' || $_GET['order'] == 'listing_price_num DESC') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.'.$_GET['order'];
		} else {
			$_SESSION['Order'] .= ' DESC';
		}
	}
	$ModSQL = '';
	if (function_exists('fsrep_pro_visibility')) { 
		if (fsrep_pro_visibility() == 1) {
			$ModSQL .= " AND listing_visibility = 1 ";
		}
	}
	if ($type == 'search') {
		$Listings = $wpdb->get_results($value);
	} elseif ($type == 'country') {
		if ($CountryID != 0) { 
			$CountryDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
			//$Overview = stripslashes($CountryDetails->country_overview);
			$OverviewTitle = $CountryDetails->country_name;
		} else {
			$CountryID = $value; 
		}
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_country = $CountryID ".$ModSQL." ORDER BY ".$_SESSION['Order']);
	} elseif ($type == 'province' || $type == 'state') {
		if ($ProvinceID != 0) { 
			$ProvinceDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
			//$Overview = stripslashes($ProvinceDetails->province_overview);
			$OverviewTitle = $ProvinceDetails->province_name;
		} else {
			$ProvinceID = $value; 
		}
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_province = $ProvinceID ".$ModSQL." ORDER BY ".$_SESSION['Order']);
	} elseif ($type == 'city') {
		if ($CityID != 0) { 
			$CityDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID");
			//$Overview = stripslashes($CityDetails->city_overview);
			$OverviewTitle = $CityDetails->city_name;
		} else {
			$CityID = $value; 
		}
		$Listings = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_city = $CityID ".$ModSQL." ORDER BY ".$_SESSION['Order']);
	} else {
		$ListingsSQL = "SELECT * FROM ".$wpdb->prefix."fsrep_listings";
		if ($Filter != 0) {
			if ($wpdb->get_var("SELECT filter_map FROM ".$wpdb->prefix."fsrep_filters WHERE filter_id = $Filter") == 0) { $GoogleMap = FALSE; }
			$FieldSQL = "SELECT DISTINCT t1.listing_id FROM ".$wpdb->prefix."fsrep_listings_to_fields t1, ".$wpdb->prefix."fsrep_listings WHERE EXISTS ";
			$Filters = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_filters_details WHERE filter_id = ".$Filter);
			$FieldID = '';
			$ListingValue = '';
			$FieldValueSQL = '';
			foreach ($Filters as $Filters) {
				$FieldID .= "'$Filters->field_id', ";
				$ListingValue .= "'$Filters->field_values', ";
				$FieldValueSQL .= "(SELECT * FROM wp_fsrep_listings_to_fields WHERE field_id = '$Filters->field_id' AND listing_value = '$Filters->field_values' AND listing_id = t1.listing_id) AND EXISTS ";
			}
			$FieldID = substr($FieldID, 0, -2);
			$ListingValue = substr($ListingValue, 0, -2);
			$FieldSQL .= substr($FieldValueSQL, 0, -11);
			$MatchingListings = $wpdb->get_results($FieldSQL);
			$MatchingListingsID = '';
			foreach ($MatchingListings as $MatchingListings) {
				$MatchingListingsID .= "'".$MatchingListings->listing_id."', ";
			}
			$MatchingListingsID = substr($MatchingListingsID, 0, -2);
			$ListingsSQL .= " WHERE listing_id IN ($MatchingListingsID) ";
		}
		$SQLExtra = '';
		if ($_SESSION['Order'] == 'listing_id') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_id';
			$SQLExtra = ' DESC';
		} elseif ($_SESSION['Order'] == 'listing_priced') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_price_num';
			$SQLExtra = ' DESC';
		}
		$ListingsSQL .= " ORDER BY ".$_SESSION['Order'].$SQLExtra;
		$Listings = $wpdb->get_results($ListingsSQL);
	}
	$ListingCount = count($Listings);
	$Listings = array_slice($Listings, $PS, $PE);
	
	if ($GoogleMap == TRUE) {
		$PageContent .= '<div id="listings_map" style="width: 100%; height: 300px; border: 1px solid #999999; margin-bottom: 12px;"></div>';
	} else {
		$PageContent .= '';
	}
		
	$TotalPages = 1;		
	
	if ($FSREPconfig['DisablePageSorting'] == 0) {
		$PageContent .= '<div id="fs-category-options">';
		$PageContent .= '<form action="./" method="GET">';
		$PageContent .= '<div style="text-align: right; float: right; width: 34%;">Sort by: <select name="order" onchange="this.form.submit();" class="sortby" style="width: 130px;">';
		$PageContent .= '<option value="listing_id" '; if ($_SESSION['Order'] == "listing_id" || $_SESSION['Order'] == "wp_fsrep_listings.listing_id") { $PageContent .= 'selected'; } $PageContent .= '>Recently Added</option>';
		$PageContent .= '<option value="listing_price_num" '; if ($_SESSION['Order'] == "listing_price_num" || $_SESSION['Order'] == "wp_fsrep_listings.listing_price_num") { $PageContent .= 'selected'; } $PageContent .= '>Price (low to high)</option>';
		$PageContent .= '<option value="listing_price_num DESC" '; if ($_SESSION['Order'] == "listing_price_num DESC" || $_SESSION['Order'] == "wp_fsrep_listings.listing_price_num DESC") { $PageContent .= 'selected'; } $PageContent .= '>Price (high to low)</option>';
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
	}

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
			if ($_GET['order'] == 'listing_price_num' || $_GET['order'] == 'listing_price_num DESC') {
				$_SESSION['Order'] = $_GET['order'];
			} else {
				$_SESSION['Order'] = ' DESC';
			}
		}
		$PageContent .= '<div id="fsrep-page-numbers"><a href="'.get_option('home').'/'.$ListingHomeURL.'/">1</a>';
		for ($i=1;$i<$TotalPages;$i++) {
			$PageNumber = $i + 1;
			$PS = $i * $PE;
			$PageContent .= ' | <a href="http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'?ps='.$PS.'&pe='.$PE.'">'.$PageNumber.'</a>';
		}
		$PageContent .= '</div>';
	} else {
		$PageContent .= 'No listings were found.';
	}
	
	if ($Overview != '') { $PageContent .= '<div id="fsrep-overview"><h2>About '.$OverviewTitle.'</h2>'.$Overview.'</div>'; }
	if (isset($FSREPconfig['DisplaySubLocations'])) {
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