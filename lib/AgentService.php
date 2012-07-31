<?php
class AgentIDsType {
  public $ID; // int
}

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
  public $Type; // string
  public $DaysSearchable; // int
  public $SodEmail; // string
  public $SodDays; // int
  public $AutomatchPeriod; // int
  public $AutomatchEmail; // string
  public $Discount; // int
  public $MasterUserID; // int
  public $AccountID; // int
  public $CreatedBy; // int
  public $CreatedDate; // dateTime
  public $UpdatedBy; // int
  public $UpdatedDate; // dateTime
  public $ArchivedBy; // int
  public $ArchivedDate; // dateTime
}

class AccountsType {
  public $Account; // AccountType
}

class AccountType {
  public $ID; // int
  public $Name; // string
}

class TransactionsType {
  public $Transaction; // TransactionType
}

class TransactionType {
  public $ID; // int
  public $EntryDate; // dateTime
  public $Description; // string
  public $FromAccountID; // int
  public $ToAccountID; // int
  public $Amount; // float
  public $Cleared; // boolean
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

class SearchAgentsType {
  public $Token; // string
  public $SearchAgentsCriteria; // SearchAgentsCriteriaType
}

class SearchAgentsCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $DetailLevel; // DetailLevelType
  public $AreaID; // int
  public $PortalID; // int
  public $Owner; // string
  public $Contact; // string
  public $Email; // EmailType
  public $MembersOnly; // boolean
  public $AdminsOnly; // boolean
  public $AgentsOnly; // boolean
  public $Type; // string
  public $AgentIDs; // AgentIDsType
  public $References; // ReferencesType
  public $WithOwner; // boolean
  public $WithoutOwner; // boolean
  public $Keyword; // string
  public $SortField; // string
  public $SortOrder; // SortOrderType
}

class CreateAgentType {
  public $Token; // string
  public $Agent; // AgentType
}

class UpdateAgentType {
  public $Token; // string
  public $Agent; // AgentType
}

class ResetAgentPasswordType {
  public $Token; // string
  public $UID; // int
  public $NewPassword; // string
}

class SearchAgentTokensType {
  public $Token; // string
  public $AgentTokensSearchCriteria; // AgentTokensSearchCriteriaType
}

class AgentTokensSearchCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $UID; // int
  public $Keyword; // string
  public $SortField; // string
  public $SortOrder; // SortOrderType
}

class ListAgentServicesType {
  public $Token; // string
}

class SearchAgentTransactionsType {
  public $Token; // string
  public $Criteria; // TransactionsCriteriaType
}

class TransactionsCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $AccountID; // int
  public $Keyword; // string
  public $FromDate; // dateTime
  public $ToDate; // dateTime
  public $SortField; // string
  public $SortOrder; // SortOrderType
}

class AuthenticateAgentResponseType {
  public $Token; // string
  public $Agent; // AgentType
}

class SearchAgentsResponseType {
  public $ResultCount; // int
  public $Agents; // AgentsType
}

class CreateAgentResponseType {
  public $Agent; // AgentType
}

class UpdateAgentResponseType {
  public $Agent; // AgentType
}

class ResetAgentPasswordResponseType {
}

class SearchAgentTokensResponseType {
  public $ResultCount; // int
  public $AgentTokens; // AgentTokensType
}

class AreasType {
  public $Area; // string
}

class AgentTokenType {
  public $UID; // int
  public $Token; // string
  public $Type; // string
  public $CreatedDate; // dateTime
  public $LastUsed; // dateTime
  public $ExpiryDate; // dateTime
}

class AgentTokensType {
  public $AgentToken; // AgentTokenType
}

class ListAgentServicesResponseType {
  public $ResultCount; // int
  public $AgentServices; // AgentServicesType
}

class AgentServiceType {
  public $ID; // string
  public $Name; // string
  public $Description; // string
}

class AgentServicesType {
  public $AgentService; // AgentServiceType
}

