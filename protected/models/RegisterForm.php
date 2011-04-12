<?php

class RegisterForm extends CFormModel
{
	public $email;
	public $password;
    public $verifyPassword;
    public $verifyCode;

	public function rules()
    {
		return array(
			array('email, password, verifyPassword', 'required'),
            array('email', 'email'),
            array('email', 'unique', 'attributeName'=>'email', 'className'=>'User'),
            array('password', 'compare', 'compareAttribute'=>'verifyPassword', 'message' => 'Retype password is incorrect.'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

    public function attributeLabels()
    {
        return array(
            'verifyCode'=>'Verification Code',
        );
    }
}
