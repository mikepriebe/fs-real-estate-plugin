<?php
// fs_categories_page() displays the page content for the first submenu of the custom Shopping Cart menu
function fsrep_fields_ordering_fix($FSREPFieldType) {
	global $wpdb;
	$Ordering = $wpdb->get_results("SELECT * FROM ".$FSREPFieldType." ORDER BY field_order");
	$count = 0;
	foreach ($Ordering as $Ordering) {
		$count++;
		$wpdb->query("UPDATE ".$FSREPFieldType." SET field_order = $count WHERE field_id = $Ordering->field_id");
	}
}
function fsrep_fields() {
	global $FSREPconfig,$wpdb;
	
	$FSREPFieldType = $wpdb->prefix.'fsrep_fields';
	if (isset($_GET['cform'])) {
		$FSREPFieldType = $wpdb->prefix.'fsrep_contact_fields';
	}

	if (isset($_POST['submit'])) {
		if (isset($_POST['nfieldv']) && isset($_POST['nfieldn'])) {
			$FieldCount = $wpdb->get_var("SELECT COUNT(field_id) FROM ".$FSREPFieldType);
			$FieldCount++;
			$_POST['nfieldv'] = str_replace(', ',',',$_POST['nfieldv']);
			$wpdb->query("INSERT INTO ".$FSREPFieldType." (field_name, field_value, field_order) VALUES ('".$_POST['nfieldn']."','".$_POST['nfieldv']."',$FieldCount)");
		} else {
			$Fields = $wpdb->get_results("SELECT * FROM ".$FSREPFieldType." ORDER BY field_order");
			foreach($Fields as $Fields) {
				$_POST['v'.$Fields->field_id] = str_replace(', ',',',$_POST['v'.$Fields->field_id]);
				$wpdb->query("UPDATE ".$FSREPFieldType." SET field_name = '".$_POST['n'.$Fields->field_id]."' WHERE field_id = $Fields->field_id");
				$wpdb->query("UPDATE ".$FSREPFieldType." SET field_value = '".$_POST['v'.$Fields->field_id]."' WHERE field_id = $Fields->field_id");
				$wpdb->query("UPDATE ".$FSREPFieldType." SET field_type = '".$_POST['t'.$Fields->field_id]."' WHERE field_id = $Fields->field_id");
				if (isset($_POST['c'.$Fields->field_id])) {
					$wpdb->query("UPDATE ".$FSREPFieldType." SET field_search = 1 WHERE field_id = $Fields->field_id");
				} else {
					$wpdb->query("UPDATE ".$FSREPFieldType." SET field_search = 0 WHERE field_id = $Fields->field_id");
				}
			}
		}
	} elseif(isset($_GET['f'])) {
		if ($_GET['f'] == 'del') {
			$wpdb->query("DELETE FROM ".$FSREPFieldType." WHERE field_id = ".$_GET['fid']);
			fsrep_fields_ordering_fix($FSREPFieldType);
		} elseif ($_GET['f'] == 'up') {
			$OldOrder = $wpdb->get_var("SELECT field_order FROM ".$FSREPFieldType." WHERE field_id = ".$_GET['fid']);
			$NewOrder = $OldOrder - 1;
			$OtherID = $wpdb->get_var("SELECT field_id FROM ".$FSREPFieldType." WHERE field_order = $NewOrder");
			$wpdb->query("UPDATE ".$FSREPFieldType." SET field_order = $NewOrder WHERE field_id = ".$_GET['fid']);
			$wpdb->query("UPDATE ".$FSREPFieldType." SET field_order = $OldOrder WHERE field_id = $OtherID");
		} elseif ($_GET['f'] == 'down') {
			$OldOrder = $wpdb->get_var("SELECT field_order FROM ".$FSREPFieldType." WHERE field_id = ".$_GET['fid']);
			$NewOrder = $OldOrder + 1;
			$OtherID = $wpdb->get_var("SELECT field_id FROM ".$FSREPFieldType." WHERE field_order = $NewOrder");
			$wpdb->query("UPDATE ".$FSREPFieldType." SET field_order = $NewOrder WHERE field_id = ".$_GET['fid']);
			$wpdb->query("UPDATE ".$FSREPFieldType." SET field_order = $OldOrder WHERE field_id = $OtherID");
		}
	}
	
	
	echo '<div class="wrap">';
	echo '<form name="fsrep-settings" action="#" method="POST">';
	echo '<h2>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Custom Fields Label', 'Custom Fields').'</h2>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column"><a href="admin.php?page=fsrep_fields">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Listing Input Fields Label', 'Listing Input Fields').'</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="admin.php?page=fsrep_fields&cform">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Contact Form Fields Label', 'Contact Form Fields').'</a></th>
		</tr>
		</thead>
		</table>';
	echo '<p>&nbsp;</p>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add Field Name Label', 'Add Field Name').'</th>
		<th scope="col" class="manage-column" >'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add Field Values Label', 'Add Field Values').' <span style="font-weight: normal; font-style: italic;">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add View About Label', '(Leave blank for input fields. Separate values by commas for select boxes, radios and checkboxes.)').'</span></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" colspan="2"><input type="submit" name="submit" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add New Field Label', 'Add New Field').'"></th>
		</tr>
		</tfoot>
		<tbody>';
		echo '<tr>';
		echo '<td><input type="text" name="nfieldn" value=""></td>';
		echo '<td><input type="text" name="nfieldv" value=""></td>';
		echo '</tr>';
		echo '</tbody></table>';
	echo '</form>';
	echo '<p>&nbsp;</p>';
	echo '<form name="fsrep-settings" action="#" method="POST">';
	echo '<p>&nbsp;</p>';
	if (isset($_GET['cform'])) { $Search = ''; } else { $Search = fsrep_text_translator('FireStorm Real Estate Plugin', 'Search Label', 'Search'); }
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="25">&nbsp;</th>
		<th scope="col" class="manage-column" width="100">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Type Label', 'Type').'</th>
		<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Field Name Label', 'Field Name').'</th>
		<th scope="col" class="manage-column" >'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Field Value About Label', 'Field Values <span style="font-weight: normal; font-style: italic;">(Leave blank for input fields. Separate values by commas for select boxes, radios and checkboxes.)</span>').'</th>
		<th scope="col" class="manage-column" width="150">&nbsp;</th>
		<th scope="col" class="manage-column" width="60">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Order Label', 'Order').'</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" width="25">&nbsp;</th>
		<th scope="col" class="manage-column" width="100">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Type Label', 'Type').'</th>
		<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Field Name Label', 'Field Name').'</th>
		<th scope="col" class="manage-column" >'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Field Value About Label', 'Field Values <span style="font-weight: normal; font-style: italic;">(Leave blank for input fields. Separate values by commas for select boxes, radios and checkboxes.)</span>').'</th>
		<th scope="col" class="manage-column" width="150">&nbsp;</th>
		<th scope="col" class="manage-column" width="60">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Order Label', 'Order').'</th>
		</tr>
		</tfoot>
		<tbody>';
		$Fields = $wpdb->get_results("SELECT * FROM ".$FSREPFieldType." ORDER BY field_order");
		$count = count($Fields);
		foreach($Fields as $Fields) {
			$Checked = '';
			if ($Fields->field_search == 1) {
				$Checked = ' checked';
			}
			$DELPage = 'fsrep_fields';
			if (isset($_GET['cform'])) { $DELPage = 'fsrep_fields&cform'; }
			echo '<tr>';
			echo '<td align="center" valign="middle"><a href="admin.php?page='.$DELPage.'&f=del&fid='.$Fields->field_id.'"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
			echo '<td><select name="t'.$Fields->field_id.'">';
			echo '<option value="text"'; if ($Fields->field_type == 'text') { echo ' selected'; } echo '>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Text Input Label', 'Text Input').'</option>';
			echo '<option value="textarea"'; if ($Fields->field_type == 'textarea') { echo ' selected'; } echo '>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Text Area Label', 'Text Area').'</option>';
			echo '<option value="selectbox"'; if ($Fields->field_type == 'selectbox') { echo ' selected'; } echo '>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Select Box Label', 'Select Box').'</option>';
			echo '<option value="radio"'; if ($Fields->field_type == 'radio') { echo ' selected'; } echo '>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Radio Label', 'Radio').'</option>';
			echo '<option value="checkbox"'; if ($Fields->field_type == 'checkbox') { echo ' selected'; } echo '>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Checkbox Label', 'Checkbox').'</option>';
			echo '</select></td>';
			echo '<td><input type="text" name="n'.$Fields->field_id.'" value="'.$Fields->field_name.'"></td>';
			if ($FSREPFieldType = $wpdb->prefix.'fsrep_fields' && $Fields->field_type == 'checkbox') {
				$ListingsUsingCF = $wpdb->get_var("SELECT COUNT(listing_id) FROM ".$wpdb->prefix."fsrep_listings_to_fields WHERE field_id = ".$Fields->field_id);
				if ($ListingsUsingCF > 0) {
					echo '<td><input type="hidden" name="v'.$Fields->field_id.'" value="'.$Fields->field_value.'">'.$Fields->field_value.'<br /><span style="color: #C7C7C7; font-style: italic;">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Field Cannot Be Edited Label', 'This custom field value cannot be edited as listings are currently using this custom field.').'</span></td>';
				} else {
					echo '<td><input type="text" name="v'.$Fields->field_id.'" value="'.$Fields->field_value.'"></td>';
				}
			} else {
				echo '<td><input type="text" name="v'.$Fields->field_id.'" value="'.$Fields->field_value.'"></td>';
			}
			echo '<td>';
			if (isset($_GET['cform'])) { echo ''; } else { 
				echo '<input type="checkbox" name="c'.$Fields->field_id.'" value="1"'.$Checked.'> '.fsrep_text_translator('FireStorm Real Estate Plugin', 'Searchable Label', 'Searchable').'<br/>';
				if (function_exists('fsrep_pro_field_listings')) { echo fsrep_pro_field_listings($Fields);} else { echo '<input type="checkbox" disabled> <span style="color: #999999">Hidden (PRO Only)</span>'; }
			}
			echo '</td>';
			// ORDERING BUTTONS
			echo '<td valign="middle">';
			// CHECK TO SEE IF THE BUTTON IS ALREADY FIRST IN LINE
			if (isset($_GET['cform'])) { $OrderingURL = '&cform'; } else { $OrderingURL = ''; }
			if ($Fields->field_order == 1) {
				echo "<img src=\"".get_option('home')."/wp-content/plugins/fs-real-estate-plugin/images/btn-mini-up-g.gif\" border=\"0\" alt=\"UP\"> ";
			} else {
				echo "<a href=\"admin.php?page=fsrep_fields".$OrderingURL."&f=up&fid=".$Fields->field_id."\"><img src=\"".get_option('home')."/wp-content/plugins/fs-real-estate-plugin/images/btn-mini-up.gif\" border=\"0\" alt=\"UP\"></a> ";
			}
			// CHECK TO SEE IF THE PRODUCT IS LAST IN LINE
			if ($Fields->field_order == $count) {
				echo "<img src=\"".get_option('home')."/wp-content/plugins/fs-real-estate-plugin/images/btn-mini-down-g.gif\" border=\"0\" alt=\"DOWN\"><br />";
			} else {
				echo "<a href=\"admin.php?page=fsrep_fields".$OrderingURL."&f=down&fid=".$Fields->field_id."\"><img src=\"".get_option('home')."/wp-content/plugins/fs-real-estate-plugin/images/btn-mini-down.gif\" border=\"0\" alt=\"DOWN\"></a><br />";
			}

			echo "</td>";
			echo '</tr>';
		}
		echo '</tbody></table>';
	echo '<br />';
	echo '<input type="submit" name="submit" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Fields Label', 'Update Fields').'">';
	echo '</form>';
	echo '</div>';
 
}
?>