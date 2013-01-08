<?php
// Custom Extension
if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/custom.php')) { 			require_once(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/custom.php'); }

// FireStorm Extensions
$FSREPExtensions['ProFunctions'] = FALSE; 	if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/profeatures.php')) { 		require_once(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/profeatures.php'); 	$FSREPExtensions['ProFunctions'] = TRUE; 		$FSREPExtensions['ProFunctionsV'] = fsrep_extension_version(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/profeatures.php'); 			$FSREPExtensions['ProFunctionsL'] = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'ProFunctionsL'"); }
$FSREPExtensions['Membership'] = FALSE; 		if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/membership.php')) { 		require_once(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/membership.php'); 		$FSREPExtensions['Membership'] = TRUE; 			$FSREPExtensions['MembershipV'] = fsrep_extension_version(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/membership.php'); 				$FSREPExtensions['MembershipL'] = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'MembershipL'"); }
$FSREPExtensions['Payments'] = FALSE; 		if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/payments.php')) { 		require_once(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/payments.php'); 		$FSREPExtensions['Payments'] = TRUE; 			$FSREPExtensions['PaymentsV'] = fsrep_extension_version(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/payments.php'); 				$FSREPExtensions['PaymentsL'] = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'PaymentsL'"); }
$FSREPExtensions['MailChimp'] = FALSE; 			if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/mailchimp.php')) { 			require_once(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/mailchimp.php'); 		$FSREPExtensions['MailChimp'] = TRUE; 			$FSREPExtensions['MailChimpV'] = fsrep_extension_version(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/extensions/mailchimp.php');  					$FSREPExtensions['MailChimpL'] = $wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'MailChimpL'");}
?>