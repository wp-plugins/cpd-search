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
  public $Sectors; // AgentSectorsType
  public $Type; // string
  public $DaysSearchable; // int
  public $SodEmail; // string
  public $SodDays; // int
  public $AutomatchPeriod; // int
  public $AutomatchEmail; // string
  public $SenderEmail; // string
  public $Discount; // int
  public $UpdateCharge; // float
  public $UserVerificationURL; // string
  public $UserPasswordChangeURL; // string
  public $MasterUserID; // int
  public $AccountID; // int
  public $CreatedBy; // int
  public $CreatedDate; // dateTime
  public $UpdatedBy; // int
  public $UpdatedDate; // dateTime
  public $ArchivedBy; // int
  public $ArchivedDate; // dateTime
}

class AccountIDsType {
  public $ID; // int
}

class AccountsType {
  public $Account; // AccountType
}

class AccountType {
  public $ID; // int
  public $Name; // string
}

class TransactionIDsType {
  public $ID; // int
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

class AgentsHistoryListType {
  public $AgentsHistory; // AgentsHistoryType
}

class AgentsHistoryType {
  public $ID; // int
  public $AgentID; // int
  public $UserID; // int
  public $EntryDate; // dateTime
  public $Action; // string
  public $Data; // string
}

class AgentSectorsType {
  public $Sector; // string
}

class AgentSectorType {
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
  public $ServiceContext; // string
}

class SearchAgentsType {
  public $Token; // string
  public $SearchAgentsCriteria; // SearchAgentsCriteriaType
}

class SearchAgentsCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $DetailLevel; // DetailLevelType
  public $IncludeSectors; // boolean
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

class SearchAgentsHistoryType {
  public $Token; // string
  public $Criteria; // AgentsHistoryCriteriaType
}

class AgentsHistoryCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $SortField; // SearchAgentsSortFieldType
  public $SortOrder; // SortOrderType
  public $AgentID; // int
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
  public $AgentID; // int
  public $Keyword; // string
  public $SortField; // string
  public $SortOrder; // SortOrderType
}

class CreateAgentTokenType {
  public $Token; // string
  public $AgentID; // int
  public $Context; // string
  public $ExpiryDate; // dateTime
}

class RemoveAgentTokenType {
  public $Token; // string
  public $AgentToken; // string
}

class ListAgentServicesType {
  public $Token; // string
}

class SearchAgentAccountsType {
  public $Token; // string
  public $Criteria; // AccountsCriteriaType
}

class AccountsCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $AccountIDs; // AccountIDsType
  public $Keyword; // string
  public $FromDate; // dateTime
  public $ToDate; // dateTime
}

class SearchAgentTransactionsType {
  public $Token; // string
  public $Criteria; // TransactionsCriteriaType
}

class TransactionsCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $AccountID; // int
  public $FromDate; // dateTime
  public $ToDate; // dateTime
}

class CreateTransactionType {
  public $Token; // string
  public $Transaction; // TransactionType
}

class UpdateTransactionType {
  public $Token; // string
  public $Transaction; // TransactionType
}

class DeleteTransactionsType {
  public $Token; // string
  public $ID; // int
}

class AuthenticateAgentResponseType {
  public $Token; // string
  public $Agent; // AgentType
}

class SearchAgentsResponseType {
  public $ResultCount; // int
  public $Agents; // AgentsType
}

