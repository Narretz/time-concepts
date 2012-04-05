<?php
$this->breadcrumbs=array(
	'Sets'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Set', 'url'=>array('index')),
	array('label'=>'Create Set', 'url'=>array('create')),
	array('label'=>'View Set', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Set', 'url'=>array('admin')),
);
?>

<h1>Update Set <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>