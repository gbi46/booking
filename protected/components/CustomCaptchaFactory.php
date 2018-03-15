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

class CustomCaptchaFactory
{

    public $captchaAction;
    public $buttonOptions;
    public $imageOptions;
    public $clickableImage = true;
    public $model;
    public $attribute;
    public $isSecureToken = false;

    public function run()
    {
        
    }

    public function init()
    {
        if (param('useReCaptcha', 0)) {
            $key = param('reCaptchaKey', 'dev');
            $secret = param('reCaptchaSecret', 'dev');

            Yii::app()->reCaptcha->key = $key;
            Yii::app()->reCaptcha->secret = $secret;

            $widget = Yii::app()->controller->createWidget('ReCaptcha', array(
                'model' => $this->model,
                'attribute' => $this->attribute,
                'isSecureToken' => $this->isSecureToken,
                'key' => $key,
                'secret' => $secret,
            ));

            $widget->run();
            return $widget;
        } else {
            $widget = Yii::app()->controller->createWidget('CustomCCaptcha', array(
                'captchaAction' => $this->captchaAction,
                'buttonOptions' => $this->buttonOptions,
                'imageOptions' => $this->imageOptions,
                'clickableImage' => $this->clickableImage,
            ));

            $widget->run();
            return $widget;
        }
    }
}
