<div id="poststuff" class="metabox-holder has-right-sidebar">        
<div id="side-info-column" class="inner-sidebar">
  <div id="side-sortables" class="meta-box-sortables ui-sortable">
  	<div id="submitdiv" class="postbox ">
      <div class="handlediv" title="Click to toggle"><br></div>
      <h3 class="hndle"><span><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Listing Information Label', 'Listing Information'); ?></span></h3>
      <div class="inside">
        <div class="submitbox" id="submitpost">
          <div id="minor-publishing">
            <div id="misc-publishing-actions" style="border-bottom: none;">
            	<?php if ($_POST['listing_id'] != '0') { ?>
              <div class="misc-pub-section"><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Status Label', 'Status'); ?>: <span id="post-status-display">
              <?php
              	if (function_exists('fsrep_member_listing_status')) {
									echo fsrep_member_listing_status($ListingID);
								} else {
									echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Listed Label', 'Listed');
								}
							?>
              </span></div>
              <div class="misc-pub-section"><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Updated Label', 'Updated'); ?>: <span id="post-status-display"><?php echo $_POST['last_updated']; ?></span></div>
              <div class="misc-pub-section"><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Date Listed Label', 'Date Listed'); ?>: <span id="post-status-display"><?php echo $_POST['date_listed']; ?></span></div>
              <?php }
              	if (function_exists('fsrep_pro_listing_sort')) {
									echo fsrep_pro_listing_sort($ListingID);
								}
							?>
              <div id="publishing-action" style="padding: 10px;">
              	<?php
									if ($_POST['listing_id'] != '0') {
										echo '<div style="float: left; margin-right: 68px;"><a class="preview button" href="'.fsrep_listing_url_gen($_POST['listing_id']).'" target="_blank">View Listing</a></div>';
										echo '<input type="submit" name="submit" id="publish" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Listing Label', 'Update Listing').'" tabindex="5" accesskey="p">';
									} else {
										echo '<input type="submit" name="submit" id="publish" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add Listing Label', 'Add Listing').'" tabindex="5" accesskey="p">';
									}
                ?>
               </div>
            </div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
    </div>

    <div id="postimagediv" class="postbox ">
      <div class="handlediv" title="Click to toggle"><br></div>
      <h3 class="hndle"><span><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Featured Image Label', 'Featured Image'); ?></span></h3>
      <div class="inside" style="text-align: center;">
				<?php
        $Picture = 'No Picture Uploaded';
        if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/medium/'.$_POST['listing_id'].'.jpg')) {
        	echo '<img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/medium/'.$_POST['listing_id'].'.jpg" style="border: 1px solid #999999;"><br /><br />';
        } else {
					echo '<p>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Please Upload Label', 'Please upload a picture.').'</p>';
				}
        ?>
        <input type="file" name="image" value="" size="20">
      </div>
    </div>

    <div id="postimagediv" class="postbox ">
      <div class="handlediv" title="Click to toggle"><br></div>
      <h3 class="hndle"><span><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Additional Images Label', 'Additional Images'); ?></span></h3>
      <div class="inside">
      <?php
			if ($_POST['listing_id'] != '0') {
				if (isset($_GET['iid']) && isset($_GET['if'])) {
					if ($_GET['if'] == 'idel') {
						if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg')) { unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg'); }
						if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg')) { unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg'); }
						if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg')) { unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg'); }
						if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg')) { unlink(ABSPATH.'wp-content/uploads/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg'); }
					}
				}
				$AImages = 0;
				for ($i=1;$i<=50;$i++) {
					if (file_exists(ABSPATH.'wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg')) {
						echo '<div style="float: left; width: 129px; height: 110px; text-align: center;"><img src="'.get_option('home').'/wp-content/uploads/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg" style="border: 1px solid #999999;"><br /><a href="admin.php?page=fsrep_listings&hid='.$_POST['listing_id'].'&f=edit&iid='.$i.'&if=idel">remove</a></div>';
						$AImages++;
					}
				}
				if ($AImages == 0) {
					echo fsrep_text_translator('FireStorm Real Estate Plugin', 'No Additional Pictures Label', 'No additional images found.');
				}
				echo '<input type="hidden" name="aimagen" value="'.$AImages.'">';
      	echo '<input type="file" name="aimage" value="" size="20">';
      	echo '<p>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Max Picture Filesize Label', 'Max filesize 5mb.').'</p>';

			} else {
				echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Additional Pictures Disabled Label', 'Additional images can be uploaded once the listing is added.');
			}
      ?>
      <div class="clear"></div>
      </div>
    </div>

    <div id="postimagediv" class="postbox ">
      <div class="handlediv" title="Click to toggle"><br></div>
      <h3 class="hndle"><span><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Documents Label', 'Documents'); ?></span></h3>
			<?php
 			if ($_POST['listing_id'] != '0') {
				if (isset($_GET['did']) && isset($_GET['df'])) {
					if ($_GET['df'] == 'ddel') {
						$DocumentName = $wpdb->get_var("SELECT document_name FROM ".$wpdb->prefix."fsrep_listings_docs WHERE document_id = ".$_GET['did']);
						unlink(ABSPATH.'wp-content/uploads/fsrep/houses/docs/'.$DocumentName);
						$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_listings_docs WHERE document_id = ".$_GET['did']);
					}
				}
				$Documents = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_listings_docs WHERE listing_id = ".$_POST['listing_id']);
				foreach ($Documents as $Documents) {
					echo '<div class="misc-pub-section">'.str_replace($_POST['listing_id'],'',$Documents->document_name).' - <a href="'.get_bloginfo('home').'/wp-content/uploads/fsrep/houses/docs/'.$Documents->document_name.'" target="_blank">view</a> | <a href="admin.php?page=fsrep_listings&hid='.$_POST['listing_id'].'&f=edit&did='.$Documents->document_id.'&df=ddel">remove</a></div>';
					//echo '<tr><td>'.str_replace($_POST['listing_id'],'',$Documents->document_name).'</td><td><a href="'.get_bloginfo('home').'/wp-content/uploads/fsrep/houses/docs/'.$Documents->document_name.'" target="_blank">view</a> | <a href="'.$CurrentURL.'&hid='.$_POST['listing_id'].'&f=edit&did='.$Documents->document_id.'&df=ddel">remove</a></td><td>&nbsp;</td></tr>';
				}
				echo '<div class="inside">';
      	echo '<input type="file" name="doc" value="" size="20">';
      	echo '<p>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Document Types Label', 'Upload doc, docx, xls, xlsx, ppt, pps, ppts, pdf, rtf and txt. Max filesize 5mb.').'</p>';
      	echo '</div>';
			} else {
				echo '<div class="inside">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Document Upload Disabled Label', 'Documents can be uploaded once the listing is added.').'</div>';
			}
      ?>
    </div>

    <div id="postimagediv" class="postbox ">
      <div class="handlediv" title="Click to toggle"><br></div>
      <h3 class="hndle"><span><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Videos and Slideshows Label', 'Videos and Slideshows'); ?></span></h3>
      <div class="inside">
        <p><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Virtual Tour Link Label', 'Virtual Tour Link'); ?>:<br/><input type="text" name="listing_virtual_tour" value="<?php echo $_POST['listing_virtual_tour']; ?>" size="25"></p>
        <p><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Slideshow Link Label', 'Slideshow Link'); ?>:<br/><input type="text" name="listing_slideshow" value="<?php echo $_POST['listing_slideshow']; ?>" size="25"></p>
        <p><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Video Link Label', 'Video Link'); ?>:<br/><input type="text" name="listing_video" value="<?php echo $_POST['listing_video']; ?>" size="25"></p>
      </div>
    </div>

    <div id="postimagediv" class="postbox ">
      <div class="handlediv" title="Click to toggle"><br></div>
      <h3 class="hndle"><span><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Google Coordinates Label', 'Google Coordinates'); ?></span></h3>
      <div class="inside">
       	<p><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Coordinates Label', 'Coordinates'); ?>: <input type="radio" name="listing_auto_coords" value="1" <?php if ($_POST['listing_auto_coords'] == 1) { echo 'checked'; } ?>> <?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Auto Label', 'Auto'); ?> &nbsp; <input type="radio" name="listing_auto_coords" value="0" <?php if ($_POST['listing_auto_coords'] == 0) { echo 'checked'; } ?>> <?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Manual Label', 'Manual'); ?></p>
        <p><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Longitude Label', 'Longitude'); ?>:<br/><input type="text" name="listing_long" value="<?php echo $_POST['listing_long']; ?>" size="25"></p>
        <p><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Latitude Label', 'Latitude'); ?>:<br/><input type="text" name="listing_lat" value="<?php echo $_POST['listing_lat']; ?>" size="25"></p>
        <p><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Zoom Label', 'Zoom'); ?>:<br/><input type="text" name="listing_zoom" value="<?php echo $_POST['listing_zoom']; ?>" size="25"></p>
      </div>
    </div>

  </div>
