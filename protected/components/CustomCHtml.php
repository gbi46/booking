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

class CustomCHtml extends CHtml
{

    public static function errorSummary($model, $header = null, $footer = null, $htmlOptions = array())
    {
        $content = '';
        if (!is_array($model))
            $model = array($model);
        if (isset($htmlOptions['firstError'])) {
            $firstError = $htmlOptions['firstError'];
            unset($htmlOptions['firstError']);
        } else
            $firstError = false;
        foreach ($model as $m) {
            foreach ($m->getErrors() as $errors) {
                foreach ($errors as $error) {
                    if ($error != '') {
                        //$content.= '<li>'.self::encode($error)."</li>\n";
                        $content .= '<li>' . $error . "</li>\n";
                    }
                    if ($firstError)
                        break;
                }
            }
        }
        if ($content !== '') {
            if ($header === null)
                $header = '<p>' . Yii::t('yii', 'Please fix the following input errors:') . '</p>';
            if (!isset($htmlOptions['class']))
                $htmlOptions['class'] = self::$errorSummaryCss;
            return self::tag('div', $htmlOptions, $header . "\n<ul>\n$content</ul>" . $footer);
        } else
            return '';
    }
}
