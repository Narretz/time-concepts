<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'task-complete-take',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->errorSummary($model->results); ?>

	<?php echo $form->hiddenField($model, 'id');?>

	<?php echo $form->hiddenField($model->results, 'time');?>

	<div class="row">
		<?php echo $form->label($model,'text'); ?>
		<?php echo $model->text;?>
	</div>

<?php if(!empty($model->question)):?>

	<div class="row">
		<?php echo $form->label($model,'question'); ?>
		<?php echo $model->question;?>
	</div>

<?php endif;?>

<?php echo CHtml::activeRadioButtonList($model->results, 'answer', $model->getRadioAnswers());?>

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

<?php $this->endWidget();?>


</div><!-- form -->