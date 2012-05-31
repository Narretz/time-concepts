<div class="form">

<?php $session = Yii::app()->getSession();	?>

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
		<?php echo $form->textArea($model,'missing',array('rows'=>3,'cols'=>80, 'maxlength' =>500)); ?>
 <?php if($session['Quiz.start']['id'] == 1):?>
  <p class="hint">Be concise; one sentence is sufficient. Describe the aspect that is most appropriate.</p> 
 <?php elseif($session['Quiz.start']['id'] == 3):?>
   <p class="hint">Halten sie die Antwort kurz, aber präzise. Ein Satz ist ausreichend.</p> 
<?php endif;?>

</div>

	<br />

	<div class="row buttons">
	<?php
	$caption = ($event->sender->stepCount == $this->getCurrentStep()) ? 'Finish' : 'Next Question';
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name'=>'submit',
			'caption'=>$caption,
			)
	);
	?>
	</div>

<?php $this->endWidget();?>

</div><!-- form -->