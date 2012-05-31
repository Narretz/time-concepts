<?php
$this->breadcrumbs=array(
	'Completion Tasks'=>array('index'),
	$model->id => array('view'),
	'Results'
);

$this->menu=array(
	array('label'=>'List TaskComplete', 'url'=>array('index')),
	array('label'=>'Create TaskComplete', 'url'=>array('create')),
	array('label'=>'Update TaskComplete', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TaskComplete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TaskComplete', 'url'=>array('admin')),
);
?>

<h1>Global Results for Completion Task #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'description',
		'text',
		'question',
		'missing',
		'create_time',
		'update_time',
	),
)); ?>

<?php 

$search = $result->search();

// CVarDumper::dump($search->data[0]->user, 10, true);

?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'taskcomplete-result-grid',
	'dataProvider'=>$result->search(),
	'filter'=>$result,
	'columns'=>array(
		'set_id',
		'user_id',
		array( 'name'=>'user_native_language', 'value'=>'$data->user->lge_native' ),
		array( 'name'=>'user_prof_english', 'value'=>'$data->user->prof_english' ),
		array( 'name'=>'user_prof_german', 'value'=>'$data->user->prof_german' ),
		'missing',
		'create_time',
	),
	'itemsCssClass' => 'items draggable',
)); ?>

