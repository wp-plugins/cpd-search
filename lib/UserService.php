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
  public $AgentName; // string
  public $Name; // string
  public $Email; // string
  public $Phone; // string
  public $Confirmed; // boolean
  public $Newsletter; // boolean
  public $RegistrationContext; // string
  public $RegistrationDate; // dateTime
  public $Permissions; // PermissionsType
  public $TwitterID; // string
  public $SkypeName; // string
  public $GooglePlusID; // string
  public $FacebookEmail; // string
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

class PermissionsType {
  public $Permission; // string
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
  public $Permanently; // boolean
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

class ResetUserPasswordType {
  public $Token; // string
  public $UID; // int
  public $NewPassword; // string
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

class SearchUsersTokensType {
  public $Token; // string
  public $Criteria; // UsersTokensSearchCriteriaType
}

class UsersTokensSearchCriteriaType {
  public $Start; // int
  public $Limit; // int
  public $UID; // int
  public $UserID; // int
  public $Keyword; // string
  public $SortField; // string
  public $SortOrder; // SortOrderType
}

class SearchUsersHistoryType {
  public $Token; // string
  public $Criteria; // UsersHistoryCriteriaType
}

class CreateUsersHistoryType {
  public $Token; // string
  public $UserID; // int
  public $Summary; // string
  public $Data; // string
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

class SetUserConfigType {
  public $Token; // string
  public $UserID; // int
  public $Key; // string
  public $Value; // string
}

class GetUserConfigType {
  public $Token; // string
  public $UserID; // int
  public $Key; // string
}

class GetSessionStatusType {
  public $Token; // string
}

class LogoutType {
  public $Token; // string
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

class ResetUserPasswordResponseType {
}

class SearchUsersResponseType {
  public $ResultCount; // int
  public $Users; // UsersType
}

class SearchUsersTokensResponseType {
  public $ResultCount; // int
  public $Tokens; // UserTokensType
}

class UserTokenType {
  public $UID; // int
  public $Token; // string
  public $Type; // string
  public $Context; // string
  public $CreatedDate; // dateTime
  public $LastUsed; // dateTime
  public $ExpiryDate; // dateTime
}

class UserTokensType {
  public $UserToken; // UserTokenType
}

class SearchUsersHistoryResponseType {
  public $ResultCount; // int
  public $UsersHistoryList; // UsersHistoryListType
}

class CreateUsersHistoryResponseType {
}

class SetUserConfigResponseType {
}

class GetUserConfigResponseType {
  public $Value; // string
}

class GetSessionStatusResponseType {
  public $User; // UserType
}

class LogoutResponseType {
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

class UserConfigKeyNotFoundExceptionType {
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
                                    'PermissionsType' => 'PermissionsType',
                                    'DetailLevelType' => 'DetailLevelType',
                                    'SortOrderType' => 'SortOrderType',
                                    'EmailType' => 'EmailType',
                                    'InvalidTokenExceptionType' => 'InvalidTokenExceptionType',
                                    'AccessDeniedExceptionType' => 'AccessDeniedExceptionType',
                                    'RegisterUserType' => 'RegisterUserType',
                                    'VerifyUserType' => 'VerifyUserType',
                                    'PasswordResetType' => 'PasswordResetType',
                                    'PasswordChangeType' => 'PasswordChangeType',
                                    'AuthenticateUserType' => 'AuthenticateUserType',
                                    'FetchPreferencesType' => 'FetchPreferencesType',
                                    'UpdatePreferencesType' => 'UpdatePreferencesType',
                                    'CreateUserType' => 'CreateUserType',
                                    'UpdateUserType' => 'UpdateUserType',
                                    'ResetUserPasswordType' => 'ResetUserPasswordType',
                                    'SearchUsersType' => 'SearchUsersType',
                                    'SearchUsersCriteriaType' => 'SearchUsersCriteriaType',
                                    'SearchUsersTokensType' => 'SearchUsersTokensType',
                                    'UsersTokensSearchCriteriaType' => 'UsersTokensSearchCriteriaType',
                                    'SearchUsersHistoryType' => 'SearchUsersHistoryType',
                                    'CreateUsersHistoryType' => 'CreateUsersHistoryType',
                                    'UsersHistoryCriteriaType' => 'UsersHistoryCriteriaType',
                                    'SetUserConfigType' => 'SetUserConfigType',
                                    'GetUserConfigType' => 'GetUserConfigType',
                                    'GetSessionStatusType' => 'GetSessionStatusType',
                                    'LogoutType' => 'LogoutType',
                                    'RegisterUserResponseType' => 'RegisterUserResponseType',
                                    'VerifyUserResponseType' => 'VerifyUserResponseType',
                                    'PasswordResetResponseType' => 'PasswordResetResponseType',
                                    'PasswordChangeResponseType' => 'PasswordChangeResponseType',
                                    'AuthenticateUserResponseType' => 'AuthenticateUserResponseType',
                                    'FetchPreferencesResponseType' => 'FetchPreferencesResponseType',
                                    'UpdatePreferencesResponseType' => 'UpdatePreferencesResponseType',
                                    'CreateUserResponseType' => 'CreateUserResponseType',
                                    'UpdateUserResponseType' => 'UpdateUserResponseType',
                                    'ResetUserPasswordResponseType' => 'ResetUserPasswordResponseType',
                                    'SearchUsersResponseType' => 'SearchUsersResponseType',
                                    'SearchUsersTokensResponseType' => 'SearchUsersTokensResponseType',
                                    'UserTokenType' => 'UserTokenType',
                                    'UserTokensType' => 'UserTokensType',
                                    'SearchUsersHistoryResponseType' => 'SearchUsersHistoryResponseType',
                                    'CreateUsersHistoryResponseType' => 'CreateUsersHistoryResponseType',
                                    'SetUserConfigResponseType' => 'SetUserConfigResponseType',
                                    'GetUserConfigResponseType' => 'GetUserConfigResponseType',
                                    'GetSessionStatusResponseType' => 'GetSessionStatusResponseType',
                                    'LogoutResponseType' => 'LogoutResponseType',
                                    'UserNotFoundExceptionType' => 'UserNotFoundExceptionType',
                                    'UserUnconfirmedExceptionType' => 'UserUnconfirmedExceptionType',
                                    'AuthenticateUserExceptionType' => 'AuthenticateUserExceptionType',
                                    'InvalidUserTokenExceptionType' => 'InvalidUserTokenExceptionType',
                                    'UserAlreadyExistsExceptionType' => 'UserAlreadyExistsExceptionType',
                                    'UserAlreadyConfirmedExceptionType' => 'UserAlreadyConfirmedExceptionType',
                                    'UserUnchangedExceptionType' => 'UserUnchangedExceptionType',
                                    'UserConfigKeyNotFoundExceptionType' => 'UserConfigKeyNotFoundExceptionType',
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
   * @param ResetUserPasswordType $parameters
   * @return ResetUserPasswordResponseType
   */
  public function ResetUserPassword(ResetUserPasswordType $parameters) {
    return $this->__soapCall('ResetUserPassword', array($parameters),       array(
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
   * @param SearchUsersTokensType $parameters
   * @return SearchUsersTokensResponseType
   */
  public function SearchUsersTokens(SearchUsersTokensType $parameters) {
    return $this->__soapCall('SearchUsersTokens', array($parameters),       array(
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

  /**
   *  
   *
   * @param CreateUsersHistoryType $parameters
   * @return CreateUsersHistoryResponseType
   */
  public function CreateUsersHistory(CreateUsersHistoryType $parameters) {
    return $this->__soapCall('CreateUsersHistory', array($parameters),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SetUserConfigType $parameters
   * @return SetUserConfigResponseType
   */
  public function SetUserConfig(SetUserConfigType $parameters) {
    return $this->__soapCall('SetUserConfig', array($parameters),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetUserConfigType $parameters
   * @return GetUserConfigResponseType
   */
  public function GetUserConfig(GetUserConfigType $parameters) {
    return $this->__soapCall('GetUserConfig', array($parameters),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSessionStatusType $parameters
   * @return GetSessionStatusResponseType
   */
  public function GetSessionStatus(GetSessionStatusType $parameters) {
    return $this->__soapCall('GetSessionStatus', array($parameters),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param LogoutType $parameters
   * @return LogoutResponseType
   */
  public function Logout(LogoutType $parameters) {
    return $this->__soapCall('Logout', array($parameters),       array(
            'uri' => 'http://user.webservice.cpd.co.uk/',
            'soapaction' => ''
           )
      );
  }

}

?>
