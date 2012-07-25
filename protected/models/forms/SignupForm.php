<?php

class SignupForm extends CFormModel {
	public $username;
	public $email;
	public $password;
	
	public $verifyCode;
	
	/**
	 * Declares the validation rules.
	 */
	public function rules() {
		return array(
			array('username, email, password', 'required'),
			array('email', 'email'),
			array('password', 'length', 'min' => 3),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	public function login() {
		try {
			$identity=new UserIdentity($this->username, $this->password);
			$identity->authenticate();
			
			Yii::app()->user->login($identity);
			
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels() {
		return array(
				'verifyCode'=>'Verification Code',
		);
	}
	
}