<?php
add_action('admin_init', 'editor_admin_init');
add_action('admin_head', 'editor_admin_head');
 
function editor_admin_init() {
  wp_enqueue_script('word-count');
  wp_enqueue_script('post');
  wp_enqueue_script('editor');
  wp_enqueue_script('media-upload');
}
 
function editor_admin_head() {
  wp_tiny_mce();
}


if(!function_exists('fsrep_listings')) {
function fsrep_listings() {
	global $wpdb,$FSREPconfig,$RListingLimit,$FSREPAPI,$user_ID,$FSREPMembers;

	$ListingCount = $wpdb->get_var("SELECT COUNT(listing_id) FROM ".$wpdb->prefix."fsrep_listings_to_users WHERE ID = $user_ID");
	$UserInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."users WHERE ID = $user_ID");
	$DenyListings = FALSE;
	$ListingLimit = FALSE;
	if ($FSREPconfig['EnableListingPlans'] == 'Yes' && $user_ID != 1) {
		if ($UserInfo->plan == 0) {
			$DenyListings = TRUE;
		} else {
			$PlanDetails = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_plans WHERE plan_id = ".$UserInfo->plan);
			if ($ListingCount >= $PlanDetails->plan_listing_limit) {
				$ListingLimit = TRUE;
			}
		}
	}
	$HType = 'Your';
	$CurrentURL = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	if (!preg_match('/admin.php/i',$CurrentURL)) {
		$CurrentURL .= '?listings';
	}
	if ($user_ID == 1) {
		$HType = 'All';
	}
	
	ini_set("memory_limit","80M");
	
	echo '<div class="wrap">';
		
	// EDIT LISTING
	if (isset($_GET['f'])) {
		if ($_GET['f'] == 'add') {
			echo '<h2>Add Listing</h2>';
			if (isset($_POST['submit'])) {
				$SQLField = '';
				$SQLValue = '';
				$RegisterFormError = '';
				
				// CHECK REQUIRED FIELDS
				if ($_POST['listing_label'] == '' || $_POST['listing_price'] == '' || $_POST['listing_address_number'] == '' || $_POST['listing_address_postal'] == '') {
					$RegisterFormError = 'Please fill in all required fields.';
				}

				// MAKE SURE IMAGE IS JPG
				if ($_FILES['image']['name'] != '') {
					if (!preg_match('/jpg/i', $_FILES['image']['name'])) {
						$RegisterFormError = 'House Picture Must Be JPG Format';
					}
				}
				
				//$HTMLCheck = implode(' ',$_POST);
				//if (preg_match("/([\<])([^\>]{1,})*([\>])/i", $HTMLCheck)) {
				//	$RegisterFormError = "Your listing information contains invalid HTML characters.";
				//} 				
				
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
						
						// MODERATION
						$ListingVisibility = 1;
						if ($FSREPconfig['ListingModeration'] == 'Yes') {
							$ListingVisibility = 0;
						}
						
						// ADD LISTING
						$AdditionSQL = "INSERT INTO ".$wpdb->prefix."fsrep_listings (
																																					listing_sold, 
																																					listing_visibility, 
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
																																					listing_virtual_tour, 
																																					listing_slideshow, 
																																					listing_video, 
																																					listing_contact_display, 
																																					listing_contact_name, 
																																					listing_contact_email, 
																																					listing_contact_home_phone, 
																																					listing_contact_cell_phone, 
																																					listing_contact_special_instructions, 
																																					listing_contact_form_email
																																				) VALUES (
																																					'".$_POST['listing_sold']."', 
																																					'".$ListingVisibility."', 
																																					NOW(), 
																																					NOW(), 
																																					'".$_POST['listing_label']."', 
																																					'".str_replace('$','',str_replace(',','',$_POST['listing_price']))."', 
																																					'".$_POST['listing_address_number']."', 
																																					'".$_POST['listing_address_street']."', 
																																					'".$_POST['listing_address_city']."', 
																																					'".$_POST['listing_address_province']."', 
																																					'".$_POST['listing_address_country']."', 
																																					'".$_POST['listing_address_postal']."', 
																																					'".$_POST['listing_description']."', 
																																					'".$_POST['listing_virtual_tour']."', 
																																					'".$_POST['listing_slideshow']."', 
																																					'".$_POST['listing_video']."', 
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
						$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_users (listing_id, ID) VALUES ($ListingID, $user_ID)");
						
						// ADD CUSTOM FIELDS
						$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
						foreach ($Fields as $Fields) {
							if ($Fields->field_type != 'checkbox') {
								if (isset($_POST['field'.$Fields->field_id])) {
									if ($_POST['field'.$Fields->field_id] != '' && $_POST['field'.$Fields->field_id] != 'Not Applicable') {
										$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_fields (listing_id, field_id, listing_value) VALUES (".$ListingID.",$Fields->field_id,'".$_POST['field'.$Fields->field_id]."')");
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
								$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_fields (listing_id, field_id, listing_value) VALUES (".$ListingID.",$Fields->field_id,'".$CBValue."')");
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
							if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/medium/'.$ListingID.'.jpg')) {
								unlink(ABSPATH.'wp-content/uploads/fsrep/houses/medium/'.$ListingID.'.jpg');
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
								fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/'.$ListingID.'.jpg', 300, 225);
								fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$ListingID.'.jpg', 80, 60);
								fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/medium/'.$ListingID.'.jpg', 200, 150);
								fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/large/'.$ListingID.'.jpg', 800, 600);
								
								unlink($uploaddir.basename($_FILES['image']['name']));
								
							}
						}
						
						
						echo '<div id="message" class="updated fade"><p><strong>Your listing has been added.</strong> <a href="admin.php?page=fsrep_listings&hid='.$ListingID.'&f=edit">Click here</a> to edit your listing.</p></div>';
						$HideForm = TRUE;
					}
				} else {
					echo '<div id="message" class="error fade"><p><strong>There was an error adding your listing:</strong><br />'.$RegisterFormError.'</p></div>';
				}
			}
			
			
			if (!isset($HideForm)) {
			echo '<form name="fsrep-add-listing" id="fsrep-add-listing" action="" method="post" enctype="multipart/form-data">';
			fsrep_print_hidden_input('MAX_FILE_SIZE', '10000000');
			if (!isset($_POST['listing_id'])) { $_POST['listing_id'] = ''; }
			fsrep_print_hidden_input('listing_id', $_POST['listing_id']);
	

			include("admin_listings_form.php");



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
				
				$RegisterFormError = '';
				
				// CHECK REQUIRED FIELDS
				if ($_POST['listing_label'] == '' || $_POST['listing_price'] == '' || $_POST['listing_address_number'] == '' || $_POST['listing_address_postal'] == '') {
					$RegisterFormError = 'Please fill in all required fields.';
				}

				// MAKE SURE IMAGE IS JPG
				if ($_FILES['image']['name'] != '') {
					if (!preg_match('/jpg/i', $_FILES['image']['name'])) {
						$RegisterFormError = 'House Picture Must Be JPG Format';
					}
				}
				
				//$HTMLCheck = implode(' ',$_POST);
				//if (preg_match("/([\<])([^\>]{1,})*([\>])/i", $HTMLCheck)) {
					//$RegisterFormError = "Your listing information contains invalid HTML characters.";
				//} 				

				if ($RegisterFormError == '') {
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
	
					// MODERATION
					if ($FSREPconfig['ListingModeration'] == 'Yes' && $user_ID != 1) {
						$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_visibility = '0' WHERE listing_id = ".$_POST['listing_id']);
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
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_description = '".addslashes($_POST['listing_description'])."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_display = '".$_POST['listing_contact_display']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_name = '".$_POST['listing_contact_name']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_email = '".$_POST['listing_contact_email']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_home_phone = '".$_POST['listing_contact_home_phone']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_cell_phone = '".$_POST['listing_contact_cell_phone']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_contact_special_instructions = '".$_POST['listing_contact_special_instructions']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_virtual_tour = '".$_POST['listing_virtual_tour']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_slideshow = '".$_POST['listing_slideshow']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_video = '".$_POST['listing_video']."' WHERE listing_id = ".$_POST['listing_id']);
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_listings SET listing_last_updated = NOW() WHERE listing_id = ".$_POST['listing_id']);
					
					// UPDATE CUSTOM FIELDS
					$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE listing_id = ".$_POST['listing_id']."");
					$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
					foreach ($Fields as $Fields) {
						if ($Fields->field_type != 'checkbox') {
							if (isset($_POST['field'.$Fields->field_id])) {
								if ($_POST['field'.$Fields->field_id] != '' && $_POST['field'.$Fields->field_id] != 'Not Applicable') {
									$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_fields (listing_id, field_id, listing_value) VALUES (".$_POST['listing_id'].",$Fields->field_id,'".$_POST['field'.$Fields->field_id]."')");
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
							$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_to_fields (listing_id, field_id, listing_value) VALUES (".$_POST['listing_id'].",$Fields->field_id,'".$CBValue."')");
						}
					}
					
					
					
					
					// UPDATE GOOGLE COORDS
					$Coords = google_geocoder($_POST['listing_address_number'].' '.$_POST['listing_address_street'].' '.$CityName.' '.$ProvinceName.' '.$_POST['listing_address_postal'].' ', $FSREPconfig['GoogleMapAPI']);
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
						if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/medium/'.$_POST['listing_id'].'.jpg')) {
							unlink(ABSPATH.'wp-content/uploads/fsrep/houses/medium/'.$_POST['listing_id'].'.jpg');
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
							fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/'.$_POST['listing_id'].'.jpg', 300, 225);
							fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/small/'.$_POST['listing_id'].'.jpg', 80, 60);
							fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/medium/'.$_POST['listing_id'].'.jpg', 200, 150);
							fsrep_imageresizer($uploaddir.basename($_FILES['image']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/large/'.$_POST['listing_id'].'.jpg', 800, 600);
							
							unlink($uploaddir.basename($_FILES['image']['name']));
							
						}
					}
					
					// ADDITIONAL IMAGES
					//$ANumb = $_POST['aimagen'] + 1;
					if (isset($_FILES['aimage']['name'])) {
						if ($_FILES['aimage']['name'] != "") {
							// UPDATE IMAGE NAMING
							$AImageID = 1;
							for ($i=1;$i<=50;$i++) {
								if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$i.'.jpg')) {
									rename(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$i.'.jpg', ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$AImageID.'.jpg');
									rename(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg', ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$AImageID.'.jpg');
									rename(ABSPATH.'wp-content/uploads/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$i.'.jpg', ABSPATH.'wp-content/uploads/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$AImageID.'.jpg');
									rename(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$i.'.jpg', ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$AImageID.'.jpg');
									$AImageID++;
								}
							}
							$ANumb = $AImageID;

							// UNLINK CURRENT IMAGES IF THEY EXIST
							if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$ANumb.'.jpg')) { unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$ANumb.'.jpg'); }
							if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$ANumb.'.jpg')) { unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$ANumb.'.jpg'); }
							if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$ANumb.'.jpg')) { unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$ANumb.'.jpg'); }
							if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$ANumb.'.jpg')) { unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$ANumb.'.jpg'); }
							
							// UPDATE IMAGE
							$uploaddir = ABSPATH.'wp-content/uploads/fsrep/houses/additional/temp/';
							$uploadfile = $uploaddir . basename($_FILES['aimage']['name']);
							if (move_uploaded_file($_FILES['aimage']['tmp_name'], $uploadfile)) {
								// Upload Image as Enlarged Version
								rename($uploadfile, $uploaddir.basename($_FILES['aimage']['name']));
								
								// CONVERT ENLARGED IMAGE TO THUMBNAIL AND STANDARD SIZE
								fsrep_imageresizer($uploaddir.basename($_FILES['aimage']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$ANumb.'.jpg', 300, 225);
								fsrep_imageresizer($uploaddir.basename($_FILES['aimage']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$ANumb.'.jpg', 80, 60);
								fsrep_imageresizer($uploaddir.basename($_FILES['aimage']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$ANumb.'.jpg', 200, 150);
								fsrep_imageresizer($uploaddir.basename($_FILES['aimage']['name']), ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$ANumb.'.jpg', 800, 600);
								
								unlink($uploaddir.basename($_FILES['aimage']['name']));
								
							}
							
						}
					}
					
					// DOCUMENTS
					if (isset($_FILES['doc']['name'])) {
						if ($_FILES['doc']['name'] != "") {
							$FileType = substr($_FILES['doc']['name'], -4);
							if($FileType == '.doc' || $FileType == 'docx' || $FileType == '.xls' || $FileType == 'xlsx' || $FileType == '.ppt' || $FileType == '.pps' || $FileType == '.pdf' || $FileType == '.rtf' || $FileType == '.txt' || $FileType == 'ppts') {
								$uploaddir = ABSPATH.'wp-content/uploads/fsrep/houses/docs/';
								$uploadfile = $uploaddir.$_POST['listing_id'].basename($_FILES['doc']['name']);
								if (move_uploaded_file($_FILES['doc']['tmp_name'], $uploadfile)) {
									$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_listings_docs (listing_id, document_name) VALUES (".$_POST['listing_id'].", '".$_POST['listing_id'].basename($_FILES['doc']['name'])."')");
								}
							}
						}
					}
				}
					
				echo '<div id="message" class="updated fade"><p><strong>Your listing has been updated.</strong></p></div>';
			}
			
			
			
			
			$sql = mysql_query("SELECT *, date_format(".$wpdb->prefix."fsrep_listings.listing_date_added, '%W %M %D %Y') as date_listed, date_format(".$wpdb->prefix."fsrep_listings.listing_last_updated, '%W %M %D %Y') as last_updated FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$_GET['hid']);
			$_POST = mysql_fetch_array($sql);
			echo '<h2>Edit Listing</h2>';
			echo '<form name="fsrep-add-listing" id="fsrep-add-listing" action="admin.php?page=fsrep_listings&hid='.$_POST['listing_id'].'&f=edit" method="post" enctype="multipart/form-data">';
			fsrep_print_hidden_input('MAX_FILE_SIZE', '10000000');
			fsrep_print_hidden_input('listing_id', $_POST['listing_id']);
			
			if (isset($RegisterFormError)) {
				if ($RegisterFormError != '') {
					echo '<div id="fsrep-form-error">'.$RegisterFormError.'</div>';
				}
			}








			include("admin_listings_form.php");








			echo '</form>';
			if (isset($_POST['listing_address_country'])) {
				echo '<script type="text/javascript">
					getFSREPlist(\''.$_POST['listing_address_country'].'\', \'listing_address_province\', \'CountryID\', \''.$_POST['listing_address_province'].'\');
					getFSREPlist(\''.$_POST['listing_address_province'].'\', \'listing_address_city\', \'ProvinceID\', \''.$_POST['listing_address_city'].'\');
					</script>';
			}
			
			
		} elseif ($_GET['f'] == 'del') {
			echo '<h2>Delete House Listing </h2>';
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings WHERE listing_id = ".$_GET['hid']);
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_users WHERE listing_id = ".$_GET['hid']);
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE listing_id = ".$_GET['hid']);
			echo '<p></p>';
			echo '<div id="message" class="updated fade"><p><strong>The listing has been removed.</strong> <a href="admin.php?page=fsrep_listings">Click here</a> to view your listings.</p></div>';
		} elseif ($_GET['f'] == 'mod') {
			if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/members/admin_listings_mod.php')) { require_once("includes/members/admin_listings_mod.php"); }
		} elseif ($_GET['f'] == 'feature') {
			if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/members/admin_listings_feature.php')) { require_once("includes/members/admin_listings_feature.php"); }
		}
		
	// DISPLAY LIST	
	} else {
	
		
		
		
		if ($ListingLimit == TRUE) {
			echo '<h2>'.$HType.' House Listings</h2>';
			echo '<p>You have reached your limit of listings.</p>';
		} elseif ($DenyListings == FALSE) {
			echo '<h2>'.$HType.' House Listings <a href="'.$CurrentURL.'&f=add" class="add-new-h2">Add New</a></h2>';
		}
		
		
		

		if ($user_ID == 1) {
			$Listings = $wpdb->get_results("SELECT *, date_format(listing_date_added, '%W %M %D %Y') as date_listed FROM ".$wpdb->prefix."fsrep_listings ORDER BY listing_id");
		} else {
			$Listings = $wpdb->get_results("SELECT *, date_format(".$wpdb->prefix."fsrep_listings.listing_date_added, '%W %M %D %Y') as date_listed 
																			FROM ".$wpdb->prefix."fsrep_listings, ".$wpdb->prefix."fsrep_listings_to_users 
																			WHERE ".$wpdb->prefix."fsrep_listings_to_users.ID = $user_ID AND ".$wpdb->prefix."fsrep_listings.listing_id = ".$wpdb->prefix."fsrep_listings_to_users.listing_id 
																			ORDER BY ".$wpdb->prefix."fsrep_listings.listing_id");
		}
		if (count($Listings) > 0) {
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="50">ID</th>
		<th scope="col" class="manage-column" width="125">Status</th>
		<th scope="col" class="manage-column" width="100">Listing Name</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column" width="200">Date Listed</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column">ID</th>
		<th scope="col" class="manage-column">Status</th>
		<th scope="col" class="manage-column" width="100">Listing Name</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">Date Listed</th>
		</tr>
		</tfoot>
		<tbody>';
		foreach ($Listings as $Listings) {
			$ListingStatus = 'Available';
			if ($Listings->listing_visibility == 0) { 
				$ListingStatus = 'Under Moderation'; 
			} elseif ($Listings->listing_sold == 1) { 
				$ListingStatus = 'Sold'; 
			}  elseif ($Listings->listing_featured == 1) { 
				$ListingStatus = 'Featured'; 
			} 
			echo '<tr>';
			echo '<td>'.$Listings->listing_id.'</td>';
			echo '<td>'.$ListingStatus.'</td>';
			echo '<td width="85">';
			if (file_exists(ABSPATH.'/wp-content/uploads/fsrep/houses/small/'.$Listings->listing_id.'.jpg')) { echo '<img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/small/'.$Listings->listing_id.'.jpg" border="0"  style="border: 1px solid #999999;" />'; } else { echo '&nbsp;'; }
			echo '</td>';
			echo '<td><strong>'.fsrep_listing_name_gen($Listings->listing_id, $FSREPconfig['ListingNameDisplay']).'</strong><br />';
			echo '<span style="color: #888888;">'.$Listings->listing_address_number.' '.$Listings->listing_address_street.', '.fsrep_get_address_name($Listings->listing_address_city, 'city').', '.fsrep_get_address_name($Listings->listing_address_province, 'province').' '.$Listings->listing_address_postal.'</span><br />';
			if ($FSREPMembers == TRUE && $user_ID == 1 && $FSREPconfig['ListingModeration'] == 'Yes') {
				if ($ListingStatus == 'Under Moderation') {
					echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=mod&v=accept">accept</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
				} else {
					echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=mod&v=hide">hide</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
				}
			}
			if ($FSREPMembers == TRUE && $FSREPconfig['EnableFeaturedListings'] == 'Yes') {
				if ($Listings->listing_featured == 0) {
					echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=feature">feature</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
				} else {
					if ($user_ID == 1) {
						echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=feature">unfeature</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
					}
				}
			}
			echo '<a href="'.fsrep_listing_url_gen($Listings->listing_id).'" target="_blank">view</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=edit">edit</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
			if ($FSREPMembers == TRUE) {
				if ($Listings->listing_sold == 0) {
					echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=mod&v=sold">sold</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
				} else {
					echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=mod&v=unsold">relist</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ';
				}
			}
			echo '<a href="'.$CurrentURL.'&hid='.$Listings->listing_id.'&f=del" onclick="if (!confirm(\'Do you really want to remove this listing?\')) return false">remove</a></td>';
			echo '<td>'.$Listings->date_listed.'</td>';
			echo '</tr>';
		}
		echo '</tbody></table><br />';
		} else {
			echo 'No listings found.';
		}
	}
	echo '</div>';
}
}
?>