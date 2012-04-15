<?php
echo $event->sender->menu->run();
echo '<div>Question '.$event->sender->currentStep.' of '.$event->sender->stepCount;

echo '<h3>'.$event->sender->getStepLabel($event->step).'</h3>';
//echo CHtml::tag('div',array('class'=>'form'),$form->render());
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'task-complete-take',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $model->text;?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'missing'); ?>
		<?php echo $form->textField($model,'missing' ,array('size'=>60,'maxlength'=>256)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Next Question'); ?>
	</div>

<?php $this->endWidget();

//Yii::app()->session['var'] = 'value';

//CVarDumper::dump(Yii::app()->getSession(), 10, true);

CVarDumper::dump($_SESSION, 10, true);

?>


</div><!-- form -->