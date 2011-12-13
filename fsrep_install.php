<?php
if(!function_exists('fsrep_install')) {
function fsrep_install () {
	global $wpdb;
	global $fsrep_version;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$table_name = $wpdb->prefix."fsrep_listings";
	if($wpdb->get_var("show tables like '".$table_name."'") != $table_name) {
	
		// ADD PAGES
		if($wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."posts WHERE post_name = 'listings'") == 0) {
			$sql = "INSERT INTO ".$wpdb->prefix."posts (post_author, post_date, post_date_gmt, post_content, post_title, post_status, comment_status, ping_status, post_name, post_modified, post_modified_gmt, post_type) VALUES (1, NOW(), NOW(), '[fsrep-listings]', 'Listings', 'publish', 'open', 'open', 'listings', NOW(), NOW(), 'page')";
			dbDelta($sql);
		}
		
		// ADD DB STRUCTURE
		include('fsrep_install_sql.php');
		
		// ADD FSREP VERSION
		add_option("fsrep_db_version", $fsrep_version);
	} else {
		$installed_ver = get_option( "fsrep_db_version" );
		if( $installed_ver != $fsrep_version ) {
			// UPDATE DB STRUCTURE
			include('fsrep_install_sql.php');
			
			// UPDATE FSREP VERSION
			update_option( "fsrep_db_version", $fsrep_version );
		}
	}
	
	// CREATE DIRECTORIES
	if (!file_exists(ABSPATH.'wp-content/uploads/')) {
		mkdir(ABSPATH."wp-content/uploads", 0777);
	}
	if (!is_dir(ABSPATH."wp-content/uploads/fsrep")) { mkdir(ABSPATH."wp-content/uploads/fsrep", 0777); }
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
}
?>