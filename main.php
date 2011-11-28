<?php
/*

Plugin Name: FireStorm Real Estate Plugin
Plugin URI: http://www.firestorminteractive.com/wordpress/real-estate/
Description: This is a WordPress real estate plugin created by Wes Fernley @ FireStorm Interactive Inc..
Author: Wes Fernley
Version: 1.1.1
Author URI: http://www.firestorminteractive.com/

Copyright (C) 2008-2009 FireStorm Interactive Inc., www.firestorminteractive.com, info@firestorminteractive.com

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

session_start();

// ASSIGN VERSION
global $wpdb, $fsrep_version;
$fsrep_version = "1.1.1";

// BACKEND FUNCTIONS
require_once("fsrep_install.php");
require_once("common_functions.php");
require_once("hooks.php");
require_once("filters.php");

// ADMIN PAGES
require_once("includes/admin_home.php");
require_once("includes/admin_house_listings.php");
require_once("includes/admin_settings.php");
require_once("includes/admin_fields.php");
require_once("includes/admin_locations.php");

// WIDGETS
require_once("widget_local.php");
require_once("widget_search.php");

// INSTALL / UPGRADE
register_activation_hook(__FILE__,'fsrep_install');

// CREATE REWRITE RULES
add_action('init', 'fsrep_flush_rewrite_rules');
add_action('generate_rewrite_rules', 'fsrep_add_rewrite_rules');

include("define.php");

?>
