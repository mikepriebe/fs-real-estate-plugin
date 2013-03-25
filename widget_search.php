<?php
class FSREP_Search_Widget extends WP_Widget {
	function __construct() {
		parent::WP_Widget( 'fsrep_search_widget', 'FireStorm Real Estate Search', array( 'description' => 'FireStorm Real Estate listing search form.' ) );
	}

	function widget( $args, $instance ) {
		global $wpdb,$FSREPconfig,$ListingHomeURL;
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['fsrepwstitle'] );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 
		
		echo '<form id="fsrep_search_widget_form" name="fsrep_search_widget_form" action="'.get_option('home').'/'.$ListingHomeURL.'/search/" method="POST">';

		if (!isset($_POST['fsrepw-widget-search-submit'])) {
			if (preg_match('#/search/#i', $_SERVER['REQUEST_URI'])) {
				$_POST = $_SESSION['fsrep-search'];
			}
		} else {
			$_SESSION['fsrep-search'] = $_POST;
		}
		
		if (isset($FSREPconfig['DefaultCountry']) && $FSREPconfig['DefaultCountry'] != '') {
			echo '<input type="hidden" name="fsrepw-search-country" value="'.$FSREPconfig['DefaultCountry'].'">';
		} else {
			echo '<div id="fsrepws-input"><div id="fsrepws-input-title">'.$FSREPconfig['CountryLabel'].':</div>';
			echo '<select id="fsrepw-search-country" name="fsrepw-search-country"  onchange="getFSREPlist(this, \'fsrepw-search-province\', \'CountryID\', \'\')">';
			echo '<option value="">Select '.$FSREPconfig['CountryLabel'].'</option>';
			$FSREPCountries = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_countries ORDER BY country_name");
			foreach ($FSREPCountries as $FSREPCountries) {
				$selected = '';
				if (isset($_POST['fsrepw-search-country']) && $_POST['fsrepw-search-country'] == $FSREPCountries->country_id) {
					$selected = ' selected';
				}
				echo '<option value="'.$FSREPCountries->country_id.'"'.$selected.'>'.$FSREPCountries->country_name.'</option>';
			}
			echo '</select></div>';
		}
		if (isset($FSREPconfig['DefaultProvince']) && $FSREPconfig['DefaultProvince'] != '') {
			echo '<input type="hidden" name="fsrepw-search-province" value="'.$FSREPconfig['DefaultProvince'].'">';
		} else {
			echo '<div id="fsrepws-input"><div id="fsrepws-input-title">'.$FSREPconfig['ProvinceLabel'].':</div>';
			echo '<select id="fsrepw-search-province" name="fsrepw-search-province"  onchange="getFSREPlist(this, \'fsrepw-search-city\', \'ProvinceID\', \'\')">';
			echo '<option value="">- - - - - -</option>';
			echo '</select></div>';
		}
		if (function_exists('fsrep_pro_regions_search_form')) { 
			fsrep_pro_regions_search_form(); 
		} else {
			if (isset($FSREPconfig['DefaultCity']) && $FSREPconfig['DefaultCity'] != '') {
				echo '<input type="hidden" name="fsrepw-search-city" value="'.$FSREPconfig['DefaultCity'].'">';
			} else {
				echo '<div id="fsrepws-input"><div id="fsrepws-input-title">'.$FSREPconfig['CityLabel'].':</div>';
				echo '<select id="fsrepw-search-city" name="fsrepw-search-city">';
				echo '<option value="">- - - - - -</option>';
				echo '</select></div>';
			}
		}
		
