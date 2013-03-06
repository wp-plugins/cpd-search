<?php
class PropertyIDsType {
  public $PropertyID; // int
}

class PropertyListType {
  public $Property; // PropertyType
}

class PropertyType {
  public $PropertyID; // int
  public $PropertyStatus; // PropertyStatusType
  public $PropertyType; // PropertyTypeType
  public $Address; // string
  public $BuildingNumber; // string
  public $BuildingName; // string
  public $AgentRef; // string
  public $AgentID; // int
  public $AgentName; // string
  public $BriefSummary; // string
  public $ContactDetails; // string
  public $ContactEmail; // string
  public $Sector; // SectorType
  public $SectorDescription; // string
  public $Tenure; // TenureType
  public $TenureDescription; // string
  public $AgentsHold; // boolean
  public $PublicHold; // boolean
  public $Confidential; // boolean
  public $EPC; // string
  public $Headings; // HeadingsType
  public $Labels; // LabelsType
  public $ArchivableDate; // dateTime
  public $LeaseExpiryDate; // string
  public $Postcode; // string
  public $SizeFrom; // string
  public $SizeTo; // string
  public $SizeUnits; // SizeUnitsType
  public $SizeDescription; // string
  public $Premium; // string
  public $Rent; // string
  public $RentUnit; // RentUnitType
  public $RentOffersInvited; // boolean
  public $RentReview; // dateTime
  public $Price; // string
  public $PriceType; // string
  public $PriceOffersInvited; // boolean
  public $RateableValue; // string
  public $RatesPayable; // string
  public $RatesYear; // string
  public $FullDetails; // boolean
  public $OptionalInfo; // string
  public $AdditionalInfo; // string
  public $Latitude; // double
  public $Longitude; // double
  public $PropertyContact; // PropertyContactType
  public $PropertyMedia; // PropertyMediaType
  public $RegionName; // string
  public $CreatedBy; // int
  public $CreatedByName; // string
  public $CreatedByEmail; // string
  public $CreatedDate; // dateTime
  public $UpdatedBy; // int
  public $UpdatedByName; // string
  public $UpdatedByEmail; // string
  public $UpdatedDate; // dateTime
  public $ArchivedBy; // int
  public $ArchivedByName; // string
  public $ArchivedByEmail; // string
  public $ArchivedDate; // dateTime
  public $ArchiveDetails; // PropertyArchiveDetailsType
  public $DatasourceID; // int
  public $DatasourceRef; // string
}

class PropertyContactType {
  public $PropertyID; // int
  public $UserID; // int
  public $Name; // string
  public $Email; // string
  public $Phone; // string
  public $AgentID; // int
  public $AgentName; // string
}

class PropertyMediaIDsType {
  public $PropertyMediaID; // int
}

class PropertyMediaType {
  public $MediaID; // int
  public $PropertyID; // int
  public $Type; // PropertyMediaImageType
  public $Position; // int
  public $URL; // string
  public $ThumbURL; // string
  public $MediumURL; // string
  public $ViewCount; // int
  public $UUID; // string
  public $Filename; // string
  public $ContentType; // string
  public $ContentLength; // long
  public $Description; // string
  public $UploadedBy; // int
  public $UploadedDate; // dateTime
  public $Status; // string
  public $StatusMessage; // string
  public $StatusDate; // dateTime
  public $MediaLinkID; // int
  public $Delete; // boolean
}

class PropertyHistoryType {
  public $ID; // int
  public $PropertyID; // int
  public $UserID; // int
  public $UserName; // string
  public $UserEmail; // string
  public $EntryDate; // dateTime
  public $Action; // string
  public $Data; // string
}

class PropertyArchiveDetailsType {
  public $ID; // int
  public $PropertyID; // int
  public $PropertyStatus; // PropertyStatusType
  public $Quarter; // string
  public $Lessee; // string
  public $RentPerAnnum; // string
  public $RentPerSqFt; // string
  public $Premium; // string
  public $DateLet; // string
  public $Purchaser; // string
  public $Price; // string
  public $DateSold; // string
  public $Notes; // string
  public $RentReviews; // RentReviewType
  public $RateableValues; // RateableValueType
  public $ArchivedBy; // int
  public $ArchivedDate; // dateTime
}

