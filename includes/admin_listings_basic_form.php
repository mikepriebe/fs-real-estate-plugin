<?php 
echo '<div align="center">';
if ($_POST['listing_id'] != '0') {
	echo '<input type="submit" name="submit" id="publish" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Listing Label', 'Update Listing').'" tabindex="5" accesskey="p">';
} else {
	echo '<input type="submit" name="submit" id="publish" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add Listing Label', 'Add Listing').'" tabindex="5" accesskey="p">';
}
echo '</div><p>&nbsp;</p>';

if ($_POST['listing_id'] != '0') { 
	//echo '<div class="misc-pub-section">Status: <span id="post-status-display">';
	//if (function_exists('fsrep_member_listing_status')) {
	//	echo fsrep_member_listing_status($ListingID);
	//} else {
	//	echo 'Listed';
	//}
	//echo '</span></div>';
	//echo '<div class="misc-pub-section">Updated: <span id="post-status-display">'.$_POST['last_updated'].'</span></div>';
	//echo '<div class="misc-pub-section">Date Listed: <span id="post-status-display">'.$_POST['date_listed'].'</span></div>';
}

echo '<div id="post-body">';
echo '<div id="post-body-content">';
echo '<div id="titlediv"><div id="titlewrap">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Name Label', 'Name').': <input type="text" name="listing_label" size="90" tabindex="1" value="'.stripslashes($_POST['listing_label']).'" id="title" autocomplete="off"></div></div>';
?><div id="poststuff"><?php wp_editor(stripslashes($_POST['listing_description']), "listing_description", "", false); ?></div><p>&nbsp;</p><?php

echo '<table class="widefat page fixed" cellspacing="0">
<thead>
<tr>
<th scope="col" class="manage-column" width="200"><h3>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'General Information Label', 'General Information').'</h3></th>
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


echo '<table class="widefat page fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Additional Information Label', 'Additional Information').'</th>
	<th scope="col" class="manage-column" colspan="2" style="font-weight: normal;">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Leave Blank to Hide Label', 'Select "Not Applicable" or leave blank to hide.').'</th>
	</tr>
	</thead>
	<tbody>';
	$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
	foreach ($Fields as $Fields) {
		if($Fields->field_type != 'checkbox') {	
			$HouseFieldInfo = $wpdb->get_var("SELECT listing_value FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = $Fields->field_id AND listing_id = ".$_POST['listing_id']);
			$FieldArray = array("Not Applicable" => "Not Applicable");
			$Array = explode(',',$Fields->field_value);
			for($i=0;$i<count($Array);$i++) {
				$AddArray = array($Array[$i] => $Array[$i]);
				$FieldArray = array_merge($FieldArray, $AddArray);
			}
		} else {
			$HouseFieldInfo = $wpdb->get_results("SELECT listing_value FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = $Fields->field_id AND listing_id = ".$_POST['listing_id']);
			$CBArray = array();
			foreach($HouseFieldInfo as $HouseFieldInfo) {
				array_push($CBArray, $HouseFieldInfo->listing_value);
			}
			$FieldArray = array("Not Applicable" => "Not Applicable");
			$Array = explode(',',$Fields->field_value);
			for($i=0;$i<count($Array);$i++) {
				$AddArray = array($Array[$i] => $Array[$i]);
				$FieldArray = array_merge($FieldArray, $AddArray);
			}
		}
		if ($Fields->field_type == 'selectbox') {
			fsrep_print_admin_field_selectbox($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, $FieldArray, '', '');
		} elseif($Fields->field_type == 'radio') {
			fsrep_print_admin_radio($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, $FieldArray, '', '');
		} elseif($Fields->field_type == 'checkbox') {
			fsrep_print_admin_checkbox($Fields->field_name, 'field'.$Fields->field_id, $CBArray, $FieldArray, '', '');
		} elseif($Fields->field_type == 'textarea') {
			fsrep_print_admin_textarea($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, 8, 40, '');
		} else {
			fsrep_print_admin_input($Fields->field_name, 'field'.$Fields->field_id, $HouseFieldInfo, 35, '');
		}
	}
