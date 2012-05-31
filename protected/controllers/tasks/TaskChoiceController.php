<?php

class TaskChoiceController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';


	/**
	 * @return array action filters
	 * Uses the Rights extension automatic access filter
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
		return '';
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = TaskChoice::model()->with('choiceAnswers')->findByPk($id);

		if($model === null)
		{
			throw new CHttpException(404,'The requested Choice Task does not exist.');
		}
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TaskChoice('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TaskChoice']))
			$model->attributes=$_GET['TaskChoice'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TaskChoice;
		//2 Answers are the default
		$model->choiceAnswers=array(new TaskChoiceAnswer, new TaskChoiceAnswer);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['TaskChoice']) || $_POST['TaskChoiceAnswer'])
		{
			$task = new Task;
			//Set the type for the task
			$task->type = 2;

			$model->attributes = $_POST['TaskChoice'];

			$task->taskChoice = $model;

			$answers = array();

			foreach($_POST['TaskChoiceAnswer'] as $answer)
			{
				$choiceAnswer = new TaskChoiceAnswer;
				$choiceAnswer->attributes = $answer;
				$answers[] = $choiceAnswer;
			}

			$model->choiceAnswers = $answers;
			$task->taskChoice->choiceAnswers = $answers;

			if($task->withRelated->save(true, array('taskChoice' => array('choiceAnswers',),)))
			{
				$this->redirect(array('view','id'=>$model->id));
			}

		}

		$this->render('create',array(
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
			$crit = new CDbCriteria;
			$crit->condition = 't.id = :taskId';
			$crit->params=array(':taskId'=>$id);
			$crit->with = array('taskChoice','taskChoice.choiceAnswers');
			$task=Task::model()->find($crit); // $params is not needed
			
			foreach($task->taskChoice->choiceAnswers as $answer)
			{
				$answer->delete();
			}

			$task->taskChoice->delete();
			$task->delete();

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
		$dataProvider=new CActiveDataProvider('TaskChoice');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id)->with(array('choiceAnswers'));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TaskChoice']))
		{
			$model->attributes=$_POST['TaskChoice'];
			$toUpdate = array();
			$fromForm = array();
			foreach ($model->choiceAnswers as $old)
			{
				$toUpdate[] = $old->id;
			}

			foreach ($_POST['TaskChoiceAnswer'] as $new)
			{
				$fromForm[] = $new['id'];
			}

			//store the related models
			$answers = $model->choiceAnswers;
			//delete the linking table entries
			Yii::app()->db->createCommand()->delete('task_choice_2_answer', 'task_choice_id = ?', array($model->id));

			//CVarDumper::dump($model->choiceAnswers, 10, true);

			foreach($_POST['TaskChoiceAnswer'] as $i => $answer)
			{
				foreach ($answers as $k => $old)
				{
					if($answer['id'] == $old->id)
					{//exisiting answer
						$old->attributes=$answer;
					} else if (!in_array($answer['id'], $toUpdate))
					{//new answer
						$answers[$i] = new TaskChoiceAnswer;
						$answers[$i]->attributes = $answer;
					} else if(!in_array($old->id, $fromForm))
						//previously existing answer is not in form
						unset($answers[$k]);
				}
			}

			$model->choiceAnswers = $answers;	

			//CVarDumper::dump($model->choiceAnswers, 10, true);

			if($model->withRelated->save(true, array('task', 'choiceAnswers')))
					$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Displays Results for a single task.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionResult($id)
	{
		$crit = new CDbCriteria;
		$crit->params=array(':id'=> $id);
		$crit->condition='t.id=:id';
		$crit->with = 'choiceAnswers';
		$model = TaskChoice::model()->find($crit);

		//model instance to provide filter / search function for results
		$result = new TaskChoiceResult('search');
		$result->unsetAttributes(); //clear any default values
		$result->task_id = $id; //but restrain the results to the id of the current task

		if(isset($_GET['TaskChoiceResult']))
			$result->attributes=$_GET['TaskChoiceResult'];

		$this->render('result',array(
			'model'=>$model,
			'result' => $result,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=TaskChoice::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested Choice Task does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='task-choice-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
