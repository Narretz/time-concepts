<?php
$this->breadcrumbs=array(
	'Completion Tasks'=>array('index'),
	'List Results',
);

$this->menu=array(
	array('label'=>'List Completion Tasks', 'url'=>array('index')),
	array('label'=>'Manage Completion Tasks', 'url'=>array('admin')),
	array('label'=>'Create Completion Task', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('task-complete-result-index-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>List Completion Task Results</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_searchresult',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'task-complete-result-index-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('name' => 'task_id', 'value' => 'CHtml::link($data->task_id, array("tasks/taskComplete/result", "id"=>$data->task_id))',  'type'=>'raw'),
		'set_id',
		'user_id',
		array( 'name'=>'user_native_language', 'value'=>'$data->user->lge_native' ),
		array( 'name'=>'user_prof_english', 'value'=>'$data->user->prof_english' ),
		array( 'name'=>'user_prof_german', 'value'=>'$data->user->prof_german' ),
		'missing',
		'create_time',
	),
)); ?>
