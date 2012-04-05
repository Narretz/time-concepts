<?php
$this->breadcrumbs=array(
	'Task Completes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TaskComplete', 'url'=>array('index')),
	array('label'=>'Create TaskComplete', 'url'=>array('create')),
	array('label'=>'View TaskComplete', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TaskComplete', 'url'=>array('admin')),
);
?>

<h1>Update TaskComplete <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>