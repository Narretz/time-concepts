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

	/*This function is called before an action is called
	It is used to set up the Quiz wizard before the 'take' action
	- completely when it is loaded for the first time
	- from session when session data is saved*/
	public function beforeAction($action) {
		$config = array();
		switch ($action->id) {
		case 'take':

				//get the get parameters and load the associated model to prepare the steps
				if(isset($_GET['id']))
				{
					if(Yii::app()->user->isGuest)
					{
						Yii::app()->user->setReturnUrl(Yii::app()->baseUrl.'/set/take/id/'.$_GET['id'].'?step=');
						$this->redirect(array('site/login'));
					}
					//check if the user has access to the quiz
					$criteriaAccess = new CDbCriteria;
					$criteriaAccess->params=array(':id'=> $_GET['id'], ':user_id' => Yii::app()->user->id);
					$criteriaAccess->condition='user_id=:user_id AND set_id=:id';
					$setUser = SetUser::model()->find($criteriaAccess);

					if($setUser && !Yii::app()->user->checkAccess('Set.Take', array('completed'=>$setUser->completed)) && !Yii::app()->user->checkAccess('Supervisor'))
					{
						throw new CHttpException('403', 'You have already finished this part of the study.');
					}

					$steps = array();
					$session = Yii::app()->getSession();		

					//When step is empty, this means wizard start
					//Fetch relevant set data and save it in the session so we do not have to query the db on every page request
					if(empty($_GET['step']) || $_GET['step'] != $session['Quiz.start']['id'])
					{	
						$setId=$_GET['id'];
						$criteria = new CDbCriteria;
						$criteria->condition='t.id=:id';
						$criteria->params=array(':id'=> $_GET['id']);
						$criteria->with = array('tasks');
						$criteria->together=true;
						$model = Set::model()->find($criteria);

						if(empty($model))
							throw new CHttpException('404', 'The page you search for does not exist');		

						$session['Quiz.start'] = new CMap;
						$session['Quiz.start']['text'] = $model->description;
						$session['Quiz.start']['id'] = $model->id;
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

						//if the user had already entered his details, don't show the info form
						foreach($user->attributes as $attr => $val)
						{
							//checks if one attribute is set, which means that the form was already fiiled once
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
							'onProcessStep'=>'setProcessStep',
							'onFinished'=>'setFinished',
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
	public function setProcessStep($event) {
		
		switch ($event->step) {
			case 'info':
				//renders the user info form
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
				//start page of a set
				if(isset($_POST['Submit']))
				{
					$event->sender->save(array('start' => 'started'));
					$event->handled = true;
				} else {
					$this->render('start', array('event' => $event));
				}
				break;

			default:
				//a task page in a set
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
	* what happens when a set is finished
	* @param WizardEvent The event
	*/
	public function setFinished($event) {
		$setId = $_GET['id'];
		$userId = Yii::app()->user->id;

		//mail body
		$body;

		//Save the actual results, as stored in the session		
		foreach ($event->data as $result)
		{
			//create a model for each saved result in the session and save it
			if(is_array($result) && isset($result['task_id']))
			{
				$modelType = $result['type'].'Result';
				$model = new $modelType;
				unset($result['type']);
				//set task_id and specific result data
				$model->attributes = $result;
				$model->user_id = $userId;
				$model->set_id = $setId;
				try {
					$model->save();
				} catch (Exception $e) {
					throw $e;
				}

				//Create the email response body based on the task type
				$body .= "Task Id:". $result['task_id']."\n";
				
				if(isset($result['answer']))
					$body .= "Answer Id:". $result['answer']."\n";

				if(isset($result['missing']))
					$body .= "Answer:". $result['missing']."\n";

				$body .= "\n";

			}
		}

		//Save the information that identifies the user with the experiment (set)
		$setUser = new SetUser;
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
		unset(Yii::app()->session['Quiz.start']);
		unset(Yii::app()->session['Quiz.types']);
		unset(Yii::app()->session['Quiz.steps']);

		//add header and body to email, send
		$subject = $userId.' just finished the set with the id '.$setId;
		// Sending mails is disabled on localhost, since you cannot send emails from localhost!
		mail(Yii::app()->params['adminEmail'],$subject,$body);

		//Redirect the user either to the end page or to the next part of the quiz
		//This is hardcoded, since we only have four sets
		if($setId == 1 || $setId == 3)
		{
			$nextQuiz = ++$setId;
			$this->redirect(array('set/take', 'id' => $nextQuiz, 'step' => ''));
		}
		else
		{
			$this->render('/set/end', array(compact('event'), "setId" => $setId));
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

		// Uncomment the @following line if AJAX validation is needed
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
		$model = Set::model()->findByPk($id)->with(array('tasks', 'tasks.taskComplete', 'tasks.taskChoice', 'userCount'));

		//create a data provider for the relations

		$crit = new CDbCriteria;
		$crit->with = array('taskComplete', 'taskChoice', 'set');
		$crit->condition = 'set.id = '.$id.'';
		$crit->together = true;

		$taskProvider=new CActiveDataProvider('Task', array(
			'criteria' => $crit,
			'sort' => array(
				'defaultOrder' => 't.title ASC',
				),
		    'id'=>'tasks',
		    'pagination'=>array(
		        'pageSize'=>15,
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
