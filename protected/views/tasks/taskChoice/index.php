<?php
$this->breadcrumbs=array(
	'Task Choices',
);

$this->menu=array(
	array('label'=>'Create TaskChoice', 'url'=>array('create')),
	array('label'=>'Manage TaskChoice', 'url'=>array('admin')),
);
?>

<h1>Task Choices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
