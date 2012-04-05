<?php
$this->breadcrumbs=array(
	'Task Completes',
);

$this->menu=array(
	array('label'=>'Create TaskComplete', 'url'=>array('create')),
	array('label'=>'Manage TaskComplete', 'url'=>array('admin')),
);
?>

<h1>Task Completes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$taskProvider,
	'itemView'=>'_view',
)); ?>
