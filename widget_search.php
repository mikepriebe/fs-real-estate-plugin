<?php
function fsrep_search_widget_init() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ) {
		return;
	}
	function fsrep_search_widget($args) {
		global $wpdb,$FSREPconfig;
		extract($args);
		$options = get_option('fsrep_search_widget');
		$fsrep_title = empty($options['title']) ? 'Search Listings' : $options['title'];
		echo $before_widget;
		echo $before_title;
		echo $fsrep_title;
		echo $after_title;
		echo '<form id="fsrep_search_widget_form" name="fsrep_search_widget_form" action="'.get_option('home').'/listings/search/" method="POST">';
		echo '<div id="fsrepws-input"><div id="fsrepws-input-title">Country:</div>';
		echo '<select id="fsrepw-search-country" name="fsrepw-search-country"  onchange="getFSREPlist(this, \'fsrepw-search-province\', \'CountryID\', \'\')">';
		echo '<option value="">Select Country</option>';
		$FSREPCountries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
		foreach ($FSREPCountries as $FSREPCountries) {
			echo '<option value="'.$FSREPCountries->country_id.'">'.$FSREPCountries->country_name.'</option>';
		}
		echo '</select></div>';
		echo '<div id="fsrepws-input"><div id="fsrepws-input-title">State/Prov.:</div>';
		echo '<select id="fsrepw-search-province" name="fsrepw-search-province"  onchange="getFSREPlist(this, \'fsrepw-search-city\', \'ProvinceID\', \'\')">';
		echo '<option value="">- - - - - -</option>';
		echo '</select></div>';
		echo '<div id="fsrepws-input"><div id="fsrepws-input-title">City:</div>';
		echo '<select id="fsrepw-search-city" name="fsrepw-search-city">';
		echo '<option value="">- - - - - -</option>';
		echo '</select></div>';
		echo '<div id="fsrepws-input"><div id="fsrepws-input-title">Price:</div>';
		echo '<select name="fsrepw-search-price-range">';
		echo fsrep_price_range_print('options');
		echo '</select></div>';
		echo '<div id="fsrepws-input"><div id="fsrepws-input-title">to</div>';
		echo '<select name="fsrepw-search-price-range2">';
		echo fsrep_price_range_print('options');
		echo '</select></div>';
		$SFields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields WHERE field_search = 1 ORDER BY field_order");
		foreach($SFields as $SFields) {
			echo '<div id="fsrepws-input"><div id="fsrepws-input-title">'.$SFields->field_name.'</div>';
			if ($SFields->field_type == 'selectbox') {
				echo '<select name="field-'.$SFields->field_id.'">';
				echo '<option value="">All</option>';
				$Array = explode(',',$SFields->field_value);
				for($i=0;$i<count($Array);$i++) {
					echo '<option value="'.$Array[$i].'">'.$Array[$i].'</option>';
				}
				echo '</select></div>';
			} elseif ($SFields->field_type == 'radio') {
				$selected = '';
				echo '<br /><input type="radio" style="width: 25px;" name="field-'.$SFields->field_id.'" value="" '.$selected.'> All &nbsp; &nbsp;<br />';
				$Array = explode(',',$SFields->field_value);
				for($i=0;$i<count($Array);$i++) {
					$selected = '';
					echo '<input type="radio" style="width: 25px;" name="field-'.$SFields->field_id.'" value="'.$Array[$i].'" '.$selected.'> '.$Array[$i].' &nbsp; &nbsp;<br />';
				}
				echo '</div>';
			} elseif ($SFields->field_type == 'checkbox') {
				$selected = '';
				echo '<br /><input type="checkbox" style="width: 25px;" name="field-'.$SFields->field_id.'" value="" '.$selected.'> All &nbsp; &nbsp;<br />';
				$Array = explode(',',$SFields->field_value);
				for($i=0;$i<count($Array);$i++) {
					$selected = '';
					echo '<input type="checkbox" style="width: 25px;" name="field-'.$SFields->field_id.'-'.$i.'" value="'.$Array[$i].'" '.$selected.'> '.$Array[$i].' &nbsp; &nbsp;<br />';
				}
				echo '</div>';
			} else {
				echo '<input type="text" name="field-'.$SFields->field_id.'" id="field-'.$SFields->field_id.'" value="">';
				echo '</div>';
			}
		}
		echo '<div id="fsrepws-submit"><input type="submit" name="fsrepw-widget-search-submit" id="fsrepw-widget-search-submit" value="Search Listings"></div>';
		echo '</form>';
		if ($FSREPconfig['EnableAdvancedSearch'] == 'Yes') {
			echo '<div align="center"><a href="'.get_option('home').'/listings/search/">Advanced Search</a></div>';
		}
		echo $after_widget;
	}
	function fsrep_search_widget_control() {
		$options = get_option('fsrep_search_widget');
		$fsrep_title = htmlspecialchars($options['title'], ENT_QUOTES);
		echo '<div>There are no options for this widget.</div>';
	}
	register_sidebar_widget('FSREP Search Widget', 'fsrep_search_widget');
	register_widget_control('FSREP Search Widget', 'fsrep_search_widget_control');
}
add_action('plugins_loaded', 'fsrep_search_widget_init');
?>