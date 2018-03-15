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

class CustomCaptchaValidatorFactory extends CValidator
{

    public $allowEmpty = false;
    public $caseSensitive = false;
    public $captchaAction = 'captcha';

    public function init()
    {
        if (param('useReCaptcha', 0)) {
            $reCaptchaValidator = new ReCaptchaValidator();

            return $reCaptchaValidator;
        } elseif (param('useJQuerySimpleCaptcha', 0)) {
            $valid = false;

            if (isset($_POST) && isset($_POST['captchaSelection'])) {
                if (Yii::app()->user->hasState("simpleCaptchaAnswer") && $_POST['captchaSelection'] == Yii::app()->user->getState('simpleCaptchaAnswer')) {
                    $valid = true;
                }
            }

            return $valid;
        } else {
            $captchaValidator = new CCaptchaValidator();

            return $captchaValidator;
        }
    }

    protected function validateAttribute($object, $attribute)
    {
        $validateClass = '';
        if (param('useReCaptcha', 0)) {
            $key = param('reCaptchaKey', 'dev');
            $secret = param('reCaptchaSecret', 'dev');

            Yii::app()->reCaptcha->key = $key;
            Yii::app()->reCaptcha->secret = $secret;

            $validateClass = new ReCaptchaValidator();
            $validateClass->allowEmpty = $this->allowEmpty;
        } elseif (param('useJQuerySimpleCaptcha', 0)) {
            
        } else {
            $validateClass = new CCaptchaValidator();
            $validateClass->allowEmpty = $this->allowEmpty;
            $validateClass->caseSensitive = $this->caseSensitive;
            $validateClass->captchaAction = $this->captchaAction;
        }

        if ($validateClass) {
            $validateClass->validateAttribute($object, $attribute);
        }
    }
}
