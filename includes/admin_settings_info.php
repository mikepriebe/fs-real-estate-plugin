<?php
	if (isset($_GET['showwelcome'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = 'No' WHERE config_name = 'ShowWelcome'");
	}
	if (isset($_GET['ExtensionUpdateMsg'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = 'No' WHERE config_name = 'ExtensionUpdateMsg'");
	}
	
	
	
	if (isset($_POST['submit'])) {
		if (!$FSREPconfig['FooterLink']) { $FSREPconfig['FooterLink'] = 0; }
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['FooterLink'])."' WHERE config_name = 'FooterLink'");		
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ProFeaturesL'])."' WHERE config_name = 'ProFeaturesL'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['MembershipL'])."' WHERE config_name = 'MembershipL'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['ImportExportL'])."' WHERE config_name = 'ImportExportL'");

		$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
		while($dbfsrepconfig = mysql_fetch_array($sql)) {
			$FSREPconfig[$dbfsrepconfig['config_name']] = $dbfsrepconfig['config_value'];
		}

		if (!is_dir(ABSPATH."wp-content/uploads/fsrep/extensions")) { mkdir(ABSPATH."wp-content/uploads/fsrep/extensions", 0755); }
		if ($_FILES['UploadExtension']['name'] != '') {
			if (!preg_match('/php/i', $_FILES['UploadExtension']['name']) && !preg_match('/zip/i', $_FILES['UploadExtension']['name'])) {
				$ExtensionUploadError = 'Extension must be PHP or ZIP format.';
				unset($_FILES['UploadExtension']);
			}
		}
		$WPUploadDir = wp_upload_dir();
		if ($_FILES['UploadExtension']['name'] != '') {
			if (file_exists($WPUploadDir['basedir'].'/fsrep/extensions/'.$_FILES['UploadExtension']['name'])) {
				unlink($WPUploadDir['basedir'].'/fsrep/extensions/'.$_FILES['UploadExtension']['name']);
			}
			$uploaddir = $WPUploadDir['basedir'].'/fsrep/extensions/';
			$uploadfile = $uploaddir.basename($_FILES['UploadExtension']['name']);
			if (move_uploaded_file($_FILES['UploadExtension']['tmp_name'], $uploadfile)) {
				rename($uploadfile, $uploaddir.basename($_FILES['UploadExtension']['name']));
			}
		}
	}
	
	fsrep_print_hidden_input('MAX_FILE_SIZE', '10000000');
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="130"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Like Our Plugin? Label', 'Like Our Plugin?').'</b></th>
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
		<div class="fb-like" data-href="https://www.facebook.com/pages/Firestorm-Plugins/219724038190945" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false"></div>
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
		<a href="https://twitter.com/fsplugins" class="twitter-follow-button" data-show-count="false">Follow @fsplugins</a>
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
		<th scope="col" class="manage-column" width="130"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Extensions Label', 'Extensions').'</b></th>
		<th scope="col" class="manage-column" width="234"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'License Label', 'License').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
		fsrep_print_admin_extension_input('PRO Features', 'ProFeatures', 'profeatures.php', 'http://www.firestormplugins.com/extensions/real-estate/', 'http://www.firestormplugins.com/extensions/pro-features/');
		fsrep_print_admin_extension_input('Memberships', 'Membership', 'membership.php', 'http://www.firestormplugins.com/extensions/real-estate/', 'http://www.firestormplugins.com/extensions/membership/');
		fsrep_print_admin_extension_input('Import/Export', 'ImportExport', 'importexport.php', 'http://www.firestormplugins.com/extensions/real-estate/', 'http://www.firestormplugins.com/extensions/real-estate/');
		fsrep_print_admin_extension_input('Payments', 'Payments', 'payments.php', 'http://www.firestormplugins.com/extensions/real-estate/', 'http://www.firestormplugins.com/extensions/real-estate/');
	echo '</tbody></table>';	
	echo '<table class="widefat page fixed" cellspacing="0" border="1">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="130"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Upload Extension Label', 'Upload Extension').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
		fsrep_print_admin_extension_upload('Upload Extension', 'UploadExtension');
	echo '</tbody></table>';	
?>