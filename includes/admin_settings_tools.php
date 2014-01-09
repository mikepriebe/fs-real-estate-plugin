<?php
	if (isset($_GET['permalinks'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."options SET option_value = '/%category%/%postname%/' WHERE option_name = 'permalink_structure'");
	}
	if (isset($_GET['showwelcome'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = 'No' WHERE config_name = 'ShowWelcome'");
	}
	if (isset($_GET['repairdb'])) {
		fsrep_install_sql();
	}
	
	if (isset($_POST['submit'])) {
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = '".addslashes($_POST['FSREPDebug'])."' WHERE config_name = 'FSREPDebug'");
		

		$sql = mysql_query("SELECT * FROM ".$wpdb->prefix."fsrep_config");
		while($dbfsrepconfig = mysql_fetch_array($sql)) {
			$FSREPconfig[$dbfsrepconfig['config_name']] = $dbfsrepconfig['config_value'];
		}
	}
	
	echo '<table class="widefat page fixed" cellspacing="0">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Plugin Status Label', 'Plugin Status').'</b></th>
		<th scope="col" class="manage-column" width="250">&nbsp;</th>
		<th scope="col" class="manage-column">&nbsp;</th>
		</tr>
		</thead>
		<tbody>';
			$Pages = array(
		'Listings Page' => '[fsrep-listings]'
	);
	foreach ($Pages as $Title => $Content) {
		if ($wpdb->get_var("SELECT COUNT(post_content) FROM ".$wpdb->prefix."posts WHERE post_content = '$Content' AND post_status = 'publish'") == 0) {
			$PageStatus = '<span style="color: red;">Missing</span>';
			$PageSolution = '<a href="admin.php?page=fsrep_settings&f=tools&pagecheck=true" class="button-primary">Automatically Fix</a>';
		} else {
			$PageStatus = '<span style="color: green;">Found</span>';
			$PageSolution = '';
		}
		echo '<tr><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', $Title.'. Label', $Title).'</td><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', $PageStatus.'. Label', $PageStatus).'</td><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', $PageSolution.' Label', $PageSolution).'</td>';
	}
	$FSREPPermalinkStructure = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = 'permalink_structure'");
	if ($FSREPPermalinkStructure == '') {
		$PermaStatus = '<span style="color: red;">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Invalid Label', 'Invalid').'</span>';
		$PermaSolution = '<a href="admin.php?page=fsrep_settings&f=tools&permalinks=fix" class="button-primary">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Automatically Fix Label', 'Automatically Fix').'</a>';
	} else {
		$PermaStatus = '<span style="color: green;">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Correct Label', 'Correct').'</span>';
		$PermaSolution = '';
	}
	echo '<tr><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Permalinks Structure Label', 'Permalinks Structure').'</td><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', $PageStatus.'. Label', $PageStatus).'</td><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', $PageSolution.' Label', $PageSolution).'</td>';
	if (!file_exists(ABSPATH.'wp-content/uploads/') ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/agents") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/agents/temp") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/small") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/large") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/temp") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/additional") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/additional/small") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/additional/large") ||
		!file_exists(ABSPATH."wp-content/uploads/fsrep/houses/additional/temp")
	 ) {
		echo '<tr><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Directory Structure Label', 'Directory Structure').'</td><td><span style="color: red;">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Invalid Label', 'Invalid').'</span></td><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Directory Fix Label', 'Verify /wp-content/uploads/ exists and is writable. Once confirmed, reactivate the plugin.').'</td>';
	 } else {
		echo '<tr><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Directory Structure Label', 'Directory Structure').'</td><td><span style="color: green;">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Valid Label', 'Valid').'</span></td><td>&nbsp;</td>';
	 }	
	echo '<tr><td>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Repair Database Label', 'Repair Database').'</td><td><a href="admin.php?page=fsrep_settings&f=tools&repairdb" class="button-primary">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Repair Label', 'Repair').'</a></td><td>&nbsp;</td>';
	
	echo '</tbody></table>';
	
	echo '<table class="widefat page fixed" cellspacing="0">
		<thead>
		<tr>
		<th scope="col" class="manage-column" width="200"><b>'.fsrep_text_translator('FireStorm Real Estate Plugin', 'Tools Label', 'Tools').'</b></th>
		<th scope="col" class="manage-column">&nbsp;</th>
		<th scope="col" class="manage-column">'.fsrep_admin_update_button().'</th>
		</tr>
		</thead>
		<tbody>';
	fsrep_print_admin_selectbox('Enable Debugging', 'FSREPDebug', $FSREPconfig['FSREPDebug'], array('Yes' => 'Yes', 'No' => 'No'), '', '');
	echo '</tbody></table>';	
?>