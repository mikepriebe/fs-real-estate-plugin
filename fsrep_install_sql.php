<?php

	$table_name = $wpdb->prefix."fsrep_contact_fields";
	$sql = "CREATE TABLE " . $table_name . " (
	field_id INT(11) NOT NULL AUTO_INCREMENT,
	field_name VARCHAR(255) NOT NULL,
	field_value TEXT NOT NULL,
	field_type VARCHAR(255) NOT NULL DEFAULT 'text',
	field_required TEXT NOT NULL,
	field_fixed TEXT NOT NULL,
	field_search TINYINT(1) NOT NULL DEFAULT 0, 
	field_order INT(11) NOT NULL,
	UNIQUE KEY field_id (field_id)
	);";
	dbDelta($sql);
	
	if ($wpdb->get_var("SELECT COUNT(field_id) FROM ".$table_name." ") == 0) {
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_contact_fields (field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order) VALUES (1, 'Name', '', 'text', 1, 0, 0, 1)");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_contact_fields (field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order) VALUES (2, 'Email', '', 'text', 1, 0, 0, 2)");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_contact_fields (field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order) VALUES (3, 'Day Phone Number', '', 'text', 1, 0, 0, 3)");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_contact_fields (field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order) VALUES (4, 'Evening Phone Number', '', 'text', 1, 0, 0, 4)");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_contact_fields (field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order) VALUES (5, 'Best Time to Call', 'Anytime,Morning,Afternoon,Evening', 'selectbox', 1, 0, 0, 5)");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_contact_fields (field_id, field_name, field_value, field_type, field_required, field_fixed, field_search, field_order) VALUES (6, 'Message', '', 'textarea', 1, 0, 0, 6)");
	}


	$table_name = $wpdb->prefix."fsrep_listings";
	$sql = "CREATE TABLE " . $table_name . " (
	listing_id INT(11) NOT NULL AUTO_INCREMENT,
	listing_sold TINYINT(1) NOT NULL DEFAULT 0, 
	listing_last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
	listing_date_added TIMESTAMP NOT NULL, 
	listing_expiry TIMESTAMP NOT NULL, 
	listing_label VARCHAR(255) NOT NULL,
	listing_price VARCHAR(255) NOT NULL,
	listing_price_num DECIMAL(10,2) NOT NULL,
	listing_address_number VARCHAR(255) NOT NULL,
	listing_address_street VARCHAR(255) NOT NULL,
	listing_address_city VARCHAR(255) NOT NULL,
	listing_address_province VARCHAR(255) NOT NULL,
	listing_address_country VARCHAR(255) NOT NULL,
	listing_address_postal VARCHAR(255) NOT NULL,
	listing_long VARCHAR(255) NOT NULL,
	listing_lat VARCHAR(255) NOT NULL,
	listing_zoom INT(11) DEFAULT 16 NOT NULL,
	listing_auto_coords TINYINT(1) DEFAULT 1 NOT NULL,
	listing_description TEXT NOT NULL, 
	listing_virtual_tour VARCHAR(255) NOT NULL,
	listing_slideshow VARCHAR(255) NOT NULL,
	listing_video VARCHAR(255) NOT NULL,
	listing_contact_display VARCHAR(255) NOT NULL,
	listing_contact_name VARCHAR(255) NOT NULL,
	listing_contact_email VARCHAR(255) NOT NULL,
	listing_contact_home_phone VARCHAR(255) NOT NULL,
	listing_contact_cell_phone VARCHAR(255) NOT NULL,
	listing_contact_special_instructions TEXT NOT NULL, 
	listing_contact_form_email VARCHAR(255) NOT NULL,
	listing_visibility TINYINT(1) DEFAULT 1 NOT NULL,
	listing_featured TINYINT(1) DEFAULT 0 NOT NULL,
	UNIQUE KEY listing_id (listing_id)
	);";
	dbDelta($sql);

	$table_name = $wpdb->prefix."fsrep_fields";
	$sql = "CREATE TABLE " . $table_name . " (
	field_id INT(11) NOT NULL AUTO_INCREMENT,
	field_name VARCHAR(255) NOT NULL,
	field_value TEXT NOT NULL,
	field_type VARCHAR(255) NOT NULL DEFAULT 'text',
	field_required TEXT NOT NULL,
	field_fixed TEXT NOT NULL,
	field_search TINYINT(1) NOT NULL DEFAULT 0, 
	field_order INT(11) NOT NULL,
	UNIQUE KEY field_id (field_id)
	);";
	dbDelta($sql);
	
	if ($wpdb->get_var("SELECT COUNT(field_id) FROM ".$wpdb->prefix."fsrep_fields") == 0) {
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_fields (field_id, field_name, field_value, field_search, field_order, field_type) VALUES (1, 'Bedrooms', '1,2,3,4,5,5+', 1, 1, 'selectbox')");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_fields (field_id, field_name, field_value, field_search, field_order, field_type) VALUES (2, 'Bathrooms', '1,1.5,2,2.5,3,3.5,4,4.5,4+', 1, 2, 'selectbox')");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_fields (field_id, field_name, field_value, field_search, field_order, field_type) VALUES (3, 'Property Type', 'House,Apartment,Condo,Commercial,Lot', 1, 3, 'selectbox')");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_fields (field_id, field_name, field_value, field_search, field_order, field_type) VALUES (4, 'Location Details', '', 1, 4, 'text')");
		$wpdb->query("INSERT INTO ".$wpdb->prefix."fsrep_fields (field_id, field_name, field_value, field_search, field_order, field_type) VALUES (5, 'Notes', '', 1, 5, 'text')");
	}

	$table_name = $wpdb->prefix."fsrep_listings_to_fields";
	$sql = "CREATE TABLE " . $table_name . " (
	listing_id INT(11) NOT NULL,
	field_id INT(11) NOT NULL,
	listing_value VARCHAR(255) NOT NULL
	);";
	dbDelta($sql);
	
	$table_name = $wpdb->prefix."fsrep_filters";
	$sql = "CREATE TABLE " . $table_name . " (
	filter_id INT(11) NOT NULL AUTO_INCREMENT,
	filter_name VARCHAR(255) NOT NULL,
	UNIQUE KEY filter_id (filter_id)
	);";
	dbDelta($sql);
	
	$table_name = $wpdb->prefix."fsrep_filters_details";
	$sql = "CREATE TABLE " . $table_name . " (
	filter_id INT(11) NOT NULL,
	field_id INT(11) NOT NULL DEFAULT 0,
	field_values TEXT NOT NULL,
	custom_field VARCHAR(255) NOT NULL
	);";
	dbDelta($sql);
	
	$table_name = $wpdb->prefix."fsrep_listings_to_users";
	$sql = "CREATE TABLE " . $table_name . " (
	listing_id INT(11) NOT NULL,
	ID INT(11) NOT NULL
	);";
	dbDelta($sql);

	$table_name = $wpdb->prefix."fsrep_listings_docs";
	$sql = "CREATE TABLE " . $table_name . " (
	document_id INT(11) NOT NULL AUTO_INCREMENT,
	listing_id INT(11) NOT NULL,
	document_name VARCHAR(255) NOT NULL, 
	UNIQUE KEY document_id (document_id)
	);";
	dbDelta($sql);

	$table_name = $wpdb->prefix."fsrep_provinces";
	$sql = "CREATE TABLE " . $table_name . " (
	province_id INT(11) NOT NULL AUTO_INCREMENT,
	province_name VARCHAR(255) NOT NULL,
	province_url VARCHAR(255) NOT NULL,
	province_long VARCHAR(255) NOT NULL,
	province_lat VARCHAR(255) NOT NULL,
	province_zoom INT(2) NOT NULL,
	country_id INT(11) NOT NULL,
	province_overview TEXT NOT NULL,
	UNIQUE KEY province_id (province_id)
	);";
	dbDelta($sql);
	
	if ($wpdb->get_var("SELECT COUNT(province_id) FROM ".$table_name." ") == 0) {
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('1', '".$wpdb->escape('Alberta')."', '".$wpdb->escape('alberta')."', '2'); ");	//										ID = 1
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('2', '".$wpdb->escape('British Columbia')."', '".$wpdb->escape('british-columbia')."', '2'); "); //						ID = 2
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('3', '".$wpdb->escape('Manitoba')."', '".$wpdb->escape('manitoba')."', '2'); ");	//									ID = 3
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('4', '".$wpdb->escape('New Brunswick')."', '".$wpdb->escape('new-brunswick')."', '2'); ");	//							ID = 4
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('5', '".$wpdb->escape('Newfoundland and Labrador')."', '".$wpdb->escape('newfoundland-and-labrador')."', '2'); ");	//	ID = 5
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('6', '".$wpdb->escape('Northwest Territories')."', '".$wpdb->escape('northwest-territories')."', '2'); ");	//			ID = 6
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('7', '".$wpdb->escape('Nova Scotia')."', '".$wpdb->escape('nova-scotia')."', '2'); ");	//								ID = 7
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('8', '".$wpdb->escape('Nunavut')."', '".$wpdb->escape('nunavut')."', '2'); ");	//										ID = 8
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('9', '".$wpdb->escape('Ontario')."', '".$wpdb->escape('ontario')."', '2'); ");	//										ID = 9
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('10', '".$wpdb->escape('Prince Edward Island')."', '".$wpdb->escape('prince-edward-island')."', '2'); ");	//			ID = 10
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('11', '".$wpdb->escape('Quebec')."', '".$wpdb->escape('quebec')."', '2'); ");	//										ID = 11
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('12', '".$wpdb->escape('Saskatchewan')."', '".$wpdb->escape('saskatchewan')."', '2'); ");	//							ID = 12
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('13', '".$wpdb->escape('Yukon')."', '".$wpdb->escape('yukon')."', '2'); ");	//										ID = 13
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('14', '".$wpdb->escape('Alabama')."', '".$wpdb->escape('alabama')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('15', '".$wpdb->escape('Alaska')."', '".$wpdb->escape('alaska')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('16', '".$wpdb->escape('Arizona')."', '".$wpdb->escape('arizona')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('17', '".$wpdb->escape('Arkansas')."', '".$wpdb->escape('arkansas')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('18', '".$wpdb->escape('California')."', '".$wpdb->escape('california')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('19', '".$wpdb->escape('Colorado')."', '".$wpdb->escape('colorado')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('20', '".$wpdb->escape('Connecticut')."', '".$wpdb->escape('connecticut')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('21', '".$wpdb->escape('Delaware')."', '".$wpdb->escape('delaware')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('22', '".$wpdb->escape('Florida')."', '".$wpdb->escape('florida')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('23', '".$wpdb->escape('Georgia')."', '".$wpdb->escape('georgia')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('24', '".$wpdb->escape('Hawaii')."', '".$wpdb->escape('hawaii')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('25', '".$wpdb->escape('Idaho')."', '".$wpdb->escape('idaho')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('26', '".$wpdb->escape('Illinois')."', '".$wpdb->escape('illinois')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('27', '".$wpdb->escape('Indiana')."', '".$wpdb->escape('indiana')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('28', '".$wpdb->escape('Iowa')."', '".$wpdb->escape('iowa')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('29', '".$wpdb->escape('Kansas')."', '".$wpdb->escape('kansas')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('30', '".$wpdb->escape('Kentucky')."', '".$wpdb->escape('kentucky')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('31', '".$wpdb->escape('Louisiana')."', '".$wpdb->escape('louisiana')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('32', '".$wpdb->escape('Maine')."', '".$wpdb->escape('maine')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('33', '".$wpdb->escape('Maryland')."', '".$wpdb->escape('maryland')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('34', '".$wpdb->escape('Massachusetts')."', '".$wpdb->escape('massachusetts')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('35', '".$wpdb->escape('Michigan')."', '".$wpdb->escape('michigan')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('36', '".$wpdb->escape('Minnesota')."', '".$wpdb->escape('minnesota')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('37', '".$wpdb->escape('Mississippi')."', '".$wpdb->escape('mississippi')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('38', '".$wpdb->escape('Missouri')."', '".$wpdb->escape('missouri')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('39', '".$wpdb->escape('Montana')."', '".$wpdb->escape('montana')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('40', '".$wpdb->escape('Nebraska')."', '".$wpdb->escape('nebraska')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('41', '".$wpdb->escape('Nevada')."', '".$wpdb->escape('nevada')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('42', '".$wpdb->escape('New Hampshire')."', '".$wpdb->escape('new-hampshire')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('43', '".$wpdb->escape('New Jersey')."', '".$wpdb->escape('new-jersey')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('44', '".$wpdb->escape('New Mexico')."', '".$wpdb->escape('new-mexico')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('45', '".$wpdb->escape('New York')."', '".$wpdb->escape('new-york')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('46', '".$wpdb->escape('North Carolina')."', '".$wpdb->escape('north-carolina')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('47', '".$wpdb->escape('North Dakota')."', '".$wpdb->escape('north-dakota')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('48', '".$wpdb->escape('Ohio')."', '".$wpdb->escape('ohio')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('49', '".$wpdb->escape('Oklahoma')."', '".$wpdb->escape('oklahoma')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('50', '".$wpdb->escape('Oregon')."', '".$wpdb->escape('oregon')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('51', '".$wpdb->escape('Pennsylvania')."', '".$wpdb->escape('pennsylvania')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('52', '".$wpdb->escape('Rhode Island')."', '".$wpdb->escape('rhode-island')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('53', '".$wpdb->escape('South Carolina')."', '".$wpdb->escape('south-carolina')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('54', '".$wpdb->escape('South Dakota')."', '".$wpdb->escape('south-dakota')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('55', '".$wpdb->escape('Tennessee')."', '".$wpdb->escape('tennessee')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('56', '".$wpdb->escape('Texas')."', '".$wpdb->escape('texas')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('57', '".$wpdb->escape('Utah')."', '".$wpdb->escape('utah')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('58', '".$wpdb->escape('Vermont')."', '".$wpdb->escape('vermont')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('59', '".$wpdb->escape('Virginia')."', '".$wpdb->escape('virginia')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('60', '".$wpdb->escape('Washington')."', '".$wpdb->escape('washington')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('61', '".$wpdb->escape('West Virginia')."', '".$wpdb->escape('west-virginia')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('62', '".$wpdb->escape('Wisconsin')."', '".$wpdb->escape('wisconsin')."', '1'); ");
		$wpdb->query("INSERT INTO ".$table_name." (province_id, province_name, province_url, country_id) VALUES ('63', '".$wpdb->escape('Wyoming')."', '".$wpdb->escape('wyoming')."', '1'); ");
	}
	
	$table_name = $wpdb->prefix."fsrep_countries";
	$sql = "CREATE TABLE " . $table_name . " (
	country_id INT(11) NOT NULL AUTO_INCREMENT,
	country_name VARCHAR(255) NOT NULL,
	country_url VARCHAR(255) NOT NULL,
	country_long VARCHAR(255) NOT NULL,
	country_lat VARCHAR(255) NOT NULL,
	country_zoom INT(2) NOT NULL,
	country_overview TEXT NOT NULL,
	UNIQUE KEY country_id (country_id)
	);";
	dbDelta($sql);
	
	if ($wpdb->get_var("SELECT COUNT(country_id) FROM ".$table_name." ") == 0) {
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('1', '".$wpdb->escape('United States')."', '".$wpdb->escape('united-states')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('2', '".$wpdb->escape('Canada')."', '".$wpdb->escape('canada')."'); ");
	}
	
	$table_name = $wpdb->prefix."fsrep_cities";
	$sql = "CREATE TABLE " . $table_name . " (
	city_id INT(11) NOT NULL AUTO_INCREMENT,
	city_name VARCHAR(255) NOT NULL,
	city_url VARCHAR(255) NOT NULL,
	province_id INT(11) NOT NULL,
	country_id INT(11) NOT NULL,
	city_long VARCHAR(255) NOT NULL,
	city_lat VARCHAR(255) NOT NULL,
	city_zoom INT(2) NOT NULL,
	city_overview TEXT NOT NULL,
	UNIQUE KEY city_id (city_id)
	);";
	dbDelta($sql);


	$table_name = $wpdb->prefix."fsrep_listings_to_cities";
	$sql = "CREATE TABLE " . $table_name . " (
	listing_id INT(11) NOT NULL,
	city_id INT(11) NOT NULL
	);";
	dbDelta($sql);


	$table_name = $wpdb->prefix."fsrep_listings_pictures";
	$sql = "CREATE TABLE " . $table_name . " (
	picture_id INT(11) NOT NULL AUTO_INCREMENT,
	listing_id INT(11) NOT NULL,
	picture_last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
	picture_date_added TIMESTAMP NOT NULL, 
	picture_name VARCHAR(255) NOT NULL, 
	UNIQUE KEY picture_id (picture_id)
	);";
	dbDelta($sql);


	$table_name = $wpdb->prefix."fsrep_config";
	$sql = "CREATE TABLE " . $table_name . " (
	config_id INT(11) NOT NULL AUTO_INCREMENT,
	config_name VARCHAR(255) NOT NULL,
	config_value VARCHAR(255) NOT NULL,
	UNIQUE KEY config_id (config_id)
	);";
	dbDelta($sql);
	
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'FireStorm API'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'FireStormAPI' WHERE config_id = 1"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'Google Map API'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'GoogleMapAPI' WHERE config_id = 2"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'Listing Type'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'ListingType' WHERE config_id = 3"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'User Listing Limit'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'UserListingLimit' WHERE config_id = 4"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'User Image Limit'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'UserImageLimit' WHERE config_id = 5"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'Map Center Lat'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'MapCenterLat' WHERE config_id = 6"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'Map Center Long'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'MapCenterLong' WHERE config_id = 7"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'Map Center Zoom'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'MapCenterZoom' WHERE config_id = 8"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'Listing Name Display'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'ListingNameDisplay' WHERE config_id = 9"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'Allow Members to List'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'AllowMemberstoList' WHERE config_id = 10"); }
	if ($wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." WHERE config_name = 'Listings Per Page'") > 0) { $wpdb->query("UPDATE ".$table_name." SET config_name = 'ListingsPerPage' WHERE config_id = 11"); }
	
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (1,'".$wpdb->escape('FireStormAPI')."','".$wpdb->escape('')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (2,'".$wpdb->escape('GoogleMapAPI')."','".$wpdb->escape('')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (3,'".$wpdb->escape('ListingType')."','".$wpdb->escape('FSBO')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (4,'".$wpdb->escape('UserListingLimit')."','".$wpdb->escape('1')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (5,'".$wpdb->escape('UserImageLimit')."','".$wpdb->escape('10')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (6,'".$wpdb->escape('MapCenterLat')."','".$wpdb->escape('58')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (7,'".$wpdb->escape('MapCenterLong')."','".$wpdb->escape('-92')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (8,'".$wpdb->escape('MapCenterZoom')."','".$wpdb->escape('3')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (9,'".$wpdb->escape('ListingNameDisplay')."','".$wpdb->escape('listing_label - listing_address_city, listing_address_province')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (10,'".$wpdb->escape('AllowMemberstoList')."','".$wpdb->escape('1')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (11,'".$wpdb->escape('ListingsPerPage')."','".$wpdb->escape('10')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (12,'".$wpdb->escape('Currency')."','".$wpdb->escape('$')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (13,'".$wpdb->escape('EnablereCaptcha')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (14,'".$wpdb->escape('reCaptchaPublicKey')."','".$wpdb->escape('')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (15,'".$wpdb->escape('reCaptchaPrivateKey')."','".$wpdb->escape('')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (16,'".$wpdb->escape('ListingModeration')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (17,'".$wpdb->escape('EnableFeaturedListings')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (18,'".$wpdb->escape('FeaturedListingsCost')."','".$wpdb->escape('0')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (19,'".$wpdb->escape('EnableListingPlans')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (20,'".$wpdb->escape('PlanExpiryNotification')."','".$wpdb->escape('0')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (21,'".$wpdb->escape('HideListingsOnExpiry')."','".$wpdb->escape('Yes')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (22,'".$wpdb->escape('EnableFeaturedAgents')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (23,'".$wpdb->escape('FeaturedAgentsCost')."','".$wpdb->escape('0')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (24,'".$wpdb->escape('PayPalEmailAddress')."','".$wpdb->escape('')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (25,'".$wpdb->escape('PayPalCurrency')."','".$wpdb->escape('USD')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (26,'".$wpdb->escape('ListingReqContactInfo')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (27,'".$wpdb->escape('ContactInfoNote')."','".$wpdb->escape('')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (28,'".$wpdb->escape('DisplayCurrency')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (29,'".$wpdb->escape('CurrencyType')."','".$wpdb->escape('USD')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (30,'".$wpdb->escape('PayPalPaymentType')."','".$wpdb->escape('BuyNow')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (31,'".$wpdb->escape('DisplaySubLocations')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (32,'".$wpdb->escape('CopyAdminOnListingMessages')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (33,'".$wpdb->escape('ListingPriceID')."','".$wpdb->escape('Asking Price:')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (34,'".$wpdb->escape('EnableAdvancedSearch')."','".$wpdb->escape('No')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (35,'".$wpdb->escape('SearchHeader')."','".$wpdb->escape('Map and Search')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (36,'".$wpdb->escape('DefaultMapLocation')."','".$wpdb->escape('')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (37,'".$wpdb->escape('AllowXMLFeed')."','".$wpdb->escape('Yes')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (38,'".$wpdb->escape('EnableBreadcrumbs')."','".$wpdb->escape('Yes')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (39,'".$wpdb->escape('EnableSearchWithin')."','".$wpdb->escape('Yes')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (40,'".$wpdb->escape('EnableCompare')."','".$wpdb->escape('Yes')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (41,'".$wpdb->escape('ListingsOrientation')."','".$wpdb->escape('vertical')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (42,'".$wpdb->escape('ListingsPerLine')."','".$wpdb->escape('50')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (43,'".$wpdb->escape('ListingsPageID')."','".$wpdb->escape('')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (44,'".$wpdb->escape('GoogleMap')."','".$wpdb->escape('1')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (45,'".$wpdb->escape('CountryLabel')."','".$wpdb->escape('Country')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (46,'".$wpdb->escape('ProvinceLabel')."','".$wpdb->escape('State/Prov.')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (47,'".$wpdb->escape('CityLabel')."','".$wpdb->escape('City')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (48,'".$wpdb->escape('FooterLink')."','".$wpdb->escape('0')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (49,'".$wpdb->escape('DisablePageSorting')."','".$wpdb->escape('0')."'); ");
	$wpdb->query("INSERT INTO ".$table_name." (config_id, config_name, config_value) VALUES (50,'".$wpdb->escape('SoldLabel')."','".$wpdb->escape('SOLD!')."'); ");


	$table_name = $wpdb->prefix."fsrep_plans";
	$sql = "CREATE TABLE " . $table_name . " (
	plan_id INT(11) NOT NULL AUTO_INCREMENT,
	plan_name VARCHAR(255) NOT NULL,
	plan_price DECIMAL(8,2) NOT NULL,
	plan_description TEXT NOT NULL,
	plan_duration INT(11) DEFAULT '0' NOT NULL,
	plan_listing_limit INT(11) DEFAULT '0' NOT NULL,
	plan_order INT(11) DEFAULT '0' NOT NULL,
	UNIQUE KEY plan_id (plan_id)
	);";
	dbDelta($sql);

	// UPDATE USERS TABLE TO CONTAIN ADDITIONAL INFORMATION
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD plan INT(11) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD first_name VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD last_name VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD agency VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD address VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD city VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD stateprov VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD zippostal VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD country VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD phone_number VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD cell_number VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD fax_number VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD website VARCHAR(255) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD agent TINYINT(1) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD featured_agent TINYINT(1) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD bio TEXT NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD plan_expiry datetime NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD plan_warned TINYINT(1) NOT NULL;");
	$wpdb->query("ALTER TABLE ".$wpdb->prefix."users ADD send_new_listings TINYINT(1) NOT NULL;");
	
	// LISTING ID FIX
	if ($wpdb->get_var("SELECT config_value FROM ".$wpdb->prefix."fsrep_config WHERE config_name = 'ListingsPageID'") == '') {
		$ListingPageID = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_content = '[fsrep-listings]'");
		$wpdb->query("UPDATE ".$wpdb->prefix."fsrep_config SET config_value = $ListingPageID WHERE config_name = 'ListingsPageID'");
	}
	
	
?>