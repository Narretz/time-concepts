<?php
$this->breadcrumbs=array(
	'Task Completes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TaskComplete', 'url'=>array('index')),
	array('label'=>'Create TaskComplete', 'url'=>array('create')),
	array('label'=>'Update TaskComplete', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TaskComplete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TaskComplete', 'url'=>array('admin')),
);
?>

<h1>View TaskComplete #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'text',
		'missing',
		'create_time',
		'update_time',
	),
)); ?>