class RentReviewType {
  public $Date; // string
  public $Rent; // string
}

class RateableValueType {
  public $Value; // string
  public $Year; // string
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

class VisitorIDsType {
  public $ID; // int
}

class VisitorListType {
  public $Visitor; // VisitorType
}

class VisitorNotificationListType {
  public $Notification; // VisitorNotificationType
}

class ArchiveQtrsType {
  public $ArchiveQtr; // string
}

class TenureType {
  const F = 'F';
  const L = 'L';
  const B = 'B';
}

class SearchScopeType {
  const all = 'all';
  const live = 'live';
  const archive = 'archive';
}

class PropertyTypeType {
  const property = 'property';
  const investments = 'investments';
  const developments = 'developments';
}

class PropertyStatusType {
  const Available = 'Available';
  const Under_Offer = 'Under Offer';
  const Sold = 'Sold';
  const Let = 'Let';
  const Withdrawn = 'Withdrawn';
  const Surrendered = 'Surrendered';
  const Disinstructed = 'Disinstructed';
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
  const epc = 'epc';
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

class VisitorNotificationType {
  public $NotificationID; // int
  public $TokenID; // int
  public $VisitorUserID; // int
  public $PropertyID; // int
  public $Action; // string
  public $Context; // string
  public $LoggedDate; // dateTime
  public $TokenLastUsed; // dateTime
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

class UserClipboardType {
  public $ClipboardID; // int
  public $UserID; // int
  public $Name; // string
  public $CreatedDate; // dateTime
  public $UpdatedDate; // dateTime
  public $PropertyIDs; // PropertyIDsType
}

class ClipboardReportFormatType {
  const XLS = 'XLS';
  const DOC = 'DOC';
  const RTF = 'RTF';
}

class GetSectorsType {
  public $AllSectors; // boolean
  public $SectorsWithInstructions; // boolean
}

class SearchPropertyType {
  public $SearchCriteria; // SearchCriteriaType
}

class VisitorCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $AgentID; // int
  public $VisitorIDs; // VisitorIDsType
  public $WithUnprocessedActions; // boolean
  public $Keyword; // string
  public $SortField; // VisitorSortFieldType
  public $SortOrder; // SortOrderType
}

class VisitorNotificationCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $AgentID; // int
  public $VisitorIDs; // VisitorIDsType
  public $SinceDate; // dateTime
  public $OnlyUnprocessed; // boolean
  public $Keyword; // string
  public $SortField; // VisitorSortFieldType
  public $SortOrder; // SortOrderType
}

class SearchCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $DetailLevel; // DetailLevelType
  public $Agent; // string
  public $AgentIDs; // AgentUIDsType
  public $ArchiveQtrs; // ArchiveQtrsType
  public $SortUsingAgents; // SortUsingAgentsType
  public $BuildingNumber; // string
  public $BuildingName; // string
  public $Address; // string
  public $BriefSummary; // string
  public $Tenure; // TenureType
  public $PropertyType; // PropertyTypeType
  public $PropertyStatus; // PropertyStatusType
  public $Scope; // SearchScopeType
  public $Sectors; // SectorsType
  public $CPDAreaIDs; // CPDAreaIDsType
  public $PortalAreaIDs; // PortalAreaIDsType
  public $Postcode; // string
  public $Postcodes; // PostcodesType
  public $UpdatedFrom; // dateTime
  public $UpdatedTo; // dateTime
  public $UpdatedHours; // int
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
  const Postcode = 'Postcode';
  const Sector = 'Sector';
  const Size = 'Size';
  const Price = 'Price';
  const UpdatedDate = 'UpdatedDate';
}

class SearchPropertyHistorySortFieldType {
  const EntryDate = 'EntryDate';
}

class VisitorSortFieldType {
  const ID = 'ID';
}

class SearchPropertyHistoryType {
  public $Criteria; // PropertyHistoryCriteriaType
}

class PropertyHistoryCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $SortField; // SearchPropertyHistorySortFieldType
  public $SortOrder; // SortOrderType
  public $AgentID; // int
  public $PropertyID; // int
}

class PostcodesType {
  public $Postcode; // string
}

class RegisterInterestType {
  public $PropertyID; // int
  public $ServiceContext; // string
}

