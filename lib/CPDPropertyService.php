<?php
class PropertyType {
  public $PropertyID; // int
  public $Address; // string
  public $BuildingNumber; // string
  public $BuildingName; // string
  public $AgentRef; // string
  public $AgentName; // string
  public $BriefSummary; // string
  public $ContactDetails; // string
  public $Sector; // SectorType
  public $SectorDescription; // string
  public $Tenure; // TenureType
  public $TenureDescription; // string
  public $AgentsHold; // boolean
  public $PublicHold; // boolean
  public $Headings; // HeadingsType
  public $Labels; // LabelsType
  public $LastUpdatedBy; // string
  public $LastUpdatedDate; // dateTime
  public $PropertyStatus; // PropertyStatusType
  public $ContactEmail; // string
  public $Contacts; // string
  public $LeaseExpiryDate; // string
  public $Postcode; // string
  public $SizeFrom; // string
  public $SizeTo; // string
  public $SizeUnits; // SizeUnitsType
  public $SizeDescription; // string
  public $Premium; // string
  public $Rent; // string
  public $RentUnit; // RentUnitType
  public $RentReview; // dateTime
  public $Price; // string
  public $PriceType; // string
  public $RateableValue; // string
  public $RatesPayable; // string
  public $RatesYear; // string
  public $FullDetails; // boolean
  public $OptionalInfo; // string
  public $AdditionalInfo; // string
  public $Latitude; // double
  public $Longitude; // double
  public $CreatedBy; // string
  public $CreationDate; // dateTime
  public $PropertyMedia; // PropertyMediaType
  public $RegionName; // string
}

class PropertyMediaType {
  public $MediaID; // int
  public $PropertyID; // int
  public $Type; // PropertyMediaImageType
  public $Position; // int
  public $URL; // string
  public $ThumbURL; // string
  public $MediumURL; // string
  public $AltText; // string
  public $DateAdded; // dateTime
  public $ViewCount; // int
  public $ThumbQuality; // string
}

class SortUsingAgentsType {
  public $Agent; // string
}

class CPDAreaIDsType {
  public $CPDAreaID; // int
}

class PortalAreaIDsType {
  public $PortalAreaID; // int
}

class SectorsType {
  public $Sector; // SectorType
}

class PropertyIDsType {
  public $PropertyID; // int
}

class ArchiveQtrsType {
  public $ArchiveQtr; // string
}

class TenureType {
  const F = 'F';
  const L = 'L';
  const B = 'B';
}

class PropertyStatusType {
  const A = 'A';
  const O = 'O';
}

class SectorType {
  const O = 'O';
  const SO = 'SO';
  const S = 'S';
  const I = 'I';
  const BU = 'BU';
  const R = 'R';
  const PU = 'PU';
  const L = 'L';
  const W = 'W';
  const X = 'X';
  const M = 'M';
  const C = 'C';
  const H = 'H';
  const G = 'G';
  const AC = 'AC';
  const U = 'U';
  const A = 'A';
  const E = 'E';
  const B = 'B';
  const Z = 'Z';
  const GC = 'GC';
  const others = 'others';
}

class SectorDescriptionType {
  public $SectorCode; // SectorType
  public $SectorDescription; // string
}

class SizeUnitsType {
  const value_1 = '1';
  const value_2 = '2';
  const value_3 = '3';
  const value_4 = '4';
}

class RadiusProximityType {
  public $Longitude; // float
  public $Latitude; // float
  public $Radius; // int
}

class HoldType {
}

class SortOrderType {
}

class AN3 {
}

class RentUnitType {
  const psf = 'psf';
  const psm = 'psm';
  const pwi = 'pwi';
  const pwx = 'pwx';
  const pmi = 'pmi';
  const pmx = 'pmx';
  const pai = 'pai';
  const pax = 'pax';
  const ona = 'ona';
}

class HeadingsType {
  public $Heading; // string
}

class LabelsType {
  public $Label; // LabelType
}

class LabelType {
  public $LabelName; // string
  public $LabelText; // string
}

class PropertyMediaImageType {
  const scan = 'scan';
  const photo = 'photo';
  const map = 'map';
  const pdf = 'pdf';
}

class PostcodeCoordinates {
  public $Postcode; // string
  public $Longitude; // double
  public $Latitude; // double
}

class VisitorType {
  public $UserID; // int
  public $Name; // string
  public $Email; // string
  public $PropertyView; // PropertyView
  public $PropertyMediaView; // PropertyMediaView
  public $RegisteredInterest; // RegisteredInterest
}

