<?php

/**
 * This is the model class for table "sets_to_users".
 *
 * The followings are the available columns in table 'sets_to_users':
 * @property integer $id
 * @property integer $set_id
 * @property integer $user_id
 * @property integer $completed
 * @property integer $tries
 */
class SetUser extends Model
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SetUser the static model class
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
		return 'sets_to_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('set_id, user_id, completed, tries', 'required'),
			array('set_id, user_id, completed, tries', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, set_id, user_id, completed, tries', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'set_id' => 'Set',
			'user_id' => 'User',
			'completed' => 'Completed',
			'tries' => 'Tries',
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
		$criteria->compare('set_id',$this->set_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('completed',$this->completed);
		$criteria->compare('tries',$this->tries);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}