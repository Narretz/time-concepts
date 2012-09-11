<?php

/**
 * This is the model class for table "task_complete_results".
 *
 * The followings are the available columns in table 'task_complete_results':
 * @property integer $id
 * @property integer $task_id
 * @property integer $user_id
 * @property string $missing
 * @property string $create_time
 * @property string $update_time
 */
class TaskCompleteResult extends Model
{
	//Variables that are used as search fields for relations
	public $user_native_language;
	public $user_prof_german;
	public $user_prof_english;
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
		return 'task_complete_results';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_id, user_id', 'required'),
			array('task_id, user_id', 'numerical', 'integerOnly'=>true),
			array('missing', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('task_id, set_id, user_id, missing, create_time, update_time, user_prof_english, user_native_language, user_prof_german', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' =>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'task_id' => 'Task',
			'user_id' => 'User',
			'missing' => 'Answer',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'user_native_language' => 'User Native Language',
			'user_prof_english' => 'English Proficiency',
			'user_prof_german' => 'German Proficiency',
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

		//get the user to which the result belongs
		$criteria->with = array('user');
		$criteria->together = true;

		//task id is preset
		$criteria->compare('task_id',$this->task_id);

		$criteria->compare('set_id',$this->set_id);	
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('missing',$this->missing,true);
		$criteria->compare('create_time',$this->create_time,true);
		//The folloeing three searches in relation models
		$criteria->compare('user.lge_native', $this->user_native_language, true);
		$criteria->compare('user.prof_german', $this->user_prof_german, true);
		$criteria->compare('user.prof_english', $this->user_prof_english, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		    'sort'=>array(
		    	'defaultOrder'=>'task_id ASC',
		        'attributes'=>array(
		            'user_native_language' => array( //specify the sorting mechanism for the virtual attribute
		                'asc'=>'user.lge_native',
		                'desc'=>'user.lge_native DESC',
		            ),
		            'user_prof_english' => array( //specify the sorting mechanism for the virtual attribute
		                'asc'=>'user.prof_english',
		                'desc'=>'user.prof_english DESC',
		            ),
		            'user_prof_german' => array( //specify the sorting mechanism for the virtual attribute
		                'asc'=>'user.prof_german',
		                'desc'=>'user.prof_german DESC',
		            ),		           		       
		            '*',
		     	),
		   	),
		));
	}
}