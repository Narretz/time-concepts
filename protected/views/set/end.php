



<?php if($setId == 2):?>
<h1>Thanks!</h1>
<p>You have completed the English part of the study. If you want to (and have not done so already), you can also take part in the German part of the study.</p>
	<?php echo CHtml::link('German Part', array('set/take', 'id' => '3', 'step' => ''));?>
<?php elseif ($setId == 4): ?>
<h1>Danke!</h1>
<p>Sie haben den deutschen Teil der Studie absolviert. Wenn Sie auch am englischen Teil der Studie teilnehmen wollen, klicken Sie bitte hier.</p>
	<?php echo CHtml::link('Englischer Teil', array('set/take', 'id' => '1', 'step' => ''));?>
<?php endif;?>