class SearchAgentsHistoryResponseType {
  public $ResultCount; // int
  public $AgentsHistoryList; // AgentsHistoryListType
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

class CreateAgentTokenResponseType {
  public $AgentToken; // AgentTokenType
}

class RemoveAgentTokenResponseType {
}

class AreasType {
  public $Area; // string
}

class AgentTokenType {
  public $UID; // int
  public $Token; // string
  public $Type; // string
  public $Context; // string
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

class SearchAgentAccountsResponseType {
  public $ResultCount; // int
  public $Accounts; // AccountsType
}

class SearchAgentTransactionsResponseType {
  public $ResultCount; // int
  public $StartingBalance; // float
  public $Transactions; // TransactionsType
}

class CreateTransactionResponseType {
  public $Transaction; // TransactionType
}

class UpdateTransactionResponseType {
  public $Transaction; // TransactionType
}

class DeleteTransactionsResponseType {
}

class AuthenticateAgentExceptionType {
  public $Detail; // string
}

class InvalidAgentTokenExceptionType {
  public $Detail; // string
}

class UnconfirmedAgentExceptionType {
  public $Detail; // string
}

class AgentAlreadyExistsExceptionType {
  public $Detail; // string
}

class AgentUnchangedExceptionType {
  public $Detail; // string
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
                                    'AccountIDsType' => 'AccountIDsType',
                                    'AccountsType' => 'AccountsType',
                                    'AccountType' => 'AccountType',
                                    'TransactionIDsType' => 'TransactionIDsType',
                                    'TransactionsType' => 'TransactionsType',
                                    'TransactionType' => 'TransactionType',
                                    'AgentsHistoryListType' => 'AgentsHistoryListType',
                                    'AgentsHistoryType' => 'AgentsHistoryType',
                                    'AgentSectorsType' => 'AgentSectorsType',
                                    'AgentSectorType' => 'AgentSectorType',
                                    'ServicesType' => 'ServicesType',
                                    'ReferencesType' => 'ReferencesType',
                                    'EmailType' => 'EmailType',
                                    'AuthenticateAgentType' => 'AuthenticateAgentType',
                                    'SearchAgentsType' => 'SearchAgentsType',
                                    'SearchAgentsCriteriaType' => 'SearchAgentsCriteriaType',
                                    'SearchAgentsHistoryType' => 'SearchAgentsHistoryType',
                                    'AgentsHistoryCriteriaType' => 'AgentsHistoryCriteriaType',
                                    'CreateAgentType' => 'CreateAgentType',
                                    'UpdateAgentType' => 'UpdateAgentType',
                                    'ResetAgentPasswordType' => 'ResetAgentPasswordType',
                                    'SearchAgentTokensType' => 'SearchAgentTokensType',
                                    'AgentTokensSearchCriteriaType' => 'AgentTokensSearchCriteriaType',
                                    'CreateAgentTokenType' => 'CreateAgentTokenType',
                                    'RemoveAgentTokenType' => 'RemoveAgentTokenType',
                                    'ListAgentServicesType' => 'ListAgentServicesType',
                                    'SearchAgentAccountsType' => 'SearchAgentAccountsType',
                                    'AccountsCriteriaType' => 'AccountsCriteriaType',
                                    'SearchAgentTransactionsType' => 'SearchAgentTransactionsType',
                                    'TransactionsCriteriaType' => 'TransactionsCriteriaType',
                                    'CreateTransactionType' => 'CreateTransactionType',
                                    'UpdateTransactionType' => 'UpdateTransactionType',
                                    'DeleteTransactionsType' => 'DeleteTransactionsType',
                                    'AuthenticateAgentResponseType' => 'AuthenticateAgentResponseType',
                                    'SearchAgentsResponseType' => 'SearchAgentsResponseType',
                                    'SearchAgentsHistoryResponseType' => 'SearchAgentsHistoryResponseType',
                                    'CreateAgentResponseType' => 'CreateAgentResponseType',
                                    'UpdateAgentResponseType' => 'UpdateAgentResponseType',
                                    'ResetAgentPasswordResponseType' => 'ResetAgentPasswordResponseType',
                                    'SearchAgentTokensResponseType' => 'SearchAgentTokensResponseType',
                                    'CreateAgentTokenResponseType' => 'CreateAgentTokenResponseType',
                                    'RemoveAgentTokenResponseType' => 'RemoveAgentTokenResponseType',
                                    'AreasType' => 'AreasType',
                                    'AgentTokenType' => 'AgentTokenType',
                                    'AgentTokensType' => 'AgentTokensType',
                                    'ListAgentServicesResponseType' => 'ListAgentServicesResponseType',
                                    'AgentServiceType' => 'AgentServiceType',
                                    'AgentServicesType' => 'AgentServicesType',
                                    'SearchAgentAccountsResponseType' => 'SearchAgentAccountsResponseType',
                                    'SearchAgentTransactionsResponseType' => 'SearchAgentTransactionsResponseType',
                                    'CreateTransactionResponseType' => 'CreateTransactionResponseType',
                                    'UpdateTransactionResponseType' => 'UpdateTransactionResponseType',
                                    'DeleteTransactionsResponseType' => 'DeleteTransactionsResponseType',
                                    'AuthenticateAgentExceptionType' => 'AuthenticateAgentExceptionType',
                                    'InvalidAgentTokenExceptionType' => 'InvalidAgentTokenExceptionType',
                                    'UnconfirmedAgentExceptionType' => 'UnconfirmedAgentExceptionType',
                                    'AgentAlreadyExistsExceptionType' => 'AgentAlreadyExistsExceptionType',
                                    'AgentUnchangedExceptionType' => 'AgentUnchangedExceptionType',
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
   * @param SearchAgentsHistoryType $parameters
   * @return SearchAgentsHistoryResponseType
   */
  public function SearchAgentsHistory(SearchAgentsHistoryType $parameters) {
    return $this->__soapCall('SearchAgentsHistory', array($parameters),       array(
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
   * @param CreateAgentTokenType $parameters
   * @return CreateAgentTokenResponseType
   */
  public function CreateAgentToken(CreateAgentTokenType $parameters) {
    return $this->__soapCall('CreateAgentToken', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param RemoveAgentTokenType $parameters
   * @return RemoveAgentTokenResponseType
   */
  public function RemoveAgentToken(RemoveAgentTokenType $parameters) {
    return $this->__soapCall('RemoveAgentToken', array($parameters),       array(
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
   * @param SearchAgentAccountsType $parameters
   * @return SearchAgentAccountsResponseType
   */
  public function SearchAgentAccounts(SearchAgentAccountsType $parameters) {
    return $this->__soapCall('SearchAgentAccounts', array($parameters),       array(
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

  /**
   *  
   *
   * @param CreateTransactionType $parameters
   * @return CreateTransactionResponseType
   */
  public function CreateTransaction(CreateTransactionType $parameters) {
    return $this->__soapCall('CreateTransaction', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param UpdateTransactionType $parameters
   * @return UpdateTransactionResponseType
   */
  public function UpdateTransaction(UpdateTransactionType $parameters) {
    return $this->__soapCall('UpdateTransaction', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param DeleteTransactionsType $parameters
   * @return DeleteTransactionsResponseType
   */
  public function DeleteTransactions(DeleteTransactionsType $parameters) {
    return $this->__soapCall('DeleteTransactions', array($parameters),       array(
            'uri' => 'http://agent.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

}

?>
