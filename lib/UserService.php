<?php
class UserIDsType {
  public $ID; // int
}

class UsersType {
  public $User; // UserType
}

class UserType {
  public $UID; // int
  public $AgentID; // int
  public $Name; // string
  public $Email; // string
  public $Phone; // string
  public $Confirmed; // boolean
  public $Newsletter; // boolean
  public $RegistrationContext; // string
  public $RegistrationDate; // dateTime
}

class UsersHistoryListType {
  public $UsersHistory; // UsersHistoryType
}

class UsersHistoryType {
  public $ID; // int
  public $UserID; // int
  public $EntryDate; // dateTime
  public $Action; // string
  public $Data; // string
}

class RegisterUserType {
  public $Name; // string
  public $Email; // string
  public $Phone; // string
  public $Agent; // string
  public $Password; // string
  public $ServiceContext; // string
}

class VerifyUserType {
  public $Token; // string
  public $ServiceContext; // string
}

class PasswordResetType {
  public $Email; // string
  public $Agent; // string
  public $ServiceContext; // string
}

class PasswordChangeType {
  public $Token; // string
  public $Password; // string
  public $Agent; // string
}

class AuthenticateUserType {
  public $Email; // string
  public $Password; // string
  public $ServiceContext; // string
  public $Agent; // string
}

class FetchPreferencesType {
  public $Token; // string
}

class UpdatePreferencesType {
  public $Token; // string
  public $User; // UserType
}

class CreateUserType {
  public $Token; // string
  public $User; // UserType
}

class UpdateUserType {
  public $Token; // string
  public $User; // UserType
}

class SearchUsersType {
  public $Token; // string
  public $SearchUsersCriteria; // SearchUsersCriteriaType
}

class SearchUsersCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $DetailLevel; // DetailLevelType
  public $AgentID; // int
  public $UserIDs; // UserIDsType
  public $Keyword; // string
  public $SortField; // string
  public $SortOrder; // SortOrderType
}

class SearchUsersHistoryType {
  public $Token; // string
  public $Criteria; // UsersHistoryCriteriaType
}

class UsersHistoryCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $SortField; // SearchUsersSortFieldType
  public $SortOrder; // SortOrderType
  public $UserID; // int
  public $StartTime; // dateTime
  public $EndTime; // dateTime
}

class RegisterUserResponseType {
  public $Token; // string
  public $User; // UserType
}

class VerifyUserResponseType {
  public $Token; // string
  public $User; // UserType
}

class PasswordResetResponseType {
}

class PasswordChangeResponseType {
  public $Problem; // string
}

class AuthenticateUserResponseType {
  public $Token; // string
  public $User; // UserType
}

class FetchPreferencesResponseType {
  public $User; // UserType
}

class UpdatePreferencesResponseType {
  public $User; // UserType
}

class CreateUserResponseType {
  public $User; // UserType
}

class UpdateUserResponseType {
  public $User; // UserType
}

class SearchUsersResponseType {
  public $ResultCount; // int
  public $Users; // UsersType
}

class SearchUsersHistoryResponseType {
  public $ResultCount; // int
  public $UsersHistoryList; // UsersHistoryListType
}

class UserNotFoundExceptionType {
  public $Detail; // string
}

class UserUnconfirmedExceptionType {
  public $Detail; // string
}

class AuthenticateUserExceptionType {
  public $Detail; // string
}

class InvalidUserTokenExceptionType {
  public $Detail; // string
}

class UserAlreadyExistsExceptionType {
  public $Detail; // string
}

class UserAlreadyConfirmedExceptionType {
  public $Detail; // string
}

class UserUnchangedExceptionType {
  public $Detail; // string
}


