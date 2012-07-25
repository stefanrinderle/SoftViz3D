<?php

class UserController extends BaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions() {
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			)
		);
	}

	public function actionSignup() {
		$model = new SignupForm();
		
		if(isset($_POST['SignupForm'])) {
			$model->attributes = $_POST['SignupForm'];
			
			if($model->validate()) {
				$user = new User();
				$user->username = $model->username;
				$user->password = md5($model->password);
				$user->email = $model->email;
				
				try {
					$user->save();
					
					$headers="From: {$user->email}\r\nReply-To: {$user->email}";
					mail(Yii::app()->params['adminEmail'], "Neue anmeldung", "", $headers);
				} catch (Exception $e) {
					$model->addErrors($e);
				}
				
				if($model->login()) {
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}
		$this->render('signup', array('model' => $model));
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin() {
		$model = new LoginForm();

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm'])) {
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
}