<?php

/**
 * This is the model class for table "task_choice_question".
 *
 * The followings are the available columns in table 'task_choice_question':
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $question
 * @property string $create_time
 * @property string $update_time
 */
class TaskChoice extends Model
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TaskChoiceQuestion the static model class
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
		return 'task_choice';
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
			array('question, text', 'required'),
			array('title, question, text', 'length', 'max'=>500),
			array('description', 'safe', 'on' => 'create, update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, text, question, create_time, update_time', 'safe', 'on'=>'search'),
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
			   'choiceAnswers' =>array(self::MANY_MANY, 'TaskChoiceAnswer', 
            		'task_choice_2_answer(task_choice_id, task_choice_answer_id)'),
			   'results' => array(self::HAS_MANY, 'TaskChoiceResult', 'task_id'
			   	),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'text' => 'Text',
			'question' => 'Question',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getTypeLabel()
	{
		return 'Choice Task';
	}

	//Get the fields that are user input
	public function getInput()
	{
		return array('answer', 'time');
	}

	public function getRadioAnswers()
	{
		$answers = array();

		foreach ($this->choiceAnswers as $answer)
		{
			$answers[$answer->id] = $answer->answer; 
		}

		return $answers;
	}

	public function prepareTask($event)
	{
		//the form input is an instance of the result set
		$this->results = new TaskChoiceResult;
		//if the user input has been saved already, put it back in the form
		$this->results->attributes = $event->data;
	}	

	public function handleQuizInput($post)
	{
		$this->results->scenario = 'take';
		$this->results->attributes =  $post['TaskChoiceResult'];
		//CVarDumper::dump($post, 10, true);
		if ($this->results->validate()) {
			$data = array();
			foreach ($this->getInput() as $attribute)
			{
				$data[$attribute] = $this->results->$attribute;
			}
			return $data;
		} else {
			//$this->results->addError('answer', 'Answer cannot be empty.');
			return false;
		}
	}

	public function mapAnswerIdText(){
		foreach($this->choiceAnswers as $answer){
			$idSwitch[$answer->id] = $i;
		}
	}

	/**
	 * Retrieves the text of an answer based on its id.
	 * @return string the answer.
	 */

	public function getAnswerText($answer_id){
		CVarDumper::dump($answer_id, 10, true);
		foreach($this->choiceAnswers as $answer){
			if($answer_id == $answer->id){
				return $answer->answer;
			}
		}
		return 'blubb';
	}
}