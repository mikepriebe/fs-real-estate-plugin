<?php
require("../../../wp-config.php"); 

// BACKEND FUNCTIONS
require_once("common_functions.php");

//  SPAM CHECK
if (isset($_POST)) { if (fsrep_spam_check($_POST) == TRUE) { unset($_POST); } }
if (isset($_GET)) { if (fsrep_spam_check($_GET) == TRUE) { unset($_GET); } }


if(isset($_GET['ProvinceID'])){
	if($_GET['ProvinceID'] != '' && is_numeric($_GET['ProvinceID'])){
		$FSREPCities = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_cities WHERE province_id = ".$_GET['ProvinceID']." ORDER BY city_name");
		$FSREPProvince = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE province_id = ".$_GET['ProvinceID']);
		$HTTPREFERER = explode('?', $_SERVER['HTTP_REFERER']);
		if (substr($HTTPREFERER[0], -13) == '/add-listing/') {
			echo "obj.options[obj.options.length] = new Option('Select ".$FSREPconfig['CityLabel'].",'');\n";
		} else {
			echo "obj.options[obj.options.length] = new Option('Show All of ".$FSREPProvince->province_name."','');\n";
		}
		$count = 1;
		foreach ($FSREPCities as $FSREPCities) {
			echo "obj.options[obj.options.length] = new Option('".$FSREPCities->city_name."','".$FSREPCities->city_id."');\n";
			if (is_numeric($_GET['cvalue'])) {
				if ($FSREPCities->city_id == $_GET['cvalue']) {
					echo "obj.options[$count].selected = true;";
				}
			}
			$count++;
		}
	}
}
if(isset($_GET['CountryID'])){
	if ($_GET['CountryID'] != '' && is_numeric($_GET['CountryID'])) {
		$FSREPProvinces = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_provinces WHERE country_id = ".$_GET['CountryID']." ORDER BY province_name");
		$FSREPCountry = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."fsrep_countries WHERE country_id = ".$_GET['CountryID']);
		$count = 1;
		if (isset($HTTPREFERER[0]) && substr($HTTPREFERER[0], -13) == '/add-listing/') {
			echo "obj.options[obj.options.length] = new Option('Select ".$FSREPconfig['ProvinceLabel']."','');\n";
		} else {
			echo "obj.options[obj.options.length] = new Option('Show All of ".$FSREPCountry->country_name."','');\n";
		}
		foreach ($FSREPProvinces as $FSREPProvinces) {
			echo "obj.options[obj.options.length] = new Option('".$FSREPProvinces->province_name."','".$FSREPProvinces->province_id."');\n";
			if (is_numeric($_GET['cvalue'])) {
				if ($FSREPProvinces->province_id == $_GET['cvalue']) {
					echo "obj.options[$count].selected = true;";
				}
			}
			$count++;
		}
	}
}


?>