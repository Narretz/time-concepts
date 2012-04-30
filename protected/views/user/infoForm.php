<?php
$this->pageTitle=Yii::app()->name . ' - Information';

echo $event->sender->menu->run();
echo '<div>Question '.$event->sender->currentStep.' of '.$event->sender->stepCount;

echo '<h3>'.$event->sender->getStepLabel($event->step).'</h3>';

?>

<p>
Finally, please consider answering some optional questions, which we will be used to put the results in context. They will never be made public. If you do not want to answer, simply click 'Finish'.
</p>

<?php
    $this->widget('ext.ETooltip.ETooltip', array("selector"=>"#user-info-form option[title]", "tooltip" => array("position" => "center right", "tipClass" => "mytooltip", "offset" => array(-2, 10),)));
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-info-form',
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'lge_native'); ?>
		<?php echo $form->textField($model,'lge_native', array('title' => 'blubb')); ?>
		<?php echo $form->error($model,'lge_native'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prof_english'); ?>
		<?php echo $form->dropDownList($model,'prof_english', $model->languageOptions, array('prompt' => 'Choose a profiency', 'options' => $model->languageHints)
		); ?>
		<?php echo $form->error($model,'prof_english'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prof_german'); ?>
		<?php echo $form->dropDownList($model,'prof_german', $model->languageOptions, array('prompt' => 'Choose a profiency', 'options' => $model->languageHints)
		); ?>
		<?php echo $form->error($model,'prof_german'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'age'); ?>
		<?php echo $form->textField($model,'age'); ?>
		<?php echo $form->error($model,'age'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'occupation'); ?>
		<?php echo $form->textField($model,'occupation'); ?>
		<?php echo $form->error($model,'occupation'); ?>
	</div>

	<div class="row buttons">
		<?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name'=>'submit',
				'caption'=>'Finish',
				)
		);
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->