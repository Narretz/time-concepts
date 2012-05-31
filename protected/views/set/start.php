<?php
$this->setPageTitle(Yii::app()->name.' - Study Start');
echo $event->sender->menu->run();
echo '<h3>'.$event->sender->getStepLabel($event->step).'</h3>';

$session = Yii::app()->getSession();	

?>



<p>

<?php $this->beginWidget('CMarkdown', array('purifyOutput'=>true));?>

<?php echo CHtml::encode($session['Quiz.start']['text']); ?>

<?php $this->endWidget(); ?>
</p>


<?php if($this->getViewFile('examples/_id'.$session['Quiz.start']['id'])):?>

<div id="example">

<?php $this->renderPartial('examples/_id'.$session['Quiz.start']['id']);?>

</div>

<?php endif;?>

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