echo '</tbody></table><p>&nbsp;</p>';

echo '<table class="widefat page fixed" cellspacing="0">
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
fsrep_print_admin_input('Contact Form Email Recipient', 'listing_contact_form_email', $_POST['listing_contact_form_email'], 35, 'Separate emails by comma.');
echo '</tbody></table><p>&nbsp;</p>';




echo '<table class="widefat page fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Videos and Slideshows Label', 'Videos and Slideshows').'</th>
	<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
	</tr>
	</thead>
	<tbody>';
fsrep_print_admin_input('Virtual Tour Link', 'listing_virtual_tour', $_POST['listing_virtual_tour'], 35, '');
fsrep_print_admin_input('Slideshow Link', 'listing_slideshow', $_POST['listing_slideshow'], 35, '');
fsrep_print_admin_input('Video Link', 'listing_video', $_POST['listing_video'], 35, '');
echo '</tbody></table><p>&nbsp;</p>';





echo '<table class="widefat page fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Google Coordinates Label', 'Google Coordinates').'</th>
	<th scope="col" class="manage-column" colspan="2">&nbsp;</th>
	</tr>
	</thead>
	<tbody>';
fsrep_print_admin_radio('Coordinates', 'listing_auto_coords', $_POST['listing_auto_coords'], array('Auto' => '1', 'Manual' => '0'), '', '');
fsrep_print_admin_input('Longitude', 'listing_long', $_POST['listing_long'], 35, '');
fsrep_print_admin_input('Latitude', 'listing_lat', $_POST['listing_lat'], 35, '');
fsrep_print_admin_input('Zoom', 'listing_zoom', $_POST['listing_zoom'], 35, '');
echo '</tbody></table><p>&nbsp;</p>';

				if (function_exists('fsrep_pro_listing_category_form')) { fsrep_pro_listing_category_form(); }











echo '</div>';
echo '</div>';







?>










<div id="poststuff" class="metabox-holder has-right-sidebar">        
<div id="side-info-column" class="inner-sidebar">
  <div id="side-sortables" class="meta-box-sortables ui-sortable">

    <div id="postimagediv" class="postbox ">
      <div class="handlediv" title="Click to toggle"><br></div>
      <h3 class="hndle"><span><?php echo fsrep_text_translator('FireStorm Real Estate Plugin', 'Featured Image Label', 'Featured Image'); ?></span></h3>
      <div class="inside" style="text-align: center;">
				<?php
        $Picture = 'No Picture Uploaded';
        if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/medium/'.$_POST['listing_id'].'.jpg')) {
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
						if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg')) { unlink($WPUploadDir['basedir'].'/fsrep/houses/additional/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg'); }
						if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg')) { unlink($WPUploadDir['basedir'].'/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg'); }
						if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg')) { unlink($WPUploadDir['basedir'].'/fsrep/houses/additional/medium/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg'); }
						if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg')) { unlink($WPUploadDir['basedir'].'/fsrep/houses/additional/large/'.$_POST['listing_id'].'-'.$_GET['iid'].'.jpg'); }
					}
				}
				$AImages = 0;
				for ($i=1;$i<=50;$i++) {
					if (file_exists($WPUploadDir['basedir'].'/fsrep/houses/additional/small/'.$_POST['listing_id'].'-'.$i.'.jpg')) {
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
						unlink($WPUploadDir['basedir'].'/fsrep/houses/docs/'.$DocumentName);
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


  </div>
</div>               
<p>&nbsp;</p>      
        
        
        
<?php
echo '<div align="center">';
if ($_POST['listing_id'] != '0') {
	echo '<input type="submit" name="submit" id="publish" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Listing Label', 'Update Listing').'" tabindex="5" accesskey="p">';
} else {
	echo '<input type="submit" name="submit" id="publish" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add Listing Label', 'Add Listing').'" tabindex="5" accesskey="p">';
}
echo '</div><p>&nbsp;</p>';
?>