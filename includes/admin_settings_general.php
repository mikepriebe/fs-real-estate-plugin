<?php
	if (isset($_POST['submit'])) {
		//$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingNameDisplay'])."' WHERE config_name = 'Listing Name Display'");
		//$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['EnablereCaptcha'])."' WHERE config_name = 'EnablereCaptcha'");
		//$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['reCaptchaPublicKey'])."' WHERE config_name = 'reCaptchaPublicKey'");
		//$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['reCaptchaPrivateKey'])."' WHERE config_name = 'reCaptchaPrivateKey'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['Currency'])."' WHERE config_name = 'Currency'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['CurrencyType'])."' WHERE config_name = 'CurrencyType'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingPriceID'])."' WHERE config_name = 'ListingPriceID'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingReqContactInfo'])."' WHERE config_name = 'ListingReqContactInfo'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ContactInfoNote'])."' WHERE config_name = 'ContactInfoNote'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['DisplayCurrency'])."' WHERE config_name = 'DisplayCurrency'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['DisplaySubLocations'])."' WHERE config_name = 'DisplaySubLocations'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['CopyAdminOnListingMessages'])."' WHERE config_name = 'CopyAdminOnListingMessages'");
		//$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['EnableAdvancedSearch'])."' WHERE config_name = 'EnableAdvancedSearch'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['SearchHeader'])."' WHERE config_name = 'SearchHeader'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['AllowXMLFeed'])."' WHERE config_name = 'AllowXMLFeed'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['EnableBreadcrumbs'])."' WHERE config_name = 'EnableBreadcrumbs'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['EnableSearchWithin'])."' WHERE config_name = 'EnableSearchWithin'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['EnableCompare'])."' WHERE config_name = 'EnableCompare'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingsOrientation'])."' WHERE config_name = 'ListingsOrientation'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ListingsPerLine'])."' WHERE config_name = 'ListingsPerLine'");

		$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
		while($dbfsrepconfig = mysql_fetch_array($sql)) {
			$FSREPconfig[$dbfsrepconfig['config_name']] = $dbfsrepconfig['config_value'];
		}
	}
	
echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>General Settings</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column"><input type="submit" name="submit" class="button-primary" value="Update General Settings" style="padding: 3px 8px;"></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>General Settings</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column"><input type="submit" name="submit" class="button-primary" value="Update General Settings" style="padding: 3px 8px;"></th>
		</tr>
		</tfoot>
		<tbody>';
	fsrep_print_admin_input('Currency Symbol', 'Currency', $FSREPconfig['Currency'], 1, '');
	fsrep_print_admin_input('Listing Price Label', 'ListingPriceID', $FSREPconfig['ListingPriceID'], 30, '');
	fsrep_print_admin_input('Currency Type', 'CurrencyType', $FSREPconfig['CurrencyType'], 3, 'Ex. USD, CAD, EUR, etc..');
	fsrep_print_admin_selectbox('Display Currency', 'DisplayCurrency', $FSREPconfig['DisplayCurrency'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Listing Require Contact Information', 'ListingReqContactInfo', $FSREPconfig['ListingReqContactInfo'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_input('ContactInfoNote', 'ContactInfoNote', $FSREPconfig['ContactInfoNote'], 30, 'Display a special notice below the contact details (ex. \'Please mention you saw this listing at....\')');
	fsrep_print_admin_selectbox('Display Locations Below Listings', 'DisplaySubLocations', $FSREPconfig['DisplaySubLocations'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Copy Admin On Listing Messages', 'CopyAdminOnListingMessages', $FSREPconfig['CopyAdminOnListingMessages'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	//fsrep_print_admin_selectbox('Enable Advanced Search', 'EnableAdvancedSearch', $FSREPconfig['EnableAdvancedSearch'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Search Header', 'SearchHeader', $FSREPconfig['SearchHeader'], array('No Map and Search' => 'No Map and Search', 'Map and Search' => 'Map and Search', 'Just a Map' => 'Just a Map', 'Just the Search' => 'Just the Search'), '', '');
	fsrep_print_admin_selectbox('Allow XML Feed', 'AllowXMLFeed', $FSREPconfig['AllowXMLFeed'], array('Yes' => 'Yes', 'No' => 'No'), '', 'Allow the XML listing feed: <a href="'.get_bloginfo('url').'/wp-content/plugins/fs-real-estate-plugin/xml/listingfeed.xml" target="_blank">'.get_bloginfo('url').'/wp-content/plugins/fs-real-estate-plugin/xml/listingfeed.xml</a>');
	fsrep_print_admin_selectbox('Enable Breadcrumbs', 'EnableBreadcrumbs', $FSREPconfig['EnableBreadcrumbs'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Enable "Search Within Location"', 'EnableSearchWithin', $FSREPconfig['EnableSearchWithin'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Enable Listing Compare', 'EnableCompare', $FSREPconfig['EnableCompare'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	fsrep_print_admin_selectbox('Listings Orientation', 'ListingsOrientation', $FSREPconfig['ListingsOrientation'], array('Horizontal Listings' => 'horizontal', 'Vertical Listings' => 'vertical'), '', '');
	fsrep_print_admin_selectbox('Listings Per Line (Horizontal)', 'ListingsPerLine', $FSREPconfig['ListingsPerLine'], array('2' => '50', '3' => '33', '4' => '25', '5' => '20'), '', '');
	echo '</tbody></table><br />';	

	/*
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>reCaptcha</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column"><input type="submit" name="submit" value="Update Settings"></th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_selectbox('Enable reCaptcha', 'EnablereCaptcha', $FSREPconfig['EnablereCaptcha'], array('Yes' => 'Yes', 'No' => 'No'), '', 'reCaptcha stops spam from being sent through contact forms. To use reCaptcha, register for a free account at <a href="http://recaptcha.net/" target="_blank">http://recaptcha.net/</a>');
	fsrep_print_admin_input('reCaptcha Public Key', 'reCaptchaPublicKey', $FSREPconfig['reCaptchaPublicKey'], 20, '');
	fsrep_print_admin_input('reCaptcha Private Key', 'reCaptchaPrivateKey', $FSREPconfig['reCaptchaPrivateKey'], 20, '');
	echo '</tbody></table><br />';
	*/


	/*
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>Default Listing Label</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column"><input type="submit" name="submit" value="Update Settings"></th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_input('Listing Name Display', 'ListingNameDisplay', $FSREPconfig['Listing Name Display'], 30, '<strong>Examples include:</strong><br />listing_label, listing_price, listing_address_number, listing_address_street, listing_address_city, listing_address_province, listing_address_country, listing_address_postal, listing_bedrooms, listing_bathrooms, listing_kitchens, listing_property_type, and text. ');
	echo '</tbody></table><br />';
	*/

?>