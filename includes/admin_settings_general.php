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

	if (isset($_GET['showwelcome'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = 'No' WHERE config_name = 'ShowWelcome'");
	}
	if (isset($_GET['ExtensionUpdateMsg'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = 'No' WHERE config_name = 'ExtensionUpdateMsg'");
	}
	
	
	
	if (isset($_POST['submit'])) {
		if (!$FSREPconfig['FooterLink']) { $FSREPconfig['FooterLink'] = 0; }
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
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['FooterLink'])."' WHERE config_name = 'FooterLink'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['DisablePageSorting'])."' WHERE config_name = 'DisablePageSorting'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['SoldLabel'])."' WHERE config_name = 'SoldLabel'");
		

		$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
		while($dbfsrepconfig = mysql_fetch_array($sql)) {
			$FSREPconfig[$dbfsrepconfig['config_name']] = $dbfsrepconfig['config_value'];
		}
	}
	

	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Like Our Plugin? Label', 'Like Our Plugin?').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<tr>
		<td colspan="2">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Help Support Label', 'Help support our plugin by telling others and giving us positive ratings:').'</td>
		</tr>
		<tr>
		<td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'WordPress Rating Label', 'WordPress Rating').'</td>
		<td><a href="http://wordpress.org/extend/plugins/fs-real-estate-plugin/" target="_blank">'.fsrep_text_translator('FireStorm Real Estate Plugin', '5 Star Rating Label', 'Give us a five star rating on WordPress.org').'</a></td>
		</tr>
		<tr>
		<td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'FaceBook Label', 'FaceBook').'</td>
		<td>
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));</script>
		<div class="fb-like" data-href="http://www.firestormplugins.com/plugins/real-estate/" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true"></div>
		</tr>
		<tr>
		<td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Google Plus Label', 'Google+').'</td>
		<td>
			<!-- Place this tag where you want the +1 button to render -->
			<g:plusone annotation="inline" href="http://www.firestormplugins.com/plugins/real-estate/"></g:plusone>
			
			<!-- Place this render call where appropriate -->
			<script type="text/javascript">
				(function() {
					var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
					po.src = \'https://apis.google.com/js/plusone.js\';
					var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
				})();
			</script>		
		</td>
		</tr>
		<tr>
		<td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Twitter Label', 'Twitter').'</td>
		<td>
		<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.firestormplugins.com/plugins/real-estate/" data-text="Check out this WordPress real estate plugin">Tweet</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		<a href="https://twitter.com/fsweb" class="twitter-follow-button" data-show-count="false">Follow @fsweb</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</td>
		</tr>
		<tr>
		<td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Add Footer Link Label', 'Add Footer Link').'</td>
		<td><input type="checkbox" name="FooterLink" value="1" '; if ($FSREPconfig['FooterLink'] == 1) { echo 'checked'; } echo '></td>
		</tr>
		</tbody></table>';
		
		
		
		
		
		
		
		
		
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'General Settings Label', 'General Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_admin_update_button().'</th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_selectbox('Listings Page', 'ListingsPageID', $FSREPconfig['ListingsPageID'], fsrep_get_pages(), '', '');
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

	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'General Settings Label', 'General Settings').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_admin_update_button().'</th>
		</tr>
		</thead>
		</table>';
?>