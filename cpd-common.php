<?php

require_once(dirname(__FILE__) . "/cpd-area-options.php");

$results_per_page_options = array(
	"5", "10", "20", "25", "50", "100"
);

function cpd_sector_options($sectors, $all = false) { 
	if(!is_array($sectors)) {
		$sectors = array();
	}

	// Build sectors options
	// [TODO] Should gather this from a GetSectors SOAP API call
	$sector_options = array(
		"O" => "Offices",
		"S" => "Shops",
		"R" => "Restaurant/Takeaway",
		"E" => "Education",
		"H" => "Medical",
	);
	if($all) {
		$sector_options = array(
			"O" => "Offices",
			"SO" => "Serviced Office",
			"S" => "Shops",
			"I" => "Industrial",
			"BU" => "Business Units",
			"R" => "Restaurant/Takeaway",
			"PU" => "Pubs",
			"L" => "Leisure",
			"W" => "Retail Warehousing",
			"X" => "Showrooms",
			"M" => "Motor Related",
			"C" => "Mixed/Commercial",
			"H" => "Medical",
			"G" => "Studio/Gallery",
			"AC" => "Arts/Crafts",
			"U" => "Live/Work Unit",
			"E" => "Education",
			"A" => "Storage",
			"B" => "Land/Site",
			"Z" => "Hall/Misc",
			"GC" => "Garden Centers",
		);
	}
	foreach($sector_options as $key => $value) {
		$selected = (in_array($key, $sectors) ? "selected=\"selected\"" : "");
		$sectoroptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $sectoroptions;
}

function cpd_area_options($areas) {
	if(!is_array($areas)) {
		$areas = array();
	}

	// Add options for perpage pulldown
	global $cpd_area_options;
	$areaoptions = "";
	foreach($cpd_area_options as $key => $value) {
		$selected = (in_array($key, $areas) ? "selected=\"selected\"" : "");
		$areaoptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $areaoptions;
}

function cpd_sizeunit_options($sizeunits) {
	// Add options for sizeunits pulldown
	$sizeunit_options = array(
		"1" => "sq m",
		"2" => "sq ft",
		"3" => "acres",
		"4" => "hectares",
	);
	$sizeunitoptions = "";
	foreach($sizeunit_options as $key => $value) {
		$selected = ($key == $sizeunits ? "selected=\"selected\"" : "");
		$sizeunitoptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $sizeunitoptions;
}

function cpd_tenure_options($tenure) {
	// Add options for tenure pulldown
	$tenure_options = array(
		"" => "Leasehold and Freehold",
		"F" => "Freehold",
		"L" => "Leasehold",
	);
	$tenureoptions = "";
	foreach($tenure_options as $key => $value) {
		$selected = ($key == $tenure ? "selected=\"selected\"" : "");
		$tenureoptions .= "<option value=\"".$key."\" ".$selected.">".$value."</option>\n";
	}
	return $tenureoptions;
}

function cpd_perpage_options($limit) {
	// Add options for perpage pulldown
	global $results_per_page_options;
	$perpageoptions = "";
	foreach($results_per_page_options as $value) {
		$selected = ($value == $limit ? "selected='selected'" : "");
		$perpageoptions .= "<option value=\"".$value."\" ".$selected.">".$value."</option>\n";
	}
	return $perpageoptions;
}

?>
