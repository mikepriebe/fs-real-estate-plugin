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
	listing_address_number VARCHAR(255) NOT NULL,
	listing_address_street VARCHAR(255) NOT NULL,
	listing_address_city VARCHAR(255) NOT NULL,
	listing_address_province VARCHAR(255) NOT NULL,
	listing_address_country VARCHAR(255) NOT NULL,
	listing_address_postal VARCHAR(255) NOT NULL,
	listing_long VARCHAR(255) NOT NULL,
	listing_lat VARCHAR(255) NOT NULL,
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
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('3', '".$wpdb->escape('Afghanistan ')."', '".$wpdb->escape('afghanistan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('4', '".$wpdb->escape('Albania ')."', '".$wpdb->escape('albania ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('5', '".$wpdb->escape('Algeria ')."', '".$wpdb->escape('algeria ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('6', '".$wpdb->escape('American Samoa ')."', '".$wpdb->escape('american-samoa ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('7', '".$wpdb->escape('Andorra ')."', '".$wpdb->escape('andorra ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('8', '".$wpdb->escape('Angola ')."', '".$wpdb->escape('angola ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('9', '".$wpdb->escape('Anguilla ')."', '".$wpdb->escape('anguilla ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('10', '".$wpdb->escape('Antarctica ')."', '".$wpdb->escape('antarctica ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('11', '".$wpdb->escape('Antigua And Barbuda ')."', '".$wpdb->escape('antigua-and-barbuda ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('12', '".$wpdb->escape('Argentina ')."', '".$wpdb->escape('argentina ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('13', '".$wpdb->escape('Armenia ')."', '".$wpdb->escape('armenia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('14', '".$wpdb->escape('Aruba ')."', '".$wpdb->escape('aruba ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('15', '".$wpdb->escape('Australia ')."', '".$wpdb->escape('australia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('16', '".$wpdb->escape('Austria ')."', '".$wpdb->escape('austria ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('17', '".$wpdb->escape('Azerbaijan ')."', '".$wpdb->escape('azerbaijan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('18', '".$wpdb->escape('Bahamas, The ')."', '".$wpdb->escape('bahamas-the ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('19', '".$wpdb->escape('Bahrain ')."', '".$wpdb->escape('bahrain ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('20', '".$wpdb->escape('Bangladesh ')."', '".$wpdb->escape('bangladesh ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('21', '".$wpdb->escape('Barbados ')."', '".$wpdb->escape('barbados ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('22', '".$wpdb->escape('Belarus ')."', '".$wpdb->escape('belarus ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('23', '".$wpdb->escape('Belgium ')."', '".$wpdb->escape('belgium ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('24', '".$wpdb->escape('Belize ')."', '".$wpdb->escape('belize ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('25', '".$wpdb->escape('Benin ')."', '".$wpdb->escape('benin ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('26', '".$wpdb->escape('Bermuda ')."', '".$wpdb->escape('bermuda ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('27', '".$wpdb->escape('Bhutan ')."', '".$wpdb->escape('bhutan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('28', '".$wpdb->escape('Bolivia ')."', '".$wpdb->escape('bolivia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('29', '".$wpdb->escape('Bosnia and Herzegovina ')."', '".$wpdb->escape('bosnia-and-herzegovina ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('30', '".$wpdb->escape('Botswana ')."', '".$wpdb->escape('botswana ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('31', '".$wpdb->escape('Bouvet Island ')."', '".$wpdb->escape('bouvet-island ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('32', '".$wpdb->escape('Brazil ')."', '".$wpdb->escape('brazil ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('33', '".$wpdb->escape('British Indian Ocean T... ')."', '".$wpdb->escape('british-indian-ocean-t ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('34', '".$wpdb->escape('Brunei ')."', '".$wpdb->escape('brunei ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('35', '".$wpdb->escape('Bulgaria ')."', '".$wpdb->escape('bulgaria ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('36', '".$wpdb->escape('Burkina Faso ')."', '".$wpdb->escape('burkina-faso ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('37', '".$wpdb->escape('Burundi ')."', '".$wpdb->escape('burundi ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('38', '".$wpdb->escape('Cambodia ')."', '".$wpdb->escape('cambodia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('39', '".$wpdb->escape('Cameroon ')."', '".$wpdb->escape('cameroon ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('40', '".$wpdb->escape('Cape Verde ')."', '".$wpdb->escape('cape-verde ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('41', '".$wpdb->escape('Cayman Islands ')."', '".$wpdb->escape('cayman-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('42', '".$wpdb->escape('Central African Republic ')."', '".$wpdb->escape('central-african-republic ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('43', '".$wpdb->escape('Chad ')."', '".$wpdb->escape('chad ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('44', '".$wpdb->escape('Chile ')."', '".$wpdb->escape('chile ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('45', '".$wpdb->escape('China ')."', '".$wpdb->escape('china ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('46', '".$wpdb->escape('Christmas Island ')."', '".$wpdb->escape('christmas-island ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('47', '".$wpdb->escape('Cocos Islands ')."', '".$wpdb->escape('cocos-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('48', '".$wpdb->escape('Colombia ')."', '".$wpdb->escape('colombia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('49', '".$wpdb->escape('Comoros ')."', '".$wpdb->escape('comoros ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('50', '".$wpdb->escape('Congo ')."', '".$wpdb->escape('congo ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('51', '".$wpdb->escape('Congo, Democratic Repu... ')."', '".$wpdb->escape('congo-democratic-repu ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('52', '".$wpdb->escape('Cook Islands ')."', '".$wpdb->escape('cook-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('53', '".$wpdb->escape('Costa Rica ')."', '".$wpdb->escape('costa-rica ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('54', '".$wpdb->escape('Cote D\'Ivoire ')."', '".$wpdb->escape('cote-divoire ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('55', '".$wpdb->escape('Croatia ')."', '".$wpdb->escape('croatia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('56', '".$wpdb->escape('Cuba ')."', '".$wpdb->escape('cuba ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('57', '".$wpdb->escape('Cyprus ')."', '".$wpdb->escape('cyprus ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('58', '".$wpdb->escape('Czech Republic ')."', '".$wpdb->escape('czech-republic ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('59', '".$wpdb->escape('Denmark ')."', '".$wpdb->escape('denmark ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('60', '".$wpdb->escape('Djibouti ')."', '".$wpdb->escape('djibouti ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('61', '".$wpdb->escape('Dominica ')."', '".$wpdb->escape('dominica ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('62', '".$wpdb->escape('Dominican Republic ')."', '".$wpdb->escape('dominican-republic ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('63', '".$wpdb->escape('East Timor ')."', '".$wpdb->escape('east-timor ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('64', '".$wpdb->escape('Ecuador ')."', '".$wpdb->escape('ecuador ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('65', '".$wpdb->escape('Egypt ')."', '".$wpdb->escape('egypt ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('66', '".$wpdb->escape('El Salvador ')."', '".$wpdb->escape('el-salvador ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('67', '".$wpdb->escape('Equatorial Guinea ')."', '".$wpdb->escape('equatorial-guinea ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('68', '".$wpdb->escape('Eritrea ')."', '".$wpdb->escape('eritrea ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('69', '".$wpdb->escape('Estonia ')."', '".$wpdb->escape('estonia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('70', '".$wpdb->escape('Ethiopia ')."', '".$wpdb->escape('ethiopia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('71', '".$wpdb->escape('Falkland Islands ')."', '".$wpdb->escape('falkland-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('72', '".$wpdb->escape('Faroe Islands ')."', '".$wpdb->escape('faroe-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('73', '".$wpdb->escape('Fiji Islands ')."', '".$wpdb->escape('fiji-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('74', '".$wpdb->escape('Finland ')."', '".$wpdb->escape('finland ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('75', '".$wpdb->escape('France ')."', '".$wpdb->escape('france ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('76', '".$wpdb->escape('French Guiana ')."', '".$wpdb->escape('french-guiana ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('77', '".$wpdb->escape('French Polynesia ')."', '".$wpdb->escape('french-polynesia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('78', '".$wpdb->escape('French Southern Territ... ')."', '".$wpdb->escape('french-southern-territ ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('79', '".$wpdb->escape('Gabon ')."', '".$wpdb->escape('gabon ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('80', '".$wpdb->escape('Gambia, The ')."', '".$wpdb->escape('gambia-the ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('81', '".$wpdb->escape('Georgia ')."', '".$wpdb->escape('georgia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('82', '".$wpdb->escape('Germany ')."', '".$wpdb->escape('germany ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('83', '".$wpdb->escape('Ghana ')."', '".$wpdb->escape('ghana ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('84', '".$wpdb->escape('Gibraltar ')."', '".$wpdb->escape('gibraltar ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('85', '".$wpdb->escape('Greece ')."', '".$wpdb->escape('greece ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('86', '".$wpdb->escape('Greenland ')."', '".$wpdb->escape('greenland ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('87', '".$wpdb->escape('Grenada ')."', '".$wpdb->escape('grenada ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('88', '".$wpdb->escape('Guadeloupe ')."', '".$wpdb->escape('guadeloupe ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('89', '".$wpdb->escape('Guam ')."', '".$wpdb->escape('guam ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('90', '".$wpdb->escape('Guatemala ')."', '".$wpdb->escape('guatemala ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('91', '".$wpdb->escape('Guinea ')."', '".$wpdb->escape('guinea ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('92', '".$wpdb->escape('Guinea-Bissau ')."', '".$wpdb->escape('guinea-bissau ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('93', '".$wpdb->escape('Guyana ')."', '".$wpdb->escape('guyana ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('94', '".$wpdb->escape('Haiti ')."', '".$wpdb->escape('haiti ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('95', '".$wpdb->escape('Heard and McDonald Isl... ')."', '".$wpdb->escape('heard-and-mcdonald-isl ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('96', '".$wpdb->escape('Honduras ')."', '".$wpdb->escape('honduras ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('97', '".$wpdb->escape('Hong Kong S.A.R. ')."', '".$wpdb->escape('hong-kong-sar ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('98', '".$wpdb->escape('Hungary ')."', '".$wpdb->escape('hungary ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('99', '".$wpdb->escape('Iceland ')."', '".$wpdb->escape('iceland ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('100', '".$wpdb->escape('India ')."', '".$wpdb->escape('india ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('101', '".$wpdb->escape('Indonesia ')."', '".$wpdb->escape('indonesia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('102', '".$wpdb->escape('Iran ')."', '".$wpdb->escape('iran ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('103', '".$wpdb->escape('Iraq ')."', '".$wpdb->escape('iraq ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('104', '".$wpdb->escape('Ireland ')."', '".$wpdb->escape('ireland ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('105', '".$wpdb->escape('Israel ')."', '".$wpdb->escape('israel ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('106', '".$wpdb->escape('Italy ')."', '".$wpdb->escape('italy ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('107', '".$wpdb->escape('Jamaica ')."', '".$wpdb->escape('jamaica ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('108', '".$wpdb->escape('Japan ')."', '".$wpdb->escape('japan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('109', '".$wpdb->escape('Jordan ')."', '".$wpdb->escape('jordan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('110', '".$wpdb->escape('Kazakhstan ')."', '".$wpdb->escape('kazakhstan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('111', '".$wpdb->escape('Kenya ')."', '".$wpdb->escape('kenya ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('112', '".$wpdb->escape('Kiribati ')."', '".$wpdb->escape('kiribati ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('113', '".$wpdb->escape('Korea ')."', '".$wpdb->escape('korea ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('114', '".$wpdb->escape('Korea, North ')."', '".$wpdb->escape('korea-north ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('115', '".$wpdb->escape('Kuwait ')."', '".$wpdb->escape('kuwait ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('116', '".$wpdb->escape('Kyrgyzstan ')."', '".$wpdb->escape('kyrgyzstan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('117', '".$wpdb->escape('Laos ')."', '".$wpdb->escape('laos ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('118', '".$wpdb->escape('Latvia ')."', '".$wpdb->escape('latvia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('119', '".$wpdb->escape('Lebanon ')."', '".$wpdb->escape('lebanon ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('120', '".$wpdb->escape('Lesotho ')."', '".$wpdb->escape('lesotho ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('121', '".$wpdb->escape('Liberia ')."', '".$wpdb->escape('liberia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('122', '".$wpdb->escape('Libya ')."', '".$wpdb->escape('libya ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('123', '".$wpdb->escape('Liechtenstein ')."', '".$wpdb->escape('liechtenstein ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('124', '".$wpdb->escape('Lithuania ')."', '".$wpdb->escape('lithuania ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('125', '".$wpdb->escape('Luxembourg ')."', '".$wpdb->escape('luxembourg ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('126', '".$wpdb->escape('Macau S.A.R. ')."', '".$wpdb->escape('macau-sar ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('127', '".$wpdb->escape('Macedonia, Former Yugo... ')."', '".$wpdb->escape('macedonia-former-yugo ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('128', '".$wpdb->escape('Madagascar ')."', '".$wpdb->escape('madagascar ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('129', '".$wpdb->escape('Malawi ')."', '".$wpdb->escape('malawi ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('130', '".$wpdb->escape('Malaysia ')."', '".$wpdb->escape('malaysia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('131', '".$wpdb->escape('Maldives ')."', '".$wpdb->escape('maldives ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('132', '".$wpdb->escape('Mali ')."', '".$wpdb->escape('mali ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('133', '".$wpdb->escape('Malta ')."', '".$wpdb->escape('malta ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('134', '".$wpdb->escape('Marshall Islands ')."', '".$wpdb->escape('marshall-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('135', '".$wpdb->escape('Martinique ')."', '".$wpdb->escape('martinique ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('136', '".$wpdb->escape('Mauritania ')."', '".$wpdb->escape('mauritania ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('137', '".$wpdb->escape('Mauritius ')."', '".$wpdb->escape('mauritius ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('138', '".$wpdb->escape('Mayotte ')."', '".$wpdb->escape('mayotte ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('139', '".$wpdb->escape('Mexico ')."', '".$wpdb->escape('mexico ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('140', '".$wpdb->escape('Micronesia ')."', '".$wpdb->escape('micronesia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('141', '".$wpdb->escape('Moldova ')."', '".$wpdb->escape('moldova ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('142', '".$wpdb->escape('Monaco ')."', '".$wpdb->escape('monaco ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('143', '".$wpdb->escape('Mongolia ')."', '".$wpdb->escape('mongolia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('144', '".$wpdb->escape('Montenegro ')."', '".$wpdb->escape('montenegro ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('145', '".$wpdb->escape('Montserrat ')."', '".$wpdb->escape('montserrat ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('146', '".$wpdb->escape('Morocco ')."', '".$wpdb->escape('morocco ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('147', '".$wpdb->escape('Mozambique ')."', '".$wpdb->escape('mozambique ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('148', '".$wpdb->escape('Myanmar ')."', '".$wpdb->escape('myanmar ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('149', '".$wpdb->escape('Namibia ')."', '".$wpdb->escape('namibia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('150', '".$wpdb->escape('Nauru ')."', '".$wpdb->escape('nauru ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('151', '".$wpdb->escape('Nepal ')."', '".$wpdb->escape('nepal ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('152', '".$wpdb->escape('Netherlands Antilles ')."', '".$wpdb->escape('netherlands-antilles ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('153', '".$wpdb->escape('Netherlands, The ')."', '".$wpdb->escape('netherlands-the ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('154', '".$wpdb->escape('New Caledonia ')."', '".$wpdb->escape('new-caledonia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('155', '".$wpdb->escape('New Zealand ')."', '".$wpdb->escape('new-zealand ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('156', '".$wpdb->escape('Nicaragua ')."', '".$wpdb->escape('nicaragua ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('157', '".$wpdb->escape('Niger ')."', '".$wpdb->escape('niger ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('158', '".$wpdb->escape('Nigeria ')."', '".$wpdb->escape('nigeria ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('159', '".$wpdb->escape('Niue ')."', '".$wpdb->escape('niue ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('160', '".$wpdb->escape('Norfolk Island ')."', '".$wpdb->escape('norfolk-island ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('161', '".$wpdb->escape('Northern Mariana Islands ')."', '".$wpdb->escape('northern-mariana-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('162', '".$wpdb->escape('Norway ')."', '".$wpdb->escape('norway ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('163', '".$wpdb->escape('Oman ')."', '".$wpdb->escape('oman ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('164', '".$wpdb->escape('Pakistan ')."', '".$wpdb->escape('pakistan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('165', '".$wpdb->escape('Palau ')."', '".$wpdb->escape('palau ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('166', '".$wpdb->escape('Panama ')."', '".$wpdb->escape('panama ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('167', '".$wpdb->escape('Papua New Guinea ')."', '".$wpdb->escape('papua-new-guinea ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('168', '".$wpdb->escape('Paraguay ')."', '".$wpdb->escape('paraguay ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('169', '".$wpdb->escape('Peru ')."', '".$wpdb->escape('peru ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('170', '".$wpdb->escape('Philippines ')."', '".$wpdb->escape('philippines ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('171', '".$wpdb->escape('Pitcairn Island ')."', '".$wpdb->escape('pitcairn-island ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('172', '".$wpdb->escape('Poland ')."', '".$wpdb->escape('poland ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('173', '".$wpdb->escape('Portugal ')."', '".$wpdb->escape('portugal ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('174', '".$wpdb->escape('Puerto Rico ')."', '".$wpdb->escape('puerto-rico ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('175', '".$wpdb->escape('Qatar ')."', '".$wpdb->escape('qatar ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('176', '".$wpdb->escape('Reunion ')."', '".$wpdb->escape('reunion ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('177', '".$wpdb->escape('Romania ')."', '".$wpdb->escape('romania ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('178', '".$wpdb->escape('Russia ')."', '".$wpdb->escape('russia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('179', '".$wpdb->escape('Rwanda ')."', '".$wpdb->escape('rwanda ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('180', '".$wpdb->escape('Saint Helena ')."', '".$wpdb->escape('saint-helena ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('181', '".$wpdb->escape('Saint Kitts And Nevis ')."', '".$wpdb->escape('saint-kitts-and-nevis ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('182', '".$wpdb->escape('Saint Lucia ')."', '".$wpdb->escape('saint-lucia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('183', '".$wpdb->escape('Saint Pierre and Miquelon ')."', '".$wpdb->escape('saint-pierre-and-miquelon ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('184', '".$wpdb->escape('Saint Vincent And The ... ')."', '".$wpdb->escape('saint-vincent-and-the- ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('185', '".$wpdb->escape('Samoa ')."', '".$wpdb->escape('samoa ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('186', '".$wpdb->escape('San Marino ')."', '".$wpdb->escape('san-marino ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('187', '".$wpdb->escape('Sao Tome and Principe ')."', '".$wpdb->escape('sao-tome-and-principe ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('188', '".$wpdb->escape('Saudi Arabia ')."', '".$wpdb->escape('saudi-arabia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('189', '".$wpdb->escape('Senegal ')."', '".$wpdb->escape('senegal ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('190', '".$wpdb->escape('Serbia ')."', '".$wpdb->escape('serbia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('191', '".$wpdb->escape('Seychelles ')."', '".$wpdb->escape('seychelles ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('192', '".$wpdb->escape('Sierra Leone ')."', '".$wpdb->escape('sierra-leone ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('193', '".$wpdb->escape('Singapore ')."', '".$wpdb->escape('singapore ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('194', '".$wpdb->escape('Slovakia ')."', '".$wpdb->escape('slovakia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('195', '".$wpdb->escape('Slovenia ')."', '".$wpdb->escape('slovenia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('196', '".$wpdb->escape('Solomon Islands ')."', '".$wpdb->escape('solomon-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('197', '".$wpdb->escape('Somalia ')."', '".$wpdb->escape('somalia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('198', '".$wpdb->escape('South Africa ')."', '".$wpdb->escape('south-africa ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('199', '".$wpdb->escape('South Georgia And The ... ')."', '".$wpdb->escape('south-georgia-and-the- ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('200', '".$wpdb->escape('Spain ')."', '".$wpdb->escape('spain ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('201', '".$wpdb->escape('Sri Lanka ')."', '".$wpdb->escape('sri-lanka ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('202', '".$wpdb->escape('Sudan ')."', '".$wpdb->escape('sudan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('203', '".$wpdb->escape('Suriname ')."', '".$wpdb->escape('suriname ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('204', '".$wpdb->escape('Svalbard And Jan Mayen... ')."', '".$wpdb->escape('svalbard-and-jan-mayen ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('205', '".$wpdb->escape('Swaziland ')."', '".$wpdb->escape('swaziland ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('206', '".$wpdb->escape('Sweden ')."', '".$wpdb->escape('sweden ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('207', '".$wpdb->escape('Switzerland ')."', '".$wpdb->escape('switzerland ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('208', '".$wpdb->escape('Syria ')."', '".$wpdb->escape('syria ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('209', '".$wpdb->escape('Taiwan ')."', '".$wpdb->escape('taiwan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('210', '".$wpdb->escape('Tajikistan ')."', '".$wpdb->escape('tajikistan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('211', '".$wpdb->escape('Tanzania ')."', '".$wpdb->escape('tanzania ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('212', '".$wpdb->escape('Thailand ')."', '".$wpdb->escape('thailand ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('213', '".$wpdb->escape('Togo ')."', '".$wpdb->escape('togo ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('214', '".$wpdb->escape('Tokelau ')."', '".$wpdb->escape('tokelau ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('215', '".$wpdb->escape('Tonga ')."', '".$wpdb->escape('tonga ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('216', '".$wpdb->escape('Trinidad And Tobago ')."', '".$wpdb->escape('trinidad-and-tobago ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('217', '".$wpdb->escape('Tunisia ')."', '".$wpdb->escape('tunisia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('218', '".$wpdb->escape('Turkey ')."', '".$wpdb->escape('turkey ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('219', '".$wpdb->escape('Turkmenistan ')."', '".$wpdb->escape('turkmenistan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('220', '".$wpdb->escape('Turks And Caicos Islands ')."', '".$wpdb->escape('turks-and-caicos-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('221', '".$wpdb->escape('Tuvalu ')."', '".$wpdb->escape('tuvalu ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('222', '".$wpdb->escape('Uganda ')."', '".$wpdb->escape('uganda ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('223', '".$wpdb->escape('Ukraine ')."', '".$wpdb->escape('ukraine ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('224', '".$wpdb->escape('United Arab Emirates ')."', '".$wpdb->escape('united-arab-emirates ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('225', '".$wpdb->escape('United Kingdom ')."', '".$wpdb->escape('united-kingdom ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('226', '".$wpdb->escape('Uruguay ')."', '".$wpdb->escape('uruguay ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('227', '".$wpdb->escape('Uzbekistan ')."', '".$wpdb->escape('uzbekistan ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('228', '".$wpdb->escape('Vanuatu ')."', '".$wpdb->escape('vanuatu ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('229', '".$wpdb->escape('Vatican City State ')."', '".$wpdb->escape('vatican-city-state ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('230', '".$wpdb->escape('Venezuela ')."', '".$wpdb->escape('venezuela ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('231', '".$wpdb->escape('Vietnam ')."', '".$wpdb->escape('vietnam ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('232', '".$wpdb->escape('Virgin Islands ')."', '".$wpdb->escape('virgin-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('233', '".$wpdb->escape('Wallis And Futuna Islands ')."', '".$wpdb->escape('wallis-and-futuna-islands ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('234', '".$wpdb->escape('Yemen ')."', '".$wpdb->escape('yemen ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('235', '".$wpdb->escape('Yugoslavia ')."', '".$wpdb->escape('yugoslavia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('236', '".$wpdb->escape('Zambia ')."', '".$wpdb->escape('zambia ')."'); ");
		$wpdb->query("INSERT INTO ".$table_name." (country_id, country_name, country_url) VALUES ('237', '".$wpdb->escape('Zimbabwe ')."', '".$wpdb->escape('zimbabwe ')."'); ");
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