</div>               
        
        
        
        
        <?php
				
				echo '<div id="post-body">';
				echo '<div id="post-body-content">';
				$ListingNameLabel = ''; if ($_POST['listing_label'] == '') { $ListingNameLabel = fsrep_text_translator('FireStorm Real Estate Plugin', 'Example Listing Name Label', 'Enter listing name here (ex. "Unique Beach Front Home")'); }
				echo '<div id="titlediv"><div id="titlewrap"><label class="hide-if-no-js" style="" id="title-prompt-text" for="title">'.$ListingNameLabel.'</label><input type="text" name="listing_label" size="30" tabindex="1" value="'.$_POST['listing_label'].'" id="title" autocomplete="off"></div></div>';
				?><div id="poststuff"><?php wp_editor(stripslashes($_POST['listing_description']), "listing_description", "", false); ?></div><p>&nbsp;</p><?php

				echo '<table class="widefat page fixed" cellspacing="0" border="1">
				<thead>
				<tr>
				<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'General Information Label', 'General Information').'</th>
				<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
				</tr>
				</thead>
				<tbody>';
				fsrep_print_admin_selectbox('Is it Sold?', 'listing_sold', $_POST['listing_sold'], array('No' => 0, 'Yes' => 1), '', '');
				fsrep_print_admin_input('Selling Price', 'listing_price', $_POST['listing_price'], 10, 'Leave blank to hide price.');
				fsrep_print_admin_input('Street Number', 'listing_address_number', $_POST['listing_address_number'], 5, '');
				fsrep_print_admin_input('Street Name', 'listing_address_street', $_POST['listing_address_street'], 35, '');
				fsrep_print_admin_selectbox($FSREPconfig['CountryLabel'], 'listing_address_country', $_POST['listing_address_country'], fsrep_get_countries('array'), 'getFSREPlist(this, \'listing_address_province\', \'CountryID\', \''.$_POST['listing_address_country'].'\')', '');
				fsrep_print_admin_selectbox($FSREPconfig['ProvinceLabel'], 'listing_address_province', $_POST['listing_address_province'], '', 'getFSREPlist(this, \'listing_address_city\', \'ProvinceID\', \''.$_POST['listing_address_province'].'\')', '');
				if (function_exists('fsrep_pro_regions_add_form')) { fsrep_print_admin_selectbox($FSREPconfig['CityLabel'], 'listing_address_city', $_POST['listing_address_city'], '', 'getFSREPlist(this, \'listing_address_region\', \'CityID\', \''.$_POST['listing_address_city'].'\')', ''); } else { fsrep_print_admin_selectbox($FSREPconfig['CityLabel'], 'listing_address_city', $_POST['listing_address_city'], '', '', ''); }
				if (!isset($_POST['listing_address_city2'])) { $_POST['listing_address_city2'] = ''; }
				fsrep_print_admin_input('Other '.$FSREPconfig['CityLabel'].' <span style="font-size: 10px;">(if not found in select box)</span>', 'listing_address_city2', $_POST['listing_address_city2'], 11, '');
				if (function_exists('fsrep_pro_regions_add_form')) { fsrep_pro_regions_add_form($_POST); }
				fsrep_print_admin_input('Zip / Postal Code', 'listing_address_postal', $_POST['listing_address_postal'], 6, '');
				echo '</tbody></table><p>&nbsp;</p>';
			

				echo '<table class="widefat page fixed" cellspacing="0" border="1">
					<thead>
					<tr>
					<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Additional Information Label', 'Additional Information').'</th>
					<th scope="col" class="manage-column" colspan="2" style="font-weight: normal;">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Leave Blank to Hide Label', 'Select "Not Applicable" or leave blank to hide.').'</th>
					</tr>
					</thead>
					<tbody>';
					$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
					foreach ($Fields as $Fields) {
						$HouseFieldInfo = $wpdb->get_var("SELECT listing_value FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = $Fields->field_id AND listing_id = ".$_POST['listing_id']);
						$FieldArray = array("Not Applicable" => "Not Applicable");
						$Array = explode(',',$Fields->field_value);
						for($i=0;$i<count($Array);$i++) {
							$AddArray = array($Array[$i] => $Array[$i]);
							$FieldArray = array_merge($FieldArray, $AddArray);
						}
						if ($Fields->field_type == 'selectbox') {
							fsrep_print_admin_field_selectbox($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, $FieldArray, '', '');
						} elseif($Fields->field_type == 'radio') {
							fsrep_print_admin_radio($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, $FieldArray, '', '');
						} elseif($Fields->field_type == 'checkbox') {
							fsrep_print_admin_checkbox($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, $FieldArray, '', '');
						} elseif($Fields->field_type == 'textarea') {
							fsrep_print_admin_textarea($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, 8, 40, '');
						} else {
							fsrep_print_admin_input($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, 35, '');
						}
					}
				echo '</tbody></table><p>&nbsp;</p>';

				echo '<table class="widefat page fixed" cellspacing="0" border="1">
					<thead>
					<tr>
					<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Contact Information Label', 'Contact Information').'</th>
					<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
					</tr>
					</thead>
					<tbody>';
					//if ($FSREPconfig['ListingReqContactInfo'] == 'Yes') { 
						//$ContactArray = array('Display Contact Information' => 'Display Contact Information', 'Display Contact Form' => 'Display Contact Form');
					//} else {
						$ContactArray = array('Do Not Display' => 'Do Not Display', 'Display Contact Information' => 'Display Contact Information', 'Display Contact Form' => 'Display Contact Form', 'Display Information and Form' => 'Display Information and Form');
					//}
				fsrep_print_admin_selectbox('Display Contact Information', 'listing_contact_display', $_POST['listing_contact_display'], $ContactArray, '', '');
				fsrep_print_admin_input('Contact Name', 'listing_contact_name', $_POST['listing_contact_name'], 35, '');
				fsrep_print_admin_input('Contact Email', 'listing_contact_email', $_POST['listing_contact_email'], 35, '');
				fsrep_print_admin_input('Contact Phone', 'listing_contact_home_phone', $_POST['listing_contact_home_phone'], 35, '');
				fsrep_print_admin_input('Contact Cell Phone', 'listing_contact_cell_phone', $_POST['listing_contact_cell_phone'], 35, '');
				fsrep_print_admin_input('Special Instructions', 'listing_contact_special_instructions', $_POST['listing_contact_special_instructions'], 35, '');
				fsrep_print_admin_input('Contact Form Email Recipient', 'listing_contact_form_email', $_POST['listing_contact_form_email'], 35, 'For multiple recipients: separate by comma (ex. email@example.com,email2@example.com)');
				echo '</tbody></table><p>&nbsp;</p>';
			
				echo '</div>';
				echo '</div>';
?>