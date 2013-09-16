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
function fsrep_license_error($Name) {
	add_action('admin_notices', 'fsrep_license_error_alert');
	return;
}
function fsrep_license_error_alert() {
	echo '<div class="error"><p><strong>FireStorm Plugin Error: </strong>Please enter your extension license.</div>';
}
function fsrep_license_check($Extension, $ExtensionName, $ConfigURL) {
	global $FSREPExtensions,$wpdb;
	$ExtensionNotice = '';
	if ($FSREPExtensions[$Extension.'L'] > 10) {
		$ExtensionNotice = '<a href="'.$ConfigURL.'">Please enter your '.$ExtensionName.' license.</a>';
	}
	if (abs(strtotime(date("Y-m-d H:i:s")) - strtotime(date($FSREPExtensions[$Extension.'T'], strtotime("24 Hours")))) >= 86400) {
		fsrep_extension_update($Extension, $FSREPExtensions[$Extension.'V']);
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".date("Y-m-d H:i:s")."' WHERE config_name = '".$Extension."T'");
	}
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
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div><input type="file" name="'.$name.'" value="'.$value.'" size="'.$length.'"></div>';
}
function fsrep_print_password_input($label, $name, $value, $length) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div><input type="password" name="'.$name.'" value="'.$value.'" size="'.$length.'"></div>';
}
function fsrep_print_input($label, $name, $value, $length) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div><input type="text" name="'.$name.'" value="'.$value.'" size="'.$length.'"></div>';
}
function fsrep_print_textarea($label, $name, $value, $rows, $cols) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div><textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea></div>';
}
function fsrep_print_selectbox($label, $name, $selvalue, $options, $onchange) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
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
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'checked'; }
		echo '<input type="checkbox" name="'.$name.'" value="'.$value.'" '.$selected.'>'.$key.' ';
	}
	echo '</div>';
}
function fsrep_print_radio($label, $name, $selvalue, $options) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	echo '<div id="fsrep_input"><div id="fsrep_input_label">'.$label.'</div>';
	foreach ($options as $key=>$value) {
		$selected = '';
		if ($selvalue == $value) { $selected = 'checked'; }
		echo '<input type="radio" name="'.$name.'" value="'.$value.'" '.$selected.'>'.$key.' ';
	}
	echo '</div>';
}
function fsrep_print_admin_file_input($label, $name, $value, $length, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td><td><input type="file" name="'.$name.'" value="'.$value.'" size="'.$length.'"></td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_input($label, $name, $value, $length, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td><td><input type="text" name="'.$name.'" value="'.$value.'" size="'.$length.'"></td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_input_disabled($label, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td><td><input type="text" disabled></td><td style="font-weight: normal; color: #999999;">'.$description.'</td></tr>';
}
function fsrep_print_admin_textarea($label, $name, $value, $rows, $cols, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td><td><textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea></td></tr>';
}
function fsrep_print_admin_selectbox($label, $name, $selvalue, $options, $onchange, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td>';
	echo '<td><select id="'.$name.'" name="'.$name.'"'; if ($onchange != '') { echo 'onchange="'.$onchange.'"'; } echo '>';
	if ($options != '') {
		foreach ($options as $key=>$value) {
			$key = fsrep_text_translator('FireStorm Real Estate Plugin', $key.' Label', $key);
			$selected = '';
			if ($selvalue == $value) { $selected = 'selected'; }
			echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
		}
	}
	echo '</select>';
	echo '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_selectbox_disabled($label, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td>';
	echo '<td><select disabled><option value="Disabled">Disabled</option></select>';
	echo '</td><td style="font-weight: normal; color: #999999;">'.$description.'</td></tr>';
}
function fsrep_print_admin_field_selectbox($label, $name, $selvalue, $options, $onchange, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td>';
	echo '<td><select id="'.$name.'" name="'.$name.'"'; if ($onchange != '') { echo 'onchange="'.$onchange.'"'; } echo '>';
	foreach ($options as $key=>$value) {
		$key = fsrep_text_translator('FireStorm Real Estate Plugin', $key.' Label', $key);
		$selected = '';
		if ($selvalue == $key) { $selected = 'selected'; }
		echo '<option value="'.$key.'" '.$selected.'>'.$key.'</option>';
	}
	echo '</select>';
	echo '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_radio($label, $name, $selvalue, $options, $onchange, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td><td>';
	foreach ($options as $key=>$value) {
		$key = fsrep_text_translator('FireStorm Real Estate Plugin', $key.' Label', $key);
		$selected = '';
		if ($selvalue == $value) { $selected = 'checked'; }
		echo '<label><input type="radio" id="'.$name.'" name="'.$name.'" value="'.$value.'" '.$selected.'>'.$key.' &nbsp; &nbsp; </label>';
	}
	echo '</select>';
	echo '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_print_admin_checkbox($label, $name, $selvalue, $options, $onchange, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	echo '<tr><td>'.$label.'</td><td>';
	$number = 0;
	$selvalue = str_replace(', ',',',$selvalue);
	$selvalue = explode(',',$selvalue);
	foreach ($options as $key=>$value) {
		$key = fsrep_text_translator('FireStorm Real Estate Plugin', $key.' Label', $key);
		$number++;
		$selected = '';
		if (in_array($value, $selvalue)) { $selected = 'checked'; }
		echo '<label><input type="checkbox" id="'.$name.'" name="'.$number.$name.'" value="'.$value.'" '.$selected.'>'.$key.' &nbsp; &nbsp; </label>';
	}
	echo '</select>';
	echo '</td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_return_admin_file_input($label, $name, $value, $length, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	return '<tr><td>'.$label.'</td><td><input type="file" name="'.$name.'" value="'.$value.'" size="'.$length.'"></td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_return_admin_input($label, $name, $value, $length, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	return '<tr><td>'.$label.'</td><td><input type="text" name="'.$name.'" value="'.$value.'" size="'.$length.'"></td><td style="font-weight: normal;">'.$description.'</td></tr>';
}
function fsrep_return_admin_textarea($label, $name, $value, $rows, $cols, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
	return '<tr><td>'.$label.'</td><td><textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea></td></tr>';
}
function fsrep_return_admin_selectbox($label, $name, $selvalue, $options, $onchange, $description) {
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
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
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
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
	$label = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' Label', $label);
	$description = fsrep_text_translator('FireStorm Real Estate Plugin', $label.' About Label', $description);
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
function fsrep_image_sizes() {
	$FSREPImageSizes = new stdClass();
	$FSREPImageSizes->main[0] = 300;
	$FSREPImageSizes->main[1] = 225;
	$FSREPImageSizes->small[0] = 80;
	$FSREPImageSizes->small[1] = 60;
	$FSREPImageSizes->medium[0] = 200;
	$FSREPImageSizes->medium[1] = 150;
	$FSREPImageSizes->large[0] = 800;
	$FSREPImageSizes->large[1] = 600;
	if (function_exists('fsrep_pro_image_sizes')) { $FSREPImageSizes = fsrep_pro_image_sizes(); }
	return $FSREPImageSizes;
}
function fsrep_imageresizer($source_pic, $destination_pic, $max_width, $max_height) {
	global $FSREPconfig;
	if (!isset($FSREPconfig['FSREPImageCompression'])) { $FSREPconfig['FSREPImageCompression'] = 80; }
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
	
	imagejpeg($tmp,$destination_pic,$FSREPconfig['FSREPImageCompression']);
	imagedestroy($src);
	imagedestroy($tmp);
}
function fsrep_admin_update_button() {
	return '<input type="submit" name="submit" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Label', 'Update').'">';
}



function google_geocoder($address, $api) {
	$Coords = '';
	$address = str_replace(' ', '+', $address);
	// OLD $XMLUrl = 'http://maps.google.com/maps/geo?q='.$address.'&key='.$api.'&sensor=false&output=xml&oe=utf8';
	$XMLUrl = 'http://maps.googleapis.com/maps/api/geocode/xml?address='.$address.'&sensor=false';
	
	// V3 $XMLUrl = 'http://maps.googleapis.com/maps/api/geocode/xml?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&sensor=false';
	
	
	$XMLContents = file_get_contents($XMLUrl);
	
	$XML = new SimpleXMLElement($XMLContents);
	
	
	// OLD if (isset($XML->Response->Placemark->Point->coordinates)) { $Coords = explode(',',$XML->Response->Placemark->Point->coordinates); }
	if (isset($XML->result->geometry->location->lat)) { $Coords[1] = $XML->result->geometry->location->lat; }
	if (isset($XML->result->geometry->location->lng)) { $Coords[0] = $XML->result->geometry->location->lng; }
	
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
function fsrep_price_range_print($Type, $CurrentValue) {
	global $FSREPconfig;
	$Options = '<option value="0">'.$FSREPconfig['Currency'].fsrep_currency_format(0).'</option>';
	$Array = array($FSREPconfig['Currency'].'0' => 0);
	for($i=10000;$i<=100000;$i=$i+10000) {
		$selected = '';
		if ($CurrentValue == $i) {
			$selected = ' selected';
		}
		$Options .= '<option value="'.$i.'"'.$selected.'>'.$FSREPconfig['Currency'].fsrep_currency_format($i).'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].fsrep_currency_format($i) => $i));
	}
	for($i=125000;$i<=500000;$i=$i+25000) {
		$selected = '';
		if ($CurrentValue == $i) {
			$selected = ' selected';
		}
		$Options .= '<option value="'.$i.'"'.$selected.'>'.$FSREPconfig['Currency'].fsrep_currency_format($i).'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].fsrep_currency_format($i) => $i));
	}
	for($i=550000;$i<=1000000;$i=$i+50000) {
		$selected = '';
		if ($CurrentValue == $i) {
			$selected = ' selected';
		}
		$Options .= '<option value="'.$i.'"'.$selected.'>'.$FSREPconfig['Currency'].fsrep_currency_format($i).'</option>';
		$Array = array_merge($Array, array($FSREPconfig['Currency'].fsrep_currency_format($i) => $i));
	}
	$selected = '';
	if ($CurrentValue == '999999999999') {
		$selected = ' selected';
	}
	$Array = array_merge($Array, array($FSREPconfig['Currency'].'1,000,001+' => '1000001+'));
	$Options .= '<option value="999999999999"'.$selected.'>'.$FSREPconfig['Currency'].'1,000,001+</option>';
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
	$Price = str_replace('.',$FSREPconfig['PriceCSeparator'],$Price);
	$Price = str_replace(',',$FSREPconfig['PriceTSeparator'],$Price);
	if (function_exists('fsrep_pro_currency_format')) { $Price = fsrep_pro_currency_format($Price); }
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
function fsrep_sql_clean($SQL) {
	$SQL = str_replace('  ',' ',$SQL);
	$SQL = str_replace('DESC DESC','DESC',$SQL);
	$SQL = str_replace('AND AND','AND',$SQL);
	$SQL = str_replace('AND GROUP BY','GROUP BY',$SQL);
	$SQL = str_replace('AND ORDER BY','ORDER BY',$SQL);
	$SQL = str_replace('ORDER BY DESC','ORDER BY listing_id DESC',$SQL);
	$SQL = str_replace('WHERE ORDER BY','ORDER BY',$SQL);
	$SQL = str_replace('WHERE AND','WHERE',$SQL);
	if (substr($SQL, -9, -1) == 'ORDER BY') { $SQL = str_replace('ORDER BY','',$SQL); }
	return $SQL;
}
function fsrep_listings_update($ListingID) {
	global $wpdb,$user_ID,$FSREPconfig,$FSREPExtensions,$_POST,$_GET,$FSREPCurrentPermission,$FSREPAdminPermissions,$MAPageID,$post;
	
	$AllowAccess = FALSE;
	if ($FSREPCurrentPermission == $FSREPAdminPermissions) {
		$AllowAccess = TRUE;
	} elseif ($FSREPExtensions['Membership'] == TRUE) {
		if (function_exists('fsrep_member_listing_check')) {
			$AllowAccess = fsrep_member_listing_check($user_ID, $_GET['hid']);
		}
	}
	if ($_GET['f'] == 'add') {
		$AllowAccess = TRUE;
	}
	
	if ($AllowAccess == TRUE) {
		if (isset($_POST['submit'])) {
			$ManagementType = 'edit';
			$RegisterFormError = '';
			
			// MAKE SURE IMAGE IS JPG
			if ($_FILES['image']['name'] != '') {
				if (!preg_match('/jpg/i', $_FILES['image']['name'])) {
					$RegisterFormError = 'Pictures must be in jpg format.';
				}
			}
			if ($RegisterFormError == '') {
				// GET COUNTRY NAME
				if ($_POST['listing_address_country'] != '') {
					$CountryName = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$_POST['listing_address_country']);
				} else {
					$CountryName = '';
				}
				// GET PROVINCE NAME
				if (isset($_POST['listing_address_province']) && $_POST['listing_address_province'] != '') {
					$ProvinceName = $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$_POST['listing_address_province']);
				} else {
					$ProvinceName = '';
				}
				// GET CITY NAME OR ADD
				if (isset($_POST['listing_address_city']) && $_POST['listing_address_city'] != '' && $_POST['listing_address_city2'] == '') {
					$CityName = $wpdb->get_var("SELECT city_name FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = ".$_POST['listing_address_city']);
					
				} else {
					if ($_POST['listing_address_city2'] != '') {
						// INSERT CITY
						$CityURL = fsrep_url_generator($_POST['listing_address_city2']);
						$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_cities (city_name, city_url, province_id, country_id) VALUES('".ucwords($_POST['listing_address_city2'])."', '$CityURL', ".$_POST['listing_address_province'].", ".$_POST['listing_address_country'].")");
						$CityID = $wpdb->get_var("SELECT city_id FROM ".$wpdb->prefix."fsrep_cities ORDER BY city_id DESC LIMIT 1");
						$CityName = $wpdb->get_var("SELECT city_name FROM ".$wpdb->prefix."fsrep_cities ORDER BY city_id DESC LIMIT 1");
						$_POST['listing_address_city'] = $CityID;
					} else {
						$CityName = '';
						$CityID = '';
					}
				}
				
				// GET REGION NAME OR ADD
				$RegionName = '';
				if (function_exists('fsrep_pro_regions_add')) { $RegionName = fsrep_pro_regions_add($_POST); }


				if ($ListingID == 0) {
					// ADD LISTING
					$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings (listing_label, listing_date_added) VALUES ('".$_POST['listing_label']."', NOW())");
					$ListingID = $wpdb->get_var("SELECT listing_id FROM ".$wpdb->prefix."fsrep_listings ORDER BY listing_id DESC LIMIT 1");
					$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_users (listing_id, ID) VALUES ($ListingID, $user_ID)");
					$ManagementType = 'add';
				}
				
				// UPDATE LISTING
				foreach ($_POST as $key=>$value) {
					$FieldCheck = count($wpdb->get_var("SHOW COLUMNS FROM ".$wpdb->prefix."fsrep_listings LIKE '".$key."'"));
					if ($FieldCheck == 1 && $key != 'listing_id') {
						$UpdateSQL = "UPDATE ".$wpdb->prefix."fsrep_listings SET $key = '".addslashes($value)."' WHERE listing_id = $ListingID; ";
						$wpdb->query($UpdateSQL);
					}
				}
	
				//$ListingVisibility = 1; if (function_exists('fsrep_pro_visibility') && $FSREPCurrentPermission != $FSREPAdminPermissions) { $ListingVisibility = fsrep_pro_visibility(); }
				if (function_exists('fsrep_member_moderation') && $FSREPCurrentPermission != $FSREPAdminPermissions) { fsrep_member_moderation($ListingID, $ManagementType); }
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_price_num = '".str_replace(',','',$_POST['listing_price'])."' WHERE listing_id = $ListingID");
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_last_updated = NOW() WHERE listing_id = $ListingID");
				// UPDATE CUSTOM FIELDS
				$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE listing_id = $ListingID");
				$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
				foreach ($Fields as $Fields) {
					if ($Fields->field_type != 'checkbox') {
						if (isset($_POST['field'.$Fields->field_id])) {
							if ($_POST['field'.$Fields->field_id] != '' && $_POST['field'.$Fields->field_id] != 'Not Applicable') {
								$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_fields (listing_id, field_id, listing_value) VALUES ($ListingID,$Fields->field_id,'".$_POST['field'.$Fields->field_id]."')");
							}
						}
					} else {
						$CBValues = explode(',',$Fields->field_value);
						$CBValue = '';
						$CheckBoxCount = count($CBValues) + 1;
						for($i=0;$i<=$CheckBoxCount;$i++) {
							if (isset($_POST[$i.'field'.$Fields->field_id])) {
								$CBValue .= $_POST[$i.'field'.$Fields->field_id].', ';
							}
						}
						$CBValue = substr($CBValue,0,-2);
						$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_fields (listing_id, field_id, listing_value) VALUES ($ListingID,$Fields->field_id,'".$CBValue."')");
					}
				}
				
				// UPDATE GOOGLE COORDS
				if ($_POST['listing_auto_coords'] == 1) {
					$Coords = google_geocoder($_POST['listing_address_number'].' '.$_POST['listing_address_street'].' '.$RegionName.' '.$CityName.' '.$ProvinceName.' '.$_POST['listing_address_postal'].' ', $FSREPconfig['GoogleMapAPI']);
					if (isset($Coords[0]) && isset($Coords[1])) {
						$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_long = '".$Coords[0]."', listing_lat = '".$Coords[1]."', listing_zoom = '16' WHERE listing_id = $ListingID");
					}
				} else {
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_long = '".$_POST['listing_long']."', listing_lat = '".$_POST['listing_lat']."', listing_zoom = '".$_POST['listing_zoom']."' WHERE listing_id = $ListingID");
				}
				
				$WPUploadDir = wp_upload_dir();
					
				// UPDATE IMAGE IF NEEDED
				if ($_FILES['image']['name'] != "") {


					// UNLINK CURRENT IMAGES
					if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/small/'.$ListingID.'.jpg')) {
						unlink($WPUploadDir['basedir'].'/fsrep/houses/small/'.$ListingID.'.jpg');
					}
					if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/large/'.$ListingID.'.jpg')) {
						unlink($WPUploadDir['basedir'].'/fsrep/houses/large/'.$ListingID.'.jpg');
					}
					if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/medium/'.$ListingID.'.jpg')) {
						unlink($WPUploadDir['basedir'].'/fsrep/houses/medium/'.$ListingID.'.jpg');
					}
					if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/'.$ListingID.'.jpg')) {
						unlink($WPUploadDir['basedir'].'/fsrep/houses/'.$ListingID.'.jpg');
					}
					
					// UPDATE IMAGE
					$uploaddir = $WPUploadDir['basedir'].'/fsrep/houses/temp/';
					$uploadfile = $uploaddir . basename($_FILES['image']['name']);
					if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
						// Upload Image as Enlarged Version
						rename($uploadfile, $uploaddir.basename($_FILES['image']['name']));
						
						// CONVERT ENLARGED IMAGE TO THUMBNAIL AND STANDARD SIZE
						$FSREPImageSizes = fsrep_image_sizes();
						fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), $WPUploadDir['basedir'].'/fsrep/houses/'.$ListingID.'.jpg', $FSREPImageSizes->main[0], $FSREPImageSizes->main[1]);
						fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), $WPUploadDir['basedir'].'/fsrep/houses/small/'.$ListingID.'.jpg', $FSREPImageSizes->small[0], $FSREPImageSizes->small[1]);
						fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), $WPUploadDir['basedir'].'/fsrep/houses/medium/'.$ListingID.'.jpg', $FSREPImageSizes->medium[0], $FSREPImageSizes->medium[1]);
						fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), $WPUploadDir['basedir'].'/fsrep/houses/large/'.$ListingID.'.jpg', $FSREPImageSizes->large[0], $FSREPImageSizes->large[1]);
						
						unlink($uploaddir.basename($_FILES['image']['name']));
						
					}
				}
				
				// ADDITIONAL IMAGES
				//$ANumb = $_POST['aimagen'] + 1;
				if (isset($_FILES['aimage']['name'])) {
					if ($_FILES['aimage']['name'] != "") {
						// UPDATE IMAGE NAMING
						$FSREPImageSizes = fsrep_image_sizes();
						$AImageID = 1;
						for ($i=1;$i<=50;$i++) {
							if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/'.$ListingID.'-'.$i.'.jpg')) {
								rename($WPUploadDir['basedir'].'/fsrep/houses/additional/'.$ListingID.'-'.$i.'.jpg', $WPUploadDir['basedir'].'/fsrep/houses/additional/'.$ListingID.'-'.$AImageID.'.jpg');
								rename($WPUploadDir['basedir'].'/fsrep/houses/additional/small/'.$ListingID.'-'.$i.'.jpg', $WPUploadDir['basedir'].'/fsrep/houses/additional/small/'.$ListingID.'-'.$AImageID.'.jpg');
								rename($WPUploadDir['basedir'].'/fsrep/houses/additional/medium/'.$ListingID.'-'.$i.'.jpg', $WPUploadDir['basedir'].'/fsrep/houses/additional/medium/'.$ListingID.'-'.$AImageID.'.jpg');
								rename($WPUploadDir['basedir'].'/fsrep/houses/additional/large/'.$ListingID.'-'.$i.'.jpg', $WPUploadDir['basedir'].'/fsrep/houses/additional/large/'.$ListingID.'-'.$AImageID.'.jpg');
								$AImageID++;
							}
						}
						$ANumb = $AImageID;
	
						// UNLINK CURRENT IMAGES IF THEY EXIST
						if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/medium/'.$ListingID.'-'.$ANumb.'.jpg')) { unlink($WPUploadDir['basedir'].'/fsrep/houses/additional/medium/'.$ListingID.'-'.$ANumb.'.jpg'); }
						if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/small/'.$ListingID.'-'.$ANumb.'.jpg')) { unlink($WPUploadDir['basedir'].'/fsrep/houses/additional/small/'.$ListingID.'-'.$ANumb.'.jpg'); }
						if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/large/'.$ListingID.'-'.$ANumb.'.jpg')) { unlink($WPUploadDir['basedir'].'/fsrep/houses/additional/large/'.$ListingID.'-'.$ANumb.'.jpg'); }
						if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/'.$ListingID.'-'.$ANumb.'.jpg')) { unlink($WPUploadDir['basedir'].'/fsrep/houses/additional/'.$ListingID.'-'.$ANumb.'.jpg'); }
						
						// UPDATE IMAGE
						$uploaddir = $WPUploadDir['basedir'].'/fsrep/houses/additional/temp/';
						$uploadfile = $uploaddir . basename($_FILES['aimage']['name']);
						if (move_uploaded_file($_FILES['aimage']['tmp_name'], $uploadfile)) {
							// Upload Image as Enlarged Version
							rename($uploadfile, $uploaddir.basename($_FILES['aimage']['name']));
							
							// CONVERT ENLARGED IMAGE TO THUMBNAIL AND STANDARD SIZE
							fsrep_imageresizer($uploaddir.basename($_FILES['aimage']['name']), $WPUploadDir['basedir'].'/fsrep/houses/additional/'.$ListingID.'-'.$ANumb.'.jpg', $FSREPImageSizes->main[0], $FSREPImageSizes->main[1]);
							fsrep_imageresizer($uploaddir.basename($_FILES['aimage']['name']), $WPUploadDir['basedir'].'/fsrep/houses/additional/small/'.$ListingID.'-'.$ANumb.'.jpg', $FSREPImageSizes->small[0], $FSREPImageSizes->small[1]);
							fsrep_imageresizer($uploaddir.basename($_FILES['aimage']['name']), $WPUploadDir['basedir'].'/fsrep/houses/additional/medium/'.$ListingID.'-'.$ANumb.'.jpg', $FSREPImageSizes->medium[0], $FSREPImageSizes->medium[1]);
							fsrep_imageresizer($uploaddir.basename($_FILES['aimage']['name']), $WPUploadDir['basedir'].'/fsrep/houses/additional/large/'.$ListingID.'-'.$ANumb.'.jpg', $FSREPImageSizes->large[0], $FSREPImageSizes->large[1]);
							
							unlink($uploaddir.basename($_FILES['aimage']['name']));
							
						}
						
					}
				}
				
				// DOCUMENTS
				if (isset($_FILES['doc']['name'])) {
					if ($_FILES['doc']['name'] != "") {
						$FileType = substr($_FILES['doc']['name'], -4);
						if($FileType == '.doc' || $FileType == 'docx' || $FileType == '.xls' || $FileType == 'xlsx' || $FileType == '.ppt' || $FileType == '.pps' || $FileType == '.pdf' || $FileType == '.rtf' || $FileType == '.txt' || $FileType == 'ppts') {
							$uploaddir = $WPUploadDir['basedir'].'/fsrep/houses/docs/';
							$uploadfile = $uploaddir.$ListingID.basename($_FILES['doc']['name']);
							if (move_uploaded_file($_FILES['doc']['tmp_name'], $uploadfile)) {
								$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_docs (listing_id, document_name) VALUES (".$ListingID.", '".$ListingID.basename($_FILES['doc']['name'])."')");
							}
						}
					}
				}
			}
			
			unset($_POST);
			unset($_GET);
			$FSREPShowForm = TRUE;
			if ($ManagementType == 'add') {
				if (isset($MAPageID) && isset($post->ID) && $MAPageID == $post->ID) {
					echo '<div id="message" class="updated fade"><p><strong>Your listing has been added.</strong> <a href="'.get_permalink($post->ID).'?Listings&f=edit&hid='.$ListingID.'">Click here</a> to edit your listing.</p></div>';
				} else {
					echo '<div id="message" class="updated fade"><p><strong>Your listing has been added.</strong> <a href="admin.php?page=fsrep_listings&hid='.$ListingID.'&f=edit">Click here</a> to edit your listing.</p></div>';
				}
				$FSREPShowForm = FALSE;
			} else {
				echo '<div id="message" class="updated fade"><p><strong>Your listing has been updated.</strong></p></div>';
			}
		}
		if (!isset($FSREPShowForm)) { $FSREPShowForm = TRUE; }
		
		
		if ($ListingID !=0 && $ListingID != '') {
			$sql = mysql_query("SELECT *, date_format(".$wpdb->prefix."fsrep_listings.listing_date_added, '%W %M %D %Y') as date_listed, date_format(".$wpdb->prefix."fsrep_listings.listing_last_updated, '%W %M %D %Y') as last_updated FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID");
			$_POST = mysql_fetch_array($sql);
		} else {
			$_POST = array(
										'listing_id' => '0',
										'listing_auto_coords' => '1',
										'listing_label' => '',
										'listing_description' => '',
										'listing_sold' => '',
										'listing_price' => '',
										'listing_address_number' => '',
										'listing_address_street' => '',
										'listing_address_country' => '',
										'listing_address_province' => '',
										'listing_address_city' => '',
										'listing_address_city2' => '',
										'listing_address_region' => '',
										'listing_address_region2' => '',
										'listing_address_postal' => '',
										'listing_contact_display' => '',
										'listing_contact_name' => '',
										'listing_contact_email' => '',
										'listing_contact_home_phone' => '',
										'listing_contact_cell_phone' => '',
										'listing_contact_special_instructions' => '',
										'listing_contact_form_email' => '',
										'listing_virtual_tour' => '',
										'listing_slideshow' => '',
										'listing_video' => '',
										'listing_long' => '',
										'listing_lat' => '',
										'listing_zoom' => ''
										);
		}
		if (isset($MAPageID) && isset($post->ID) && $MAPageID == $post->ID) {
			echo '<form name="fsrep-add-listing" id="fsrep-add-listing" action="'.get_permalink($post->ID).'?Listings&f=add&hid='.$ListingID.'" method="post" enctype="multipart/form-data">';
		} else {
			echo '<form name="fsrep-add-listing" id="fsrep-add-listing" action="admin.php?page=fsrep_listings&hid='.$ListingID.'&f=edit" method="post" enctype="multipart/form-data">';
		}
		fsrep_print_hidden_input('MAX_FILE_SIZE', '10000000');
		fsrep_print_hidden_input('listing_id', $ListingID);
		
		if (isset($RegisterFormError)) {
			if ($RegisterFormError != '') {
				echo '<div id="fsrep-form-error">'.$RegisterFormError.'</div>';
			}
		}
		if ($FSREPShowForm == TRUE) {
			if (isset($MAPageID) && isset($post->ID) && $MAPageID == $post->ID) {
				include("includes/admin_listings_basic_form.php");
			} else {
				include("includes/admin_listings_form.php");
			}
		}
		echo '</form>';
		if (isset($_POST['listing_address_country'])) {
			echo '<script type="text/javascript">';
			echo 'getFSREPlist(\''.$_POST['listing_address_country'].'\', \'listing_address_province\', \'CountryID\', \''.$_POST['listing_address_province'].'\'); ';
			echo 'getFSREPlist(\''.$_POST['listing_address_province'].'\', \'listing_address_city\', \'ProvinceID\', \''.$_POST['listing_address_city'].'\'); ';
			if (function_exists('fsrep_pro_regions_js')) { fsrep_pro_regions_js(); }
			echo '</script>';
		}
	} else {
		echo 'You do not have access to this listing.';
	}
}
function fsrep_del_listing($ListingID) {
	global $wpdb,$user_ID,$FSREPCurrentPermission,$FSREPAdminPermissions;
	if ($FSREPCurrentPermission == $FSREPAdminPermissions) {
		$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = $ListingID");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_users WHERE listing_id = $ListingID");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE listing_id = $ListingID");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_docs WHERE listing_id = $ListingID");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_pictures WHERE listing_id = $ListingID");
	}
}
function fsrep_listing_manager($CurrentURL) {
	global $_GET,$wpdb,$MAPageID,$post,$user_ID,$FSREPCurrentPermission,$FSREPAdminPermissions;
	$ShowListings = TRUE;
	echo '<h2>Manage Listings ';
	if (function_exists('fsrep_member_listing_limit')) {
		echo fsrep_member_listing_limit($user_ID,$CurrentURL,'Add Listing');
		
		
		
		
		
	} else {
		echo '<a href="'.$CurrentURL.'&f=add" class="add-new-h2">Add New</a>';
	}
	echo '</h2>';
	if (!isset($_GET['hid'])) {
		$_GET['hid'] = 0;
	}
	if (isset($_GET['f'])) {
		if ($_GET['f'] == 'add' && function_exists('fsrep_member_listing_limit')) {
			if (fsrep_member_listing_limit($user_ID,$CurrentURL,'Check') == FALSE) {
				$ShowListings = TRUE;
				$_GET['f'] = 'list';
			}
		}
		if ($_GET['f'] == 'add' || $_GET['f'] == 'edit') {
			echo fsrep_listings_update($_GET['hid']);
			$ShowListings = FALSE;
		} elseif ($_GET['f'] == 'del') {
			fsrep_del_listing($_GET['hid']);
			echo '<div id="message" class="updated fade"><p><strong>The listing has been removed.</strong></p></div>';
		} else {
			if (function_exists('fsrep_pro_listing_update')) {
				fsrep_pro_listing_update($_GET);
				$ShowListings = TRUE;
			}
			if (function_exists('fsrep_member_moderation_change')) {
				fsrep_member_moderation_change($_GET['hid'], $_GET);
				$ShowListings = TRUE;
			}
			if (function_exists('fsrep_pro_hide_change')) {
				fsrep_pro_hide_change($_GET['hid'], $_GET);
				$ShowListings = TRUE;
			}
		}
	}
	if ($ShowListings == TRUE) {
		$ListSQL = "SELECT *, date_format(listing_date_added, '%W %M %D %Y') as date_listed FROM ".$wpdb->prefix."fsrep_listings ORDER BY listing_id";
		if (function_exists('fsrep_member_listsql_listings')) {
			$ListSQL = fsrep_member_listsql_listings();
		}
		if (isset($MAPageID) && isset($post->ID) && $MAPageID == $post->ID) {
			fsrep_listings_mlist($ListSQL, $CurrentURL);
		} else {
			fsrep_listings_list($ListSQL, $CurrentURL);
		}
		
		
	}
}
function fsrep_listings_list($SQL, $CurrentURL) {
	global $wpdb,$FSREPconfig,$_POST;
	$WPUploadDir = wp_upload_dir();
	
	if (isset($_POST) && function_exists('fsrep_membership_change_user_post')) { fsrep_membership_change_user_post($_POST); }
	$Listings = $wpdb->get_results($SQL);
	if (count($Listings) > 0) {
		echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="50">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'ID Label', 'ID').'</th>
		<th scope="col" class="manage-column" width="125">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Status Label', 'Status').'</th>
		<th scope="col" class="manage-column" width="100">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Listing Details Label', 'Listing Details').'</th>';
		if (function_exists('fsrep_membership_whos_listing')) { echo '<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'User Label', 'User').'</th>'; }
		echo '<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Date Listed Label', 'Date Listed').'</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" width="50">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'ID Label', 'ID').'</th>
		<th scope="col" class="manage-column" width="125">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Status Label', 'Status').'</th>
		<th scope="col" class="manage-column" width="100">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Listing Details Label', 'Listing Details').'</th>';
		if (function_exists('fsrep_membership_whos_listing')) { echo '<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'User Label', 'User').'</th>'; }
		echo '<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Date Listed Label', 'Date Listed').'</th>
		</tr>
		</tfoot>
		<tbody>';
		foreach ($Listings as $Listings) {
			$ListingStatus = fsrep_text_translator('FireStorm Real Estate Plugin', 'Available Label', 'Available');
			if ($Listings->listing_sold == 1) { 
				$ListingStatus = fsrep_text_translator('FireStorm Real Estate Plugin', 'Sold Label', 'Sold'); 
			}
			if (function_exists('fsrep_pro_listing_astatus')) {
				$ListingStatus .= fsrep_pro_listing_astatus($Listings);
			}
			if (function_exists('fsrep_member_listing_status')) {
				$ListingStatus = fsrep_member_listing_status($Listings->listing_id);
			}
			echo '<tr>';
			echo '<td>'.$Listings->listing_id.'</td>';
			echo '<td>'.$ListingStatus.'</td>';
			echo '<td width="85">';
			if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/small/'.$Listings->listing_id.'.jpg')) { echo '<img src="'.$WPUploadDir['baseurl'].'/fsrep/houses/small/'.$Listings->listing_id.'.jpg" border="0"  style="border: 1px solid #999999;" />'; } else { echo '&nbsp;'; }
			echo '</td>';
			echo '<td><strong>'.fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay']).'</strong><br />';
			echo '<span style="color: #888888;">'.$Listings->listing_address_number.' '.$Listings->listing_address_street.' '.fsrep_get_address_name($Listings->listing_address_city, 'city').' '.fsrep_get_address_name($Listings->listing_address_province, 'province').' '.$Listings->listing_address_postal.'</span><br />';
			if (function_exists('fsrep_membership_whos_listing')) { fsrep_membership_whos_listing($Listings->listing_id); }
			echo '<a href="'.fsrep_listing_url_gen($Listings->listing_id).'" target="_blank">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'view Label', 'view').'</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=edit">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'edit Label', 'edit').'</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
			if ($Listings->listing_sold == 0) {
				echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=mod&v=sold">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'sold Label', 'sold').'</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
			} else {
				echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=mod&v=unsold">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'relist Label', 'relist').'</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
			}
			if (function_exists('fsrep_pro_listing_alibnks')) {
				echo fsrep_pro_listing_alibnks($Listings);
			}
			if (function_exists('fsrep_member_listing_alibnks')) {
				echo fsrep_member_listing_alibnks($Listings);
			}
			echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=del" onclick="if (!confirm(\'Do you really want to remove this listing?\')) return false">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'remove Label', 'remove').'</a></td>';
			if (function_exists('fsrep_membership_change_user')) { fsrep_membership_change_user($Listings->listing_id); }
			echo '<td>'.$Listings->date_listed.'</td>';
			echo '</tr>';
		}
		echo '</tbody></table><br />';
	} else {
		echo fsrep_text_translator('FireStorm Real Estate Plugin', 'No listings found.', 'No listings found.');
	}
}

// LISTINGS FUNCTION
function fsrep_listings_display($category_id, $value, $type, $fpagination, $hpagination, $CategoryPE, $GoogleMap, $Filter) {
	global $post,$wpdb,$FSREPconfig,$GMapLat,$GMapLong,$GMapZoom,$CountryID,$ProvinceID,$CityID,$ListingHomeURL,$SearchQueryID;
	
	$CurrentURL = get_permalink($post->ID);
	$WPUploadDir = wp_upload_dir();
	$SearchPagination = '';
	$ListingSorting = TRUE;
	$ListingCompare = TRUE;
	if (isset($SearchQueryID)) { $SearchPagination = '&searchid='.$SearchQueryID; }
	
	$PageContent = '';
	$Overview = '';
	
	
	
	
	
	$PS = 0;
	$PE = 10; if (function_exists('fsrep_pro_page_listing_limit')) { $PE = fsrep_pro_page_listing_limit(); }
	
	
	
	
	
	$Limit = ' LIMIT '.$PS.', '.$PE;
	if (isset($_GET['ps'])) {
		if (is_numeric($_GET['ps'])) {
			$PS = $_GET['ps'];
		}
	}
	if (isset($_GET['pe'])) {
		if (is_numeric($_GET['pe'])) {
			$PE = $_GET['pe'];
			$CategoryPE = $_GET['pe'];
		}
	}

	if (function_exists('fsrep_pro_page_listing_limits')) {
		$ListingPEArray = fsrep_pro_page_listing_limits($CategoryPE);
	} else {
		$ListingPEArray = '<option '; if ($CategoryPE == 10) { $ListingPEArray .= 'selected'; } $ListingPEArray .= '>10</option>';
		$ListingPEArray .= '<option '; if ($CategoryPE == 25) { $ListingPEArray .= 'selected'; } $ListingPEArray .= '>25</option>';
		$ListingPEArray .= '<option '; if ($CategoryPE == 50) { $ListingPEArray .= 'selected'; } $ListingPEArray .= '>50</option>';
		$ListingPEArray .= '<option '; if ($CategoryPE == 100) { $ListingPEArray .= 'selected'; } $ListingPEArray .= '>100</option>';
	}
	
	
	
	
	
	if (!isset($_SESSION['Order'])) {
		if (function_exists('fsrep_pro_default_order')) {
			$_SESSION['Order'] = fsrep_pro_default_order();
		} else {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_id DESC';
		}
	}

	if (isset($_GET['order'])) {
		if ($_GET['order'] == 'listing_price_num' || $_GET['order'] == 'listing_price_num DESC') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.'.$_GET['order'];
		} elseif ($_GET['order'] == 'listing_label') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.'.$_GET['order'];
		} else {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_id DESC';
		}
	}
	$ModSQL = '';
	if (function_exists('fsrep_member_moderation_check')) { 
		$ModSQL .= fsrep_member_moderation_check();
	}
	if (function_exists('fsrep_listing_hidden_mod')) { 
		$ModSQL .= fsrep_listing_hidden_mod();
	}
	if (function_exists('fsrep_listing_visibility_mod')) { 
		$ModSQL .= fsrep_listing_visibility_mod();
	}
	if ($type == 'search') {
		if (is_array($value)) { $value = $value['query']; }
		$ListingsSQL = $value;
		$CurrentURL .= 'search/';
	} elseif ($type == 'child') {
		if (is_array($value)) { $value = $value['query']; }
		$ListingsSQL = $value;
		$ListingSorting = FALSE;
		$ListingCompare = FALSE;
	} elseif ($type == 'country') {
		if ($CountryID != 0) { 
			$CountryDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = $CountryID");
			if (isset($CountryDetails->country_overview)) { $Overview = stripslashes($CountryDetails->country_overview); }
			$OverviewTitle = $CountryDetails->country_name;
		} else {
			$CountryID = $value; 
		}
		if ($_SESSION['Order'] == ' DESC') { $_SESSION['Order'] = ' listing_id DESC'; }
		$ListingsSQL = "SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_country = $CountryID".$ModSQL." ORDER BY ".$_SESSION['Order'];
	} elseif ($type == 'province' || $type == 'state') {
		if ($ProvinceID != 0) { 
			$ProvinceDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = $ProvinceID");
			if (isset($ProvinceDetails->province_overview)) { $Overview = stripslashes($ProvinceDetails->province_overview); }
			$OverviewTitle = $ProvinceDetails->province_name;
		} else {
			$ProvinceID = $value; 
		}
		if ($_SESSION['Order'] == ' DESC') { $_SESSION['Order'] = ' listing_id DESC'; }
		$ListingsSQL = "SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_province = $ProvinceID".$ModSQL." ORDER BY ".$_SESSION['Order'];
	} elseif ($type == 'city') {
		if ($CityID != 0) { 
			$CityDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = $CityID");
			if (isset($CityDetails->city_overview)) { $Overview = stripslashes($CityDetails->city_overview); }
			$OverviewTitle = $CityDetails->city_name;
		} else {
			$CityID = $value; 
		}
		if ($_SESSION['Order'] == ' DESC') { $_SESSION['Order'] = ' listing_id DESC'; }
		$ListingsSQL = "SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_address_city = $CityID".$ModSQL." ORDER BY ".$_SESSION['Order'];
	} else {
		$ListingsSQL = "SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE";
		if ($Filter != 0) {
			if ($wpdb->get_var("SELECT filter_map FROM ".$wpdb->prefix."fsrep_filters WHERE filter_id = $Filter") == 0) { $GoogleMap = FALSE; }
			if ($wpdb->get_var("SELECT filter_sorting FROM ".$wpdb->prefix."fsrep_filters WHERE filter_id = $Filter") == 0) { $ListingSorting = FALSE; }
			if (function_exists('fsrep_pro_field_sort')) { fsrep_pro_field_sort($Filter); }
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
			$ListingsSQL .= " listing_id IN ($MatchingListingsID) ".$ModSQL;
		} else {
			//$ModSQL = str_replace(' AND',' WHERE',$ModSQL);
			$ListingsSQL = $ListingsSQL.$ModSQL;
			$ListingsSQL = fsrep_sql_clean($ListingsSQL);
		}
		$SQLExtra = '';
		if ($_SESSION['Order'] == 'listing_id') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_id';
			$SQLExtra = ' DESC';
		} elseif ($_SESSION['Order'] == 'listing_priced') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_price_num';
			$SQLExtra = ' DESC';
		} elseif ($_SESSION['Order'] == 'listing_label') {
			$_SESSION['Order'] = $wpdb->prefix.'fsrep_listings.listing_label';
			$SQLExtra = '';
		}
		$ListingsSQL .= " ORDER BY ".$_SESSION['Order'].$SQLExtra;
				
	}
	$ListingsSQL = fsrep_sql_clean($ListingsSQL);
	$Listings = $wpdb->get_results($ListingsSQL);
	$ListingCount = count($Listings);
	$Listings = array_slice($Listings, $PS, $PE);
	
	if ($GoogleMap == TRUE) {
		$PageContent .= '<div id="listings_map" style="width: 100%; height: 300px; border: 1px solid #999999; margin-bottom: 12px;"></div>';
	} else {
		$PageContent .= '';
	}
		
	$TotalPages = 1;		
	
	if ($FSREPconfig['DisablePageSorting'] == 0 && $ListingSorting == TRUE) {
		$PageContent .= '<div id="fs-category-options">';
		$PageContent .= '<form action="./" method="GET">';
		if (isset($SearchQueryID)) { $PageContent .= '<input type="hidden" name="searchid" value="'.$SearchQueryID.'">'; }
		$PageContent .= '<div style="text-align: right; float: right; width: 34%;">Sort by: <select name="order" onchange="this.form.submit();" class="sortby" style="width: 130px;">';
		$PageContent .= '<option value="listing_id" '; if ($_SESSION['Order'] == "listing_id" || $_SESSION['Order'] == "wp_fsrep_listings.listing_id") { $PageContent .= 'selected'; } $PageContent .= '>Recently Added</option>';
		$PageContent .= '<option value="listing_price_num" '; if ($_SESSION['Order'] == "listing_price_num" || $_SESSION['Order'] == "wp_fsrep_listings.listing_price_num") { $PageContent .= 'selected'; } $PageContent .= '>Price (low to high)</option>';
		$PageContent .= '<option value="listing_price_num DESC" '; if ($_SESSION['Order'] == "listing_price_num DESC" || $_SESSION['Order'] == "wp_fsrep_listings.listing_price_num DESC") { $PageContent .= 'selected'; } $PageContent .= '>Price (high to low)</option>';
		$PageContent .= '<option value="listing_label" '; if ($_SESSION['Order'] == "listing_label" || $_SESSION['Order'] == "wp_fsrep_listings.listing_label") { $PageContent .= 'selected'; } $PageContent .= '>Name</option>';
		$PageContent .= '</select></div>';
		$PageContent .= '<div id="fs-page-select">';
		if ($ListingPEArray != FALSE) {
			$PageContent .= 'Page: <select name="ps" onchange="this.form.submit();" class="numerical">';
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
			$PageContent .= '</select>';
		}
		$PageContent .= '</div> ';
		$PageContent .= '<div id="fs-results-per-page">';
		if ($ListingPEArray != FALSE) {
			$PageContent .= '<select name="pe" onchange="this.form.submit();" class="numerical">';
			$PageContent .= $ListingPEArray;
			$PageContent .= '</select> per page';
		}
		$PageContent .= '</div>';
		$PageContent .= '&nbsp;</form></div>';
	}

	if (count($Listings) > 0) {
		if ($FSREPconfig['EnableCompare'] == 'Yes' && $ListingCompare == TRUE) { 
			$PageContent .= '<form id="fsrep-compare-form" name="fsrep-compare-form" action="'.get_option('home').'/'.$ListingHomeURL.'/compare/" METHOD="POST">';
			$PageContent .= '<div id="fsrep-compare-submit"><input type="submit" name="submit" value="Compare"></div>';
		}
		if (!isset($FSREPconfig['Theme']) || $FSREPconfig['Theme'] == '') { $FSREPconfig['Theme'] = 'default'; }
		foreach ($Listings as $Listings) {
			if (is_numeric($Listings->listing_price)) { $Listings->listing_price = number_format($Listings->listing_price, 2, '.', ','); }
			include(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/themes/'.$FSREPconfig['Theme'].'/listings_'.$FSREPconfig['ListingsOrientation'].'.php');
		}
		if ($FSREPconfig['EnableCompare'] == 'Yes' && $ListingCompare == TRUE) { $PageContent .= '</form>'; }
		$PageContent .= '<div style="clear: both;"></div>';
		if (!$_SESSION['Order']) {
			$_SESSION['Order'] = 'listing_id';
		}
		/*
		if (isset($_GET['order'])) {
			if ($_GET['order'] == 'listing_price_num' || $_GET['order'] == 'listing_price_num DESC') {
				$_SESSION['Order'] = $_GET['order'];
			} else {
				$_SESSION['Order'] = ' DESC';
			}
		}
		*/
		if ($type != 'child') {
			$PageContent .= '<div id="fsrep-page-numbers"><a href="'.$CurrentURL.'/'.str_replace('&','?',$SearchPagination).'">1</a>';
			for ($i=1;$i<$TotalPages;$i++) {
				$PageNumber = $i + 1;
				$PS = $i * $PE;
				$PageContent .= ' | <a href="'.$CurrentURL.'?ps='.$PS.'&pe='.$PE.$SearchPagination.'">'.$PageNumber.'</a>';
			}
			$PageContent .= '</div>';
		}
	} else {
		$PageContent .= fsrep_text_translator('FireStorm Real Estate Plugin', 'No listings found. Label', 'No listings found.').' <a href="'.get_bloginfo('url').'/'.$ListingHomeURL.'/">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Go back to view all listings. Label', 'Go back to view all listings.').'</a>';
	}
	
	if (function_exists('fsrep_pro_location_overview')) { $PageContent .= fsrep_pro_location_overview($Overview); }
	if (function_exists('fsrep_pro_sublocations')) { $PageContent .= fsrep_pro_sublocations(); }
	
	return $PageContent;
}

