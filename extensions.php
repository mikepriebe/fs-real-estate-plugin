<?php
// Custom Extension
if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/custom.php')) { require_once(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/custom.php'); }

// FireStorm Extensions
$FSREPExtensions['Membership'] = FALSE;
if (file_exists($FSREPExtensionDir.'membership.php')) {
	$FSREPExtensions['Membership'] = TRUE; 
	$FSREPExtensions['MembershipL'] = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'MembershipL'");
	$FSREPExtensions['MembershipT'] = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'MembershipT'");
	if (strlen($FSREPExtensions['MembershipL']) < 10 && !isset($_POST['submit'])) { fsrep_license_error('Membership Extension'); }
	$FSREPExtensions['MembershipV'] = fsrep_extension_version($FSREPExtensionDir.'membership.php');
	require_once($FSREPExtensionDir.'membership.php');
}

$FSREPExtensions['ProFeatures'] = FALSE;
if (file_exists($FSREPExtensionDir.'profeatures.php')) {
	$FSREPExtensions['ProFeatures'] = TRUE; 
	$FSREPExtensions['ProFeaturesL'] = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'ProFeaturesL'");
	$FSREPExtensions['ProFeaturesT'] = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'ProFeaturesT'");
	if (strlen($FSREPExtensions['ProFeaturesL']) < 10 && !isset($_POST['submit'])) { fsrep_license_error('Pro Version'); }
	$FSREPExtensions['ProFeaturesV'] = fsrep_extension_version($FSREPExtensionDir.'profeatures.php');
	require_once($FSREPExtensionDir.'profeatures.php');
}
?>
