<?php
$this->breadcrumbs=array(
	'Completion Tasks',
);

$this->menu=array(
	array('label'=>'Create Completition Tasks', 'url'=>array('create')),
	array('label'=>'Manage Completion Tasks', 'url'=>array('admin')),
);
?>

<h1>Completion Tasks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
