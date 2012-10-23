<?php

require_once(dirname(__FILE__) . "/cpd-common.php");
require_once(dirname(__FILE__) . "/cpd-register-interest.php");
require_once(dirname(__FILE__) . "/cpd-view-property-image.php");
require_once(dirname(__FILE__) . "/cpd-view-property-pdf.php");

class CPDCommonSearch {
	function rowFromDB($record) {
		$row = array();
		$row['PropertyID'] = $record->PropertyID;
		$row['SectorDescription'] = $record->SectorDescription;
		$row['SizeDescription'] = $record->SizeDescription;
		$row['TenureDescription'] = $record->TenureDescription;
		$row['BriefSummary'] = $record->BriefSummary;
		$row['Address'] = $record->Address;
		$row['Latitude'] = $record->Latitude;
		$row['Longitude'] = $record->Longitude;
		$row['RegionName'] = $record->RegionName;
	
		// Add thumb URL, only if one is available
		$options = get_option('cpd-search-options');
		if(isset($record->PropertyMedia)) {
			$mediaList = $record->PropertyMedia;
			if($mediaList instanceof PropertyMediaType) {
				$mediaList = array($propList);
			}
			foreach($mediaList as $media) {
				if($media->Position > 1) {
					continue;
				}
				if($media->Type == "photo") {
					$row['ImageThumbURL'] = $media->ThumbURL;
					$row['ImageMediaID'] = $media->MediaID;
					continue;
				}
				if($media->Type == "pdf" && $options['cpd_agentref'] == $record->AgentRef) {
					$row['PDFThumbURL'] = $media->ThumbURL;
					$row['PDFMediaID'] = $media->MediaID;
					continue;
				}
			}
		}
		return $row;
	}
}

?>
