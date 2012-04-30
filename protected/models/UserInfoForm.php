<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserInfoForm extends CFormModel
{
	public $lge_native;
	public $prof_english;
	public $prof_german;
	public $age;
	public $occupation;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('lge_native', 'length', 'max'=>50),
			array('occupation', 'length', 'max' =>256),
			array('age', 'numerical'),
			array('prof_english, prof_german', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'lge_native'=>'Your native language',
			'prof_german' => 'Your profiency with German',
			'prof_english' => 'Your profiency with English',
			'age' => 'Your age',
			'occupation' => 'Your occupation',
		);
	}

	protected function performAjaxValidation($model)
	{
	    if(isset($_POST['ajax']) && $_POST['ajax']==='user-info-form')
	    {
	        echo CActiveForm::validate($model);
	        Yii::app()->end();
	    }
	}

	public function getLanguageOptions(){
		return array(
			'A1' => 'Beginner',
			'A2' => 'Elementary',
			'B1' => 'Intermediate',
			'B2' => 'Upper Intermediate',
			'C1' => 'Advanced',
			'C2' => 'Mastery',
			);
	}

	public function getLanguageHints(){
		return array(
				'A1' => array('title' => 'Can understand and use familiar everyday expressions and very basic phrases aimed at the satisfaction of needs of a concrete type. Can introduce him/herself and others and can ask and answer questions about personal details such as where he/she lives, people he/she knows and things he/she has. Can interact in a simple way provided the other person talks slowly and clearly and is prepared to help.'),
				'A2' => array('title' => '	Can understand sentences and frequently used expressions related to areas of most immediate relevance (e.g. very basic personal and family information, shopping, local geography, employment). Can communicate in simple and routine tasks requiring a simple and direct exchange of information on familiar and routine matters. Can describe in simple terms aspects of his/her background, immediate environment and matters in areas of immediate need.'),
				'B1' => array('title' => 'Can understand the main points of clear standard input on familiar matters regularly encountered in work, school, leisure, etc. Can deal with most situations likely to arise whilst travelling in an area where the language is spoken. Can produce simple connected text on topics which are familiar or of personal interest. Can describe experiences and events, dreams, hopes & ambitions and briefly give reasons and explanations for opinions and plans.'),
				'B2' => array('title' => 'Can understand the main ideas of complex text on both concrete and abstract topics, including technical discussions in his/her field of specialisation. Can interact with a degree of fluency and spontaneity that makes regular interaction with native speakers quite possible without strain for either party. Can produce clear, detailed text on a wide range of subjects and explain a viewpoint on a topical issue giving the advantages and disadvantages of various options.'),
				'C1' => array('title' => 'Can understand a wide range of demanding, longer texts, and recognise implicit meaning. Can express him/herself fluently and spontaneously without much obvious searching for expressions. Can use language flexibly and effectively for social, academic and professional purposes. Can produce clear, well-structured, detailed text on complex subjects, showing controlled use of organisational patterns, connectors and cohesive devices.'),
				'C2' => array('title' => 'Can understand with ease virtually everything heard or read. Can summarise information from different spoken and written sources, reconstructing arguments and accounts in a coherent presentation. Can express him/herself spontaneously, very fluently and precisely, differentiating finer shades of meaning even in the most complex situations.'),
				);
	}
}