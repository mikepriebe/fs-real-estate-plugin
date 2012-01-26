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
			}
			echo 'Your New '.$AddedType.' Has Been Added.';
		} else {
			$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
			foreach ($Countries as $Countries) {
				$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_countries SET country_name = '".$_POST['c-'.$Countries->country_id]."', country_overview = '".addslashes($_POST['covr-'.$Countries->country_id])."' WHERE country_id = ".$Countries->country_id);
				$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
				if (count($Provinces) > 0) {
					foreach ($Provinces as $Provinces) {
						$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_provinces SET province_name = '".$_POST['p-'.$Provinces->province_id]."', province_overview = '".addslashes($_POST['povr-'.$Provinces->province_id])."' WHERE province_id = ".$Provinces->province_id);
						$Cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $Provinces->province_id ORDER BY city_name");
						if (count($Cities) > 0) {
							foreach ($Cities as $Cities) {
								$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_cities SET city_name = '".$_POST['cty-'.$Cities->city_id]."', city_overview = '".addslashes($_POST['ctyovr-'.$Cities->city_id])."' WHERE city_id = ".$Cities->city_id);
							}
						}
					}
				}
			}
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
		}
	}
	
	echo '<div class="wrap">';
	echo '<form name="fsrep-local" action="#" method="POST">';
	echo '<h2>Locations</h2>';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
	<thead>
	<tr>
	<th scope="col" class="manage-column" width="200">Add New Location</th>
	<th scope="col" class="manage-column">&nbsp;</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
	<th scope="col" class="manage-column" colspan="2"><input type="submit" name="submit" class="button-primary" value="Add Location" style="padding: 3px 8px;"></th>
	</tr>
	</tfoot>
	<tbody>';
	echo '<tr>';
	echo '<td><input type="text" name="localname" value=""></td>';
	echo '<td>';
	echo '<select name="location">';
	echo '<option value="0">New Country</option>';
	echo '<option value="0">-----------</option>';
	$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
	foreach ($Countries as $Countries) {
		echo '<option value="c-'.$Countries->country_id.'">'.$Countries->country_name.'</option>';
		$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
		if (count($Provinces) > 0) {
			foreach ($Provinces as $Provinces) {
				echo '<option value="p-'.$Provinces->province_id.'">&nbsp;&nbsp;&nbsp;'.$Provinces->province_name.'</option>';
			}
		}
	}
	echo '</select> Please select a parent Country or State / Province.';
	echo '</td>';
	echo '</tr>';
	echo '</tbody></table>';
	echo '</form>';
	
	
	echo '<p>&nbsp;</p>';
	
	
	echo '<form name="fsrep-local" action="#" method="POST">';
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
	<thead>
	<tr>
	<th scope="col" class="manage-column" width="35">&nbsp;</th>
	<th scope="col" class="manage-column" width="35">ID</th>
	<th scope="col" class="manage-column" width="200">Locations</th>
	<th scope="col" class="manage-column">Overview &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" class="button-primary" value="Update Locations" style="padding: 3px 8px;"></th>
	</tr>
	</thead>
	<tfoot>
	<tr>
	<th scope="col" class="manage-column" width="35">&nbsp;</th>
	<th scope="col" class="manage-column" width="35">ID</th>
	<th scope="col" class="manage-column" width="200">Locations</th>
	<th scope="col" class="manage-column">Overview &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" class="button-primary" value="Update Locations" style="padding: 3px 8px;"></th>
	</tr>
	</tfoot>
	<tbody>';
	$Countries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
	foreach ($Countries as $Countries) {
		echo '<tr>';
		echo '<td style="background: #EFEFEF;"><a href="admin.php?page=fsrep_local&f=del&type=c&lid='.$Countries->country_id.'" onClick="return confirm(\'Are you sure you want to remove this country along with its provinces and cities?\')"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
		echo '<td style="background: #EFEFEF;">'.$Countries->country_id.'</td>';
		echo '<td style="background: #EFEFEF;"><input type="text" name="c-'.$Countries->country_id.'" value="'.$Countries->country_name.'" style="width: 155px;"></td>';
		echo '<td style="background: #EFEFEF;"><input type="text" name="covr-'.$Countries->country_id.'" value="'.stripslashes($Countries->country_overview).'" style="width: 300px;"></td>';
		echo '</tr>';
		$Provinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = $Countries->country_id ORDER BY province_name");
		if (count($Provinces) > 0) {
			foreach ($Provinces as $Provinces) {
				echo '<tr>';
				echo '<td style="background: #F5F5F5;"><a href="admin.php?page=fsrep_local&f=del&type=p&lid='.$Provinces->province_id.'" onClick="return confirm(\'Are you sure you want to remove this province along with its cities?\')"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
				echo '<td style="background: #F5F5F5;">'.$Provinces->province_id.'</td>';
				echo '<td style="background: #F5F5F5; padding-left: 20px;"><input type="text" name="p-'.$Provinces->province_id.'" value="'.$Provinces->province_name.'" style="width: 140px; color: #555555;"></td>';
				echo '<td style="background: #F5F5F5;"><input type="text" name="povr-'.$Provinces->province_id.'" value="'.stripslashes($Provinces->province_overview).'" style="width: 300px; color: #555555;"></td></tr>';
				$Cities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = $Provinces->province_id ORDER BY city_name");
				if (count($Cities) > 0) {
					foreach ($Cities as $Cities) {
						echo '<tr><td><a href="admin.php?page=fsrep_local&f=del&type=cty&lid='.$Cities->city_id.'" onClick="return confirm(\'Are you sure you want to remove this city?\')"><img src="'.get_option('home').'/wp-content/plugins/fs-real-estate-plugin/images/cart-x.png" border="0" alt="X"></a></td>';
						echo '<td>'.$Cities->city_id.'</td>';
						echo '<td style="padding-left: 40px;"><input type="text" name="cty-'.$Cities->city_id.'" value="'.$Cities->city_name.'" style="width: 120px; color: #888888;"></td>';
						echo '<td><input type="text" name="ctyovr-'.$Cities->city_id.'" value="'.stripslashes($Cities->city_overview).'" style="width: 300px; color: #888888;"></td></tr>';
					}
				}
			}
		}
	}
	echo '</tbody></table>';
	echo '</form>';
	echo '</div>';
 
}
?>