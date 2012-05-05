<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>This website features a linguistic experiment set up by Martin Staffa for his Master thesis. Thanks for participating! You can click on the button below to start the experiments. If you have not registered yet, you will be prompted to log in or create an account. This is necessary to assign the experiment results uniquely to a certain user, and to prevent spam. Alternatively, you can log in with your Facebook account. This works in the same way as with any other Facebook app. Your personal data will be kept secure, and will not be published in any way.</p>

<p class="center">
<?php

$this->widget('zii.widgets.jui.CJuiButton', array(
		'id' => 'exp2',
		'name'=>'submit',
		'buttonType' => 'link',
		'caption'=>'Start German Experiment',
		'url' => array('/set/take', 'id' => '2', 'step' => ''),
));

$this->widget('zii.widgets.jui.CJuiButton', array(
		'id' => 'exp1',
		'name'=>'submit',
		'buttonType' => 'link',
		'caption'=>'Start English Experiment',
		'url' => array('/set/take', 'id' => '1', 'step' => ''),
));


$taskchoice = new TaskChoice;

?>

</p>

<?php

if(Yii::app()->user->isSuperuser)
{
	$this->menu=array(
		array('label' => 'Tasks', 'url' => array('task/index'), 'items' =>
			array(
				array('label'=>'See Completion Tasks', 'url'=>array('tasks/taskcomplete/index')),	
				array('label'=>'Create Completion Tasks', 'url'=>array('tasks/taskcomplete/create')),
				array('label'=>'Manage Completion Tasks', 'url'=>array('tasks/taskcomplete/admin')),
				array('label'=>'See Choice Tasks', 'url'=>array('tasks/taskchoice/index')),	
				array('label'=>'Create Choice Tasks', 'url'=>array('tasks/taskchoice/create')),
				array('label'=>'Manage Choice Tasks', 'url'=>array('tasks/taskchoice/admin')),			
			)
		)
	);
}

?>