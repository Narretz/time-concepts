<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

//Define the useremail as a property of the class (Parent Class does not have it)
public $useremail;	

private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{	
		$criteria = new CDbCriteria;
		$criteria->condition='email = :email';
		$criteria->params=array( ':email'=> $this->useremail);	
		$user = User::model()->find($criteria);

		//$user=User::model()->findByAttributes(array('username'=>$this->username));
		
		
		if($user === NULL) //user (email) does not exist
		{
		   $this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else if($user->password !== $this->password) //wrong password
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;

		} 
		else
		{
			$this->errorCode=self::ERROR_NONE;
			//test the state -> this gets saved in the session (but encoded for the current user)
			//$this->setState('title', 'blubb');
			$this->_id = $user->id;
			$this->username = $user->username;

			//$currentDate = date('Y-m-d H:i:s');
		}
		//what does the exclamation mark do here?
		return !$this->errorCode;
	}
	

	
	/**
	 * Constructor.
	 * @param string $useremail useremail
	 * @param string $password password
	 */
    public function __construct($useremail,$password)
	{
			$this->useremail=$useremail;
			$this->password=$password;
	}
	
    public function getId()
    {
        return $this->_id;
    }
}