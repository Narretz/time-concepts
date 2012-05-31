<?php
$this->setPageTitle(Yii::app()->name.' - Study');
echo $event->sender->menu->run();
echo '<h3>Question '.($event->sender->currentStep-1).' of '.($event->sender->stepCount-1).'</h3>';
?>

<?php
if(!empty($model->results))
{
	$relations = $model->relations();
	$resultName = $relations['results'][1];

	Yii::app()->getClientScript()->registerScript('timetrack', '

		var model = "'. $resultName .'";
		var count;
		var time = 0;
		if(model && $("#" + model + "_time"))
			countTime();

		function countTime(){
			time++;
			$("#"+ model + "_time").val(time);
			count = window.setTimeout(function() {
				countTime();
			}, 1000); 
		}
		',CClientScript::POS_READY
	);
}
?>

<?php $this->renderPartial('/tasks/'. lcfirst($type). '/_take', array('event' => $event, 'model'=>$model));?>

<?php
/*
CVarDumper::dump($event, 10, true);
CVarDumper::dump($_SESSION, 10, true);
CVarDumper::dump($_SESSION, 10, true);
*/
?>