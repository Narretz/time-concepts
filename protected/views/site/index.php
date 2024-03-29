<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <?php echo CHtml::encode(Yii::app()->name); ?></h1>

<p>This website features a linguistic study set up by Martin Staffa for his Master thesis about different concepts and experiences of "time". Thanks for participating! The study comes in an English and a German version. Both are independent of each other, and have slight differences, so it may be worthwile to participate in both versions. Each version has two parts and will take approximately 20 to 25 minutes to finish (overall, with the second part being shorter). In the first part, you have to read some short texts, and describe aspects of time in these texts. In the second part, you will have to read short texts, and then judge which one of the given expressions is best to describe the text.</p>

<p>Please start with the language you feel more proficient in. In the German version, instructions and contents will be in German.</p>

 <p>After clicking the start button, you will be prompted to log in or create an account. This is necessary to assign the study results to a unique user, and to prevent spam. Alternatively, you can log in with your Facebook account. This works in the same way as with any other Facebook app. Your personal data will be kept secure, and will not be published in any way. If you register an account, your personal data will be deleted after 60 days. After you have been logged in, you will be redirected to the starting page of the study, where you will find further instructions.</p>

<p class="center">
<?php

$this->widget('zii.widgets.jui.CJuiButton', array(
		'id' => 'exp2',
		'name'=>'submit',
		'buttonType' => 'link',
		'caption'=>'Start German',
		'url' => array('/set/take', 'id' => '3', 'step' => ''),
));

$this->widget('zii.widgets.jui.CJuiButton', array(
		'id' => 'exp1',
		'name'=>'submit',
		'buttonType' => 'link',
		'caption'=>'Start English',
		'url' => array('/set/take', 'id' => '1', 'step' => ''),
));

?>

</p>

<?php

//Check if user has access to the menu
if(Yii::app()->user->isSuperuser || Yii::app()->user->checkAccess('Supervisor'))
{
	$this->menu=array(
				array('label'=>'View Sets', 'url'=>array('set/index')),
				array('label'=>'View Free Answer Tasks', 'url'=>array('tasks/taskComplete/index')),	
				array('label'=>'Create Free Answer Tasks', 'url'=>array('tasks/taskComplete/create')),
				array('label'=>'Manage Free Answer Tasks', 'url'=>array('tasks/taskComplete/admin')),
				array('label' => 'View All Results for Free Answer Tasks', 'url' => array('tasks/taskComplete/resultindex')),
				array('label'=>'View Judgement Tasks', 'url'=>array('tasks/taskChoice/index')),	
				array('label'=>'Create Judgement Tasks', 'url'=>array('tasks/taskChoice/create')),
				array('label'=>'Manage Judgement Tasks', 'url'=>array('tasks/taskChoice/admin')),
		);
}

?>