class PropertyView {
  public $ID; // int
  public $UserID; // int
  public $PropertyID; // int
  public $ViewDate; // dateTime
  public $ProcessedDate; // dateTime
}

class PropertyMediaView {
  public $ID; // int
  public $UserID; // int
  public $MediaID; // int
  public $ViewDate; // dateTime
  public $ProcessedDate; // dateTime
}

class RegisteredInterest {
  public $ID; // int
  public $UserID; // int
  public $PropertyID; // int
  public $InterestedDate; // dateTime
  public $ProcessedDate; // dateTime
}

class SearchPropertyType {
  public $SearchCriteria; // SearchCriteriaType
}

class SearchCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $DetailLevel; // DetailLevelType
  public $Archive; // boolean
  public $ArchiveQtrs; // ArchiveQtrsType
  public $RefsOnly; // boolean
  public $SortUsingAgents; // SortUsingAgentsType
  public $Agent; // string
  public $BuildingNumber; // string
  public $BuildingName; // string
  public $Address; // string
  public $BriefSummary; // string
  public $Tenure; // TenureType
  public $PropertyStatus; // PropertyStatusType
  public $Sectors; // SectorsType
  public $CPDAreaIDs; // CPDAreaIDsType
  public $PortalAreaIDs; // PortalAreaIDsType
  public $Postcode; // string
  public $Postcodes; // PostcodesType
  public $UpdatedFrom; // dateTime
  public $UpdatedTo; // dateTime
  public $PropertyIDs; // PropertyIDsType
  public $UnassignedProperty; // boolean
  public $MinSize; // float
  public $MaxSize; // float
  public $SizeUnits; // SizeUnitsType
  public $RadiusProximity; // RadiusProximityType
  public $SortOrder; // SortOrderType
}

class DetailLevelType {
  const brief = 'brief';
  const full = 'full';
}

class PostcodesType {
  public $Postcode; // string
}

class RegisterInterestType {
  public $PropertyID; // int
}

class ViewingPropertyType {
  public $PropertyID; // int
}

class ViewingMediaType {
  public $MediaID; // int
}

class CreatePropertyType {
  public $Property; // PropertyType
}

class UpdatePropertyType {
  public $Property; // PropertyType
}

class DeletePropertyType {
  public $PropertyID; // int
}

class ArchivePropertyType {
  public $PropertyID; // int
}

class RestorePropertyType {
  public $PropertyID; // int
}

class UploadMediaType {
  public $MediaData; // base64
  public $MediaType; // PropertyMediaImageType
}

class AttachMediaType {
  public $MediaID; // int
  public $PropertyID; // int
}

class ListMediaType {
}

class SwapMediaPositionType {
  public $MediaType; // PropertyMediaImageType
  public $FromPosition; // int
  public $ToPosition; // int
}

class RemoveMediaType {
  public $MediaID; // int
}

class PostcodeLookupType {
  public $Postcode; // string
}

class GetRecentViewingsType {
  public $SinceDate; // dateTime
  public $OnlyUnprocessed; // boolean
}

class ProcessRegisteredInterestType {
  public $ID; // int
  public $SendEmail; // boolean
}

class GetDBSchemaVersionResponseType {
  public $Version; // int
}

class GetSectorsResponseType {
  public $ResultCount; // int
  public $SectorList; // SectorListType
}

class SearchPropertyResponseType {
  public $ResultCount; // int
  public $PropertyList; // PropertyListType
}

class RegisterInterestResponseType {
  public $RegisteredInterest; // RegisteredInterest
}

class ViewingPropertyResponseType {
  public $PropertyView; // PropertyView
}

class ViewingMediaResponseType {
  public $PropertyMediaView; // PropertyMediaView
}

class CreatePropertyResponseType {
  public $PropertyID; // int
}

class UpdatePropertyResponseType {
}

class ArchivePropertyResponseType {
}

class RestorePropertyResponseType {
}

class DeletePropertyResponseType {
}

class UploadMediaResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class AttachMediaResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class SwapMediaPositionResponseType {
}

class RemoveMediaResponseType {
}

class PostcodeLookupResponseType {
  public $PostcodeCoordinates; // PostcodeCoordinates
}

class GetRecentViewingsResponseType {
  public $Visitor; // VisitorType
}

class ProcessRegisteredInterestResponseType {
  public $RegisteredInterest; // RegisteredInterest
}

class ListMediaResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class SectorListType {
  public $Sector; // SectorDescriptionType
}

class PropertyListType {
  public $Property; // PropertyType
}

class PropertyMediaListType {
  public $PropertyMedia; // PropertyMediaType
}