class SearchAgentTransactionsResponseType {
  public $ResultCount; // int
  public $Transactions; // TransactionsType
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
                                    'AgentIDsType' => 'AgentIDsType',
                                    'AgentsType' => 'AgentsType',
                                    'AgentType' => 'AgentType',
                                    'AccountsType' => 'AccountsType',
                                    'AccountType' => 'AccountType',
                                    'TransactionsType' => 'TransactionsType',
                                    'TransactionType' => 'TransactionType',
                                    'ServicesType' => 'ServicesType',
                                    'ReferencesType' => 'ReferencesType',
                                    'EmailType' => 'EmailType',
                                    'AuthenticateAgentType' => 'AuthenticateAgentType',
                                    'SearchAgentsType' => 'SearchAgentsType',
                                    'SearchAgentsCriteriaType' => 'SearchAgentsCriteriaType',
                                    'CreateAgentType' => 'CreateAgentType',
                                    'UpdateAgentType' => 'UpdateAgentType',
                                    'ResetAgentPasswordType' => 'ResetAgentPasswordType',
                                    'SearchAgentTokensType' => 'SearchAgentTokensType',
                                    'AgentTokensSearchCriteriaType' => 'AgentTokensSearchCriteriaType',
                                    'ListAgentServicesType' => 'ListAgentServicesType',
                                    'SearchAgentTransactionsType' => 'SearchAgentTransactionsType',
                                    'TransactionsCriteriaType' => 'TransactionsCriteriaType',
                                    'AuthenticateAgentResponseType' => 'AuthenticateAgentResponseType',
                                    'SearchAgentsResponseType' => 'SearchAgentsResponseType',
                                    'CreateAgentResponseType' => 'CreateAgentResponseType',
                                    'UpdateAgentResponseType' => 'UpdateAgentResponseType',
                                    'ResetAgentPasswordResponseType' => 'ResetAgentPasswordResponseType',
                                    'SearchAgentTokensResponseType' => 'SearchAgentTokensResponseType',
                                    'AreasType' => 'AreasType',
                                    'AgentTokenType' => 'AgentTokenType',
                                    'AgentTokensType' => 'AgentTokensType',
                                    'ListAgentServicesResponseType' => 'ListAgentServicesResponseType',
                                    'AgentServiceType' => 'AgentServiceType',
                                    'AgentServicesType' => 'AgentServicesType',
                                    'SearchAgentTransactionsResponseType' => 'SearchAgentTransactionsResponseType',
                                   );

  public function AgentService($wsdl = "https://staging.cpd.co.uk/soap/services/AgentService?wsdl", $options = array()) {
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
   * @param SearchAgentsType $parameters
   * @return SearchAgentsResponseType
   */
  public function SearchAgents(SearchAgentsType $parameters) {
    return $this->__soapCall('SearchAgents', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CreateAgentType $parameters
   * @return CreateAgentResponseType
   */
  public function CreateAgent(CreateAgentType $parameters) {
    return $this->__soapCall('CreateAgent', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param UpdateAgentType $parameters
   * @return UpdateAgentResponseType
   */
  public function UpdateAgent(UpdateAgentType $parameters) {
    return $this->__soapCall('UpdateAgent', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ResetAgentPasswordType $parameters
   * @return ResetAgentPasswordResponseType
   */
  public function ResetAgentPassword(ResetAgentPasswordType $parameters) {
    return $this->__soapCall('ResetAgentPassword', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchAgentTokensType $parameters
   * @return SearchAgentTokensResponseType
   */
  public function SearchAgentTokens(SearchAgentTokensType $parameters) {
    return $this->__soapCall('SearchAgentTokens', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param ListAgentServicesType $parameters
   * @return ListAgentServicesResponseType
   */
  public function ListAgentServices(ListAgentServicesType $parameters) {
    return $this->__soapCall('ListAgentServices', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchAgentTransactionsType $parameters
   * @return SearchAgentTransactionsResponseType
   */
  public function SearchAgentTransactions(SearchAgentTransactionsType $parameters) {
    return $this->__soapCall('SearchAgentTransactions', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

}

?>
