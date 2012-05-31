<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginRegisterForm extends CFormModel
{
	public $useremail;
	public $password;
	public $rememberMe;
	public $sendMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('useremail, password', 'required'),
			//useremail needs to be in email format
			array('useremail', 'email'),
			// rememberMe needs to be a boolean
			array('rememberMe, sendMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate', 'on' => 'login'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'useremail'=>'Email address',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->useremail,$this->password);
			if(!$this->_identity->authenticate())
			{
				switch($this->_identity->errorCode)
				{
					case UserIdentity::ERROR_USERNAME_INVALID:
						$this->addError('password','Your email address was not found.');
						break;
					case UserIdentity::ERROR_PASSWORD_INVALID:
						$this->addError('password','You have entered a wrong password.');
						break;
				}
			}
		}
	}

	public function register()
	{
		if(!$this->hasErrors())
		{

			$criteria = new CDbCriteria;
			$criteria->condition='email = :email';
			$criteria->params=array( ':email'=> $this->useremail);	
			$email = User::model()->find($criteria);

			if($email)
			{
				$this->addError('useremail', 'Registration failed. Did you try to log in?');
				return false;
			}

			$user = new User;
			$user->email = $this->useremail;
			$user->username = $this->useremail;
			$user->password = hash('sha256', $this->password);
			$user->type = 'native';
			$user->last_login_time = date('Y-m-d h:i:s');
			$user->create_time = date('Y-m-d h:i:s');

			//create initial auth role for new user
			$auth = Yii::app()->authManager;

			if($user->save() && $auth->assign('Authenticated', $user->id))
			{
				return true;
			} else {
				return false;
			}

		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->useremail,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			//$duration = 0;
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity, $duration);
			//var_dump(Yii::app()->user->login($this->_identity, $duration));
			//CVarDumper::dump($this->_identity, 10, true);
			return true;
		}
		else
			return false;
	}

	/**
	 * Creates a Facebook login or logout url
	 * string $fbUser
	 * @return string $url
	 */
	public function getFbLogLink($fbUser)
	{
		if ($fbUser && !Yii::app()->user->isGuest) {
			$logType = 'logout';
		} else if ($fbUser && Yii::app()->user->isGuest) {
			$logType = 'login';
		} else {
			$logType = 'login';
		}

		if($logType == 'logout')
		{
			$params = array(
				'next' => 'http://narretz.de/time/site/logout/'
			);	
			$url = Yii::app()->facebook->getLogoutUrl($params);		
		} else if($logType == 'login')
		{
			$params = array(
				'redirect_uri' => 'http://narretz.de/time/site/login?fbLogin=true'
			);	
			$url = Yii::app()->facebook->getLoginUrl($params);	
		} else {
			return false;
		}
		return $url;
	}
}

