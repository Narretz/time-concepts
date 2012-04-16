<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<?php
// Login or logout url will be needed depending on current user state.
if ($fbUser) {
  echo CHtml::link('Log out from Facebook', $logout_url); 
} else if(Yii::app()->user->isGuest){
  echo CHtml::link('Log in with Facebook', $login_url); 
}

CVarDumper::dump($_SESSION, 10, true);


CVarDumper::dump($fbUser, 10, true);


if(Yii::app()->user->isGuest)
{
?>

<p>Please fill out the following form with your login credentials:</p>

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
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<?php }; ?>
