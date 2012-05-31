<div class="view">
	
	<?php $type = lcfirst($data->getType($data->type));?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('/tasks/'.$data->getType($data->type).'/view', 'id'=>$data->id)); ?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->getType($data->type)); ?>
	<br />

	<b><?php echo CHtml::encode('Text'); ?>:</b>
	<?php echo CHtml::encode($data->$type->text); ?>
	<br />

	<?php echo CHtml::link('Results', array('/tasks/'.$data->getType($data->type).'/result', 'id'=>$data->id)); ?>
	<br />


</div>