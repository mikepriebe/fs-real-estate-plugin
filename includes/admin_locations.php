<?php
function fsrep_local() {
	global $FSREPconfig,$wpdb;
	
	if (isset($_POST['submit'])) {
		if (isset($_POST['localname']) && isset($_POST['location'])) {
			$LocalURL = fsrep_url_generator($_POST['localname']);
			$LocalType = explode('-',$_POST['location']);
			if ($_POST['location'] == '0') {
				$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_countries (country_name, country_url) VALUES ('".$_POST['localname']."','".$LocalURL."')");
				$AddedType = 'Country';
			} elseif ($LocalType[0] == 'c') {
				$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_provinces (province_name, province_url, country_id) VALUES ('".$_POST['localname']."','".$LocalURL."','".$LocalType[1]."')");
				$AddedType = 'Province';
			} elseif ($LocalType[0] == 'p') {
				$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_cities (city_name, city_url, province_id) VALUES ('".$_POST['localname']."','".$LocalURL."','".$LocalType[1]."')");
				$AddedType = 'City';
			} elseif ($LocalType[0] == 'cty') {
				if (function_exists('fsrep_pro_regions_locations')) { fsrep_pro_regions_locations('add', $_POST['localname'], $LocalType[1]); }
				$AddedType = 'Region';
			}
			echo fsrep_text_translator('FireStorm Real Estate Plugin', $AddedType.'Added Label', 'Your New '.$AddedType.' Has Been Added.');
		} else {
			$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
			foreach ($Countries as $Countries) {
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_countries SET country_name = '".$_POST['c-'.$Countries->country_id]."' WHERE country_id = ".$Countries->country_id);
				$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
				if (count($Provinces) > 0) {
					foreach ($Provinces as $Provinces) {
						$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_provinces SET province_name = '".$_POST['p-'.$Provinces->province_id]."' WHERE province_id = ".$Provinces->province_id);
						$Cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $Provinces->province_id ORDER BY city_name");
						if (count($Cities) > 0) {
							foreach ($Cities as $Cities) {
								$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_cities SET city_name = '".$_POST['cty-'.$Cities->city_id]."' WHERE city_id = ".$Cities->city_id);
							}
						}
					}
				}
			}
			if (function_exists('fsrep_pro_regions_locations')) { fsrep_pro_regions_locations('edit', $_POST, ''); }
		}
	} elseif(isset($_GET['f'])) {
		if ($_GET['f'] == 'del') {
			if ($_GET['type'] == 'c') {
				$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$_GET['lid']);
				$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = ".$_GET['lid']);
			} elseif ($_GET['type'] == 'p') {
				$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$_GET['lid']);
				$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = ".$_GET['lid']);
			} elseif ($_GET['type'] == 'cty') {
				$wpdb->query("DELETE FROM ".$wpdb->prefix."fsrep_cities WHERE city_id = ".$_GET['lid']);
			}
			if (function_exists('fsrep_pro_regions_locations')) { fsrep_pro_regions_locations('del', $_GET['lid'], ''); }
		}
	}
	
	echo '<div class="wrap">';
	echo '<h2>Locations</h2>';
	
	echo '<table border="0" cellspacing="0" cellpadding="0">';
	echo '<tr><td valign="top">';	
	
	echo '<form name="fsrep-local" action="#" method="POST">';
	echo '<table class="widefat page fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add New Location Label', 'Add New Location').'</th>
	<th scope="col" class="manage-column">&nbsp;</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
	<th scope="col" class="manage-column" colspan="2"><input type="submit" name="submit" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add Location Label', 'Add Location').'"></th>
	</tr>
	</tfoot>
	<tbody>';
	echo '<tr>';
	echo '<td><input type="text" name="localname" value=""></td>';
	echo '<td>';
	echo '<select name="location">';
	echo '<option value="0">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'New Country Label', 'New Country').'</option>';
	echo '<option value="0">-----------</option>';
	$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
	foreach ($Countries as $Countries) {
		echo '<option value="c-'.$Countries->country_id.'">'.$Countries->country_name.'</option>';
		$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
		if (count($Provinces) > 0) {
			foreach ($Provinces as $Provinces) {
				echo '<option value="p-'.$Provinces->province_id.'">&nbsp;&nbsp;&nbsp;'.$Provinces->province_name.'</option>';
				if (function_exists('fsrep_pro_regions_locations')) { fsrep_pro_regions_locations('list', $Provinces->province_id, ''); }
			}
		}
	}
	echo '</select> '.fsrep_text_translator('FireStorm Real Estate Plugin', 'Select Parent Location Label', 'Please select a parent Country or State / Province.').'';
	echo '</td>';
	echo '</tr>';
	echo '</tbody></table>';
	echo '</form>';
	
	
	echo '<p>&nbsp;</p>';
	
	
	echo '<form name="fsrep-local" action="#" method="POST">';
	echo '<table class="widefat page fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" class="manage-column" width="35">&nbsp;</th>
	<th scope="col" class="manage-column" width="35">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'ID Label', 'ID').'</th>
	<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Locations Label', 'Locations').'</th>
	<th scope="col" class="manage-column"><input type="submit" name="submit" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Locations Label', 'Update Locations').'"></th>
	</tr>
	</thead>
	<tfoot>
	<tr>
	<th scope="col" class="manage-column" width="35">&nbsp;</th>
	<th scope="col" class="manage-column" width="35">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'ID Label', 'ID').'</th>
	<th scope="col" class="manage-column" width="200">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Locations Label', 'Locations').'</th>
	<th scope="col" class="manage-column"><input type="submit" name="submit" class="button-primary" value="'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Update Locations Label', 'Update Locations').'"></th>
	</tr>
	</tfoot>
	<tbody>';
	$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
	foreach ($Countries as $Countries) {
		echo '<tr>';
		echo '<td><a href="admin.php?page=fsrep_local&f=del&type=c&lid='.$Countries->country_id.'" onClick="return confirm(\''.fsrep_text_translator('FireStorm Real Estate Plugin', 'Remove Country Label', 'Are you sure you want to remove this country along with its provinces and cities?').'\')"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
		echo '<td>'.$Countries->country_id.'</td>';
		echo '<td><input type="text" name="c-'.$Countries->country_id.'" value="'.$Countries->country_name.'" style="width: 155px;"></td>';
		echo '<td>&nbsp;</td>';
		echo '</tr>';
		$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
		if (count($Provinces) > 0) {
			foreach ($Provinces as $Provinces) {
				echo '<tr>';
				echo '<td><a href="admin.php?page=fsrep_local&f=del&type=p&lid='.$Provinces->province_id.'" onClick="return confirm(\''.fsrep_text_translator('FireStorm Real Estate Plugin', 'Remove Province Label', 'Are you sure you want to remove this province along with its cities?').'\')"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
				echo '<td>'.$Provinces->province_id.'</td>';
				echo '<td style="padding-left: 20px;"><input type="text" name="p-'.$Provinces->province_id.'" value="'.$Provinces->province_name.'" style="width: 140px;"></td>';
				echo '<td>&nbsp;</td></tr>';
				$Cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $Provinces->province_id ORDER BY city_name");
				if (count($Cities) > 0) {
					foreach ($Cities as $Cities) {
						echo '<tr><td><a href="admin.php?page=fsrep_local&f=del&type=cty&lid='.$Cities->city_id.'" onClick="return confirm(\''.fsrep_text_translator('FireStorm Real Estate Plugin', 'Remove City Label', 'Are you sure you want to remove this city?').'\')"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
						echo '<td>'.$Cities->city_id.'</td>';
						echo '<td style="padding-left: 40px;"><input type="text" name="cty-'.$Cities->city_id.'" value="'.$Cities->city_name.'" style="width: 120px;"></td>';
						echo '<td>&nbsp;</td></tr>';
						if (function_exists('fsrep_pro_regions_locations')) { fsrep_pro_regions_locations('input', $Cities->city_id, ''); }
					}
				}
			}
		}
	}
	echo '</tbody></table>';
	echo '</form>';

	echo '</td>';
	include('newsbar.php');		
	echo '</tr></table>';
	
	echo '</div>';
 
}
?>