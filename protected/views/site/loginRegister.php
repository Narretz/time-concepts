<?php
$this->pageTitle=Yii::app()->name . ' - Login / Register';
$this->breadcrumbs=array(
	'Login / Register',
);
?>

<h1>Login / Register</h1>

<?php
if(Yii::app()->user->isGuest)
{
?>

<div style="float: right; width: 470px; margin-left: 30px">
	<p>You can also log in with your Facebook account. You will be redirected to Facebook and asked to authorize this website. No other information than what you share publicy on Facebook will be needed.</p>


	 <p class="hint"><strong>After you authorized the app on Facebook, it is possible that you will be redirected to this page. In this case, click again on "Connect" to begin the study.</strong></p>
	 <p>
	<?php
	  echo CHtml::link('<img src="'.Yii::app()->baseUrl.'/images/fb.png" />', $url); 
	?>
	</p> 

</div>



<div>

<p>Please fill out the following form with your login credentials. If you do not have an account yet, you can register one. Simply enter an email address and a password. This is necessary to assign the experiment results uniquely. None of your information will be shared publicy or published in any way.</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-register-form',
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

	<div class="row buttons">
		<?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'id' => 'yt0',
				'name'=>'yt0',
				'caption'=>'Register',
				)
		);
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'id' => 'yt1',
				'name'=>'yt1',
				'caption'=>'Login',
				)
		);
		?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

</div> <!--left box-->


<?php 

}

if (!Yii::app()->user->isGuest && Yii::app()->user->type == 'native') {
?>

<div style="clear:both">


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

</div>
