<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.23.0
 * 	copyright			:	(c) 2015 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */

class RecoverForm extends CFormModel
{

    public $email;
    public $verifyCode;

    public function rules()
    {
        $rules = array(
            array('email', 'filter', 'filter' => 'trim'),
            array('email', 'required'),
            array('email', 'email'),
        );

        $rules[] = array('verifyCode', 'required');

        $rules[] = array('verifyCode', 'CustomCaptchaValidatorFactory');

        return $rules;
    }

    public function attributeLabels()
    {
        return array(
            'recoverPass' => tc('Forgot password?'),
            'email' => tc('Email'),
            'verifyCode' => tc('Verify Code'),
        );
    }
}
