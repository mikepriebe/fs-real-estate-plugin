<?php
add_action('admin_init', 'editor_admin_init');
 
function editor_admin_init() {
  wp_enqueue_script('word-count');
  wp_enqueue_script('post');
  wp_enqueue_script('editor');
  wp_enqueue_script('media-upload');
}

function fsrep_listings() {
	global $wpdb,$FSREPconfig,$RListingLimit,$FSREPAPI,$user_ID,$FSREPMembers,$FSREPCurrentPermission,$FSREPAdminPermissions;

	//  SPAM CHECK
	if (isset($_POST)) { if (fsrep_spam_check($_POST) == TRUE) { unset($_POST); } }
	if (isset($_GET)) { if (fsrep_spam_check($_GET) == TRUE) { unset($_GET); } }

	$ListingCount = $wpdb->get_var("SELECT COUNT(listing_id) FROM ".$wpdb->prefix."fsrep_listings_to_users WHERE ID = $user_ID");
	$UserInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."users WHERE ID = $user_ID");
	$HType = 'All';
	if (function_exists('fsre_who_listings')) { $HType = fsre_who_listings($user_ID); }
	
	$CurrentURL = get_admin_url().'admin.php?page=fsrep_listings';
	if (!preg_match('/admin.php/i',$CurrentURL)) {
		$CurrentURL .= '?listings';
	}
	
	echo '<div class="wrap">';
	fsrep_listing_manager($CurrentURL);
	echo '</div>';
}
?>