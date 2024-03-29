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

$session = Yii::app()->getSession();

if($session['Quiz.start']['id'] == 2)
{
	$hints = $model->languageHints;
} else if ($session['Quiz.start']['id'] == 4)
{
	$hints = $model->languageHintsGerman;
} else {
	$hints = $model->languageHints;
}


?>

<?php
    $this->widget('ext.ETooltip.ETooltip', array("selector"=>"#user-info-form option[title]", "tooltip" => array("position" => "center right", "tipClass" => "mytooltip", "offset" => array(-2, 10),)));
?>

<?php

Yii::app()->getClientScript()->registerScript('hide', '

		if($("#UserInfoForm_lge_native").val().match(/\b(\w*)(english|german)\b/i))
			disable($("#UserInfoForm_lge_native"));


		$("#UserInfoForm_lge_native").bind("keyup", function(){
			if($(this).val().match(/\b(\w*)(english|german)\b/i))
			{
				disable($(this));
			}else if($(".dropdown").is("[disabled]")){
				$(".dropdown").removeAttr("disabled");
				$(".dropdown").val("");
				
			}
		});

function disable($element){
		language = $element.val().match(/\b(\w*)(english|german)\b/i)[0];
		language.toLowerCase();
		langSelect = $("#UserInfoForm_prof_" + language.toLowerCase())
		langSelect.attr("disabled", "disabled");
		langSelect.val("native");
}

');
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
		<?php echo $form->dropDownList($model,'prof_english', $model->languageOptions, array('class' => 'dropdown', 'prompt' => 'Choose a proficiency', 'options' => $hints)
		); ?>
		<?php echo $form->error($model,'prof_english'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prof_german'); ?>
		<?php echo $form->dropDownList($model,'prof_german', $model->languageOptions, array('class' => 'dropdown', 'prompt' => 'Choose a proficiency', 'options' => $hints)
		); ?>
		<?php echo $form->error($model,'prof_german'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'age'); ?>
		<?php echo $form->textField($model,'age'); ?>
		<?php echo $form->error($model,'age'); ?>
	</div>

	<!--<div class="row">
		<?php echo $form->labelEx($model,'occupation'); ?>
		<?php echo $form->textField($model,'occupation'); ?>
		<?php echo $form->error($model,'occupation'); ?>
	</div>-->

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