class ViewPropertyType {
  public $PropertyID; // int
  public $ServiceContext; // string
  public $RegistrationNotRequired; // boolean
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
  public $ArchiveDetails; // PropertyArchiveDetailsType
}

class RestorePropertyType {
  public $PropertyID; // int
}

class UploadMediaType {
  public $MediaData; // base64
  public $MediaType; // PropertyMediaImageType
  public $ContentType; // string
  public $Filename; // string
}

class ReprocessMediaType {
  public $MediaID; // int
}

class MediaStatusType {
  public $MediaIDs; // PropertyMediaIDsType
}

class PostcodeLookupType {
  public $Postcode; // string
}

class SearchVisitorsType {
  public $Criteria; // VisitorCriteriaType
}

class SearchVisitorNotificationsType {
  public $Criteria; // VisitorNotificationCriteriaType
}

class ProcessVisitorNotificationsType {
  public $ID; // int
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

class CreateClipboardType {
  public $Name; // string
}

class AddToClipboardType {
  public $ClipboardID; // int
  public $PropertyID; // int
}

class RemoveFromClipboardType {
  public $ClipboardID; // int
  public $PropertyID; // int
}

class ListClipboardsType {
  public $UserID; // int
}

class RenameClipboardType {
  public $ClipboardID; // int
  public $Name; // string
}

class DeleteClipboardType {
  public $ClipboardID; // int
}

class ClipboardReportType {
  public $ClipboardID; // int
  public $IncludeAgentContact; // boolean
  public $IncludePhotos; // boolean
  public $AgentReport; // boolean
  public $Format; // ClipboardReportFormatType
}

class ViewingPropertyType {
  public $PropertyID; // int
  public $ServiceContext; // string
}

class ViewingMediaType {
  public $MediaID; // int
  public $ServiceContext; // string
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

class GetRecentVisitorsType {
  public $SinceDate; // dateTime
  public $OnlyUnprocessed; // boolean
}

class ProcessVisitorsType {
  public $ID; // int
  public $SendAgentEmail; // boolean
  public $SendVisitorEmail; // boolean
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

class ViewPropertyResponseType {
  public $PropertyView; // PropertyView
  public $Property; // PropertyType
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
  public $Property; // PropertyType
}

class RestorePropertyResponseType {
  public $Property; // PropertyType
}

class DeletePropertyResponseType {
}

class UploadMediaResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class ReprocessMediaResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class MediaStatusResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class PostcodeLookupResponseType {
  public $PostcodeCoordinates; // PostcodeCoordinates
}

class SearchVisitorsResponseType {
  public $ResultCount; // int
  public $VisitorList; // VisitorListType
}

class SearchVisitorNotificationsResponseType {
  public $ResultCount; // int
  public $Notifications; // VisitorNotificationListType
}

class ProcessVisitorNotificationsResponseType {
  public $Notification; // VisitorNotificationType
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

class CreateClipboardResponseType {
  public $Clipboard; // UserClipboardType
}

class AddToClipboardResponseType {
  public $Clipboard; // UserClipboardType
}

class RemoveFromClipboardResponseType {
  public $Clipboard; // UserClipboardType
}

class ListClipboardsResponseType {
  public $Clipboard; // UserClipboardType
}

class RenameClipboardResponseType {
  public $Clipboard; // UserClipboardType
}

class DeleteClipboardResponseType {
}

class ClipboardReportResponseType {
  public $ContentType; // string
  public $Content; // base64Binary
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

class ValidationExceptionType {
  public $Problem; // ProblemType
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

class PropertyUnchangedExceptionType {
  public $Detail; // string
}

class PropertyNotFoundExceptionType {
  public $Detail; // string
}

class ProblemType {
  public $Field; // string
  public $Description; // string
}

class ViewingPropertyResponseType {
  public $PropertyView; // PropertyView
  public $Property; // PropertyType
}

class ViewingMediaResponseType {
  public $PropertyMediaView; // PropertyMediaView
  public $PropertyMedia; // PropertyMediaType
}

class AttachMediaResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class SwapMediaPositionResponseType {
}

class RemoveMediaResponseType {
}

class ListMediaResponseType {
  public $PropertyMedia; // PropertyMediaType
}

class GetRecentVisitorsResponseType {
  public $Visitor; // VisitorType
}

class ProcessVisitorsResponseType {
  public $Visitor; // VisitorType
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
                                    'PropertyContactType' => 'PropertyContactType',
                                    'PropertyMediaIDsType' => 'PropertyMediaIDsType',
                                    'PropertyMediaType' => 'PropertyMediaType',
                                    'PropertyHistoryType' => 'PropertyHistoryType',
                                    'PropertyArchiveDetailsType' => 'PropertyArchiveDetailsType',
                                    'RentReviewType' => 'RentReviewType',
                                    'RateableValueType' => 'RateableValueType',
                                    'SortUsingAgentsType' => 'SortUsingAgentsType',
                                    'CPDAreaIDsType' => 'CPDAreaIDsType',
                                    'PortalAreaIDsType' => 'PortalAreaIDsType',
                                    'SectorsType' => 'SectorsType',
                                    'AgentUIDsType' => 'AgentUIDsType',
                                    'VisitorIDsType' => 'VisitorIDsType',
                                    'VisitorListType' => 'VisitorListType',
                                    'VisitorNotificationListType' => 'VisitorNotificationListType',
                                    'ArchiveQtrsType' => 'ArchiveQtrsType',
                                    'TenureType' => 'TenureType',
                                    'SearchScopeType' => 'SearchScopeType',
                                    'PropertyTypeType' => 'PropertyTypeType',
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
                                    'VisitorNotificationType' => 'VisitorNotificationType',
                                    'CPDAreaType' => 'CPDAreaType',
                                    'CPDPostcodePrefixType' => 'CPDPostcodePrefixType',
                                    'UserClipboardType' => 'UserClipboardType',
                                    'ClipboardReportFormatType' => 'ClipboardReportFormatType',
                                    'DetailLevelType' => 'DetailLevelType',
                                    'SortOrderType' => 'SortOrderType',
                                    'EmailType' => 'EmailType',
                                    'AuthenticationExceptionType' => 'AuthenticationExceptionType',
                                    'InvalidTokenExceptionType' => 'InvalidTokenExceptionType',
                                    'AccessDeniedExceptionType' => 'AccessDeniedExceptionType',
                                    'GetSectorsType' => 'GetSectorsType',
                                    'SearchPropertyType' => 'SearchPropertyType',
                                    'VisitorCriteriaType' => 'VisitorCriteriaType',
                                    'VisitorNotificationCriteriaType' => 'VisitorNotificationCriteriaType',
                                    'SearchCriteriaType' => 'SearchCriteriaType',
                                    'SearchPropertiesSortFieldType' => 'SearchPropertiesSortFieldType',
                                    'SearchPropertyHistorySortFieldType' => 'SearchPropertyHistorySortFieldType',
                                    'VisitorSortFieldType' => 'VisitorSortFieldType',
                                    'SearchPropertyHistoryType' => 'SearchPropertyHistoryType',
                                    'PropertyHistoryCriteriaType' => 'PropertyHistoryCriteriaType',
                                    'PostcodesType' => 'PostcodesType',
                                    'RegisterInterestType' => 'RegisterInterestType',
                                    'ViewPropertyType' => 'ViewPropertyType',
                                    'ViewMediaType' => 'ViewMediaType',
                                    'CreatePropertyType' => 'CreatePropertyType',
                                    'UpdatePropertyType' => 'UpdatePropertyType',
                                    'DuplicatePropertyType' => 'DuplicatePropertyType',
                                    'DeletePropertyType' => 'DeletePropertyType',
                                    'ArchivePropertyType' => 'ArchivePropertyType',
                                    'RestorePropertyType' => 'RestorePropertyType',
                                    'UploadMediaType' => 'UploadMediaType',
                                    'ReprocessMediaType' => 'ReprocessMediaType',
                                    'MediaStatusType' => 'MediaStatusType',
                                    'PostcodeLookupType' => 'PostcodeLookupType',
                                    'SearchVisitorsType' => 'SearchVisitorsType',
                                    'SearchVisitorNotificationsType' => 'SearchVisitorNotificationsType',
                                    'ProcessVisitorNotificationsType' => 'ProcessVisitorNotificationsType',
                                    'CPDAreaCriteriaType' => 'CPDAreaCriteriaType',
                                    'SearchCPDAreasType' => 'SearchCPDAreasType',
                                    'CPDPostcodePrefixesCriteriaType' => 'CPDPostcodePrefixesCriteriaType',
                                    'SearchCPDPostcodePrefixesType' => 'SearchCPDPostcodePrefixesType',
                                    'CreateCPDPostcodePrefixType' => 'CreateCPDPostcodePrefixType',
                                    'RemoveCPDPostcodePrefixType' => 'RemoveCPDPostcodePrefixType',
                                    'CreateClipboardType' => 'CreateClipboardType',
                                    'AddToClipboardType' => 'AddToClipboardType',
                                    'RemoveFromClipboardType' => 'RemoveFromClipboardType',
                                    'ListClipboardsType' => 'ListClipboardsType',
                                    'RenameClipboardType' => 'RenameClipboardType',
                                    'DeleteClipboardType' => 'DeleteClipboardType',
                                    'ClipboardReportType' => 'ClipboardReportType',
                                    'ViewingPropertyType' => 'ViewingPropertyType',
                                    'ViewingMediaType' => 'ViewingMediaType',
                                    'AttachMediaType' => 'AttachMediaType',
                                    'ListMediaType' => 'ListMediaType',
                                    'SwapMediaPositionType' => 'SwapMediaPositionType',
                                    'RemoveMediaType' => 'RemoveMediaType',
                                    'GetRecentVisitorsType' => 'GetRecentVisitorsType',
                                    'ProcessVisitorsType' => 'ProcessVisitorsType',
                                    'GetSectorsResponseType' => 'GetSectorsResponseType',
                                    'SearchPropertyResponseType' => 'SearchPropertyResponseType',
                                    'SearchPropertyHistoryResponseType' => 'SearchPropertyHistoryResponseType',
                                    'RegisterInterestResponseType' => 'RegisterInterestResponseType',
                                    'ViewPropertyResponseType' => 'ViewPropertyResponseType',
                                    'ViewMediaResponseType' => 'ViewMediaResponseType',
                                    'CreatePropertyResponseType' => 'CreatePropertyResponseType',
                                    'UpdatePropertyResponseType' => 'UpdatePropertyResponseType',
                                    'DuplicatePropertyResponseType' => 'DuplicatePropertyResponseType',
                                    'ArchivePropertyResponseType' => 'ArchivePropertyResponseType',
                                    'RestorePropertyResponseType' => 'RestorePropertyResponseType',
                                    'DeletePropertyResponseType' => 'DeletePropertyResponseType',
                                    'UploadMediaResponseType' => 'UploadMediaResponseType',
                                    'ReprocessMediaResponseType' => 'ReprocessMediaResponseType',
                                    'MediaStatusResponseType' => 'MediaStatusResponseType',
                                    'PostcodeLookupResponseType' => 'PostcodeLookupResponseType',
                                    'SearchVisitorsResponseType' => 'SearchVisitorsResponseType',
                                    'SearchVisitorNotificationsResponseType' => 'SearchVisitorNotificationsResponseType',
                                    'ProcessVisitorNotificationsResponseType' => 'ProcessVisitorNotificationsResponseType',
                                    'SearchCPDAreasResponseType' => 'SearchCPDAreasResponseType',
                                    'SearchCPDPostcodePrefixesResponseType' => 'SearchCPDPostcodePrefixesResponseType',
                                    'CreateCPDPostcodePrefixResponseType' => 'CreateCPDPostcodePrefixResponseType',
                                    'RemoveCPDPostcodePrefixResponseType' => 'RemoveCPDPostcodePrefixResponseType',
                                    'CreateClipboardResponseType' => 'CreateClipboardResponseType',
                                    'AddToClipboardResponseType' => 'AddToClipboardResponseType',
                                    'RemoveFromClipboardResponseType' => 'RemoveFromClipboardResponseType',
                                    'ListClipboardsResponseType' => 'ListClipboardsResponseType',
                                    'RenameClipboardResponseType' => 'RenameClipboardResponseType',
                                    'DeleteClipboardResponseType' => 'DeleteClipboardResponseType',
                                    'ClipboardReportResponseType' => 'ClipboardReportResponseType',
                                    'SectorListType' => 'SectorListType',
                                    'PropertyMediaListType' => 'PropertyMediaListType',
                                    'PropertyHistoryListType' => 'PropertyHistoryListType',
                                    'ValidationExceptionType' => 'ValidationExceptionType',
                                    'UnconfirmedUserExceptionType' => 'UnconfirmedUserExceptionType',
                                    'InvalidMediaTypeExceptionType' => 'InvalidMediaTypeExceptionType',
                                    'PostcodeNotFoundExceptionType' => 'PostcodeNotFoundExceptionType',
                                    'CPDPostcodePrefixNotFoundExceptionType' => 'CPDPostcodePrefixNotFoundExceptionType',
                                    'PropertyUnchangedExceptionType' => 'PropertyUnchangedExceptionType',
                                    'PropertyNotFoundExceptionType' => 'PropertyNotFoundExceptionType',
                                    'ProblemType' => 'ProblemType',
                                    'ViewingPropertyResponseType' => 'ViewingPropertyResponseType',
                                    'ViewingMediaResponseType' => 'ViewingMediaResponseType',
                                    'AttachMediaResponseType' => 'AttachMediaResponseType',
                                    'SwapMediaPositionResponseType' => 'SwapMediaPositionResponseType',
                                    'RemoveMediaResponseType' => 'RemoveMediaResponseType',
                                    'ListMediaResponseType' => 'ListMediaResponseType',
                                    'GetRecentVisitorsResponseType' => 'GetRecentVisitorsResponseType',
                                    'ProcessVisitorsResponseType' => 'ProcessVisitorsResponseType',
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
   * @param ReprocessMediaType $request
   * @return ReprocessMediaResponseType
   */
  public function ReprocessMedia(ReprocessMediaType $request) {
    return $this->__soapCall('ReprocessMedia', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param MediaStatusType $request
   * @return MediaStatusResponseType
   */
  public function MediaStatus(MediaStatusType $request) {
    return $this->__soapCall('MediaStatus', array($request),       array(
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
   * @param SearchVisitorsType $request
   * @return SearchVisitorsResponseType
   */
  public function SearchVisitors(SearchVisitorsType $request) {
    return $this->__soapCall('SearchVisitors', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchVisitorNotificationsType $request
   * @return SearchVisitorNotificationsResponseType
   */
  public function SearchVisitorNotifications(SearchVisitorNotificationsType $request) {
    return $this->__soapCall('SearchVisitorNotifications', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ProcessVisitorNotificationsType $request
   * @return ProcessVisitorNotificationsResponseType
   */
  public function ProcessVisitorNotifications(ProcessVisitorNotificationsType $request) {
    return $this->__soapCall('ProcessVisitorNotifications', array($request),       array(
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

  /**
   *  
   *
   * @param CreateClipboardType $request
   * @return CreateClipboardResponseType
   */
  public function CreateClipboard(CreateClipboardType $request) {
    return $this->__soapCall('CreateClipboard', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param AddToClipboardType $request
   * @return AddToClipboardResponseType
   */
  public function AddToClipboard(AddToClipboardType $request) {
    return $this->__soapCall('AddToClipboard', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param RemoveFromClipboardType $request
   * @return RemoveFromClipboardResponseType
   */
  public function RemoveFromClipboard(RemoveFromClipboardType $request) {
    return $this->__soapCall('RemoveFromClipboard', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ListClipboardsType $request
   * @return ListClipboardsResponseType
   */
  public function ListClipboards(ListClipboardsType $request) {
    return $this->__soapCall('ListClipboards', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param RenameClipboardType $request
   * @return RenameClipboardResponseType
   */
  public function RenameClipboard(RenameClipboardType $request) {
    return $this->__soapCall('RenameClipboard', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param DeleteClipboardType $request
   * @return DeleteClipboardResponseType
   */
  public function DeleteClipboard(DeleteClipboardType $request) {
    return $this->__soapCall('DeleteClipboard', array($request),       array(
            'uri' => 'http://property.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ClipboardReportType $request
   * @return ClipboardReportResponseType
   */
  public function ClipboardReport(ClipboardReportType $request) {
    return $this->__soapCall('ClipboardReport', array($request),       array(
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

}

?>
