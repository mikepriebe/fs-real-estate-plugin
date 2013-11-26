<?php
/*

Plugin Name: FireStorm Professional Real Estate Plugin
Plugin URI: http://www.firestormplugins.com/plugins/real-estate/
Description: This is a professional WordPress real estate plugin created by FireStorm Plugins.
Author: FireStorm Plugins
Version: 2.7.10
Author URI: http://www.firestormplugins.com/

Copyright (C) 2013 FireStorm Interactive Inc., www.firestorminteractive.com, info@firestorminteractive.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
ini_set("memory_limit","80M");

if (!isset($_SESSION)) {
	session_start();
}
remove_action('wp_head', 'rel_canonical');

// ASSIGN VERSION
global $wpdb, $fsrep_version;
$fsrep_version = "2.7.10";
$FSREPExtensionDir = ABSPATH.'wp-content/uploads/fsrep/extensions/';

// BACKEND FUNCTIONS
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/common_functions.php");

// PLUGIN FILES
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/fsrep_install.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/fsrep_install_sql.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/hooks.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/filters.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/extensions.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/includes/recaptchalib.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/includes/admin_home.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/includes/admin_listings.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/includes/admin_settings.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/includes/admin_fields.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/includes/admin_filters.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/includes/admin_locations.php");


// EXTRA FILES
$FSREPMembers = FALSE; if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/members/admin_plans.php') && file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/members/admin_profile.php')) { require_once("includes/members/admin_plans.php"); require_once("includes/members/admin_profile.php");$FSREPMembers = FALSE; }

// WIDGETS
if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/members/widget_agent.php')) { require_once("includes/members/widget_agent.php"); }
if (file_exists(ABSPATH.'wp-content/plugins/fs-real-estate-plugin/includes/members/widget_flisting.php')) { require_once("includes/members/widget_flisting.php"); }
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/widget_local.php");
require_once(ABSPATH."wp-content/plugins/fs-real-estate-plugin/widget_search.php");

// ACTIVATE DEACTIVATE
register_activation_hook(__FILE__,'fsrep_activate');
register_deactivation_hook( __FILE__, 'fsrep_deactivate' );

include(ABSPATH."wp-content/plugins/fs-real-estate-plugin/define.php");

?>
