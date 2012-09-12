<?php

class SiteController extends Controller
{
	public function filters() 
	{ 
	   return array( 
	      'rights', 
	   ); 
	} 

	public function allowedActions() 
	{ 
	   return 'captcha, page'; 
	} 

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}


	/**
	 * Displays the login / register page
	 * If a login or a register function is called depends on the button that is clicked
	 * a scenario is then set accordingly to switch the validation rules
	 */
	public function actionLogin()
	{
		$model = new LoginRegisterForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-register-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginRegisterForm']))
		{
			$model->attributes=$_POST['LoginRegisterForm'];

			if(isset($_POST['yt1']) && $_POST['yt1'] === 'Login')
			{
				$model->scenario = 'login';
				// validate user input and redirect to the previous page if valid
				if(Yii::app()->user->isGuest && $model->validate() && $model->login())
				{
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}

			if(isset($_POST['yt0']) && $_POST['yt0'] === 'Register')
			{
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->register() && $model->login())
				{
					$this->redirect(Yii::app()->user->returnUrl);

				}
			}
		}

		//handle facebook login
		$fbUser = Yii::app()->facebook->getUser();

		if ($fbUser) {
		  try {
		    //Proceed knowing you have a logged in user who's authenticated.
		    $user_profile = Yii::app()->facebook->api('/me');

		  } catch (FacebookApiException $e) {
		  	//throw $e;
		    $fbUser = null;
		  }
		 }

		//fblogin is set by the facebook plugin on a successful authentication
		if($fbUser && Yii::app()->user->isGuest && isset($_GET['fbLogin']))
		{
		    if(!$this->loginFBUser($user_profile))
		    {
		    	try{
		    		$this->registerFBUser($user_profile);	
		    	} catch (Exception $e) {
		    		throw $e;
		    	}
		    }
		}

		// Login or logout url will be needed depending on current user state.
		$url = $model->getFbLogLink($fbUser);

		// display the login form
		$this->render('loginRegister',array('model'=>$model, 'fbUser' => $fbUser, 'url' => $url));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		Yii::app()->facebook->destroySession();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionInfo(){

		CVarDumper::dump($_POST, 10, true);
		$form = new UserInfoForm;

		$this->render('/user/infoForm', array('model' => $form));
	}

	public function loginFBUser($user_profile)
	{
		$identity = new FbUserIdentity($user_profile['id']);
		$identity->authenticate();
		$identity->username = $user_profile['name'];

		if($identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration = 0;
			if(Yii::app()->user->login($identity, $duration))
			{
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		else if($identity->errorCode===UserIdentity::ERROR_USERNAME_INVALID)
		{
			return false;			
		}
	}

	public function registerFBUser($user_profile)
	{
			$user = new User;
			$user->username = $user_profile['id'];
			$user->type = 'facebook';
			$user->last_login_time = date('Y-m-d h:i:s');
			$user->create_time = date('Y-m-d h:i:s');

			//create initial auth role for new user
			$auth = Yii::app()->authManager;

			if($user->insert(array('username', 'email', 'type', 'last_login_time', 'create_time')) && $auth->assign('Authenticated', $user->id))
			{
				$this->loginFBUser($user_profile);
			} else {
				return false;
			}
	}
}