<?php

class SetController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('take'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'create', 'update'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		//Load the model together with its relations
		$model = Set::model()->findByPk($id)->with(array('task_complete'));

		//CVarDumper::dump($model->tasks_complete[0]->attributes, 10, true);
		//create a data provider for the relations
		$taskProvider=new CArrayDataProvider($model->tasks, array(
		    'id'=>'tasks',
		    'pagination'=>array(
		        'pageSize'=>10,
		    ),
		));

		$this->render('view',array(
			'model'=> $model,
			'taskProvider' => $taskProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Set;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Set']))
		{
			$model->attributes=$_POST['Set'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function beforeAction($action) {

		//CVarDumper::dump($this->getActionParams(), 10, true);
		//CVarDumper::dump($model->tasks_complete, 10, true);

		//CVarDumper::dump($_GET['step'], 10, true);

		$config = array();
		switch ($action->id) {
		case 'take':
				//get the get parameters and load the associated model to prepare the steps
				if(isset($_GET['id']))
				{
					$model = Set::model()->findByPk($_GET['id'])->with(array('tasks'));
					//CVarDumper::dump($model->tasks_complete, 10, true);
					$steps = array();
					foreach ($model->tasks as $index => $task){
						$steps['Q'.($index+1)] = $task->id;
					}
				} else {
					//raise error
					$this->invalidActionParams($this->getAction());
					
				}

				$config = array(
					'steps'=> $steps,
					'addParams' => array('id' => $_GET['id']),
					'events'=>array(
						'onStart'=>'wizardStart',
						'onProcessStep'=>'quizProcessStep',
						'onFinished'=>'quizFinished',
						'onInvalidStep'=>'wizardInvalidStep',
					)
				);

				if(empty($_GET['step']))
				{
					$session = Yii::app()->getSession();
					$session['types'] = new CMap;

					foreach ($model->tasks as $index => $task){
						$session['types'][$task->id] = $task->getType($task->type);
					}					
				}

				break;
		}

		if (!empty($config)) {
			$config['class']='application.extensions.WizardBehavior';
			$this->attachBehavior('wizard', $config);
		}
		return parent::beforeAction($action);
	}

	public function actionTake($step=null, $id) {
		//$this->pageTitle = 'Quiz Wizard';

		//CVarDumper::dump($model->tasks_complete[0]->attributes, 10, true);

		//CVarDumper::dump($_SESSION, 10, true);

		$this->process($step);
	}

	/**
	* Process steps from the quiz
	* @param WizardEvent The event
	*/
	public function quizProcessStep($event) {
		$type= Yii::app()->session['types'][$event->step];
		$model = $type::model()->findByPk($event->step);
		//empty the input the users have to make
		$fields = array();
		foreach ($model->getInput() as $attribute)
		{
			$fields[$attribute] = '';
		}
		$model->setAttributes($fields);
		$model->attributes = $event->data;

		//if we have input, validate and save the data in the wizard session
		if(isset($_POST[$type]))
		{
			$model->attributes =  $_POST[$type];
			if ($model->validate()) {
				$data = array();
				foreach ($_POST[$type] as $attribute => $value)
				{
					$data[$attribute] = $model->$attribute;
				}

				$event->sender->save($data);
				$event->handled = true;
			} else {
				$this->render('/tasks/'. lcfirst($type). '/take', array('event' => $event, 'model' => $model));
			}
		}
		else {
			$this->render('/tasks/'. lcfirst($type). '/take', array('event' => $event, 'model' => $model));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Set']))
		{
			$model->attributes=$_POST['Set'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Set');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Set('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Set']))
			$model->attributes=$_GET['Set'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Set::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='set-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	// Wizard Behavior Event Handlers
	/**
	* Raised when the wizard starts; before any steps are processed.
	* MUST set $event->handled=true for the wizard to continue.
	* Leaving $event->handled===false causes the onFinished event to be raised.
	* @param WizardEvent The event
	*/
	public function wizardStart($event) {
		$event->handled = true;
	}


	/**
	* The quiz
	* @param WizardEvent The event
	*/
	public function quizFinished($event) {
		$event->sender->reset();
		unset(Yii::app()->session['types']);
		$this->render('/set/end', compact('event'));
		Yii::app()->end();
	}

	/**
	* Raised when a step has expired.
	* @param WizardEvent The event
	*/
	public function quizExpiredStep($event) {
		$event->sender->save(array('answer'=>'<slow>'));
	}

	/**
	* Raised when a step is invalid.
	* @param WizardEvent The event
	*/
	public function wizardInvalidStep($event) {
		
		//CVarDumper::dump($event, 10, true);
	}
}
