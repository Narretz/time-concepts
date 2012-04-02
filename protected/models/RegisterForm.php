<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	public $email;
	public $password;
	public $sendMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email, password', 'required'),
			//useremail needs to be in email format
			array('email', 'email'),
			array('email', 'unique', 'className' => 'User'),
			// rememberMe needs to be a boolean
			array('sendMe', 'boolean'),
			array('password', 'length','max'=>64, 'min'=>6),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'E-mail address',
			'sendMe' => 'I want to receive an email when the experiment results are available',
		);
	}

	/**
	 * Registers the user using the given email and password in the model.
	 * @return boolean whether registration is successful
	 */

	public function register()
	{
		$user = new User;

		$user->email = $this->email;
		$user->username = $this->email;
		$user->password = hash('sha256', $this->password);
		$user->type = 'native';
		$user->last_login_time = date('Y-m-d h:i:s');
		$user->create_time = date('Y-m-d h:i:s');

		if($user->save())
		{
			return true;
		} else {
			return false;
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
			$this->_identity=new UserIdentity($this->email,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			Yii::app()->user->login($this->_identity, 0);
			return true;
		}
		else
			return false;
	}
}