class InvalidMediaTypeExceptionType {
}

class PostcodeNotFoundExceptionType {
}


/**
 * CPDPropertyService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class CPDPropertyService extends SoapClient {

  private static $classmap = array(
                                    'PropertyType' => 'PropertyType',
                                    'PropertyMediaType' => 'PropertyMediaType',
                                    'SortUsingAgentsType' => 'SortUsingAgentsType',
                                    'CPDAreaIDsType' => 'CPDAreaIDsType',
                                    'PortalAreaIDsType' => 'PortalAreaIDsType',
                                    'SectorsType' => 'SectorsType',
                                    'PropertyIDsType' => 'PropertyIDsType',
                                    'ArchiveQtrsType' => 'ArchiveQtrsType',
                                    'TenureType' => 'TenureType',
                                    'PropertyStatusType' => 'PropertyStatusType',
                                    'SectorType' => 'SectorType',
                                    'SectorDescriptionType' => 'SectorDescriptionType',
                                    'SizeUnitsType' => 'SizeUnitsType',
                                    'RadiusProximityType' => 'RadiusProximityType',
                                    'HoldType' => 'HoldType',
                                    'SortOrderType' => 'SortOrderType',
                                    'AN3' => 'AN3',
                                    'RentUnitType' => 'RentUnitType',
                                    'HeadingsType' => 'HeadingsType',
                                    'LabelsType' => 'LabelsType',
                                    'LabelType' => 'LabelType',
                                    'PropertyMediaImageType' => 'PropertyMediaImageType',
                                    'PostcodeCoordinates' => 'PostcodeCoordinates',
                                    'VisitorType' => 'VisitorType',
                                    'PropertyView' => 'PropertyView',
                                    'PropertyMediaView' => 'PropertyMediaView',
                                    'RegisteredInterest' => 'RegisteredInterest',
                                    'SearchPropertyType' => 'SearchPropertyType',
                                    'SearchCriteriaType' => 'SearchCriteriaType',
                                    'DetailLevelType' => 'DetailLevelType',
                                    'PostcodesType' => 'PostcodesType',
                                    'RegisterInterestType' => 'RegisterInterestType',
                                    'ViewingPropertyType' => 'ViewingPropertyType',
                                    'ViewingMediaType' => 'ViewingMediaType',
                                    'CreatePropertyType' => 'CreatePropertyType',
                                    'UpdatePropertyType' => 'UpdatePropertyType',
                                    'DeletePropertyType' => 'DeletePropertyType',
                                    'ArchivePropertyType' => 'ArchivePropertyType',
                                    'RestorePropertyType' => 'RestorePropertyType',
                                    'UploadMediaType' => 'UploadMediaType',
                                    'AttachMediaType' => 'AttachMediaType',
                                    'ListMediaType' => 'ListMediaType',
                                    'SwapMediaPositionType' => 'SwapMediaPositionType',
                                    'RemoveMediaType' => 'RemoveMediaType',
                                    'PostcodeLookupType' => 'PostcodeLookupType',
                                    'GetRecentViewingsType' => 'GetRecentViewingsType',
                                    'ProcessRegisteredInterestType' => 'ProcessRegisteredInterestType',
                                    'InvalidTokenExceptionType' => 'InvalidTokenExceptionType',
                                    'UnconfirmedUserExceptionType' => 'UnconfirmedUserExceptionType',
                                    'AuthenticationFailedExceptionType' => 'AuthenticationFailedExceptionType',
                                    'GetDBSchemaVersionResponseType' => 'GetDBSchemaVersionResponseType',
                                    'GetSectorsResponseType' => 'GetSectorsResponseType',
                                    'SearchPropertyResponseType' => 'SearchPropertyResponseType',
                                    'RegisterInterestResponseType' => 'RegisterInterestResponseType',
                                    'ViewingPropertyResponseType' => 'ViewingPropertyResponseType',
                                    'ViewingMediaResponseType' => 'ViewingMediaResponseType',
                                    'CreatePropertyResponseType' => 'CreatePropertyResponseType',
                                    'UpdatePropertyResponseType' => 'UpdatePropertyResponseType',
                                    'ArchivePropertyResponseType' => 'ArchivePropertyResponseType',
                                    'RestorePropertyResponseType' => 'RestorePropertyResponseType',
                                    'DeletePropertyResponseType' => 'DeletePropertyResponseType',
                                    'UploadMediaResponseType' => 'UploadMediaResponseType',
                                    'AttachMediaResponseType' => 'AttachMediaResponseType',
                                    'SwapMediaPositionResponseType' => 'SwapMediaPositionResponseType',
                                    'RemoveMediaResponseType' => 'RemoveMediaResponseType',
                                    'PostcodeLookupResponseType' => 'PostcodeLookupResponseType',
                                    'GetRecentViewingsResponseType' => 'GetRecentViewingsResponseType',
                                    'ProcessRegisteredInterestResponseType' => 'ProcessRegisteredInterestResponseType',
                                    'ListMediaResponseType' => 'ListMediaResponseType',
                                    'SectorListType' => 'SectorListType',
                                    'PropertyListType' => 'PropertyListType',
                                    'PropertyMediaListType' => 'PropertyMediaListType',
                                    'InvalidMediaTypeExceptionType' => 'InvalidMediaTypeExceptionType',
                                    'PostcodeNotFoundExceptionType' => 'PostcodeNotFoundExceptionType',
                                   );

  public function CPDPropertyService($wsdl = "https://staging.cpd.co.uk/soap/services/CPDPropertyService?wsdl", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param  
   * @return GetDBSchemaVersionResponseType
   */
  public function GetDBSchemaVersion() {
    return $this->__soapCall('GetDBSchemaVersion', array(),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param  
   * @return GetSectorsResponseType
   */
  public function GetSectors() {
    return $this->__soapCall('GetSectors', array(),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchPropertyType $request
   * @return SearchPropertyResponseType
   */
  public function SearchProperty(SearchPropertyType $request) {
    return $this->__soapCall('SearchProperty', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param RegisterInterestType $request
   * @return RegisterInterestResponseType
   */
  public function RegisterInterest(RegisterInterestType $request) {
    return $this->__soapCall('RegisterInterest', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ViewingPropertyType $request
   * @return ViewingPropertyResponseType
   */
  public function ViewingProperty(ViewingPropertyType $request) {
    return $this->__soapCall('ViewingProperty', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ViewingMediaType $request
   * @return ViewingMediaResponseType
   */
  public function ViewingMedia(ViewingMediaType $request) {
    return $this->__soapCall('ViewingMedia', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CreatePropertyType $request
   * @return CreatePropertyResponseType
   */
  public function CreateProperty(CreatePropertyType $request) {
    return $this->__soapCall('CreateProperty', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param UpdatePropertyType $request
   * @return UpdatePropertyResponseType
   */
  public function UpdateProperty(UpdatePropertyType $request) {
    return $this->__soapCall('UpdateProperty', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ArchivePropertyType $request
   * @return ArchivePropertyResponseType
   */
  public function ArchiveProperty(ArchivePropertyType $request) {
    return $this->__soapCall('ArchiveProperty', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param RestorePropertyType $request
   * @return RestorePropertyResponseType
   */
  public function RestoreProperty(RestorePropertyType $request) {
    return $this->__soapCall('RestoreProperty', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param DeletePropertyType $request
   * @return DeletePropertyResponseType
   */
  public function DeleteProperty(DeletePropertyType $request) {
    return $this->__soapCall('DeleteProperty', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param UploadMediaType $request
   * @return UploadMediaResponseType
   */
  public function UploadMedia(UploadMediaType $request) {
    return $this->__soapCall('UploadMedia', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param AttachMediaType $request
   * @return AttachMediaResponseType
   */
  public function AttachMedia(AttachMediaType $request) {
    return $this->__soapCall('AttachMedia', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ListMediaType $request
   * @return ListMediaResponseType
   */
  public function ListMedia(ListMediaType $request) {
    return $this->__soapCall('ListMedia', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SwapMediaPositionType $request
   * @return SwapMediaPositionResponseType
   */
  public function SwapMediaPosition(SwapMediaPositionType $request) {
    return $this->__soapCall('SwapMediaPosition', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param RemoveMedia $request
   * @return RemoveMediaResponseType
   */
  public function RemoveMedia(RemoveMedia $request) {
    return $this->__soapCall('RemoveMedia', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param PostcodeLookupType $request
   * @return PostcodeLookupResponseType
   */
  public function PostcodeLookup(PostcodeLookupType $request) {
    return $this->__soapCall('PostcodeLookup', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetRecentViewingsType $request
   * @return GetRecentViewingsResponseType
   */
  public function GetRecentViewings(GetRecentViewingsType $request) {
    return $this->__soapCall('GetRecentViewings', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ProcessRegisteredInterestType $request
   * @return ProcessRegisteredInterestResponseType
   */
  public function ProcessRegisteredInterest(ProcessRegisteredInterestType $request) {
    return $this->__soapCall('ProcessRegisteredInterest', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

}

?>
