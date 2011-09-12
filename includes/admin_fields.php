<?php
// fs_categories_page() displays the page content for the first submenu of the custom Shopping Cart menu
function fsrep_fields_ordering_fix() {
	global $wpdb;
	$Ordering = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
	$count = 0;
	foreach ($Ordering as $Ordering) {
		$count++;
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_order = $count WHERE field_id = $Ordering->field_id");
	}
}
function fsrep_fields() {
	global $FSREPconfig,$wpdb;
	
	if (isset($_POST['submit'])) {
		if (isset($_POST['nfieldv']) && isset($_POST['nfieldn'])) {
			$FieldCount = $wpdb->get_var("SELECT COUNT(field_id) FROM ".$wpdb->prefix."fsrep_fields");
			$FieldCount++;
			$_POST['nfieldv'] = str_replace(', ',',',$_POST['nfieldv']);
			$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_fields (field_name, field_value, field_order) VALUES ('".$_POST['nfieldn']."','".$_POST['nfieldv']."',$FieldCount)");
		} else {
			$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
			foreach($Fields as $Fields) {
				$_POST['v'.$Fields->field_id] = str_replace(', ',',',$_POST['v'.$Fields->field_id]);
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_name = '".$_POST['n'.$Fields->field_id]."' WHERE field_id = $Fields->field_id");
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_value = '".$_POST['v'.$Fields->field_id]."' WHERE field_id = $Fields->field_id");
				if (isset($_POST['c'.$Fields->field_id])) {
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_search = 1 WHERE field_id = $Fields->field_id");
				} else {
					$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_search = 0 WHERE field_id = $Fields->field_id");
				}
			}
		}
	} elseif(isset($_GET['f'])) {
		if ($_GET['f'] == 'del') {
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_fields WHERE field_id = ".$_GET['fid']);
			fsrep_fields_ordering_fix();
		} elseif ($_GET['f'] == 'up') {
			$OldOrder = $wpdb->get_var("SELECT field_order FROM ".$wpdb->prefix."fsrep_fields WHERE field_id = ".$_GET['fid']);
			$NewOrder = $OldOrder - 1;
			$OtherID = $wpdb->get_var("SELECT field_id FROM ".$wpdb->prefix."fsrep_fields WHERE field_order = $NewOrder");
			$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_order = $NewOrder WHERE field_id = ".$_GET['fid']);
			$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_order = $OldOrder WHERE field_id = $OtherID");
		} elseif ($_GET['f'] == 'down') {
			$OldOrder = $wpdb->get_var("SELECT field_order FROM ".$wpdb->prefix."fsrep_fields WHERE field_id = ".$_GET['fid']);
			$NewOrder = $OldOrder + 1;
			$OtherID = $wpdb->get_var("SELECT field_id FROM ".$wpdb->prefix."fsrep_fields WHERE field_order = $NewOrder");
			$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_order = $NewOrder WHERE field_id = ".$_GET['fid']);
			$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_fields SET field_order = $OldOrder WHERE field_id = $OtherID");
		}
	}
	
	echo '<div class="wrap">';
	echo '<form name="fsrep-settings" action="#" method="POST">';
	echo '<h2>Custom Fields</h2>';
	echo '<p>&nbsp;</p>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200">Add Field Name</th>
		<th scope="col" class="manage-column" >Add Field Values <span style="font-weight: normal; font-style: italic;">(Leave blank for input fields. Separate values by commas for select boxes.)</span></th>
		</tr>
		</thead>
		<tbody>';
		echo '<tr>';
		echo '<td><input type="text" name="nfieldn" value=""></td>';
		echo '<td><input type="text" name="nfieldv" value=""></td>';
		echo '</tr>';
		echo '</tbody></table>';
	echo '<p>&nbsp;</p>';
	echo '<input type="submit" name="submit" value="Add New Field">';
	echo '</form>';
	echo '<p>&nbsp;</p>';
	echo '<form name="fsrep-settings" action="#" method="POST">';
	echo '<p>&nbsp;</p>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="25">&nbsp;</th>
		<th scope="col" class="manage-column" width="200">Field Name</th>
		<th scope="col" class="manage-column" >Field Values <span style="font-weight: normal; font-style: italic;">(Leave blank for input fields. Separate values by commas for select boxes.)</span></th>
		<th scope="col" class="manage-column" width="75">Search</th>
		<th scope="col" class="manage-column" width="75">Order</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" width="25">&nbsp;</th>
		<th scope="col" class="manage-column" width="200">Field Name</th>
		<th scope="col" class="manage-column" >Field Values</th>
		<th scope="col" class="manage-column" width="75">Search</th>
		<th scope="col" class="manage-column" width="75">Order</th>
		</tr>
		</tfoot>
		<tbody>';
		$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
		$count = count($Fields);
		foreach($Fields as $Fields) {
			$Checked = '';
			if ($Fields->field_search == 1) {
				$Checked = ' checked';
			}
			echo '<tr>';
			echo '<td align="center" valign="middle"><a href="admin.php?page=fsrep_fields&f=del&fid='.$Fields->field_id.'"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
			echo '<td><input type="text" name="n'.$Fields->field_id.'" value="'.$Fields->field_name.'"></td>';
			echo '<td><input type="text" name="v'.$Fields->field_id.'" value="'.$Fields->field_value.'"></td>';
			echo '<td><input type="checkbox" name="c'.$Fields->field_id.'" value="1"'.$Checked.'></td>';
			// ORDERING BUTTONS
			echo '<td align="center" valign="middle">';
			// CHECK TO SEE IF THE BUTTON IS ALREADY FIRST IN LINE
			if ($Fields->field_order == 1) {
				echo "<img src=\"".get_option('home')."/wp-content/plugins/fs-real-estate-plugin/images/btn-mini-up-g.gif\" border=\"0\" alt=\"UP\"> ";
			} else {
				echo "<a href=\"admin.php?page=fsrep_fields&f=up&fid=".$Fields->field_id."\"><img src=\"".get_option('home')."/wp-content/plugins/fs-real-estate-plugin/images/btn-mini-up.gif\" border=\"0\" alt=\"UP\"></a> ";
			}
			// CHECK TO SEE IF THE PRODUCT IS LAST IN LINE
			if ($Fields->field_order == $count) {
				echo "<img src=\"".get_option('home')."/wp-content/plugins/fs-real-estate-plugin/images/btn-mini-down-g.gif\" border=\"0\" alt=\"DOWN\"><br />";
			} else {
				echo "<a href=\"admin.php?page=fsrep_fields&f=down&fid=".$Fields->field_id."\"><img src=\"".get_option('home')."/wp-content/plugins/fs-real-estate-plugin/images/btn-mini-down.gif\" border=\"0\" alt=\"DOWN\"></a><br />";
			}

			echo "</td>";
			echo '</tr>';
		}
		echo '</tbody></table>';
	echo '<p>&nbsp;</p>';
	echo '<input type="submit" name="submit" value="Update Fields">';
	echo '</form>';
	echo '</div>';
 
}
?>