/**
 * UserService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class UserService extends SoapClient {

  private static $classmap = array(
                                    'UserIDsType' => 'UserIDsType',
                                    'UsersType' => 'UsersType',
                                    'UserType' => 'UserType',
                                    'UsersHistoryListType' => 'UsersHistoryListType',
                                    'UsersHistoryType' => 'UsersHistoryType',
                                    'RegisterUserType' => 'RegisterUserType',
                                    'VerifyUserType' => 'VerifyUserType',
                                    'PasswordResetType' => 'PasswordResetType',
                                    'PasswordChangeType' => 'PasswordChangeType',
                                    'AuthenticateUserType' => 'AuthenticateUserType',
                                    'FetchPreferencesType' => 'FetchPreferencesType',
                                    'UpdatePreferencesType' => 'UpdatePreferencesType',
                                    'CreateUserType' => 'CreateUserType',
                                    'UpdateUserType' => 'UpdateUserType',
                                    'SearchUsersType' => 'SearchUsersType',
                                    'SearchUsersCriteriaType' => 'SearchUsersCriteriaType',
                                    'SearchUsersHistoryType' => 'SearchUsersHistoryType',
                                    'UsersHistoryCriteriaType' => 'UsersHistoryCriteriaType',
                                    'RegisterUserResponseType' => 'RegisterUserResponseType',
                                    'VerifyUserResponseType' => 'VerifyUserResponseType',
                                    'PasswordResetResponseType' => 'PasswordResetResponseType',
                                    'PasswordChangeResponseType' => 'PasswordChangeResponseType',
                                    'AuthenticateUserResponseType' => 'AuthenticateUserResponseType',
                                    'FetchPreferencesResponseType' => 'FetchPreferencesResponseType',
                                    'UpdatePreferencesResponseType' => 'UpdatePreferencesResponseType',
                                    'CreateUserResponseType' => 'CreateUserResponseType',
                                    'UpdateUserResponseType' => 'UpdateUserResponseType',
                                    'SearchUsersResponseType' => 'SearchUsersResponseType',
                                    'SearchUsersHistoryResponseType' => 'SearchUsersHistoryResponseType',
                                    'UserNotFoundExceptionType' => 'UserNotFoundExceptionType',
                                    'UserUnconfirmedExceptionType' => 'UserUnconfirmedExceptionType',
                                    'AuthenticateUserExceptionType' => 'AuthenticateUserExceptionType',
                                    'InvalidUserTokenExceptionType' => 'InvalidUserTokenExceptionType',
                                    'UserAlreadyExistsExceptionType' => 'UserAlreadyExistsExceptionType',
                                    'UserAlreadyConfirmedExceptionType' => 'UserAlreadyConfirmedExceptionType',
                                    'UserUnchangedExceptionType' => 'UserUnchangedExceptionType',
                                   );

  public function UserService($wsdl = "https://staging.cpd.co.uk/soap/services/UserService?wsdl", $options = array()) {
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
   * @param RegisterUserType $registerUserRequest
   * @return RegisterUserResponseType
   */
  public function RegisterUser(RegisterUserType $registerUserRequest) {
    return $this->__soapCall('RegisterUser', array($registerUserRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param VerifyUserType $verifyUserRequest
   * @return VerifyUserResponseType
   */
  public function VerifyUser(VerifyUserType $verifyUserRequest) {
    return $this->__soapCall('VerifyUser', array($verifyUserRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param PasswordResetType $passwordResetRequest
   * @return PasswordResetResponseType
   */
  public function PasswordReset(PasswordResetType $passwordResetRequest) {
    return $this->__soapCall('PasswordReset', array($passwordResetRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param PasswordChangeType $passwordChangeRequest
   * @return PasswordChangeResponseType
   */
  public function PasswordChange(PasswordChangeType $passwordChangeRequest) {
    return $this->__soapCall('PasswordChange', array($passwordChangeRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param AuthenticateUserType $authenticateUserRequest
   * @return AuthenticateUserResponseType
   */
  public function AuthenticateUser(AuthenticateUserType $authenticateUserRequest) {
    return $this->__soapCall('AuthenticateUser', array($authenticateUserRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param FetchPreferencesType $fetchPreferencesRequest
   * @return FetchPreferencesResponseType
   */
  public function FetchPreferences(FetchPreferencesType $fetchPreferencesRequest) {
    return $this->__soapCall('FetchPreferences', array($fetchPreferencesRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param UpdatePreferencesType $updatePreferencesRequest
   * @return UpdatePreferencesResponseType
   */
  public function UpdatePreferences(UpdatePreferencesType $updatePreferencesRequest) {
    return $this->__soapCall('UpdatePreferences', array($updatePreferencesRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param CreateUserType $searchUsersRequest
   * @return CreateUserResponseType
   */
  public function CreateUser(CreateUserType $searchUsersRequest) {
    return $this->__soapCall('CreateUser', array($searchUsersRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param UpdateUserType $searchUsersRequest
   * @return UpdateUserResponseType
   */
  public function UpdateUser(UpdateUserType $searchUsersRequest) {
    return $this->__soapCall('UpdateUser', array($searchUsersRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchUsersType $searchUsersRequest
   * @return SearchUsersResponseType
   */
  public function SearchUsers(SearchUsersType $searchUsersRequest) {
    return $this->__soapCall('SearchUsers', array($searchUsersRequest),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SearchUsersHistoryType $parameters
   * @return SearchUsersHistoryResponseType
   */
  public function SearchUsersHistory(SearchUsersHistoryType $parameters) {
    return $this->__soapCall('SearchUsersHistory', array($parameters),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

}

?>
