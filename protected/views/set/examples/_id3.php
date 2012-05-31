




<h4>Beispiel:</h4>

<div class="form">

<?php echo CHtml::beginForm()?>


	<div class="row">
		<?php echo CHtml::label('Text', 'text'); ?>
		<p id="text">Wenn Tims Oma ihm von ihrer Kindheit erzählt, dann sagt sie ihm immer, dass es trotz vieler Schwierigkeiten sehr schön war. Obwohl Tim weiß, dass es damals vielen Leuten sehr schlecht ging, ist das für ihn alles weit weg. Wenn ihm seine Oma von früher erzählt, sind das Geschichten wie aus einer anderen Welt.</p>
	</div>
	<div class="row">
		<?php echo CHtml::label('Question', 'question'); ?>
		<p id="question">Was können Sie über die Erfahrung von Zeit sagen, die durch diesen Text hervorgerufen wird? </p>
	</div>
	<div class="row">
		<?php echo CHtml::label('Response', 'answer1') ?>
		<textarea id="answer1" cols="50" readonly="readonly">Die Zeit, in der Großmutter gelebt hat, ist schon lange her.</textarea>
		<p>Eine andere Antwort könnte auch sein:</p>
		<?php echo CHtml::label('Response', 'answer2') ?>
		<textarea id="answer2" cols="50" readonly="readonly">Die Großmutter hat in einer schwierigen Zeit gelebt.</textarea>
		<p>Sie sollten allerdings immer den Aspekt der Zeiterfahung beschreiben, der ihnen am wichtigsten erscheint.</p>
	</div>

<?php echo CHtml::endForm();?>

</div><!-- form -->