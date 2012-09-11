<?php

class TaskController extends Controller
{
	/*It is not possible to delete, create or update tasks on their own. 
	This must always happen from one of the specific task types*/
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public $taskTypeSearch;

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
		$model = Task::model()->findByPk($id);
		
		if($model===null)
		{
			throw new CHttpException(404, 'The requested task does not exist.');
		}

		$taskType = $this->getTaskType($model->type);

		$taskDetail = $taskType::model()->findByPk($model->id);

		$this->render('view',array(
			'model'=> $model,
			'taskDetail' => $taskDetail,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Task');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Task('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Task']))
			$model->attributes=$_GET['Task'];

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
		$model=Task::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='task-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	//returns the model that is associated with a number in the type attribute
	public function getTaskType($typeId){
		
		$types = array(
				"1" => "TaskComplete",
				"2" => "TaskChoice",
			);

		if (array_key_exists($typeId, $types))
		{
			return $types[$typeId];
		} else {
			return false;
		}
	}
}
