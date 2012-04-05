<?php
$this->breadcrumbs=array(
	'Task Completes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TaskComplete', 'url'=>array('index')),
	array('label'=>'Manage TaskComplete', 'url'=>array('admin')),
);
?>

<h1>Create TaskComplete</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>