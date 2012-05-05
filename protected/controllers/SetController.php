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
	      'rights', 
	   ); 
	}

	/**
	* @return string the actions that are always allowed separated by commas.
	*/
	public function allowedActions()
	{
		return 'take';
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
					$criteriaAccess = new CDbCriteria;
					$criteriaAccess->params=array(':id'=> $_GET['id'], ':user_id' => Yii::app()->user->id);
					$criteriaAccess->condition='user_id=:user_id AND set_id=:id';
					$setUser = SetUser::model()->find($criteriaAccess);

					if(!Yii::app()->user->checkAccess('Set.Take', array('completed'=>$setUser->completed)))
					{
						$this->accessDenied();
					}

					$steps = array();
					$session = Yii::app()->getSession();		

					//When step is empty, this means wizard start
					//Fetch relevant set data and save it in the session so we do not have to query the db on every page request
					if(empty($_GET['step']))
					{	
						$setId=$_GET['id'];
						$criteria = new CDbCriteria;
						$criteria->condition='t.id=:id';
						$criteria->params=array(':id'=> $_GET['id']);
						$criteria->with = array('tasks');
						$criteria->together=true;
						$model = Set::model()->find($criteria);

						$session['Quiz.start'] = new CMap;
						$session['Quiz.start']['text'] = $model->description;
						$steps['Start'] = 'start';

						$session['Quiz.types'] = new CMap;
						$session['Quiz.steps'] = new CMap;

						$session['Quiz.steps']['Start'] = 'start';
						
						foreach ($model->tasks as $index => $task){
							$session['Quiz.types'][$task->id] = $task->getType($task->type);
							$session['Quiz.steps']['Q'.($index+1)] = $task->id;
							$steps['Q'.($index+1)] = $task->id;
						}

						//add the user info form to the steps
						$user = User::model()->findByPk(Yii::app()->user->id);
						$prop = array('lge_native', 'prof_english', 'prof_german',  'occupation', 'age');

						$info = true;

						//check if info page should be displayed
						if($setId == 1 || $setId == 3)
							$info = false;

						foreach($user->attributes as $attr => $val)
						{
							if(in_array($attr, $prop))
							{
								if(!empty($val) || $val != 0)
									$info = false;
							}
						}

						if($info)
						{
							$steps['Info'] = 'info';
							$session['Quiz.steps']['Info'] = 'info';
						}

					} else {
						$steps = $session['Quiz.steps'];
					}

					//Wizard needs to be attached on every page request
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

					if (!empty($config) && !empty($steps)) {
						$config['class']='application.extensions.WizardBehavior';
						$this->attachBehavior('wizard', $config);
					} else {
						$this->invalidActionParams($this->getAction());	
					}


				} else {
					//raise error
					$this->invalidActionParams($this->getAction());
				}

				break;
		}

		return parent::beforeAction($action);
	}

	public function actionTake($step=null, $id) {

		$this->process($step);
	}

	/**
	* Process steps from the quiz
	* @param WizardEvent The event
	*/
	public function quizProcessStep($event) {
		
		switch ($event->step) {
			case 'info':
				$model = new UserInfoForm;
				if(isset($_POST['UserInfoForm']))
				{
					$model->attributes=$_POST['UserInfoForm'];
					if($model->validate())
					{
						$event->sender->save($model->attributes);
						$event->handled = true;
					} else {
						$this->render('/user/infoForm',array('model'=>$model, 'event' => $event));
					}
				} else {
					$this->render('/user/infoForm',array('model'=>$model, 'event' => $event));
				}
				break;
			
			case 'start':
				if(isset($_POST['Submit']))
				{
					$event->sender->save(array('start' => 'started'));
					$event->handled = true;
				} else {
					$this->render('start', array('event' => $event));
				}
				break;

			default:
				//dependant on task type, handle the output / input generation
				$type = Yii::app()->session['Quiz.types'][$event->step];
				$model = $type::model()->findByPk($event->step);
				
				$model->prepareTask($event);

				$view = array('event' => $event, 'model' => $model, 'type' => $type);

				//if we have input, validate and save the data in the wizard session
				if(isset($_POST[$type]))
				{
					if($data = $model->handleQuizInput($_POST))
					{
						$data['type'] = $type;
						$data['task_id'] = $event->step;

						$event->sender->save($data);
						$event->handled = true;
					} else {
						$this->render('take', $view);
					}
				} else {
					$this->render('take', $view);
				}
				break;
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
		$setId = $_GET['id'];
		$userId = Yii::app()->user->id;

		//Save the actual results, as stored in the session
		foreach ($event->data as $result)
		{
			if(is_array($result) && isset($result['task_id']))
			{
				$modelType = $result['type'].'Result';
				$model = new $modelType;
				unset($result['type']);
				$model->attributes = $result;
				$model->user_id = $userId;
				$model->set_id = $setId;
				$model->save();
			}
		}

		//Save the information that identifies the user with the experiment (set)
		$setUser = new SetUser;
		$setUser->detachBehavior('CTimestampBehavior');
		$setUser->set_id = $setId;
		$setUser->user_id = $userId;
		$setUser->completed = 1;
		$setUser->tries = 1;
		$setUser->save();

		//Save the additional data the user may or not may have entered at the end of the experiment
		if(!empty($event->data['info']))
		{
			$user = User::model()->findByPk($userId);
			//We already validated the info in the form
			$user->setAttributes($event->data['info'], false);
			$user->save();
		}

		//Reset the quiz
		$event->sender->reset();
		//Unset the additional session data
		unset(Yii::app()->session['Quiz.types']);
		unset(Yii::app()->session['Quiz.steps']);

		if($setId == 1 || $setId == 3)
		{
			$nextQuiz = ++$setId;
			$this->redirect(array('set/take', 'id' => $nextQuiz, 'step' => ''));
		}
		else
		{
			$this->render('/set/end', compact('event'));
		}

		Yii::app()->end();

}

	/**
	* Raised when a step is invalid.
	* @param WizardEvent The event
	*/
	public function wizardInvalidStep($event) {
		
		//CVarDumper::dump($event, 10, true);
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		//Load the model together with its relations
		$model = Set::model()->findByPk($id)->with(array('tasks'));

		CVarDumper::dump($model->tasks, 10, true);
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

}
