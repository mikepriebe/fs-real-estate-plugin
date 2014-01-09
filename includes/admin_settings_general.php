<?php
	function fsrep_get_pages() {
		global $wpdb;
		$Array = array('' => '');
		$Pages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'page' ORDER BY post_title");
		foreach($Pages as $Pages) {
			$Array = array_merge($Array, array($Pages->post_title => $Pages->ID));
		}
		unset($Array['']);
		return $Array;
	}

	if (isset($_POST['submit'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['Currency'])."' WHERE config_name = 'Currency'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['PriceTSeparator'])."' WHERE config_name = 'PriceTSeparator'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['PriceCSeparator'])."' WHERE config_name = 'PriceCSeparator'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['CurrencyType'])."' WHERE config_name = 'CurrencyType'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingPriceID'])."' WHERE config_name = 'ListingPriceID'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ContactInfoNote'])."' WHERE config_name = 'ContactInfoNote'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['DisplayCurrency'])."' WHERE config_name = 'DisplayCurrency'");
		//$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['SearchHeader'])."' WHERE config_name = 'SearchHeader'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['EnableBreadcrumbs'])."' WHERE config_name = 'EnableBreadcrumbs'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['EnableSearchWithin'])."' WHERE config_name = 'EnableSearchWithin'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['EnableCompare'])."' WHERE config_name = 'EnableCompare'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingsOrientation'])."' WHERE config_name = 'ListingsOrientation'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingsPerLine'])."' WHERE config_name = 'ListingsPerLine'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingsPageID'])."' WHERE config_name = 'ListingsPageID'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['DisablePageSorting'])."' WHERE config_name = 'DisablePageSorting'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['SoldLabel'])."' WHERE config_name = 'SoldLabel'");
		

		$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
		while($dbfsrepconfig = mysql_fetch_array($sql)) {
			$FSREPconfig[$dbfsrepconfig['config_name']] = $dbfsrepconfig['config_value'];
		}

		$ListingPageInfo = get_post($FSREPconfig['ListingsPageID']);
		add_rewrite_rule($ListingPageInfo->post_name.'/(.+)','index.php?page_id='.$FSREPconfig['ListingsPageID'].'&LPage=$matches[1]','top');
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	echo '<table class="widefat page fixed" cellspacing="0">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Listing Page Label', 'Listing Page').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_admin_update_button().'</th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_selectbox('Listings Page', 'ListingsPageID', $FSREPconfig['ListingsPageID'], fsrep_get_pages(), '', '');
	echo '</tbody></table>';	
	echo '<table class="widefat page fixed" cellspacing="0">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'General Settings Label', 'General Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_admin_update_button().'</th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_input('Currency Symbol', 'Currency', $FSREPconfig['Currency'], 1, '');
	fsrep_print_admin_input('Price Thousand Separator', 'PriceTSeparator', $FSREPconfig['PriceTSeparator'], 1, '');
	fsrep_print_admin_input('Price Cent Separator', 'PriceCSeparator', $FSREPconfig['PriceCSeparator'], 1, '');
	fsrep_print_admin_input('Listing Price Label', 'ListingPriceID', $FSREPconfig['ListingPriceID'], 30, '');
	fsrep_print_admin_input('Listing Sold Label', 'SoldLabel', $FSREPconfig['SoldLabel'], 30, '');
	fsrep_print_admin_input('Currency Type', 'CurrencyType', $FSREPconfig['CurrencyType'], 3, 'Ex. USD, CAD, EUR, etc..');
	fsrep_print_admin_selectbox('Display Currency', 'DisplayCurrency', $FSREPconfig['DisplayCurrency'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_input('Contact Special Notice', 'ContactInfoNote', $FSREPconfig['ContactInfoNote'], 30, 'Display a special notice below the contact details (ex. \'Please mention you saw this listing at....\')');
	//fsrep_print_admin_selectbox('Search Header', 'SearchHeader', $FSREPconfig['SearchHeader'], array('No Map and Search' => 'No Map and Search', 'Map and Search' => 'Map and Search', 'Just a Map' => 'Just a Map', 'Just the Search' => 'Just the Search'), '', '');
	fsrep_print_admin_selectbox('Enable Breadcrumbs', 'EnableBreadcrumbs', $FSREPconfig['EnableBreadcrumbs'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Disable Page Sorting', 'DisablePageSorting', $FSREPconfig['DisablePageSorting'], array('Yes' => '1', 'No' => '0'), '', '');
	fsrep_print_admin_selectbox('Enable "Search Within Location"', 'EnableSearchWithin', $FSREPconfig['EnableSearchWithin'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Enable Listing Compare', 'EnableCompare', $FSREPconfig['EnableCompare'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Listings Orientation', 'ListingsOrientation', $FSREPconfig['ListingsOrientation'], array('Horizontal Listings' => 'horizontal', 'Vertical Listings' => 'vertical'), '', '');
	fsrep_print_admin_selectbox('Listings Per Line (Horizontal)', 'ListingsPerLine', $FSREPconfig['ListingsPerLine'], array('2' => '50', '3' => '33', '4' => '25', '5' => '20'), '', '');
	echo '</tbody></table>';	

	if (function_exists('fsrep_pro_settings')) { fsrep_pro_settings(); } else { fsrep_pro_settings_disabled(); }

	if (function_exists('fsrep_custom_settings')) { fsrep_custom_settings(); }

	echo '<table class="widefat page fixed" cellspacing="0">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'General Settings Label', 'General Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_admin_update_button().'</th>
		</tr>
		</thead>
		</table>';
?>