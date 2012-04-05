<?php
$this->breadcrumbs=array(
	'Sets'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Set', 'url'=>array('index')),
	array('label'=>'Create Set', 'url'=>array('create')),
	array('label'=>'Update Set', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Set', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Set', 'url'=>array('admin')),
);
?>

<h1>View Set #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'description',
		'create_time',
		'update_time',
	),
)); 

echo '<p>';
echo 'Tasks:';
echo '</p>';
/*
foreach ($model->tasks_complete as $task){
	echo '<p>';
	echo $task->text;
	echo '<br/>';
	echo $task->missing;
	echo '</p>';
}*/

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$taskProvider,
	'itemView'=>'/tasks/taskComplete/_view',
));

?>
