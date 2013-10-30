<?php
function fsrep_activate () {
	global $wpdb, $fsrep_version;
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	fsrep_install_sql();
		
	// ADD PAGES
	$Pages = array(
		'Listings' => '[fsrep-listings]'
	);
	fsrep_add_pages($Pages);
					
	// ADD LISTING PAGE ID TO SETTINGS
	if ($wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'ListingsPageID'") == '') {
		$LPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[fsrep-listings]%' AND post_status = 'publish' LIMIT 1");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = $LPageID WHERE config_name = 'ListingsPageID'");
	}
	$FSREPListingPageID = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'ListingsPageID'");
	$FSREPListingPage = get_post($FSREPListingPageID);
	add_rewrite_rule($FSREPListingPage->post_name.'/(.+)','index.php?page_id='.$FSREPListingPageID.'&LPage=$matches[1]','top');
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
	
	if(!get_option("fsrep_db_version")) {
		add_option("fsrep_db_version", $fsrep_version);
	} else {
		$installed_ver = get_option( "fsrep_db_version" );
		update_option( "fsrep_db_version", $fsrep_version );
	}
	
	// CREATE DIRECTORIES
	if (!file_exists(ABSPATH.'wp-content/uploads/')) {
		mkdir(ABSPATH."wp-content/uploads", 0777);
	}
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep")) { mkdir(ABSPATH."wp-content/uploads/fsrep", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/extensions")) { mkdir(ABSPATH."wp-content/uploads/fsrep/extensions", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/themes")) { mkdir(ABSPATH."wp-content/uploads/fsrep/themes", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/small")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/small", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/medium")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/medium", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/large")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/large", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/temp")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/temp", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/docs")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/docs", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/additional")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/additional", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/additional/small")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/additional/small", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/additional/medium")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/additional/medium", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/additional/large")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/additional/large", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/houses/additional/temp")) { mkdir(ABSPATH."wp-content/uploads/fsrep/houses/additional/temp", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/agents")) { mkdir(ABSPATH."wp-content/uploads/fsrep/agents", 0777); }
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep/agents/temp")) { mkdir(ABSPATH."wp-content/uploads/fsrep/agents/temp", 0777); }
}

?>