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

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->label($model,'text'); ?>
		<?php echo $model->text;?>
	</div>

<?php if(!empty($model->question))
{
?>
	<div class="row">
		<?php echo $form->label($model,'question'); ?>
		<?php echo $model->question;?>
	</div>
<?php
}
?>

	<div class="row">
		<?php echo $form->labelEx($model,'missing'); ?>
		<?php echo $form->textArea($model,'missing',array('rows'=>2,'cols'=>50, 'maxlength' =>500)); ?>
  		<p class="hint">Try to be concise; not more than one sentence. Enter the first thought you find appropriate.</p> 
	</div>

	<br />

	<div class="row buttons">
	<?php
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name'=>'submit',
			'caption'=>'Next Question',
			)
	);
	?>
	</div>

<?php $this->endWidget();

//Yii::app()->session['var'] = 'value';

//CVarDumper::dump($model->scenario, 10, true);
//CVarDumper::dump($_SESSION, 10, true);

?>


</div><!-- form -->