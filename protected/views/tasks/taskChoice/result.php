<?php
$this->breadcrumbs=array(
	'Choice Tasks'=>array('index'),
	$model->id => array('view', 'id' => $model->id),
	'Results'
);

$this->menu=array(
	array('label'=>'List Choice Tasks', 'url'=>array('index')),
	array('label'=>'Create Choice Tasks', 'url'=>array('create')),
	array('label'=>'Manage Choice Tasks', 'url'=>array('admin')),
);
?>

<h1>Global Results for Choice Task #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'description',
		'text',
		'question',
		'create_time',
		'update_time',
	),
)); ?>

<?php
$idSwitch = array();
if(!empty($model->choiceAnswers))
{
	foreach ($model->choiceAnswers as $i => $answer)
	{
		echo $this->renderPartial('_answer', array('data'=>$answer));
		$idSwitch[$answer->id] = $i;
	}
}
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'task-omplete-result-grid',
	'dataProvider'=>$result->search(),
	'filter'=>$result,
	'columns'=>array(
		'set_id',
		'user_id',
		array( 'name'=>'full_answer_search', 'value'=>'$data->fullAnswer->answer' ),
		'create_time',
	),
)); 
?>