function fsrep_custom_filter_replace ($content,$ViewListings) {
	global $FSREPconfig;
	$FilterID = explode('[fsrep-filter-', $content);
	$FilterID = explode(']', $FilterID[1]);
	if ($ViewListings == 'yes') {
		$content = str_replace('[fsrep-filter-'.$FilterID[0].']',fsrep_listings_display('', '', '', '', '', '', $FSREPconfig['GoogleMap'], $FilterID[0]),$content);
	} else {
		$content = str_replace('[fsrep-filter-'.$FilterID[0].']','Please login to view listings.',$content);
	}
	return $content;
}

// SEARCH BOX
function fsrep_search_form($FSREPSearchForm) {
	global $_POST,$wpdb,$FSREPconfig,$ListingHomeURL;
	
	$PageContent = '<form id="'.$FSREPSearchForm->ID.'" name="'.$FSREPSearchForm->Name.'" action="'.$FSREPSearchForm->Action.'" method="'.$FSREPSearchForm->Method.'">';

	if (!isset($_POST[$FSREPSearchForm->Abrv.'-widget-search-submit'])) {
		if (preg_match('#/search/#i', $_SERVER['REQUEST_URI'])) {
			$_POST = $_SESSION['fsrep-search'];
		}
	} else {
		$_SESSION['fsrep-search'] = $_POST;
	}
	
	if (isset($FSREPconfig['DefaultCountry']) && $FSREPconfig['DefaultCountry'] != '') {
		$PageContent .= '<input type="hidden" name="'.$FSREPSearchForm->Abrv.'-search-country" value="'.$FSREPconfig['DefaultCountry'].'">';
	} else {
		$PageContent .= '<div id="'.$FSREPSearchForm->Abrv.'s-input"><div id="'.$FSREPSearchForm->Abrv.'s-input-title">'.fsrep_text_translator('FireStorm Real Estate Plugin', $FSREPconfig['CountryLabel'].' Label', $FSREPconfig['CountryLabel']).':</div>';
		$PageContent .= '<select id="'.$FSREPSearchForm->Abrv.'-search-country" name="'.$FSREPSearchForm->Abrv.'-search-country"  onchange="getFSREPlist(this, \''.$FSREPSearchForm->Abrv.'-search-province\', \'CountryID\', \'\')">';
		$PageContent .= '<option value="">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Select '.$FSREPconfig['CountryLabel'].' Label', 'Select '.$FSREPconfig['CountryLabel']).'</option>';
		$FSREPCountries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
		foreach ($FSREPCountries as $FSREPCountries) {
			$selected = '';
			if (isset($_POST[$FSREPSearchForm->Abrv.'-search-country']) && $_POST[$FSREPSearchForm->Abrv.'-search-country'] == $FSREPCountries->country_id) {
				$selected = ' selected';
			}
			$PageContent .= '<option value="'.$FSREPCountries->country_id.'"'.$selected.'>'.$FSREPCountries->country_name.'</option>';
		}
		$PageContent .= '</select></div>';
	}
	if (isset($FSREPconfig['DefaultProvince']) && $FSREPconfig['DefaultProvince'] != '') {
		$PageContent .= '<input type="hidden" name="'.$FSREPSearchForm->Abrv.'-search-province" value="'.$FSREPconfig['DefaultProvince'].'">';
	} else {
		$PageContent .= '<div id="'.$FSREPSearchForm->Abrv.'s-input"><div id="'.$FSREPSearchForm->Abrv.'s-input-title">'.fsrep_text_translator('FireStorm Real Estate Plugin', $FSREPconfig['ProvinceLabel'].' Label', $FSREPconfig['ProvinceLabel']).':</div>';
		$PageContent .= '<select id="'.$FSREPSearchForm->Abrv.'-search-province" name="'.$FSREPSearchForm->Abrv.'-search-province"  onchange="getFSREPlist(this, \''.$FSREPSearchForm->Abrv.'-search-city\', \'ProvinceID\', \'\')">';
		$PageContent .= '<option value="">- - - - - -</option>';
		$PageContent .= '</select></div>';
	}
	if (function_exists('fsrep_pro_regions_search_form')) { 
		$PageContent .= fsrep_pro_regions_search_form($FSREPSearchForm); 
	} else {
		if (isset($FSREPconfig['DefaultCity']) && $FSREPconfig['DefaultCity'] != '') {
			$PageContent .= '<input type="hidden" name="'.$FSREPSearchForm->Abrv.'-search-city" value="'.$FSREPconfig['DefaultCity'].'">';
		} else {
			$PageContent .= '<div id="'.$FSREPSearchForm->Abrv.'s-input"><div id="'.$FSREPSearchForm->Abrv.'s-input-title">'.fsrep_text_translator('FireStorm Real Estate Plugin', $FSREPconfig['CityLabel'].' Label', $FSREPconfig['CityLabel']).':</div>';
			$PageContent .= '<select id="'.$FSREPSearchForm->Abrv.'-search-city" name="'.$FSREPSearchForm->Abrv.'-search-city">';
			$PageContent .= '<option value="">- - - - - -</option>';
			$PageContent .= '</select></div>';
		}
	}
	
	$FSREPPriceRange1 = ''; if (isset($_POST[$FSREPSearchForm->Abrv.'-search-price-range'])) { $FSREPPriceRange1 = $_POST[$FSREPSearchForm->Abrv.'-search-price-range'];}
	$FSREPPriceRange2 = ''; if (isset($_POST[$FSREPSearchForm->Abrv.'-search-price-range2'])) { $FSREPPriceRange2 = $_POST[$FSREPSearchForm->Abrv.'-search-price-range2'];} else { $FSREPPriceRange2 = '999999999999'; }
	$PageContent .= '<div id="'.$FSREPSearchForm->Abrv.'s-input"><div id="'.$FSREPSearchForm->Abrv.'s-input-title">'.fsrep_text_translator('FireStorm Real Estate Plugin', $FSREPconfig['PriceRangeLabel'].' Label', $FSREPconfig['PriceRangeLabel']).':</div>';
	$PageContent .= '<select name="'.$FSREPSearchForm->Abrv.'-search-price-range">';
	if (function_exists('fsrep_pro_price_range')) { 
		$PageContent .= fsrep_pro_price_range('options',$FSREPPriceRange1);
	} else {
		$PageContent .= fsrep_price_range_print('options',$FSREPPriceRange1);
	}
	$PageContent .= '</select></div>';
	$PageContent .= '<div id="'.$FSREPSearchForm->Abrv.'s-input"><div id="'.$FSREPSearchForm->Abrv.'s-input-title">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'to Label', 'to').'</div>';
	$PageContent .= '<select name="'.$FSREPSearchForm->Abrv.'-search-price-range2">';
	if (function_exists('fsrep_pro_price_range')) { 
		$PageContent .= fsrep_pro_price_range('options',$FSREPPriceRange2);
	} else {
		$PageContent .= fsrep_price_range_print('options',$FSREPPriceRange2);
	}
	$PageContent .= '</select></div>';
	if (function_exists('fsrep_zip_search')) { $PageContent .= fsrep_zip_search($FSREPSearchForm); }
	if (function_exists('fsrep_custom_search')) { $PageContent .= fsrep_custom_search($FSREPSearchForm); }
	$SFields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields WHERE field_search = 1 ORDER BY field_order");
	foreach($SFields as $SFields) {
		$PageContent .= '<div id="'.$FSREPSearchForm->Abrv.'s-input"><div id="'.$FSREPSearchForm->Abrv.'s-input-title">'.fsrep_text_translator('FireStorm Real Estate Plugin', $SFields->field_name.' Label', $SFields->field_name).'</div>';
		if ($SFields->field_type == 'selectbox') {
			$PageContent .= '<select name="field-'.$SFields->field_id.'">';
			$PageContent .= '<option value="">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'All Label', 'All').'</option>';
			$Array = explode(',',$SFields->field_value);
			for($i=0;$i<count($Array);$i++) {
				$selected = '';
				if (isset($_POST['field-'.$SFields->field_id]) && $_POST['field-'.$SFields->field_id] == $Array[$i]) {
					$selected = ' selected';
				}
				$PageContent .= '<option value="'.$Array[$i].'"'.$selected.'>'.$Array[$i].'</option>';
			}
			$PageContent .= '</select></div>';
		} elseif ($SFields->field_type == 'radio') {
			$FSREPSCheckboxes = '';
			$FSREPSCheckboxAll = ' checked';
			$Array = explode(',',$SFields->field_value);
			for($i=0;$i<count($Array);$i++) {
				$selected = '';
				if (isset($_POST['field-'.$SFields->field_id]) && $_POST['field-'.$SFields->field_id] == $Array[$i]) {
					$selected = ' checked';
					$FSREPSCheckboxAll = '';
				}
				$FSREPSCheckboxes .= '<input type="radio" style="width: 25px;" name="field-'.$SFields->field_id.'" value="'.$Array[$i].'" '.$selected.'> '.$Array[$i].' &nbsp; &nbsp;<br />';
			}
			$PageContent .= '<br /><input type="radio" style="width: 25px;" name="field-'.$SFields->field_id.'" value="" '.$FSREPSCheckboxAll.'> '.fsrep_text_translator('FireStorm Real Estate Plugin', 'All Label', 'All').' &nbsp; &nbsp;<br />';
			$PageContent .= $FSREPSCheckboxes;
			$PageContent .= '</div>';
		} elseif ($SFields->field_type == 'checkbox') {
			$FSREPSCheckboxesUncheck = '';
			$FSREPSCheckboxes = '';
			$FSREPSCheckboxAll = ' checked';
			$Array = explode(',',$SFields->field_value);
			for($i=0;$i<count($Array);$i++) {
				$selected = '';
				if (isset($_POST['field-'.$SFields->field_id.'-'.$i]) && $_POST['field-'.$SFields->field_id.'-'.$i] == $Array[$i]) {
					$selected = ' checked';
					$FSREPSCheckboxAll = '';
				}
				$FSREPSCheckboxes .= '<input type="checkbox" style="width: 25px;" name="field-'.$SFields->field_id.'-'.$i.'" value="'.$Array[$i].'" '.$selected.' onclick="this.form.elements[\'field-'.$SFields->field_id.'\'].checked = false;"> '.$Array[$i].' &nbsp; &nbsp;<br />';
				$FSREPSCheckboxesUncheck .= 'this.form.elements[\'field-'.$SFields->field_id.'-'.$i.'\'].checked = false; ';
			}
			$PageContent .= '<br /><input type="checkbox" style="width: 25px;" name="field-'.$SFields->field_id.'" value="" '.$FSREPSCheckboxAll.' onclick="'.$FSREPSCheckboxesUncheck.'"> '.fsrep_text_translator('FireStorm Real Estate Plugin', 'All Label', 'All').' &nbsp; &nbsp;<br />';
			$PageContent .= $FSREPSCheckboxes;
			$PageContent .= '</div>';
		} else {
			$PageContent .= '<input type="text" name="field-'.$SFields->field_id.'" id="field-'.$SFields->field_id.'" value="'; if (isset($_POST['field-'.$SFields->field_id])) { $PageContent .= $_POST['field-'.$SFields->field_id]; } $PageContent .= '">';
			$PageContent .= '</div>';
		}
	}
	$PageContent .= '<div id="'.$FSREPSearchForm->Abrv.'s-submit"><input type="submit" name="'.$FSREPSearchForm->Abrv.'-widget-search-submit" id="'.$FSREPSearchForm->Abrv.'-widget-search-submit" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Search Listings Label', 'Search Listings').'"></div>';
	$PageContent .= '</form>';
	
	if (isset($FSREPconfig['EnableAdvancedSearch']) && $FSREPconfig['EnableAdvancedSearch'] == 'Yes') {
		$PageContent .= '<div align="center"><a href="'.get_option('home').'/'.$ListingHomeURL.'/search/">Advanced Search</a></div>';
	}
	
	$FSREPWCountry = TRUE;
	$FSREPWProvince = TRUE;
	$FSREPWCity = TRUE;
	if (isset($FSREPconfig['DefaultCountry']) && $FSREPconfig['DefaultCountry'] != '') {
		$_POST[$FSREPSearchForm->Abrv.'-search-country'] = $FSREPconfig['DefaultCountry'];
		$FSREPWCountry = FALSE;
	}
	if (isset($FSREPconfig['DefaultProvince']) && $FSREPconfig['DefaultProvince'] != '') {
		$_POST[$FSREPSearchForm->Abrv.'-search-province'] = $FSREPconfig['DefaultProvince'];
		$FSREPWProvince = FALSE;
	}
	if (isset($FSREPconfig['DefaultCity']) && $FSREPconfig['DefaultCity'] != '') {
		$_POST[$FSREPSearchForm->Abrv.'-search-city'] = $FSREPconfig['DefaultCity'];
		$FSREPWCity = FALSE;
	}
	if (!isset($_POST[$FSREPSearchForm->Abrv.'-search-province'])) { $_POST[$FSREPSearchForm->Abrv.'-search-province'] = ''; }
	if (!isset($_POST[$FSREPSearchForm->Abrv.'-search-city'])) { $_POST[$FSREPSearchForm->Abrv.'-search-city'] = ''; }
	if (!isset($_POST[$FSREPSearchForm->Abrv.'-search-region'])) { $_POST[$FSREPSearchForm->Abrv.'-search-region'] = ''; }
	if (isset($_POST[$FSREPSearchForm->Abrv.'-search-country']) || isset($_POST[$FSREPSearchForm->Abrv.'-search-province'])) {
		$PageContent .= '<script type="text/javascript">';
		if ($FSREPWProvince == TRUE) { $PageContent .= "getFSREPlist('".$_POST[$FSREPSearchForm->Abrv.'-search-country']."', '".$FSREPSearchForm->Abrv."-search-province', 'CountryID', '".$_POST[$FSREPSearchForm->Abrv.'-search-province']."');"; }
		if ($FSREPWCity == TRUE) { $PageContent .= "getFSREPlist('".$_POST[$FSREPSearchForm->Abrv.'-search-province']."', '".$FSREPSearchForm->Abrv."-search-city', 'ProvinceID', '".$_POST[$FSREPSearchForm->Abrv.'-search-city']."');"; }
		if (function_exists('fsrep_pro_regions_search_form')) { $PageContent .= "getFSREPlist('".$_POST[$FSREPSearchForm->Abrv.'-search-city']."', '".$FSREPSearchForm->Abrv."-search-region', 'CityID', '".$_POST[$FSREPSearchForm->Abrv.'-search-region']."');"; }
		$PageContent .= '</script>';
	}
	
	return $PageContent;
}

