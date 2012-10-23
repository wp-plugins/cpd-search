<?php
class PropertyIDsType {
  public $PropertyID; // int
}

class PropertyListType {
  public $Property; // PropertyType
}

class PropertyType {
  public $PropertyID; // int
  public $Address; // string
  public $BuildingNumber; // string
  public $BuildingName; // string
  public $AgentRef; // string
  public $AgentID; // int
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
  public $ArchivableDate; // dateTime
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
  public $PropertyMedia; // PropertyMediaType
  public $RegionName; // string
  public $CreatedBy; // int
  public $CreatedDate; // dateTime
  public $UpdatedBy; // int
  public $UpdatedDate; // dateTime
  public $ArchivedBy; // int
  public $ArchivedDate; // dateTime
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

class PropertyHistoryType {
  public $ID; // int
  public $PropertyID; // int
  public $UserID; // int
  public $EntryDate; // dateTime
  public $Action; // string
  public $Data; // string
}

class SortUsingAgentsType {
  public $AgentID; // int
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

class AgentUIDsType {
  public $ID; // int
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
  const Available = 'Available';
  const Under_Offer = 'Under Offer';
  const Sold = 'Sold';
  const Let = 'Let';
  const Withdrawn = 'Withdrawn';
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
  public $RegionName; // string
}

class VisitorType {
  public $UserID; // int
  public $Name; // string
  public $Email; // string
  public $Phone; // string
  public $UserConfirmed; // boolean
  public $PropertyView; // PropertyView
  public $PropertyMediaView; // PropertyMediaView
  public $RegisteredInterest; // RegisteredInterest
  public $RegistrationContext; // string
  public $RegistrationDate; // dateTime
}

class PropertyView {
  public $ID; // int
  public $UserID; // int
  public $PropertyID; // int
  public $ServiceContext; // string
  public $ViewDate; // dateTime
  public $ProcessedDate; // dateTime
}

class PropertyMediaView {
  public $ID; // int
  public $UserID; // int
  public $MediaID; // int
  public $ServiceContext; // string
  public $ViewDate; // dateTime
  public $ProcessedDate; // dateTime
}

class RegisteredInterest {
  public $ID; // int
  public $UserID; // int
  public $PropertyID; // int
  public $ServiceContext; // string
  public $InterestedDate; // dateTime
  public $ProcessedDate; // dateTime
}

class CPDAreaType {
  public $ID; // int
  public $Description; // string
}

class CPDPostcodePrefixType {
  public $Prefix; // string
  public $CPDAreaID; // string
}

class GetSectorsType {
  public $AllSectors; // boolean
  public $SectorsWithInstructions; // boolean
}

class SearchPropertyType {
  public $SearchCriteria; // SearchCriteriaType
}

class SearchCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $DetailLevel; // DetailLevelType
  public $RefsOnly; // boolean
  public $Agent; // string
  public $AgentIDs; // AgentUIDsType
  public $Archive; // boolean
  public $ArchiveQtrs; // ArchiveQtrsType
  public $SortUsingAgents; // SortUsingAgentsType
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
  public $Keyword; // string
  public $SortField; // SearchPropertiesSortFieldType
  public $SortOrder; // SortOrderType
}

class SearchPropertiesSortFieldType {
  const ID = 'ID';
  const Agent = 'Agent';
  const Address = 'Address';
  const Area = 'Area';
  const Size = 'Size';
  const UpdatedDate = 'UpdatedDate';
}

class SearchPropertyHistoryType {
  public $PropertyHistoryCriteria; // PropertyHistoryCriteriaType
}

class PropertyHistoryCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $SortField; // SearchPropertiesSortFieldType
  public $SortOrder; // SortOrderType
  public $PropertyID; // int
}

class PostcodesType {
  public $Postcode; // string
}

class RegisterInterestType {
  public $PropertyID; // int
  public $ServiceContext; // string
}

class ViewingPropertyType {
  public $PropertyID; // int
  public $ServiceContext; // string
}

class ViewPropertyType {
  public $PropertyID; // int
  public $ServiceContext; // string
  public $RegistrationNotRequired; // boolean
}

class ViewingMediaType {
  public $MediaID; // int
  public $ServiceContext; // string
}

class ViewMediaType {
  public $PropertyID; // int
  public $MediaType; // string
  public $Position; // int
  public $ServiceContext; // string
  public $RegistrationNotRequired; // boolean
}

