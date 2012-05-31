<h4>Example:</h4>


<div class="form">

<?php echo CHtml::beginForm()?>


	<div class="row">
		<?php echo CHtml::label('Text', 'text'); ?>
		<p id="text">Many years ago, on a bright summer day, Bill's father took him into the city. They went to the races, saw a movie, and had the most fun. Bill always remembered the day vividly as one of his happiest childhood experiences, even when he himself was getting very old.</p>
	</div>
	<div class="row">
		<?php echo CHtml::label('Question', 'question'); ?>
		<p id="question">How would you describe Bill's day in the city with regard to the experience of time? </p>
	</div>
	<div class="row">
		<?php echo CHtml::label('Response', 'answer1') ?>
		<textarea id="answer1" cols="50" readonly="readonly">It was a long time ago, when Bill went with his father to the city.</textarea>
		<p>Or another response could be:</p>
		<?php echo CHtml::label('Response', 'answer2') ?>
		<textarea id="answer2" cols="50" readonly="readonly">Bill and his father had a great time in the city.</textarea>
		<p>But you should always describe the aspect of time you find most appropriate, or most important in the given context.</p>
	</div>

<?php echo CHtml::endForm();?>

</div><!-- form -->