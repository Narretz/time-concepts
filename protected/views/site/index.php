<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php

echo CHtml::link('<button>Start Experiments</button>', array('user/index'));


$this->widget('ext.yii-facebook-opengraph.plugins.LoginButton', array(
   //'href' => 'YOUR_URL', // if omitted Facebook will use the OG meta tag
   'show_faces'=>true,
));

?>