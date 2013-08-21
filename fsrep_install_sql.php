<?php
function fsrep_install_sql() {
global $wpdb;
$FSREPTableName = $wpdb->prefix."fsrep_config";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (config_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'config_name', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'config_value', 'TEXT NOT NULL');

fsrep_sql_insert($FSREPTableName, 'config_name', 'FireStormAPI', 'config_name, config_value', "'FireStormAPI', ''");
fsrep_sql_insert($FSREPTableName, 'config_name', 'GoogleMapAPI', 'config_name, config_value', "'GoogleMapAPI', ''");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ListingType', 'config_name, config_value', "'ListingType', 'FSBO'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'MapCenterLat', 'config_name, config_value', "'MapCenterLat', '58'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'MapCenterLong', 'config_name, config_value', "'MapCenterLong', '-92'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'MapCenterZoom', 'config_name, config_value', "'MapCenterZoom', '3'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ListingNameDisplay', 'config_name, config_value', "'ListingNameDisplay', 'listing_label - listing_address_city, listing_address_province'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ListingsPerPage', 'config_name, config_value', "'ListingsPerPage', '10'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'Currency', 'config_name, config_value', "'Currency', '$'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ContactInfoNote', 'config_name, config_value', "'ContactInfoNote', ''");
fsrep_sql_insert($FSREPTableName, 'config_name', 'DisplayCurrency', 'config_name, config_value', "'DisplayCurrency', 'No'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'CurrencyType', 'config_name, config_value', "'CurrencyType', 'USD'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ListingPriceID', 'config_name, config_value', "'ListingPriceID', 'Asking Price:'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'SearchHeader', 'config_name, config_value', "'SearchHeader', 'Map and Search'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'DefaultMapLocation', 'config_name, config_value', "'DefaultMapLocation', ''");
fsrep_sql_insert($FSREPTableName, 'config_name', 'EnableBreadcrumbs', 'config_name, config_value', "'EnableBreadcrumbs', 'Yes'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'EnableSearchWithin', 'config_name, config_value', "'EnableSearchWithin', 'Yes'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'EnableCompare', 'config_name, config_value', "'EnableCompare', 'Yes'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ListingsOrientation', 'config_name, config_value', "'ListingsOrientation', 'vertical'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ListingsPerLine', 'config_name, config_value', "'ListingsPerLine', '50'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ListingsPageID', 'config_name, config_value', "'ListingsPageID', ''");
fsrep_sql_insert($FSREPTableName, 'config_name', 'GoogleMap', 'config_name, config_value', "'GoogleMap', '1'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'FooterLink', 'config_name, config_value', "'FooterLink', '0'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'DisablePageSorting', 'config_name, config_value', "'DisablePageSorting', '0'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'SoldLabel', 'config_name, config_value', "'SoldLabel', 'SOLD!'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'PriceTSeparator', 'config_name, config_value', "'PriceTSeparator', ','");
fsrep_sql_insert($FSREPTableName, 'config_name', 'PriceCSeparator', 'config_name, config_value', "'PriceCSeparator', '.'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'ShowWelcome', 'config_name, config_value', "'ShowWelcome', 'Yes'");
fsrep_sql_insert($FSREPTableName, 'config_name', 'Theme', 'config_name, config_value', "'Theme', 'default'");



$FSREPTableName = $wpdb->prefix."fsrep_search_queries";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (query_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'query_value', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'glong', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'glat', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'gzoom', 'VARCHAR(255) NOT NULL');



$FSREPTableName = $wpdb->prefix."fsrep_contact_fields";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (field_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_name', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_value', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_type', 'VARCHAR(255) NOT NULL DEFAULT "text"');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_required', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_fixed', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_search', 'TINYINT(1) NOT NULL DEFAULT 0');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_order', 'INT(11) NOT NULL');

if ($wpdb->get_var("SELECT COUNT(field_id) FROM $FSREPTableName") == 0) {
	fsrep_sql_insert($FSREPTableName, 'field_id', '1', 'field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order', "1, 'Name', '', 'text', 1, 0, 0, 1");
	fsrep_sql_insert($FSREPTableName, 'field_id', '2', 'field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order', "2, 'Email', '', 'text', 1, 0, 0, 2");
	fsrep_sql_insert($FSREPTableName, 'field_id', '3', 'field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order', "3, 'Day Phone Number', '', 'text', 1, 0, 0, 3");
	fsrep_sql_insert($FSREPTableName, 'field_id', '4', 'field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order', "4, 'Evening Phone Number', '', 'text', 1, 0, 0, 4");
	fsrep_sql_insert($FSREPTableName, 'field_id', '5', 'field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order', "5, 'Best Time to Call', 'Anytime,Morning,Afternoon,Evening', 'selectbox', 1, 0, 0, 5");
	fsrep_sql_insert($FSREPTableName, 'field_id', '6', 'field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order', "6, 'Message', '', 'textarea', 1, 0, 0, 6");
}



$FSREPTableName = $wpdb->prefix."fsrep_listings";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (listing_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_sold', 'TINYINT(1) NOT NULL DEFAULT 0');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_last_updated', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_date_added', 'TIMESTAMP NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_expiry', 'TIMESTAMP NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_label', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_price', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_price_num', 'DECIMAL(10,2) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_address_number', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_address_street', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_address_city', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_address_province', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_address_country', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_address_postal', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_long', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_lat', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_zoom', 'INT(11) DEFAULT 16 NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_auto_coords', 'TINYINT(1) DEFAULT 1 NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_description', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_virtual_tour', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_slideshow', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_video', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_contact_display', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_contact_name', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_contact_email', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_contact_home_phone', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_contact_cell_phone', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_contact_special_instructions', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_contact_form_email', 'VARCHAR(255) NOT NULL');




$FSREPTableName = $wpdb->prefix."fsrep_fields";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (field_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_name', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_value', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_type', 'VARCHAR(255) NOT NULL DEFAULT "text"');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_required', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_fixed', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_search', 'TINYINT(1) NOT NULL DEFAULT 0');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_order', 'INT(11) NOT NULL');
	
if ($wpdb->get_var("SELECT COUNT(field_id) FROM $FSREPTableName") == 0) {
	fsrep_sql_insert($FSREPTableName, 'field_id', '1', 'field_id, field_name, field_value, field_search, field_order, field_type', "1, 'Bedrooms', '1,2,3,4,5,5+', 1, 1, 'selectbox'");
	fsrep_sql_insert($FSREPTableName, 'field_id', '2', 'field_id, field_name, field_value, field_search, field_order, field_type', "2, 'Bathrooms', '1,1.5,2,2.5,3,3.5,4,4.5,4+', 1, 2, 'selectbox'");
	fsrep_sql_insert($FSREPTableName, 'field_id', '3', 'field_id, field_name, field_value, field_search, field_order, field_type', "3, 'Property Type', 'House,Apartment,Condo,Commercial,Lot', 1, 3, 'selectbox'");
	fsrep_sql_insert($FSREPTableName, 'field_id', '4', 'field_id, field_name, field_value, field_search, field_order, field_type', "4, 'Location Details', '', 1, 4, 'text'");
	fsrep_sql_insert($FSREPTableName, 'field_id', '5', 'field_id, field_name, field_value, field_search, field_order, field_type', "5, 'Notes', '', 1, 5, 'text'");
}



$FSREPTableName = $wpdb->prefix."fsrep_listings_to_fields";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (listing_id INT( 11 ) NOT NULL);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_id', 'INT(11) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_value', 'TEXT NOT NULL');




$FSREPTableName = $wpdb->prefix."fsrep_filters";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (filter_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'filter_name', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'filter_map', 'TINYINT(1) NOT NULL DEFAULT 1');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'filter_sorting', 'TINYINT(1) NOT NULL DEFAULT 1');



$FSREPTableName = $wpdb->prefix."fsrep_filters_details";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (filter_id INT( 11 ) NOT NULL);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_id', 'INT(11) NOT NULL DEFAULT 0');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'field_values', 'TEXT NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'custom_field', 'VARCHAR(255) NOT NULL');




$FSREPTableName = $wpdb->prefix."fsrep_listings_to_users";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (listing_id INT( 11 ) NOT NULL);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'ID', 'INT(11) NOT NULL');




$FSREPTableName = $wpdb->prefix."fsrep_listings_docs";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (document_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_id', 'INT(11) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'document_name', 'VARCHAR(255) NOT NULL');





$FSREPTableName = $wpdb->prefix."fsrep_provinces";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (province_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'province_name', "VARCHAR(255) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'province_url', "VARCHAR(255) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'province_long', "VARCHAR(255) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'province_lat', "VARCHAR(255) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'province_zoom', "INT(2) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'country_id', "INT(11) NOT NULL");






if ($wpdb->get_var("SELECT COUNT(province_id) FROM $FSREPTableName") == 0) {
	fsrep_sql_insert($FSREPTableName, 'province_id', '1', 'province_id, province_name, province_url, country_id', "'1', '".$wpdb->escape('Alberta')."', '".$wpdb->escape('alberta')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '2', 'province_id, province_name, province_url, country_id', "'2', '".$wpdb->escape('British Columbia')."', '".$wpdb->escape('british-columbia')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '3', 'province_id, province_name, province_url, country_id', "'3', '".$wpdb->escape('Manitoba')."', '".$wpdb->escape('manitoba')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '4', 'province_id, province_name, province_url, country_id', "'4', '".$wpdb->escape('New Brunswick')."', '".$wpdb->escape('new-brunswick')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '5', 'province_id, province_name, province_url, country_id', "'5', '".$wpdb->escape('Newfoundland and Labrador')."', '".$wpdb->escape('newfoundland-and-labrador')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '6', 'province_id, province_name, province_url, country_id', "'6', '".$wpdb->escape('Northwest Territories')."', '".$wpdb->escape('northwest-territories')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '7', 'province_id, province_name, province_url, country_id', "'7', '".$wpdb->escape('Nova Scotia')."', '".$wpdb->escape('nova-scotia')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '8', 'province_id, province_name, province_url, country_id', "'8', '".$wpdb->escape('Nunavut')."', '".$wpdb->escape('nunavut')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '9', 'province_id, province_name, province_url, country_id', "'9', '".$wpdb->escape('Ontario')."', '".$wpdb->escape('ontario')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '10', 'province_id, province_name, province_url, country_id', "'10', '".$wpdb->escape('Prince Edward Island')."', '".$wpdb->escape('prince-edward-island')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '11', 'province_id, province_name, province_url, country_id', "'11', '".$wpdb->escape('Quebec')."', '".$wpdb->escape('quebec')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '12', 'province_id, province_name, province_url, country_id', "'12', '".$wpdb->escape('Saskatchewan')."', '".$wpdb->escape('saskatchewan')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '13', 'province_id, province_name, province_url, country_id', "'13', '".$wpdb->escape('Yukon')."', '".$wpdb->escape('yukon')."', '2'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '14', 'province_id, province_name, province_url, country_id', "'14', '".$wpdb->escape('Alabama')."', '".$wpdb->escape('alabama')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '15', 'province_id, province_name, province_url, country_id', "'15', '".$wpdb->escape('Alaska')."', '".$wpdb->escape('alaska')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '16', 'province_id, province_name, province_url, country_id', "'16', '".$wpdb->escape('Arizona')."', '".$wpdb->escape('arizona')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '17', 'province_id, province_name, province_url, country_id', "'17', '".$wpdb->escape('Arkansas')."', '".$wpdb->escape('arkansas')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '18', 'province_id, province_name, province_url, country_id', "'18', '".$wpdb->escape('California')."', '".$wpdb->escape('california')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '19', 'province_id, province_name, province_url, country_id', "'19', '".$wpdb->escape('Colorado')."', '".$wpdb->escape('colorado')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '20', 'province_id, province_name, province_url, country_id', "'20', '".$wpdb->escape('Connecticut')."', '".$wpdb->escape('connecticut')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '21', 'province_id, province_name, province_url, country_id', "'21', '".$wpdb->escape('Delaware')."', '".$wpdb->escape('delaware')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '22', 'province_id, province_name, province_url, country_id', "'22', '".$wpdb->escape('Florida')."', '".$wpdb->escape('florida')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '23', 'province_id, province_name, province_url, country_id', "'23', '".$wpdb->escape('Georgia')."', '".$wpdb->escape('georgia')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '24', 'province_id, province_name, province_url, country_id', "'24', '".$wpdb->escape('Hawaii')."', '".$wpdb->escape('hawaii')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '25', 'province_id, province_name, province_url, country_id', "'25', '".$wpdb->escape('Idaho')."', '".$wpdb->escape('idaho')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '26', 'province_id, province_name, province_url, country_id', "'26', '".$wpdb->escape('Illinois')."', '".$wpdb->escape('illinois')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '27', 'province_id, province_name, province_url, country_id', "'27', '".$wpdb->escape('Indiana')."', '".$wpdb->escape('indiana')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '28', 'province_id, province_name, province_url, country_id', "'28', '".$wpdb->escape('Iowa')."', '".$wpdb->escape('iowa')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '29', 'province_id, province_name, province_url, country_id', "'29', '".$wpdb->escape('Kansas')."', '".$wpdb->escape('kansas')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '30', 'province_id, province_name, province_url, country_id', "'30', '".$wpdb->escape('Kentucky')."', '".$wpdb->escape('kentucky')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '31', 'province_id, province_name, province_url, country_id', "'31', '".$wpdb->escape('Louisiana')."', '".$wpdb->escape('louisiana')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '32', 'province_id, province_name, province_url, country_id', "'32', '".$wpdb->escape('Maine')."', '".$wpdb->escape('maine')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '33', 'province_id, province_name, province_url, country_id', "'33', '".$wpdb->escape('Maryland')."', '".$wpdb->escape('maryland')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '34', 'province_id, province_name, province_url, country_id', "'34', '".$wpdb->escape('Massachusetts')."', '".$wpdb->escape('massachusetts')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '35', 'province_id, province_name, province_url, country_id', "'35', '".$wpdb->escape('Michigan')."', '".$wpdb->escape('michigan')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '36', 'province_id, province_name, province_url, country_id', "'36', '".$wpdb->escape('Minnesota')."', '".$wpdb->escape('minnesota')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '37', 'province_id, province_name, province_url, country_id', "'37', '".$wpdb->escape('Mississippi')."', '".$wpdb->escape('mississippi')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '38', 'province_id, province_name, province_url, country_id', "'38', '".$wpdb->escape('Missouri')."', '".$wpdb->escape('missouri')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '39', 'province_id, province_name, province_url, country_id', "'39', '".$wpdb->escape('Montana')."', '".$wpdb->escape('montana')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '40', 'province_id, province_name, province_url, country_id', "'40', '".$wpdb->escape('Nebraska')."', '".$wpdb->escape('nebraska')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '41', 'province_id, province_name, province_url, country_id', "'41', '".$wpdb->escape('Nevada')."', '".$wpdb->escape('nevada')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '42', 'province_id, province_name, province_url, country_id', "'42', '".$wpdb->escape('New Hampshire')."', '".$wpdb->escape('new-hampshire')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '43', 'province_id, province_name, province_url, country_id', "'43', '".$wpdb->escape('New Jersey')."', '".$wpdb->escape('new-jersey')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '44', 'province_id, province_name, province_url, country_id', "'44', '".$wpdb->escape('New Mexico')."', '".$wpdb->escape('new-mexico')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '45', 'province_id, province_name, province_url, country_id', "'45', '".$wpdb->escape('New York')."', '".$wpdb->escape('new-york')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '46', 'province_id, province_name, province_url, country_id', "'46', '".$wpdb->escape('North Carolina')."', '".$wpdb->escape('north-carolina')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '47', 'province_id, province_name, province_url, country_id', "'47', '".$wpdb->escape('North Dakota')."', '".$wpdb->escape('north-dakota')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '48', 'province_id, province_name, province_url, country_id', "'48', '".$wpdb->escape('Ohio')."', '".$wpdb->escape('ohio')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '49', 'province_id, province_name, province_url, country_id', "'49', '".$wpdb->escape('Oklahoma')."', '".$wpdb->escape('oklahoma')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '50', 'province_id, province_name, province_url, country_id', "'50', '".$wpdb->escape('Oregon')."', '".$wpdb->escape('oregon')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '51', 'province_id, province_name, province_url, country_id', "'51', '".$wpdb->escape('Pennsylvania')."', '".$wpdb->escape('pennsylvania')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '52', 'province_id, province_name, province_url, country_id', "'52', '".$wpdb->escape('Rhode Island')."', '".$wpdb->escape('rhode-island')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '53', 'province_id, province_name, province_url, country_id', "'53', '".$wpdb->escape('South Carolina')."', '".$wpdb->escape('south-carolina')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '54', 'province_id, province_name, province_url, country_id', "'54', '".$wpdb->escape('South Dakota')."', '".$wpdb->escape('south-dakota')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '55', 'province_id, province_name, province_url, country_id', "'55', '".$wpdb->escape('Tennessee')."', '".$wpdb->escape('tennessee')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '56', 'province_id, province_name, province_url, country_id', "'56', '".$wpdb->escape('Texas')."', '".$wpdb->escape('texas')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '57', 'province_id, province_name, province_url, country_id', "'57', '".$wpdb->escape('Utah')."', '".$wpdb->escape('utah')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '58', 'province_id, province_name, province_url, country_id', "'58', '".$wpdb->escape('Vermont')."', '".$wpdb->escape('vermont')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '59', 'province_id, province_name, province_url, country_id', "'59', '".$wpdb->escape('Virginia')."', '".$wpdb->escape('virginia')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '60', 'province_id, province_name, province_url, country_id', "'60', '".$wpdb->escape('Washington')."', '".$wpdb->escape('washington')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '61', 'province_id, province_name, province_url, country_id', "'61', '".$wpdb->escape('West Virginia')."', '".$wpdb->escape('west-virginia')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '62', 'province_id, province_name, province_url, country_id', "'62', '".$wpdb->escape('Wisconsin')."', '".$wpdb->escape('wisconsin')."', '1'");
	fsrep_sql_insert($FSREPTableName, 'province_id', '63', 'province_id, province_name, province_url, country_id', "'63', '".$wpdb->escape('Wyoming')."', '".$wpdb->escape('wyoming')."', '1'");
}




$FSREPTableName = $wpdb->prefix."fsrep_countries";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (country_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'country_name', "VARCHAR(255) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'country_url', "VARCHAR(255) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'country_long', "VARCHAR(255) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'country_lat', "VARCHAR(255) NOT NULL");
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'country_zoom', "INT(2) NOT NULL");


if ($wpdb->get_var("SELECT COUNT(country_id) FROM $FSREPTableName") == 0) {
	fsrep_sql_insert($FSREPTableName, 'country_name', 'United States', 'country_id, country_name, country_url', "1, '".$wpdb->escape('United States')."', '".$wpdb->escape('united-states')."'");
	fsrep_sql_insert($FSREPTableName, 'country_name', 'Canada', 'country_id, country_name, country_url', "2, '".$wpdb->escape('Canada')."', '".$wpdb->escape('canada')."'");
	fsrep_sql_insert($FSREPTableName, 'country_name', 'United Kingdom', 'country_id, country_name, country_url', "3, '".$wpdb->escape('United Kingdom')."', '".$wpdb->escape('united-kingdom')."'");
}




$FSREPTableName = $wpdb->prefix."fsrep_cities";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (city_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'city_name', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'city_url', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'province_id', 'INT(11) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'country_id', 'INT(11) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'city_long', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'city_lat', 'VARCHAR(255) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'city_zoom', 'INT(2) NOT NULL');



$FSREPTableName = $wpdb->prefix."fsrep_listings_to_cities";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (listing_id INT( 11 ) NOT NULL);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'city_id', 'INT(11) NOT NULL');




$FSREPTableName = $wpdb->prefix."fsrep_listings_pictures";
$wpdb->query("CREATE TABLE IF NOT EXISTS " . $FSREPTableName . " (picture_id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY);");

fsrep_sql_alter (DB_NAME, $FSREPTableName, 'listing_id', 'INT(11) NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'picture_last_updated', 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'picture_date_added', 'TIMESTAMP NOT NULL');
fsrep_sql_alter (DB_NAME, $FSREPTableName, 'picture_name', 'VARCHAR(255) NOT NULL');

// EXTENSIONS
if (function_exists('fsrep_sql_custom')) { fsrep_sql_custom(); }
if (function_exists('fsrep_sql_pro')) { fsrep_sql_pro(); }
if (function_exists('fsrep_sql_membership')) { fsrep_sql_membership(); }

}
?>