		$FSREPPriceRange1 = ''; if (isset($_POST['fsrepw-search-price-range'])) { $FSREPPriceRange1 = $_POST['fsrepw-search-price-range'];}
		$FSREPPriceRange2 = ''; if (isset($_POST['fsrepw-search-price-range2'])) { $FSREPPriceRange2 = $_POST['fsrepw-search-price-range2'];}
		echo '<div id="fsrepws-input"><div id="fsrepws-input-title">Price:</div>';
		echo '<select name="fsrepw-search-price-range">';
		echo fsrep_price_range_print('options',$FSREPPriceRange1);
		echo '</select></div>';
		echo '<div id="fsrepws-input"><div id="fsrepws-input-title">to</div>';
		echo '<select name="fsrepw-search-price-range2">';
		echo fsrep_price_range_print('options',$FSREPPriceRange2);
		echo '</select></div>';
		if (function_exists('fsrep_custom_search')) { fsrep_custom_search(); }
		$SFields = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fsrep_fields WHERE field_search = 1 ORDER BY field_order");
		foreach($SFields as $SFields) {
			echo '<div id="fsrepws-input"><div id="fsrepws-input-title">'.$SFields->field_name.'</div>';
			if ($SFields->field_type == 'selectbox') {
				echo '<select name="field-'.$SFields->field_id.'">';
				echo '<option value="">All</option>';
				$Array = explode(',',$SFields->field_value);
				for($i=0;$i<count($Array);$i++) {
					$selected = '';
					if (isset($_POST['field-'.$SFields->field_id]) && $_POST['field-'.$SFields->field_id] == $Array[$i]) {
						$selected = ' selected';
					}
					echo '<option value="'.$Array[$i].'"'.$selected.'>'.$Array[$i].'</option>';
				}
				echo '</select></div>';
			} elseif ($SFields->field_type == 'radio') {
				$FSREPSCheckboxes = '';
				$FSREPSCheckboxAll = ' checked';
				$Array = explode(',',$SFields->field_value);
				for($i=0;$i<count($Array);$i++) {
					$selected = '';
					if (isset($_POST['field-'.$SFields->field_id]) && $_POST['field-'.$SFields->field_id] == $Array[$i]) {
						$selected = ' checked';
						$FSREPSCheckboxAll = '';
					}
					$FSREPSCheckboxes .= '<input type="radio" style="width: 25px;" name="field-'.$SFields->field_id.'" value="'.$Array[$i].'" '.$selected.'> '.$Array[$i].' &nbsp; &nbsp;<br />';
				}
				echo '<br /><input type="radio" style="width: 25px;" name="field-'.$SFields->field_id.'" value="" '.$FSREPSCheckboxAll.'> All &nbsp; &nbsp;<br />';
				echo $FSREPSCheckboxes;
				echo '</div>';
			} elseif ($SFields->field_type == 'checkbox') {
				$FSREPSCheckboxesUncheck = '';
				$FSREPSCheckboxes = '';
				$FSREPSCheckboxAll = ' checked';
				$Array = explode(',',$SFields->field_value);
				for($i=0;$i<count($Array);$i++) {
					$selected = '';
					if (isset($_POST['field-'.$SFields->field_id.'-'.$i]) && $_POST['field-'.$SFields->field_id.'-'.$i] == $Array[$i]) {
						$selected = ' checked';
						$FSREPSCheckboxAll = '';
					}
					$FSREPSCheckboxes .= '<input type="checkbox" style="width: 25px;" name="field-'.$SFields->field_id.'-'.$i.'" value="'.$Array[$i].'" '.$selected.' onclick="this.form.elements[\'field-'.$SFields->field_id.'\'].checked = false;"> '.$Array[$i].' &nbsp; &nbsp;<br />';
					$FSREPSCheckboxesUncheck .= 'this.form.elements[\'field-'.$SFields->field_id.'-'.$i.'\'].checked = false; ';
				}
				echo '<br /><input type="checkbox" style="width: 25px;" name="field-'.$SFields->field_id.'" value="" '.$FSREPSCheckboxAll.' onclick="'.$FSREPSCheckboxesUncheck.'"> All &nbsp; &nbsp;<br />';
				echo $FSREPSCheckboxes;
				echo '</div>';
			} else {
				echo '<input type="text" name="field-'.$SFields->field_id.'" id="field-'.$SFields->field_id.'" value="'; if (isset($_POST['field-'.$SFields->field_id])) { echo $_POST['field-'.$SFields->field_id]; } echo '">';
				echo '</div>';
			}
		}
		echo '<div id="fsrepws-submit"><input type="submit" name="fsrepw-widget-search-submit" id="fsrepw-widget-search-submit" value="Search Listings"></div>';
		echo '</form>';
		if (isset($FSREPconfig['EnableAdvancedSearch']) && $FSREPconfig['EnableAdvancedSearch'] == 'Yes') {
			echo '<div align="center"><a href="'.get_option('home').'/'.$ListingHomeURL.'/search/">Advanced Search</a></div>';
		}
		
		$FSREPWCountry = TRUE;
		$FSREPWProvince = TRUE;
		$FSREPWCity = TRUE;
		if (isset($FSREPconfig['DefaultCountry']) && $FSREPconfig['DefaultCountry'] != '') {
			$_POST['fsrepw-search-country'] = $FSREPconfig['DefaultCountry'];
			$FSREPWCountry = FALSE;
		}
		if (isset($FSREPconfig['DefaultProvince']) && $FSREPconfig['DefaultProvince'] != '') {
			$_POST['fsrepw-search-province'] = $FSREPconfig['DefaultProvince'];
			$FSREPWProvince = FALSE;
		}
		if (isset($FSREPconfig['DefaultCity']) && $FSREPconfig['DefaultCity'] != '') {
			$_POST['fsrepw-search-city'] = $FSREPconfig['DefaultCity'];
			$FSREPWCity = FALSE;
		}
		if (!isset($_POST['fsrepw-search-province'])) { $_POST['fsrepw-search-province'] = ''; }
		if (!isset($_POST['fsrepw-search-city'])) { $_POST['fsrepw-search-city'] = ''; }
		if (!isset($_POST['fsrepw-search-region'])) { $_POST['fsrepw-search-region'] = ''; }
		if (isset($_POST['fsrepw-search-country']) || isset($_POST['fsrepw-search-province'])) {
			echo '<script type="text/javascript">';
			if ($FSREPWProvince == TRUE) { echo "getFSREPlist('".$_POST['fsrepw-search-country']."', 'fsrepw-search-province', 'CountryID', '".$_POST['fsrepw-search-province']."');"; }
			if ($FSREPWCity == TRUE) { echo "getFSREPlist('".$_POST['fsrepw-search-province']."', 'fsrepw-search-city', 'ProvinceID', '".$_POST['fsrepw-search-city']."');"; }
			if (function_exists('fsrep_pro_regions_search_form')) { echo "getFSREPlist('".$_POST['fsrepw-search-city']."', 'fsrepw-search-region', 'CityID', '".$_POST['fsrepw-search-region']."');"; }
			echo '</script>';
		}
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['fsrepwstitle'] = strip_tags($new_instance['fsrepwstitle']);
		return $instance;
	}

	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'fsrepwstitle' ] );
		}
		else {
			$title = __( 'Search Listings', 'text_domain' );
		}
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id('fsrepwstitle'); ?>"><?php _e('Title:'); ?></label><input class="widefat" id="<?php echo $this->get_field_id('fsrepwstitle'); ?>" name="<?php echo $this->get_field_name('fsrepwstitle'); ?>" type="text" value="<?php echo $title; ?>" /><br />
		</p>
		<?php 
	}

} 

add_action( 'widgets_init', create_function( '', 'register_widget("FSREP_Search_Widget");' ) );
?>