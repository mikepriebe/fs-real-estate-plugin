<?php

$ShowMap = $FSREPconfig['GoogleMap'];
$fsrepsearchwidgetoptions = get_option('fsrep_search_widget');
if ($fsrepsearchwidgetoptions['fsrep-search-map'] == 'Yes') {
	$ShowMap = FALSE;
}
if ($FSREPconfig['SearchHeader'] == 'Map and Search' || $FSREPconfig['SearchHeader'] == 'Just a Map') {
	$ShowMap = TRUE;
} else {
	$ShowMap = FALSE;
}
if ($FSREPconfig['SearchHeader'] == 'Map and Search' || $FSREPconfig['SearchHeader'] == 'Just the Search') {
	if (isset($_POST['EnableAdvancedSearch'])) {	
		if ($_POST['EnableAdvancedSearch'] == 'Yes') {
			$PageContent .= fsrep_search_box();
		}
	}
}

global $FSREPSearch,$SearchQueryID,$wpdb;

// PRINT LISTINGS
if (isset($FSREPSearch)) {
	$PageContent .= fsrep_listings_display(0, $FSREPSearch, 'search', 0, 0, 0, $ShowMap, 0);
	unset($_SESSION['FSREPSearch']);
}



?>