<?php

if(!function_exists('fsrep_house_listings')) {
function fsrep_house_listings() {
	global $wpdb,$FSREPconfig,$RListingLimit,$FSREPAPI;
	
	ini_set("memory_limit","80M");
	
	echo '<div class="wrap">';
	// EDIT LISTING
	if (isset($_GET['f'])) {
		$TotalListingCount = $wpdb->get_var("SELECT COUNT(listing_id) FROM ".$wpdb->prefix."fsrep_listings");
		if ($_GET['f'] == 'add' && $FSREPAPI == 'RESTRICTED' && $TotalListingCount > $RListingLimit) {
			echo 'You have already reached your limit of listings. ';
			if ($FSREPAPI == 'RESTRICTED') {
				echo ' Your API only supports 10 listings.';
			}
		} elseif ($_GET['f'] == 'add') {
			echo '<h2>Add Listing</h2>';
			if (isset($_POST['submit'])) {
				$SQLField = '';
				$SQLValue = '';
				
				// MAKE SURE IMAGE IS JPG
				if ($_FILES['image']['name'] != '') {
					if (!preg_match('/jpg/i', $_FILES['image']['name'])) {
						$RegisterFormError = 'House Picture Must Be JPG Format';
					}
				}
				
				if ($RegisterFormError == '') {
					if ($_POST['listing_id'] == '') {
						
						// GET COUNTRY NAME
						if ($_POST['listing_address_country'] != '') {
							$CountryName = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$_POST['listing_address_country']);
						} else {
							$CountryName = '';
						}
						// GET PROVINCE NAME
						if ($_POST['listing_address_province'] != '') {
							$ProvinceName = $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$_POST['listing_address_province']);
						} else {
							$ProvinceName = '';
						}
						// GET CITY NAME OR ADD
						if ($_POST['listing_address_city'] != '' && $_POST['listing_address_city2'] == '') {
							$CityName = $wpdb->get_var("SELECT city_name FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = ".$_POST['listing_address_city']);
							
						} else {
							if ($wpdb->get_var("SELECT count(city_id) FROM ".$wpdb->prefix."fsrep_cities WHERE city_name LIKE '".$_POST['listing_address_city2']."'") == 0) {
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
							} else {
								$CityName = $wpdb->get_var("SELECT city_name FROM ".$wpdb->prefix."fsrep_cities WHERE city_name LIKE '".$_POST['listing_address_city2']."'");
							}
						}
						
						
						
						
						// ADD LISTING
						$AdditionSQL = "INSERT INTO ".$wpdb->prefix."fsrep_listings (
																																					listing_sold, 
																																					listing_last_updated, 
																																					listing_date_added, 
																																					listing_label, 
																																					listing_price, 
																																					listing_address_number, 
																																					listing_address_street, 
																																					listing_address_city, 
																																					listing_address_province, 
																																					listing_address_country, 
																																					listing_address_postal, 
																																					listing_description, 
																																					listing_contact_display, 
																																					listing_contact_name, 
																																					listing_contact_email, 
																																					listing_contact_home_phone, 
																																					listing_contact_cell_phone, 
																																					listing_contact_special_instructions, 
																																					listing_contact_form_email
																																				) VALUES (
																																					'".$_POST['listing_sold']."', 
																																					NOW(), 
																																					NOW(), 
																																					'".$_POST['listing_label']."', 
																																					'".str_replace(',','',$_POST['listing_price'])."', 
																																					'".$_POST['listing_address_number']."', 
																																					'".$_POST['listing_address_street']."', 
																																					'".$_POST['listing_address_city']."', 
																																					'".$_POST['listing_address_province']."', 
																																					'".$_POST['listing_address_country']."', 
																																					'".$_POST['listing_address_postal']."', 
																																					'".$_POST['listing_description']."', 
																																					'".$_POST['listing_contact_display']."', 
																																					'".$_POST['listing_contact_name']."', 
																																					'".$_POST['listing_contact_email']."', 
																																					'".$_POST['listing_contact_home_phone']."', 
																																					'".$_POST['listing_contact_cell_phone']."', 
																																					'".$_POST['listing_contact_special_instructions']."', 
																																					'".$_POST['listing_contact_form_email']."'
																																				)";
						$wpdb->query($AdditionSQL);
						
						$ListingID = $wpdb->get_var("SELECT listing_id FROM ".$wpdb->prefix."fsrep_listings ORDER BY listing_id DESC LIMIT 1");
						$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_users (listing_id, ID) VALUES ($ListingID, $current_user->ID)");
						
						// ADD CUSTOM FIELDS
						$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
						foreach ($Fields as $Fields) {
							if ($_POST['field'.$Fields->field_id] != '' && $_POST['field'.$Fields->field_id] != 'Not Applicable') {
								$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_fields (listing_id, field_id, listing_value) VALUES ($ListingID,$Fields->field_id,'".$_POST['field'.$Fields->field_id]."')");
							}
						}
						
						
						// UPDATE GOOGLE COORDS
						$Coords = google_geocoder($_POST['listing_address_number'].' '.$_POST['listing_address_street'].' '.$CityName.' '.$ProvinceName.' '.$_POST['listing_address_postal'].' ', $FSREPconfig['Google Map API']);
						$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_long = '".$Coords[0]."', listing_lat = '".$Coords[1]."' WHERE listing_id = ".$ListingID."; ");
		
						// UPDATE IMAGE IF NEEDED
						if ($_FILES['image']['name'] != "") {
							// UNLINK CURRENT IMAGES
							if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$ListingID.'.jpg')) {
								unlink(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$ListingID.'.jpg');
							}
							if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/large/'.$ListingID.'.jpg')) {
								unlink(ABSPATH.'wp-content/uploads/fsrep/houses/large/'.$ListingID.'.jpg');
							}
							if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/'.$ListingID.'.jpg')) {
								unlink(ABSPATH.'wp-content/uploads/fsrep/houses/'.$ListingID.'.jpg');
							}
							
							// UPDATE IMAGE
							$uploaddir = ABSPATH.'wp-content/uploads/fsrep/houses/temp/';
							$uploadfile = $uploaddir . basename($_FILES['image']['name']);
							if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
								// Upload Image as Enlarged Version
								rename($uploadfile, $uploaddir.basename($_FILES['image']['name']));
								
								// CONVERT ENLARGED IMAGE TO THUMBNAIL AND STANDARD SIZE
								fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/'.$ListingID.'.jpg', 200, 200);
								fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$ListingID.'.jpg', 100, 100);
								fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/large/'.$ListingID.'.jpg', 800, 600);
								
								unlink($uploaddir.basename($_FILES['image']['name']));
								
							}
						}
						
						// ADDITIONAL IMAGES
						for ($i=1;$i<=10;$i++) {
							if ($_FILES['aimage'.$i]['name'] != "") {
								// UNLINK CURRENT IMAGES
								if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$ListingID.'-'.$i.'.jpg')) {
									unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$ListingID.'-'.$i.'.jpg');
								}
								if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$ListingID.'-'.$i.'.jpg')) {
									unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$ListingID.'-'.$i.'.jpg');
								}
								if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$ListingID.'-'.$i.'.jpg')) {
									unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$ListingID.'-'.$i.'.jpg');
								}
								
								// UPDATE IMAGE
								$uploaddir = ABSPATH.'wp-content/uploads/fsrep/houses/additional/temp/';
								$uploadfile = $uploaddir . basename($_FILES['aimage'.$i]['name']);
								if (move_uploaded_file($_FILES['aimage'.$i]['tmp_name'], $uploadfile)) {
									// Upload Image as Enlarged Version
									rename($uploadfile, $uploaddir.basename($_FILES['aimage'.$i]['name']));
									
									// CONVERT ENLARGED IMAGE TO THUMBNAIL AND STANDARD SIZE
									fsrep_imageresizer($uploaddir.basename($_FILES['aimage'.$i]['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$ListingID.'-'.$i.'.jpg', 200, 200);
									fsrep_imageresizer($uploaddir.basename($_FILES['aimage'.$i]['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$ListingID.'-'.$i.'.jpg', 90, 90);
									fsrep_imageresizer($uploaddir.basename($_FILES['aimage'.$i]['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$ListingID.'-'.$i.'.jpg', 800, 600);
									
									unlink($uploaddir.basename($_FILES['aimage'.$i]['name']));
									
								}
								
							}
						}
						
						
						echo '<div id="message" class="updated fade"><p><strong>Your listing has been added.</strong></p></div>';
						echo $GoBack;
						$HideForm = TRUE;
					}
				}
			}
			if (!isset($HideForm)) {
			echo '<form name="fsrep-add-listing" id="fsrep-add-listing" action="" method="post" enctype="multipart/form-data">';
			fsrep_print_hidden_input('MAX_FILE_SIZE', '10000000');
			fsrep_print_hidden_input('listing_id', $_POST['listing_id']);
	
			if ($RegisterFormError != '') {
				echo '<div class="error">'.$RegisterFormError.'</div>';
			}
			echo '<p><br /></p>';
			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Featured Image</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
			fsrep_print_admin_file_input('Picture', 'image', $_POST['image'], 20, 'Maximum Filesize of 5MB.');
			echo '</tbody></table><p>&nbsp;</p>';


			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Additional Images</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
				for ($i=1;$i<=10;$i++) {
					fsrep_print_admin_file_input('Picture <span style="font-size: 11px; font-style: italic; font-weight: normal;">(max filesize 5mb)</span>', 'aimage'.$i, $_POST['aimage'.$i], 20, '');
				}
			echo '</tbody></table><p>&nbsp;</p>';


			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Listing Information</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
			fsrep_print_admin_selectbox('Is it Sold?', 'listing_sold', $_POST['listing_sold'], array('No' => 0, 'Yes' => 1), '', '');
			fsrep_print_admin_input('Title', 'listing_label', $_POST['listing_label'], 35, 'Leave blank to display city name and state / province as title.');
			fsrep_print_admin_input('Selling Price', 'listing_price', $_POST['listing_price'], 10, 'Leave blank to hide price.');
			fsrep_print_admin_input('House Number', 'listing_address_number', $_POST['listing_address_number'], 5, '');
			fsrep_print_admin_input('Street Name', 'listing_address_street', $_POST['listing_address_street'], 35, '');
			fsrep_print_admin_selectbox('Country', 'listing_address_country', $_POST['listing_address_country'], fsrep_get_countries('array'), 'getFSREPlist(this, \'listing_address_province\', \'CountryID\', \''.$_POST['listing_address_country'].'\')', '');
			fsrep_print_admin_selectbox('State/Province', 'listing_address_province', $_POST['listing_address_province'], '', 'getFSREPlist(this, \'listing_address_city\', \'ProvinceID\', \''.$_POST['listing_address_province'].'\')', '');
			fsrep_print_admin_selectbox('City', 'listing_address_city', $_POST['listing_address_city'], '', '', '');
			fsrep_print_admin_input('Other City <span style="font-size: 10px;">(if not found in select box)</span>', 'listing_address_city2', $_POST['listing_address_city2'], 11, '');
			fsrep_print_admin_input('Zip / Postal Code', 'listing_address_postal', $_POST['listing_address_postal'], 6, '');
			fsrep_print_admin_textarea('Description', 'listing_description', $_POST['listing_description'], 8, 50, '');
			echo '</tbody></table><p>&nbsp;</p>';

			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Contact Information</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
			fsrep_print_admin_selectbox('Display Contact Information', 'listing_contact_display', $_POST['listing_contact_display'], array('Do Not Display' => 'Do Not Display', 'Display Contact Information' => 'Display Contact Information', 'Display Contact Form' => 'Display Contact Form',), '', '');
			fsrep_print_admin_input('Contact Name', 'listing_contact_name', $_POST['listing_contact_name'], 35, '');
			fsrep_print_admin_input('Contact Email', 'listing_contact_email', $_POST['listing_contact_email'], 35, '');
			fsrep_print_admin_input('Contact Phone', 'listing_contact_home_phone', $_POST['listing_contact_home_phone'], 35, '');
			fsrep_print_admin_input('Contact Cell Phone', 'listing_contact_cell_phone', $_POST['listing_contact_cell_phone'], 35, '');
			fsrep_print_admin_input('Special Instructions', 'listing_contact_special_instructions', $_POST['listing_contact_special_instructions'], 35, '');
			fsrep_print_admin_input('Contact Form Email Recipient', 'listing_contact_form_email', $_POST['listing_contact_form_email'], 35, '');
			echo '</tbody></table><p>&nbsp;</p>';

			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Additional Information</th>
				<th scope="col" class="manage-column" colspan="2" style="font-weight: normal;">Select "Not Applicable" or leave blank to hide.</th>
				</tr>
				</thead>
				<tbody>';
				$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
				foreach ($Fields as $Fields) {
					//$HouseFieldInfo = $wpdb->get_var("SELECT * FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = $Fields->field_id AND listing_id = ");
					if ($Fields->field_value == '') {
						fsrep_print_admin_input($Fields->field_name, 'field'.$Fields->field_id, $_POST['field'.$Fields->field_id], 35, '');
					} else {
						$FieldArray = array("Not Applicable" => "Not Applicable");
						$Array = explode(',',$Fields->field_value);
						for($i=0;$i<count($Array);$i++) {
							$AddArray = array($Array[$i] => $Array[$i]);
							$FieldArray = array_merge($FieldArray, $AddArray);
						}
						fsrep_print_admin_selectbox($Fields->field_name, 'field'.$Fields->field_id, $_POST['field'.$Fields->field_id], $FieldArray, '', '');
					}
				}
			echo '</tbody></table><p>&nbsp;</p>';
			echo '<input type="submit" name="submit" id="submitform" value="Add Listing" tabindex="4" />';
			echo '</form>';
			}
			echo '<p><br /></p>';
			if (isset($_POST['listing_address_country'])) {
				echo '<script type="text/javascript">
					getFSREPlist(\''.$_POST['listing_address_country'].'\', \'listing_address_province\', \'CountryID\', \''.$_POST['listing_address_province'].'\');
					getFSREPlist(\''.$_POST['listing_address_province'].'\', \'listing_address_city\', \'ProvinceID\', \''.$_POST['listing_address_city'].'\');
					</script>';
			}
		} elseif ($_GET['f'] == 'edit') {

			if (isset($_POST['submit'])) {
			
				// GET COUNTRY NAME
				if ($_POST['listing_address_country'] != '') {
					$CountryName = $wpdb->get_var("SELECT country_name FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$_POST['listing_address_country']);
				} else {
					$CountryName = '';
				}
				// GET PROVINCE NAME
				if ($_POST['listing_address_province'] != '') {
					$ProvinceName = $wpdb->get_var("SELECT province_name FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$_POST['listing_address_province']);
				} else {
					$ProvinceName = '';
				}
				// GET CITY NAME OR ADD
				if ($_POST['listing_address_city'] != '' && $_POST['listing_address_city2'] == '') {
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
				// UPDATE LISTING
				foreach ($_POST as $key=>$value) {
					$FieldCheck = count($wpdb->get_var("SHOW COLUMNS FROM ".$wpdb->prefix."fsrep_listings LIKE '".$key."'"));
					if ($FieldCheck == 1) {
						$UpdateSQL = "UPDATE ".$wpdb->prefix."fsrep_listings SET ".$key." = '".addslashes($value)."' WHERE listing_id = ".$_POST['listing_id']."; ";
						$wpdb->query($UpdateSQL);
					}
				}
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_sold = '".$_POST['listing_sold']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_label = '".$_POST['listing_label']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_price = '".str_replace(',','',$_POST['listing_price'])."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_address_number = '".$_POST['listing_address_number']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_address_street = '".$_POST['listing_address_street']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_address_city = '".$_POST['listing_address_city']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_address_province = '".$_POST['listing_address_province']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_address_country = '".$_POST['listing_address_country']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_address_postal = '".$_POST['listing_address_postal']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_description = '".$_POST['listing_description']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_display = '".$_POST['listing_contact_display']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_name = '".$_POST['listing_contact_name']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_email = '".$_POST['listing_contact_email']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_home_phone = '".$_POST['listing_contact_home_phone']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_cell_phone = '".$_POST['listing_contact_cell_phone']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_special_instructions = '".$_POST['listing_contact_special_instructions']."' WHERE listing_id = ".$_POST['listing_id']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_form_email = '".$_POST['listing_contact_form_email']."' WHERE listing_id = ".$_POST['listing_id']);
				
				// UPDATE CUSTOM FIELDS
				$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE listing_id = ".$_POST['listing_id']."");
				$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
				foreach ($Fields as $Fields) {
					if ($_POST['field'.$Fields->field_id] != '' && $_POST['field'.$Fields->field_id] != 'Not Applicable') {
						$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_fields (listing_id, field_id, listing_value) VALUES (".$_POST['listing_id'].", $Fields->field_id,'".$_POST['field'.$Fields->field_id]."')");
					}
				}
				
				// UPDATE GOOGLE COORDS
				$Coords = google_geocoder($_POST['listing_address_number'].' '.$_POST['listing_address_street'].' '.$CityName.' '.$ProvinceName.' '.$_POST['listing_address_postal'].' ', $FSREPconfig['Google Map API']);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_long = '".$Coords[0]."', listing_lat = '".$Coords[1]."' WHERE listing_id = ".$_POST['listing_id']."; ");
				
				// UPDATE IMAGE IF NEEDED
				if ($_FILES['image']['name'] != "") {
					// UNLINK CURRENT IMAGES
					if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$_POST['listing_id'].'.jpg')) {
						unlink(ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$_POST['listing_id'].'.jpg');
					}
					if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/large/'.$_POST['listing_id'].'.jpg')) {
						unlink(ABSPATH.'wp-content/uploads/fsrep/houses/large/'.$_POST['listing_id'].'.jpg');
					}
					if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/'.$_POST['listing_id'].'.jpg')) {
						unlink(ABSPATH.'wp-content/uploads/fsrep/houses/'.$_POST['listing_id'].'.jpg');
					}
					
					// UPDATE IMAGE
					$uploaddir = ABSPATH.'wp-content/uploads/fsrep/houses/temp/';
					$uploadfile = $uploaddir . basename($_FILES['image']['name']);
					if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
						// Upload Image as Enlarged Version
						rename($uploadfile, $uploaddir.basename($_FILES['image']['name']));
						
						// CONVERT ENLARGED IMAGE TO THUMBNAIL AND STANDARD SIZE
						fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/'.$_POST['listing_id'].'.jpg', 200, 200);
						fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$_POST['listing_id'].'.jpg', 100, 100);
						fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/large/'.$_POST['listing_id'].'.jpg', 800, 600);
						
						unlink($uploaddir.basename($_FILES['image']['name']));
						
					}
				}
				
				// ADDITIONAL IMAGES
				for ($i=1;$i<=10;$i++) {
					if ($_FILES['aimage'.$i]['name'] != "") {
						// UNLINK CURRENT IMAGES
						if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg')) {
							unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg');
						}
						if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$i.'.jpg')) {
							unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$i.'.jpg');
						}
						if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$i.'.jpg')) {
							unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$i.'.jpg');
						}
						
						// UPDATE IMAGE
						$uploaddir = ABSPATH.'wp-content/uploads/fsrep/houses/additional/temp/';
						$uploadfile = $uploaddir . basename($_FILES['aimage'.$i]['name']);
						if (move_uploaded_file($_FILES['aimage'.$i]['tmp_name'], $uploadfile)) {
							// Upload Image as Enlarged Version
							rename($uploadfile, $uploaddir.basename($_FILES['aimage'.$i]['name']));
							
							// CONVERT ENLARGED IMAGE TO THUMBNAIL AND STANDARD SIZE
							fsrep_imageresizer($uploaddir.basename($_FILES['aimage'.$i]['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$i.'.jpg', 200, 200);
							fsrep_imageresizer($uploaddir.basename($_FILES['aimage'.$i]['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg', 90, 90);
							fsrep_imageresizer($uploaddir.basename($_FILES['aimage'.$i]['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$i.'.jpg', 800, 600);
							
							unlink($uploaddir.basename($_FILES['aimage'.$i]['name']));
							
						}
						
					}
				}
					
				echo '<div id="message" class="updated fade"><p><strong>Your listing has been updated.</strong></p></div>';
				echo $GoBack;
			}
			
			$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$_GET['hid']);
			$_POST = mysql_fetch_array($sql);
			echo '<h2>Edit Listing</h2>';
			echo '<form name="fsrep-add-listing" id="fsrep-add-listing" action="" method="post" enctype="multipart/form-data">';
			fsrep_print_hidden_input('MAX_FILE_SIZE', '10000000');
			fsrep_print_hidden_input('listing_id', $_POST['listing_id']);
	
			if ($RegisterFormError != '') {
				echo '<div id="fsrep-form-error">'.$RegisterFormError.'</div>';
			}
			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Featured Image</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
			fsrep_print_admin_file_input('Picture', 'image', $_POST['image'], 20, 'Maximum Filesize of 5MB.');
			echo '</tbody></table><p>&nbsp;</p>';

			// DELETE IMAGE
			if (isset($_GET['iid']) && isset($_GET['if'])) {
				if ($_GET['if'] == 'idel') {
					unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg');
					unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg');
					unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg');
				}
			}
			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column">Current Additional Images</th>
				</tr>
				</thead>
				<tbody><tr><td>';
			$AImages = 0;
			for ($i=1;$i<=10;$i++) {
				if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg')) {
					echo '<div style="float: left; margin-right: 10px; text-align: center;"><a href="admin.php?page=fsrep_house_listings&hid='.$_POST['listing_id'].'&f=edit&iid='.$i.'&if=idel">delete image</a><br /><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg" style="border: 1px solid #999999;"><br /><br /></div>';
					$AImages++;
				}
			}
			if ($AImages == 0) {
				echo 'No additional images found.';
			}
			echo '</td></tr></tbody></table><p>&nbsp;</p>';

			
			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Additional Images</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
				if ($AImages != 10) {
					for ($i=1;$i<=10;$i++) {
						if (!file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg')) {
							fsrep_print_admin_file_input('Picture <span style="font-size: 11px; font-style: italic; font-weight: normal;">(max filesize 5mb)</span>', 'aimage'.$i, $_POST['aimage'.$i], 20, '');
						}
					}
				} else {
					echo '<tr><td colspan="3">Image limit reached.</td></tr>';
				}
			echo '</tbody></table><p>&nbsp;</p>';

			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Listing Information</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
			fsrep_print_admin_selectbox('Is it Sold?', 'listing_sold', $_POST['listing_sold'], array('No' => 0, 'Yes' => 1), '', '');
			fsrep_print_admin_input('Title', 'listing_label', $_POST['listing_label'], 35, 'Leave blank to display city name and state / province as title.');
			fsrep_print_admin_input('Selling Price', 'listing_price', $_POST['listing_price'], 10, 'Leave blank to hide price.');
			fsrep_print_admin_input('House Number', 'listing_address_number', $_POST['listing_address_number'], 5, '');
			fsrep_print_admin_input('Street Name', 'listing_address_street', $_POST['listing_address_street'], 35, '');
			fsrep_print_admin_selectbox('Country', 'listing_address_country', $_POST['listing_address_country'], fsrep_get_countries('array'), 'getFSREPlist(this, \'listing_address_province\', \'CountryID\', \''.$_POST['listing_address_country'].'\')', '');
			fsrep_print_admin_selectbox('State/Province', 'listing_address_province', $_POST['listing_address_province'], '', 'getFSREPlist(this, \'listing_address_city\', \'ProvinceID\', \''.$_POST['listing_address_province'].'\')', '');
			fsrep_print_admin_selectbox('City', 'listing_address_city', $_POST['listing_address_city'], '', '', '');
			fsrep_print_admin_input('Other City <span style="font-size: 10px;">(if not found in select box)</span>', 'listing_address_city2', $_POST['listing_address_city2'], 11, '');
			fsrep_print_admin_input('Zip / Postal Code', 'listing_address_postal', $_POST['listing_address_postal'], 6, '');
			fsrep_print_admin_textarea('Description', 'listing_description', $_POST['listing_description'], 8, 50, '');
			echo '</tbody></table><p>&nbsp;</p>';

			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Contact Information</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
			fsrep_print_admin_selectbox('Display Contact Information', 'listing_contact_display', $_POST['listing_contact_display'], array('Do Not Display' => 'Do Not Display', 'Display Contact Information' => 'Display Contact Information', 'Display Contact Form' => 'Display Contact Form',), '', '');
			fsrep_print_admin_input('Contact Name', 'listing_contact_name', $_POST['listing_contact_name'], 35, '');
			fsrep_print_admin_input('Contact Email', 'listing_contact_email', $_POST['listing_contact_email'], 35, '');
			fsrep_print_admin_input('Contact Phone', 'listing_contact_home_phone', $_POST['listing_contact_home_phone'], 35, '');
			fsrep_print_admin_input('Contact Cell Phone', 'listing_contact_cell_phone', $_POST['listing_contact_cell_phone'], 35, '');
			fsrep_print_admin_input('Special Instructions', 'listing_contact_special_instructions', $_POST['listing_contact_special_instructions'], 35, '');
			fsrep_print_admin_input('Contact Form Email Recipient', 'listing_contact_form_email', $_POST['listing_contact_form_email'], 35, '');
			echo '</tbody></table><p>&nbsp;</p>';

			echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">Additional Information</th>
				<th scope="col" class="manage-column" colspan="2" style="font-weight: normal;">Select "Not Applicable" or leave blank to hide.</th>
				</tr>
				</thead>
				<tbody>';
				$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
				foreach ($Fields as $Fields) {
					$HouseFieldInfo = $wpdb->get_var("SELECT listing_value FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = $Fields->field_id AND listing_id = ".$_POST['listing_id']);
					if ($Fields->field_value == '') {
						fsrep_print_admin_input($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, 35, '');
					} else {
						$FieldArray = array("Not Applicable" => "Not Applicable");
						$Array = explode(',',$Fields->field_value);
						for($i=0;$i<count($Array);$i++) {
							$AddArray = array($Array[$i] => $Array[$i]);
							$FieldArray = array_merge($FieldArray, $AddArray);
						}
						fsrep_print_admin_selectbox($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, $FieldArray, '', '');
					}
				}
			echo '</tbody></table><p>&nbsp;</p>';
			if ($_POST['listing_id'] != '') {
				echo '<input type="submit" name="submit" id="submitform" value="Update Listing" tabindex="4" />';
			} else {
				echo '<input type="submit" name="submit" id="submitform" value="Add Listing" tabindex="4" />';
			}
			echo '</form>';
			if (isset($_POST['listing_address_country'])) {
				echo '<script type="text/javascript">
					getFSREPlist(\''.$_POST['listing_address_country'].'\', \'listing_address_province\', \'CountryID\', \''.$_POST['listing_address_province'].'\');
					getFSREPlist(\''.$_POST['listing_address_province'].'\', \'listing_address_city\', \'ProvinceID\', \''.$_POST['listing_address_city'].'\');
					</script>';
			}
			
			
		} elseif ($_GET['f'] == 'del') {
			echo '<h2>Delete Listing </h2>';
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$_GET['hid']);
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_users WHERE listing_id = ".$_GET['hid']);
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE listing_id = ".$_GET['hid']);
			echo '<p>The listing has been removed.</p>';
		}
		
		echo '<p><a href="admin.php?page=fsrep_house_listings">Go Back to Listings</a></p>';
	// DISPLAY LIST	
	} else {
	
		$ListingCount = $wpdb->get_var("SELECT COUNT(listing_id) FROM ".$wpdb->prefix."fsrep_listings");
		$SoldCount = $wpdb->get_var("SELECT COUNT(listing_id) FROM ".$wpdb->prefix."fsrep_listings WHERE listing_sold = 1");

		echo '<h2>Edit Listings</h2>';
		echo '<p><strong><a href="admin.php?page=fsrep_house_listings&f=add">Add Listing</a></strong></p>';
		echo '<p><strong>Listings:</strong> <span class="count">('.$ListingCount.')</span> | <strong>Sold:</strong> <span class="count">('.$SoldCount.')</span></p>';
		echo '<table class="widefat page fixed" cellspacing="0" border="1">
			<thead>
			<tr>
			<th scope="col" class="manage-column" width="25">&nbsp;</th>
			<th scope="col" class="manage-column">Listing</th>
			<th scope="col" class="manage-column" width="50">ID</th>
			</tr>
			</thead>
			<tbody>';
			$Listings = $wpdb->get_results("SELECT *, date_format(listing_date_added, '%W %M %D %Y') as date_listed FROM ".$wpdb->prefix."fsrep_listings ORDER BY listing_id");
			foreach ($Listings as $Listings) {
				echo '<tr>';
				echo '<td><a href="admin.php?page=fsrep_house_listings&hid='.$Listings->listing_id.'&f=del" onClick="return confirm(\'Are you sure you want to remove this listing?\')"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
				echo '<td><a href="admin.php?page=fsrep_house_listings&hid='.$Listings->listing_id.'&f=edit"><strong>'.fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['Listing Name Display']).'</strong></a> <br /><a href="admin.php?page=fsrep_house_listings&hid='.$Listings->listing_id.'&f=edit">edit</a> &nbsp;&nbsp; <span style="color: #E0E0E0;">Listed '.$Listings->date_listed.'</span></td>';
				echo '<td>'.$Listings->listing_id.'</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
	}
	echo '</div>';
}
}
?>