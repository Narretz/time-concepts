<?php
echo $event->sender->menu->run();
echo '<div>Question '.$event->sender->currentStep.' of '.$event->sender->stepCount;

echo '<h3>'.$event->sender->getStepLabel($event->step).'</h3>';
?>

<?php
Yii::app()->getClientScript()->registerScript('timeout', '
	console.log($("#TaskChoiceResult_time"));

	//$("#TaskChoiceResult_time").val("wtf");

	var count;
	var time = 0;
	countTime();

	function countTime(){
		time++;
		$("#TaskChoiceResult_time").val(time);
		count = window.setTimeout(function() {
			countTime();
		}, 1000); 
	}
	',CClientScript::POS_READY
);
?>

<?php $this->renderPartial('/tasks/'. lcfirst($type). '/_take', array('event' => $event, 'model'=>$model));?>

<?php

CVarDumper::dump($event, 10, true);
CVarDumper::dump($_POST, 10, true);
CVarDumper::dump($_SESSION, 10, true);

?>