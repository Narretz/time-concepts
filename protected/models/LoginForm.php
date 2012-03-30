<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $useremail;
	public $password;
	public $rememberMe;

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
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
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
						$this->addError('useremail','Your e-mail address was not found in our database.');
						break;
					case UserIdentity::ERROR_PASSWORD_INVALID:
						$this->addError('password','You have entered a wrong password');
						break;
				}
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
}
