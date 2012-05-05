<?php
echo $event->sender->menu->run();
echo '<h3>'.$event->sender->getStepLabel($event->step).'</h3>';

$session = Yii::app()->getSession();	

?>


<p>
<?php

echo CHtml::encode($session['Quiz.start']['text']);

?>

</p>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'set-start',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row buttons">
	<?php
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name'=>'Submit',
			'caption'=>'Start',
			)
	);
	?>

<?php $this->endWidget();?>