<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<div style="float: right; width: 470px; margin-left: 30px">

<p>You can also log in with your Facebook account. You will be redirected to Facebook and asked to authorize this website. No other information than what you share publicy on Facebook will be needed.</p>

<?php

if(Yii::app()->user->isGuest)
{
  echo CHtml::link('<img src="'.Yii::app()->baseUrl.'/images/fb.png" />', $url); 
?>

</div>

<div>

<p>Please fill out the following form with your login credentials. If you do not have an account yet, you can register one. Simply enter an email address and a password. This is necessary to assign the experiment results uniquely. None of your information will be shared publicy or published in any way.</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'useremail'); ?>
		<?php echo $form->textField($model,'useremail'); ?>
		<?php echo $form->error($model,'useremail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Register'); ?>
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

</div> <!--left box-->

<?php 

}

if (!Yii::app()->user->isGuest && Yii::app()->user->type == 'native') {
?>
You are currently logged in. Click here to <?=CHtml::link('Log out', array('site/logout'))?>.

<?php
}

// Login or logout url will be needed depending on current user state.
if ($fbUser && !Yii::app()->user->isGuest && Yii::app()->user->type == 'fbUser') {
?>

You are currently logged in with Facebook. Click here to <?=CHtml::link('Log out', array('site/logout'))?>. You can also <?=CHtml::link('Log out from Facebook', $url);?>.

<?php
} 
?>


