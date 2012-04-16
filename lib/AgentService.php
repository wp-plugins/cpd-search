<?php

class AgentsType {
  public $Agent; // AgentType
}

class AgentType {
  public $UID; // int
  public $Ref; // string
  public $Name; // string
  public $Contact; // string
  public $Email; // string
  public $Address1; // string
  public $Address2; // string
  public $Postcode; // string
  public $Country; // string
  public $Tel; // string
  public $Fax; // string
  public $Url; // anyURI
  public $Services; // ServicesType
  public $TopImgUrl; // anyURI
  public $BottomImgUrl; // anyURI
  public $Type; // string
  public $SodEmail; // string
  public $CSSUrl; // anyURI
  public $MapImgUrl; // anyURI
  public $CCEmail; // string
  public $Invoicing; // boolean
  public $DaysSearchable; // integer
  public $DemoStatus; // boolean
  public $Sectors; // SectorsType
  public $Areas; // AreasType
  public $DefaultMethod; // string
  public $SpecifyMethod; // string
  public $SodDays; // integer
  public $AutomatchPeriod; // integer
  public $AutomatchEmail; // string
  public $Discount; // integer
  public $TemplateVersion; // integer
  public $ReportStyle; // string
  public $EmailSubscription; // boolean
}

class ServicesType {
  public $Service; // string
}

class ReferencesType {
  public $Ref; // string
}

class EmailType {
}

class AuthenticateAgentType {
  public $AgentRef; // string
  public $Password; // string
}

class SearchAgentType {
  public $AgentSearchCriteria; // AgentSearchCriteriaType
}

class AgentSearchCriteriaType {
  public $AreaID; // integer
  public $PortalID; // integer
  public $Owner; // string
  public $Contact; // string
  public $Email; // EmailType
  public $MembersOnly; // boolean
  public $AdminsOnly; // boolean
  public $AgentsOnly; // boolean
  public $Type; // string
  public $References; // ReferencesType
  public $WithOwner; // boolean
  public $WithoutOwner; // boolean
  public $Keyword; // string
  public $SortField; // string
  public $SortOrder; // SortOrderType
}

class AuthenticateAgentResponseType {
  public $Token; // string
  public $Agent; // AgentType
}

class SearchAgentResponseType {
  public $ResultCount; // integer
  public $Agents; // AgentsType
}

class AreasType {
  public $Area; // string
}


/**
 * AgentService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class AgentService extends SoapClient {

  private static $classmap = array(
                                    'InvalidTokenExceptionType' => 'InvalidTokenExceptionType',
                                    'UnconfirmedUserExceptionType' => 'UnconfirmedUserExceptionType',
                                    'AuthenticationFailedExceptionType' => 'AuthenticationFailedExceptionType',
                                    'AgentsType' => 'AgentsType',
                                    'AgentType' => 'AgentType',
                                    'ServicesType' => 'ServicesType',
                                    'ReferencesType' => 'ReferencesType',
                                    'EmailType' => 'EmailType',
                                    'AuthenticateAgentType' => 'AuthenticateAgentType',
                                    'SearchAgentType' => 'SearchAgentType',
                                    'AgentSearchCriteriaType' => 'AgentSearchCriteriaType',
                                    'SortOrderType' => 'SortOrderType',
                                    'AuthenticateAgentResponseType' => 'AuthenticateAgentResponseType',
                                    'SearchAgentResponseType' => 'SearchAgentResponseType',
                                    'AreasType' => 'AreasType',
                                   );

  public function AgentService($wsdl = "http://staging.cpd.co.uk/soap/services/AgentService?wsdl", $options = array()) {
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
   * @param AuthenticateAgentType $parameters
   * @return AuthenticateAgentResponseType
   */
  public function AuthenticateAgent(AuthenticateAgentType $parameters) {
    return $this->__soapCall('AuthenticateAgent', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchAgentType $parameters
   * @return SearchAgentResponseType
   */
  public function SearchAgent(SearchAgentType $parameters) {
    return $this->__soapCall('SearchAgent', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

}

?>
