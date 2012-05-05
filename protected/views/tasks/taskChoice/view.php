<?php
$this->breadcrumbs=array(
	'Task Choices'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List TaskChoice', 'url'=>array('index')),
	array('label'=>'Create TaskChoice', 'url'=>array('create')),
	array('label'=>'Update TaskChoice', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TaskChoice', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TaskChoice', 'url'=>array('admin')),
);
?>

<h1>View TaskChoice #<?php echo $model->id; ?></h1>

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