class CreatePropertyType {
  public $Property; // PropertyType
}

class UpdatePropertyType {
  public $Property; // PropertyType
}

class DuplicatePropertyType {
  public $PropertyID; // int
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

class GetRecentVisitorsType {
  public $SinceDate; // dateTime
  public $OnlyUnprocessed; // boolean
}

class ProcessVisitorsType {
  public $ID; // int
  public $SendAgentEmail; // boolean
  public $SendVisitorEmail; // boolean
}

class CPDAreaCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $Substring; // string
}

class SearchCPDAreasType {
  public $SearchCriteria; // CPDAreaCriteriaType
}

class CPDPostcodePrefixesCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $CPDAreaIDs; // CPDAreaIDsType
}

class SearchCPDPostcodePrefixesType {
  public $SearchCriteria; // CPDPostcodePrefixesCriteriaType
}

class CreateCPDPostcodePrefixType {
  public $PostcodePrefix; // CPDPostcodePrefixType
}

class RemoveCPDPostcodePrefixType {
  public $PostcodePrefix; // CPDPostcodePrefixType
}

class GetSectorsResponseType {
  public $ResultCount; // int
  public $SectorList; // SectorListType
}

class SearchPropertyResponseType {
  public $ResultCount; // int
  public $PropertyList; // PropertyListType
}

class SearchPropertyHistoryResponseType {
  public $ResultCount; // int
  public $PropertyHistoryList; // PropertyHistoryListType
}

class RegisterInterestResponseType {
  public $RegisteredInterest; // RegisteredInterest
  public $UnconfirmedUserWarning; // boolean
}

class ViewingPropertyResponseType {
  public $PropertyView; // PropertyView
  public $Property; // PropertyType
}

class ViewPropertyResponseType {
  public $PropertyView; // PropertyView
  public $Property; // PropertyType
}

class ViewingMediaResponseType {
  public $PropertyMediaView; // PropertyMediaView
  public $PropertyMedia; // PropertyMediaType
}

class ViewMediaResponseType {
  public $PropertyMediaView; // PropertyMediaView
  public $PropertyMedia; // PropertyMediaType
}

class CreatePropertyResponseType {
  public $Property; // PropertyType
}

class UpdatePropertyResponseType {
  public $Property; // PropertyType
}

