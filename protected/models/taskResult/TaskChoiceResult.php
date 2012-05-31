<?php

/**
 * This is the model class for table "task_complete_results".
 *
 * The followings are the available columns in table 'task_complete_results':
 * @property integer $id
 * @property integer $task_id
 * @property integer $user_id
 * @property string $answer
 * @property string $create_time
 * @property string $update_time
 */
class TaskChoiceResult extends Model
{

	/*Virtual attribute that makes it possible to search by the name of the answer instead of the id in the search function*/

	public $full_answer_search;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TaskCompleteResults the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'task_choice_results';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_id, user_id, time', 'numerical', 'integerOnly'=>true),
			array('answer', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, task_id, user_id, set_id, answer, create_time, update_time, full_answer_search', 'safe', 'on'=>'search'),
			array('answer, task_id, user_id, time', 'safe', 'on' => 'take'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// fullAnswer is the answer model for the id specified in 'answer'
		return array(
			'fullAnswer' => array(self::HAS_ONE, 'TaskChoiceAnswer', array('id' => 'answer')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'task_id' => 'Task Id',
			'user_id' => 'User Id',
			'answer' => 'Answer',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'full_answer_search' => 'Answer',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->with = array('fullAnswer' => array('select' => 'answer'));
		$criteria->together = true;
		$criteria->compare('task_id',$this->task_id);
		$criteria->compare('set_id',$this->set_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('fullAnswer.answer',$this->full_answer_search,true);
		$criteria->compare('t.create_time',$this->create_time,true);
		$criteria->compare('t.update_time',$this->update_time,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		    'sort'=>array(
		        'attributes'=>array(
		            'full_answer_search'=>array( //specify the sorting mechanism for the virtual attribute
		                'asc'=>'fullAnswer.answer',
		                'desc'=>'fullAnswer.answer DESC',
		            ),
		            '*',
		        ),
		    ),
		));
	}
}