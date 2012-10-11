<?php
function fsrep_filters() {
	global $FSREPconfig,$wpdb;
	
	if (isset($_POST['submit'])) {
		if (isset($_POST['filter_name'])) {
			$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_filters (filter_name, filter_map) VALUES ('".$_POST['filter_name']."', '".$_POST['filter_map']."')");
			$FilterID = $wpdb->get_var("SELECT filter_id FROM ".$wpdb->prefix."fsrep_filters ORDER BY filter_id DESC LIMIT 1");
			$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
			foreach ($Fields as $Fields) {
				if ($_POST['field'.$Fields->field_id] != '' && $_POST['field'.$Fields->field_id] != 'Not Applicable') {
					$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_filters_details (filter_id, field_id, field_values) VALUES ('".$FilterID."', '".$Fields->field_id."', '".$_POST['field'.$Fields->field_id]."')");
				}
			}
		}
	} elseif(isset($_GET['f'])) {
		if ($_GET['f'] == 'del') {
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_filters WHERE filter_id = ".$_GET['fid']);
			$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_filters_details WHERE filter_id = ".$_GET['fid']);
			fsrep_fields_ordering_fix();
		}
	}
	
	echo '<div class="wrap">';
	echo '<form name="fsrep-settings" action="#" method="POST">';
	echo '<h2>Filters and Short Codes</h2>';

	echo '<h3>Preset Short Codes</h3>';
	echo '<p>Short codes allow you to easily display single listings or multiple listings on any WordPress page or post. Simply use the following short code and replace the type, value and map.</p>';
	echo '<p>[fsrep-filter type="listing" map="yes" value="3"]</p>';
	
	echo '<p><strong>Type - </strong>To display multiple listings, set the type to one of the following: <em>country</em>, <em>state</em> or <em>province</em>. To display a single listing, set the type to <em>listing</em>.</p>';
	echo '<p><strong>Value - </strong>The value is the ID number of the location or listing. Location ID\'s can be found on the <a href="admin.php?page=fsrep_local">Locations</a> page. Listing ID\'s can be found on the <a href="admin.php?page=fsrep_listings">Listings</a> page.</p>';
	echo '<p><strong>Map - </strong>Set this value to <em>yes</em> or <em>no</em> to display or hide the Google map.</p>';
	
	echo '<p>&nbsp;</p>';
	
	echo '<h3>Custom Filters</h3>';
	
	echo '<p>Filters allow you to add listings to WordPress pages by copying the filter ID into your post or page content. The filter can require specific details of the listings to be met in order for them to be displayed. These details are taken from the custom input fields you have created.</p>';
	
	echo '<h3>Create Custom Filter</h3>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200">Option</th>
		<th scope="col" class="manage-column" width="300">Values</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" colspan="3"><input type="submit" name="submit" class="button-primary" value="Create Filter" style="padding: 3px 8px;"></th>
		</tr>
		</tfoot>
		<tbody>';
		echo '<tr>';
		echo '<td>Filter Name</td>';
		echo '<td colspan="2"><input type="text" name="filter_name" value=""></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Show Google Map</td>';
		echo '<td colspan="2"><select name="filter_map"><option value="1">Yes</option><option value="0">No</option></select></td>';
		echo '</tr>';
		$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields ORDER BY field_order");
		foreach ($Fields as $Fields) {
			if ($Fields->field_value == '') {
				fsrep_print_admin_input($Fields->field_name, 'field'.$Fields->field_id, '', 35, '');
			} else {
				$FieldArray = array("Not Applicable" => "Not Applicable");
				$Array = explode(',',$Fields->field_value);
				foreach ($Array as $Array) {
					//$AddArray = array($Array[$i] => $Array[$i]);
					//$AddArray = array($Array[$i] => $Array[$i]);
					$AddToArray = array($Array => $Array);
					//$FieldArray = array_merge($FieldArray, $AddToArray);
					$FieldArray = $FieldArray + $AddToArray;
				}
				fsrep_print_admin_selectbox($Fields->field_name, 'field'.$Fields->field_id, '', $FieldArray, '', '');
			}
		}
		
		
		echo '</tbody></table>';
	echo '</form>';
	echo '<p>&nbsp;</p>';
	echo '<h3>Current Custom Filters</h3>';
	echo '<form name="fsrep-filters" action="#" method="POST">';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="25">&nbsp;</th>
		<th scope="col" class="manage-column" width="100">ID</th>
		<th scope="col" class="manage-column" width="200">Filter Name</th>
		<th scope="col" class="manage-column" width="200">Show Map</th>
		<th scope="col" class="manage-column">Filter Values</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" width="25">&nbsp;</th>
		<th scope="col" class="manage-column" width="100">ID</th>
		<th scope="col" class="manage-column" width="200">Filter Name</th>
		<th scope="col" class="manage-column" width="200">Show Map</th>
		<th scope="col" class="manage-column">Filter Values</th>
		</tr>
		</tfoot>
		<tbody>';
		$Filters = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_filters ORDER BY filter_name");
		foreach($Filters as $Filters) {
			if ($Filters->filter_map == 1) { $Filters->filter_map = 'Yes'; } else { $Filters->filter_map = 'No'; }
			echo '<tr>';
			echo '<td align="center" valign="middle"><a href="admin.php?page=fsrep_filters&f=del&fid='.$Filters->filter_id.'"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
			echo '<td>[fsrep-filter-'.$Filters->filter_id.']</td>';
			echo '<td>'.$Filters->filter_name.'</td>';
			echo '<td>'.$Filters->filter_map.'</td>';
			echo '<td>';
			$Fields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_filters_details WHERE filter_id = ".$Filters->filter_id);
			foreach ($Fields as $Fields) {
				$FieldName = $wpdb->get_var("SELECT field_name FROM ".$wpdb->prefix."fsrep_fields WHERE field_id = ".$Fields->field_id);
				echo $FieldName.': '.$Fields->field_values.'<br />';
			}
			
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	echo '</form>';
	echo '</div>';
 
}
?>