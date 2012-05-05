<?php
$this->breadcrumbs=array(
	'Task Choices'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TaskChoice', 'url'=>array('index')),
	array('label'=>'Manage TaskChoice', 'url'=>array('admin')),
);
?>

<h1>Create TaskChoice</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'answer' => $answer)); ?>