<?php
$this->breadcrumbs=array(
	'Choice Tasks'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Choice Tasks', 'url'=>array('index')),
	array('label'=>'Create Choice Task', 'url'=>array('create')),
	array('label'=>'Update Choice Task', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Choice Task', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Choice Task Results', 'url'=>array('result', 'id'=>$model->id)),
	array('label'=>'Manage Choice Tasks', 'url'=>array('admin')),
);
?>

<h1>View Choice Task #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'description',
		'text',
		'question',
		'create_time',
		'update_time',
	),
)); ?>

<?php
if(!empty($model->choiceAnswers))
{
	foreach ($model->choiceAnswers as $answer)
	{
		echo $this->renderPartial('_answer', array('data'=>$answer));
	}
}