class DuplicatePropertyResponseType {
  public $Property; // PropertyType
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

class GetRecentVisitorsResponseType {
  public $Visitor; // VisitorType
}

class ProcessVisitorsResponseType {
  public $Visitor; // VisitorType
}

class SearchCPDAreasResponseType {
  public $CPDArea; // CPDAreaType
}

class SearchCPDPostcodePrefixesResponseType {
  public $PostcodePrefix; // CPDPostcodePrefixType
}

class CreateCPDPostcodePrefixResponseType {
  public $PostcodePrefix; // CPDPostcodePrefixType
}

class RemoveCPDPostcodePrefixResponseType {
}

class ListMediaResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class SectorListType {
  public $Sector; // SectorDescriptionType
}

class PropertyMediaListType {
  public $PropertyMedia; // PropertyMediaType
}

class PropertyHistoryListType {
  public $PropertyHistory; // PropertyHistoryType
}

class AccessDeniedExceptionType {
  public $Detail; // string
}

class UnconfirmedUserExceptionType {
  public $Detail; // string
}

class InvalidMediaTypeExceptionType {
  public $Detail; // string
}

class PostcodeNotFoundExceptionType {
  public $Detail; // string
}

class CPDPostcodePrefixNotFoundExceptionType {
  public $Detail; // string
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
                                    'PropertyIDsType' => 'PropertyIDsType',
                                    'PropertyListType' => 'PropertyListType',
                                    'PropertyType' => 'PropertyType',
                                    'PropertyMediaType' => 'PropertyMediaType',
                                    'PropertyHistoryType' => 'PropertyHistoryType',
                                    'SortUsingAgentsType' => 'SortUsingAgentsType',
                                    'CPDAreaIDsType' => 'CPDAreaIDsType',
                                    'PortalAreaIDsType' => 'PortalAreaIDsType',
                                    'SectorsType' => 'SectorsType',
                                    'AgentUIDsType' => 'AgentUIDsType',
                                    'ArchiveQtrsType' => 'ArchiveQtrsType',
                                    'TenureType' => 'TenureType',
                                    'PropertyStatusType' => 'PropertyStatusType',
                                    'SectorType' => 'SectorType',
                                    'SectorDescriptionType' => 'SectorDescriptionType',
                                    'SizeUnitsType' => 'SizeUnitsType',
                                    'RadiusProximityType' => 'RadiusProximityType',
                                    'HoldType' => 'HoldType',
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
                                    'CPDAreaType' => 'CPDAreaType',
                                    'CPDPostcodePrefixType' => 'CPDPostcodePrefixType',
                                    'GetSectorsType' => 'GetSectorsType',
                                    'SearchPropertyType' => 'SearchPropertyType',
                                    'SearchCriteriaType' => 'SearchCriteriaType',
                                    'SearchPropertiesSortFieldType' => 'SearchPropertiesSortFieldType',
                                    'SearchPropertyHistoryType' => 'SearchPropertyHistoryType',
                                    'PropertyHistoryCriteriaType' => 'PropertyHistoryCriteriaType',
                                    'PostcodesType' => 'PostcodesType',
                                    'RegisterInterestType' => 'RegisterInterestType',
                                    'ViewingPropertyType' => 'ViewingPropertyType',
                                    'ViewPropertyType' => 'ViewPropertyType',
                                    'ViewingMediaType' => 'ViewingMediaType',
                                    'ViewMediaType' => 'ViewMediaType',
                                    'CreatePropertyType' => 'CreatePropertyType',
                                    'UpdatePropertyType' => 'UpdatePropertyType',
                                    'DuplicatePropertyType' => 'DuplicatePropertyType',
                                    'DeletePropertyType' => 'DeletePropertyType',
                                    'ArchivePropertyType' => 'ArchivePropertyType',
                                    'RestorePropertyType' => 'RestorePropertyType',
                                    'UploadMediaType' => 'UploadMediaType',
                                    'AttachMediaType' => 'AttachMediaType',
                                    'ListMediaType' => 'ListMediaType',
                                    'SwapMediaPositionType' => 'SwapMediaPositionType',
                                    'RemoveMediaType' => 'RemoveMediaType',
                                    'PostcodeLookupType' => 'PostcodeLookupType',
                                    'GetRecentVisitorsType' => 'GetRecentVisitorsType',
                                    'ProcessVisitorsType' => 'ProcessVisitorsType',
                                    'CPDAreaCriteriaType' => 'CPDAreaCriteriaType',
                                    'SearchCPDAreasType' => 'SearchCPDAreasType',
                                    'CPDPostcodePrefixesCriteriaType' => 'CPDPostcodePrefixesCriteriaType',
                                    'SearchCPDPostcodePrefixesType' => 'SearchCPDPostcodePrefixesType',
                                    'CreateCPDPostcodePrefixType' => 'CreateCPDPostcodePrefixType',
                                    'RemoveCPDPostcodePrefixType' => 'RemoveCPDPostcodePrefixType',
                                    'GetSectorsResponseType' => 'GetSectorsResponseType',
                                    'SearchPropertyResponseType' => 'SearchPropertyResponseType',
                                    'SearchPropertyHistoryResponseType' => 'SearchPropertyHistoryResponseType',
                                    'RegisterInterestResponseType' => 'RegisterInterestResponseType',
                                    'ViewingPropertyResponseType' => 'ViewingPropertyResponseType',
                                    'ViewPropertyResponseType' => 'ViewPropertyResponseType',
                                    'ViewingMediaResponseType' => 'ViewingMediaResponseType',
                                    'ViewMediaResponseType' => 'ViewMediaResponseType',
                                    'CreatePropertyResponseType' => 'CreatePropertyResponseType',
                                    'UpdatePropertyResponseType' => 'UpdatePropertyResponseType',
                                    'DuplicatePropertyResponseType' => 'DuplicatePropertyResponseType',
                                    'ArchivePropertyResponseType' => 'ArchivePropertyResponseType',
                                    'RestorePropertyResponseType' => 'RestorePropertyResponseType',
                                    'DeletePropertyResponseType' => 'DeletePropertyResponseType',
                                    'UploadMediaResponseType' => 'UploadMediaResponseType',
                                    'AttachMediaResponseType' => 'AttachMediaResponseType',
                                    'SwapMediaPositionResponseType' => 'SwapMediaPositionResponseType',
                                    'RemoveMediaResponseType' => 'RemoveMediaResponseType',
                                    'PostcodeLookupResponseType' => 'PostcodeLookupResponseType',
                                    'GetRecentVisitorsResponseType' => 'GetRecentVisitorsResponseType',
                                    'ProcessVisitorsResponseType' => 'ProcessVisitorsResponseType',
                                    'SearchCPDAreasResponseType' => 'SearchCPDAreasResponseType',
                                    'SearchCPDPostcodePrefixesResponseType' => 'SearchCPDPostcodePrefixesResponseType',
                                    'CreateCPDPostcodePrefixResponseType' => 'CreateCPDPostcodePrefixResponseType',
                                    'RemoveCPDPostcodePrefixResponseType' => 'RemoveCPDPostcodePrefixResponseType',
                                    'ListMediaResponseType' => 'ListMediaResponseType',
                                    'SectorListType' => 'SectorListType',
                                    'PropertyMediaListType' => 'PropertyMediaListType',
                                    'PropertyHistoryListType' => 'PropertyHistoryListType',
                                    'AccessDeniedExceptionType' => 'AccessDeniedExceptionType',
                                    'UnconfirmedUserExceptionType' => 'UnconfirmedUserExceptionType',
                                    'InvalidMediaTypeExceptionType' => 'InvalidMediaTypeExceptionType',
                                    'PostcodeNotFoundExceptionType' => 'PostcodeNotFoundExceptionType',
                                    'CPDPostcodePrefixNotFoundExceptionType' => 'CPDPostcodePrefixNotFoundExceptionType',
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
   * @param GetSectorsType $request
   * @return GetSectorsResponseType
   */
  public function GetSectors(GetSectorsType $request) {
    return $this->__soapCall('GetSectors', array($request),       array(
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
   * @param SearchPropertyHistoryType $request
   * @return SearchPropertyHistoryResponseType
   */
  public function SearchPropertyHistory(SearchPropertyHistoryType $request) {
    return $this->__soapCall('SearchPropertyHistory', array($request),       array(
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
   * @param ViewPropertyType $request
   * @return ViewPropertyResponseType
   */
  public function ViewProperty(ViewPropertyType $request) {
    return $this->__soapCall('ViewProperty', array($request),       array(
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
   * @param ViewMediaType $request
   * @return ViewMediaResponseType
   */
  public function ViewMedia(ViewMediaType $request) {
    return $this->__soapCall('ViewMedia', array($request),       array(
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
   * @param DuplicatePropertyType $request
   * @return DuplicatePropertyResponseType
   */
  public function DuplicateProperty(DuplicatePropertyType $request) {
    return $this->__soapCall('DuplicateProperty', array($request),       array(
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
   * @param RemoveMediaType $request
   * @return RemoveMediaResponseType
   */
  public function RemoveMedia(RemoveMediaType $request) {
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
   * @param GetRecentVisitorsType $request
   * @return GetRecentVisitorsResponseType
   */
  public function GetRecentVisitors(GetRecentVisitorsType $request) {
    return $this->__soapCall('GetRecentVisitors', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ProcessVisitorsType $request
   * @return ProcessVisitorsResponseType
   */
  public function ProcessVisitors(ProcessVisitorsType $request) {
    return $this->__soapCall('ProcessVisitors', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchCPDAreasType $request
   * @return SearchCPDAreasResponseType
   */
  public function SearchCPDAreas(SearchCPDAreasType $request) {
    return $this->__soapCall('SearchCPDAreas', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchCPDPostcodePrefixesType $request
   * @return SearchCPDPostcodePrefixesResponseType
   */
  public function SearchCPDPostcodePrefixes(SearchCPDPostcodePrefixesType $request) {
    return $this->__soapCall('SearchCPDPostcodePrefixes', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CreateCPDPostcodePrefixType $request
   * @return CreateCPDPostcodePrefixResponseType
   */
  public function CreateCPDPostcodePrefix(CreateCPDPostcodePrefixType $request) {
    return $this->__soapCall('CreateCPDPostcodePrefix', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param RemoveCPDPostcodePrefixType $request
   * @return RemoveCPDPostcodePrefixResponseType
   */
  public function RemoveCPDPostcodePrefix(RemoveCPDPostcodePrefixType $request) {
    return $this->__soapCall('RemoveCPDPostcodePrefix', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

}

?>
