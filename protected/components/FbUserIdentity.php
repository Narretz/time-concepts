<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class FbUserIdentity extends CUserIdentity
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
		$criteria->condition='username = :username';
		$criteria->params=array( ':username'=> $this->username);	
		$user = User::model()->find($criteria);

		//$user=User::model()->findByAttributes(array('username'=>$this->username));
		
		
		if($user === NULL) //user (email) does not exist
		{
		   $this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else
		{
			$this->errorCode=self::ERROR_NONE;
			$this->_id = $user->id;
			$this->username = $user->username;
			$user->last_login_time = date('Y-m-d h:i:s');
			$user->save();
		}
		//what does the exclamation mark do here?
		return !$this->errorCode;
	}
	

	
	/**
	 * Constructor.
	 * @param string $useremail useremail
	 * @param string $password password
	 */
    public function __construct($username)
	{
			$this->username=$username;
	}
	
    public function getId()
    {
        return $this->_id;
    }
}