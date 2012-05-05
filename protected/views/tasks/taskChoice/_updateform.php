<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'task-choice-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php //CVarDumper::dump($model->getErrors(), 10, true);?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->errorSummary($model->choiceAnswers); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>3, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'question'); ?>
		<?php echo $form->textField($model,'question',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'question'); ?>
	</div>

<?php 

foreach ($model->choiceAnswers as $i => $answer): ?>

	<div class="row copy">
		<?php echo $form->labelEx($answer,"[$i]answer"); ?>
		<?php echo $form->textField($answer,"[$i]answer",array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($answer,"[$i]answer"); ?>
		<?php echo $form->hiddenField($answer, "[$i]id"); ?>
	<?php if ($i > 1): ?>
		<a href="#" onclick="$(this).parent().remove(); return false;">remove</a>
	<?php endif; ?>
	</div>

<?php endforeach;?>

	<a id="copylink" href="#" rel=".copy">Add another answer</a>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$this->widget('ext.jqrelcopy.JQRelcopy',
                 array(
                       'id' => 'copylink',
                       'removeText' => 'remove' //uncomment to add remove link
                       )
);       
?>

