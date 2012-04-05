<?php
$this->breadcrumbs=array(
	'Sets',
);

$this->menu=array(
	array('label'=>'Create Set', 'url'=>array('create')),
	array('label'=>'Manage Set', 'url'=>array('admin')),
);
?>

<h1>Sets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
