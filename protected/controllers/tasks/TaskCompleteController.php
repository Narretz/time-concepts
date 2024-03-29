<?php

class TaskCompleteController extends Controller
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new TaskComplete;
		$model->scenario = 'create';

		if(isset($_POST['TaskComplete']))
		{		
			$model->attributes = $_POST['TaskComplete'];

			//Create the base task model
			$model->task = new Task;
			$model->task->type = 1;

			if($model->withRelated->save(true, array('task')))
			{
				$this->redirect(array('view','id'=>$model->id));
			}
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
		//Note that the task relation model is not updated
		$model=$this->loadModel($id);
		$model->scenario = 'update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TaskComplete']))
		{
			$model->attributes=$_POST['TaskComplete'];
			if($model->withRelated->save(true, array('task')))
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
		//Task relation model is not updated
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
		$dataProvider=new CActiveDataProvider('TaskComplete');
		//CVarDumper::dump($dataProvider, 10, true);
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TaskComplete('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TaskComplete']))
			$model->attributes=$_GET['TaskComplete'];

		$this->render('admin',array(
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
		$model = TaskComplete::model()->find($crit);

		if($model === null)
			throw new CHttpException(404,'The requested Completion Task does not exist.');			

		//result model instance to provide filter / search function for results
		$result = new TaskCompleteResult('search');
		$result->unsetAttributes(); //clear any default values
		$result->task_id = $id; //but restrain the results to the id of the current task

		if(isset($_GET['TaskCompleteResult']))
			$result->attributes=$_GET['TaskCompleteResult'];

		$this->render('result',array(
			'model'=>$model,
			'result' => $result,
		));
	}

	/*
	* Action displays all results from any taskComplete task
	*/
	public function actionResultindex()
	{
		//model instance to provide filter / search function for all results
		$model = new TaskCompleteResult('search');
		$model->unsetAttributes(); //clear any default values

		if(isset($_GET['TaskCompleteResult']))
			$model->attributes=$_GET['TaskCompleteResult'];

		$this->render('resultindex',array(
			'model' => $model,
		));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=TaskComplete::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='task-complete-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