function fsrep_membership_settings_disabled() {
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'License Label', 'License').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
		fsrep_print_admin_input_disabled('Membership License', '');
	echo '</tbody></table>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'General Settings Label', 'General Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
		fsrep_print_admin_selectbox_disabled('Enable My Profile', 'Requires Membership Extension');
		fsrep_print_admin_selectbox_disabled('Allow Members to Add Listings', 'Requires Membership Extension');
		fsrep_print_admin_selectbox_disabled('Members See All Listings', 'Requires Membership Extension');
		fsrep_print_admin_selectbox_disabled('Moderate User Listings', 'Requires Membership Extension');
		fsrep_print_admin_selectbox_disabled('Enable Featured Users', 'Requires Membership Extension');
		fsrep_print_admin_selectbox_disabled('Require Contact Info', 'Requires Membership Extension');
		fsrep_print_admin_input_disabled('Member Listing Limit', 'Requires Membership Extension');
		fsrep_print_admin_input_disabled('Member Photo Limit', 'Requires Membership Extension');
	echo '</tbody></table>';
	
}
function fsrep_pro_gmap_settings_disabled() {
	global $FSREPconfig,$wpdb;
		
	$ProSettingsTitle = fsrep_text_translator('FireStorm Real Estate Plugin', 'Google Map Settings Label', 'Google Map Settings');
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Map Advanced Settings Label', 'Map Advanced Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_input_disabled('backgroundColor', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('draggableCursor', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('draggingCursor', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('styles', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('heading', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('maxZoom', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('minZoom', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('tilt', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('zoom', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('disableDefaultUI', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('disableDoubleClickZoom', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('draggable', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('keyboardShortcuts', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('mapMaker', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('mapTypeControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('mapTypeControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('noClear', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('overviewMapControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('overviewMapControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('panControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('panControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('rotateControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('rotateControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('scaleControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('scaleControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('scrollwheel', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('streetView', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('streetViewControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('streetViewControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('zoomControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('zoomControlOptions', 'Requires PRO Version');
	echo '</tbody></table>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Google Map Settings Label', 'Google Map Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</tfoot>
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Listings Map Settings Label', 'Listings Map Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_input_disabled('backgroundColor', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('draggableCursor', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('draggingCursor', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('styles', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('heading', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('maxZoom', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('minZoom', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('tilt', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('zoom', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('disableDefaultUI', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('disableDoubleClickZoom', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('draggable', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('keyboardShortcuts', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('mapMaker', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('mapTypeControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('mapTypeControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('noClear', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('overviewMapControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('overviewMapControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('panControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('panControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('rotateControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('rotateControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('scaleControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('scaleControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('scrollwheel', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('streetView',  'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('streetViewControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('streetViewControlOptions', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('zoomControl', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('zoomControlOptions', 'Requires PRO Version');
	echo '</tbody></table>';
}
function fsrep_pro_settings_disabled() {
	global $FSREPconfig,$wpdb;
		
	$ProSettingsTitle = fsrep_text_translator('FireStorm Real Estate Plugin', 'PRO Settings Label', 'PRO Settings');
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'License Label', 'License').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
		fsrep_print_admin_input_disabled('PRO License', '');
	echo '</tbody></table>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'PRO Settings Label', 'PRO Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_admin_update_button().'</th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_selectbox_disabled('Custom Theme', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Allow Featured Listings', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Remove Zero Cents', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Display Sub-Locations Below Listings', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Copy Admin On Listing Messages', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Enable Advanced Search', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Allow XML Feed', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Default Listing Order', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Login Required to View Listings', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Listing Map Type', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Google Map Type', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Listing Per Page Limit', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Enable Search By Zip', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('Search By Zip Units', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Search By Zip Default Radius', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Search By Zip Buffer', 'Requires PRO Version');
	fsrep_print_admin_selectbox_disabled('EnableRegions', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Country Label', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('State/Prov. Label', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('City Label', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Region Label', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Price Range Label', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Custom Search Price Range', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Open Listings in a New Page', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Thumbnail Image Dimensions', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Enlarged Image Dimensions', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Featured Image Dimensions', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Standard Image Dimensions', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('Image Compression', 'Requires PRO Version');
	echo '</tbody></table>';

	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'reCaptcha Label', 'reCaptcha').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_selectbox_disabled('Enable reCaptcha', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('reCaptcha Public Key', 'Requires PRO Version');
	fsrep_print_admin_input_disabled('reCaptcha Private Key', 'Requires PRO Version');
	echo '</tbody></table>';
}
function fsrep_pro_childprop_disabled() {
	
}
?>