<?php

/**
 * This is the model class for table "task_complete".
 *
 * The followings are the available columns in table 'task_complete':
 * @property integer $id
 * @property string $text
 * @property string $missing
 * @property string $create_time
 * @property string $update_time
 */
class TaskComplete extends Model
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TaskComplete the static model class
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
		return 'task_complete';
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text, missing, question', 'required', 'on' => 'create, update'),
			array('missing, question', 'length', 'max'=>500),
			array('description', 'safe', 'on' => 'create, update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, text, missing, question', 'safe', 'on'=>'search'),
			array('id', 'safe', 'on' => 'take'),
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
            'sets'=>array(self::MANY_MANY, 'Set',
                'sets_to_tasks(set_id, task_id)'),
            'task' =>array(self::BELONGS_TO, 'Task', 'id'),
            'results' => array(self::HAS_MANY, 'TaskCompleteResult', 'task_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Text',
			'question' => 'Question',
			'description' => 'Description',
			'missing' => 'Response',
			'create_time' => 'Creation Time',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('missing',$this->missing,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getTypeLabel()
	{
		return 'Completion Task';
	}

	public function getInput()
	{
		return array('missing');
	}

	public function prepareTask($event){
		//set the scenario, so we have different validation rules
		$this->scenario = 'take';
		//empty the input the users have to make
		$fields = array();
		foreach ($this->getInput() as $attribute)
		{
			$fields[$attribute] = '';
		}

		$this->setAttributes($fields);
		//if the user input has been saved already, put it back in he form
		$this->attributes = $event->data;
	}	

	public function handleQuizInput($post)
	{
		//CVarDumper::dump($post, 10, true);
		$this->attributes =  $post['TaskComplete'];
		if ($this->validate()) {
			$data = array();
			foreach ($this->getInput() as $attribute)
			{
				$data[$attribute] = $this->$attribute;
			}
			return $data;
		} else {
			return false;
		}
	}
}