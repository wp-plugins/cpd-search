<?php
class UserType {
  public $UID; // int
  public $Agent; // string
  public $Name; // string
  public $Email; // string
  public $Phone; // string
  public $Confirmed; // boolean
  public $Newsletter; // boolean
}

class RegisterUserType {
  public $Name; // string
  public $Email; // string
  public $Phone; // string
  public $Agent; // string
  public $Password; // string
}

class VerifyUserType {
  public $Token; // string
}

class PasswordResetType {
  public $Email; // string
  public $Agent; // string
}

class PasswordChangeType {
  public $Token; // string
  public $Password; // string
  public $Agent; // string
}

class AuthenticateUserType {
  public $Email; // string
  public $Password; // string
  public $Agent; // string
}

class FetchPreferencesType {
  public $Token; // string
}

class UpdatePreferencesType {
  public $Token; // string
  public $User; // UserType
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

class UserAlreadyExistsExceptionType {
}

class UserAlreadyConfirmedExceptionType {
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
                                    'UserType' => 'UserType',
                                    'RegisterUserType' => 'RegisterUserType',
                                    'VerifyUserType' => 'VerifyUserType',
                                    'PasswordResetType' => 'PasswordResetType',
                                    'PasswordChangeType' => 'PasswordChangeType',
                                    'AuthenticateUserType' => 'AuthenticateUserType',
                                    'FetchPreferencesType' => 'FetchPreferencesType',
                                    'UpdatePreferencesType' => 'UpdatePreferencesType',
                                    'RegisterUserResponseType' => 'RegisterUserResponseType',
                                    'VerifyUserResponseType' => 'VerifyUserResponseType',
                                    'PasswordResetResponseType' => 'PasswordResetResponseType',
                                    'PasswordChangeResponseType' => 'PasswordChangeResponseType',
                                    'AuthenticateUserResponseType' => 'AuthenticateUserResponseType',
                                    'FetchPreferencesResponseType' => 'FetchPreferencesResponseType',
                                    'UpdatePreferencesResponseType' => 'UpdatePreferencesResponseType',
                                    'UserAlreadyExistsExceptionType' => 'UserAlreadyExistsExceptionType',
                                    'UserAlreadyConfirmedExceptionType' => 'UserAlreadyConfirmedExceptionType',
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

}

?>
