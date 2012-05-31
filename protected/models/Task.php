<?php

/**
 * This is the model class for table "tasks".
 *
 * The followings are the available columns in table 'tasks':
 * @property integer $id
 * @property var $title
 * @property integer $type
 * @property string $create_time
 * @property string $update_time
 */
class Task extends Model
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Task the static model class
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
		return 'tasks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, title, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}


	public function behaviors()
	{
	    return array_merge(parent::behaviors(), array(
	        'withRelated'=>array(
	            'class'=>'ext.with-related-behavior.WithRelatedBehavior',
		        ),
		    )
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
			'taskComplete' => array(self::HAS_ONE, 'TaskComplete', 'id'),
			'taskChoice' => array(self::HAS_ONE, 'TaskChoice', 'id'),
			'set' => array(self::MANY_MANY, 'Set',
				'sets_to_tasks(task_id, set_id)'),		
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'title' => 'Title',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
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

		//problematic: type refers to different models. How do I search for that in a gridview?
		$criteria->with = array('taskChoice', 'taskChoice.choiceAnswers');
		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type);
		$criteria->compare('t.create_time',$this->create_time,true);
		$criteria->compare('t.update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected $types = array('1' => 'TaskComplete', '2' => 'TaskChoice');

	public function getType($typeId){
		return $this->types[$typeId];
	}
}