<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php

echo CHtml::link('<button>Start Experiments</button>', array('/set/take', 'id' => '1', 'step' => ''));

echo '<br />';

CVarDumper::dump($_SESSION, 10, true);
?>