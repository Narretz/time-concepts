<?php
$this->breadcrumbs=array(
	'Task Choices'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TaskChoice', 'url'=>array('index')),
	array('label'=>'Create TaskChoice', 'url'=>array('create')),
	array('label'=>'View TaskChoice', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TaskChoice', 'url'=>array('admin')),
);
?>

<h1>Update TaskChoice <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>