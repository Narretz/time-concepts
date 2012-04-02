<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php

echo CHtml::link('<button>Start Experiments</button>', array('user/index'));
/*
$this->widget('ext.yii-facebook-opengraph.plugins.LoginButton', array(
   'show_faces'=>true,
   'width' => 300,
));*/

echo '<br />';

$token = Yii::app()->facebook->getAccessToken();

if($_SESSION['fb_'.Yii::app()->params['fb_app_id'].'_access_token'])
{
	$user = Yii::app()->facebook->getUser();

	if ($user) {
	  try {
	    // Proceed knowing you have a logged in user who's authenticated.
	    $user_profile = Yii::app()->facebook->api('/me');
	    CVarDumper::dump($user_profile, 10, true);
	  } catch (FacebookApiException $e) {
	  	throw $e;
	    $user = null;
	  }
	}
}



// Login or logout url will be needed depending on current user state.
if ($user) {
  $logout_url = Yii::app()->facebook->getLogoutUrl();
  echo CHtml::link('Log out from Facebook', $logout_url); 
} else {
	$params = array(
		'redirect_uri' => 'http://narretz.de/time/'
	);
  $login_url = Yii::app()->facebook->getLoginUrl($params);
  echo CHtml::link('Log in with Facebook', $login_url); 
}


CVarDumper::dump($_SESSION, 10, true);
?>