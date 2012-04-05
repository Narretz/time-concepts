<?php
/**
 * Model is the customized base model class.
 * All model classes for this application should extend from this base class.
 */
class Model extends CActiveRecord
{
	public function behaviors(){
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'setUpdateOnCreate' => true,
			)
		);
	}
}