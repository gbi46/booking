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

Yii::import('bootstrap.widgets.BsActiveForm');

class CustomActiveForm extends CActiveForm
{

    private $_summaryAttributes = array();

    public function errorSummary($models, $header = null, $footer = null, $htmlOptions = array())
    {
        if (!$this->enableAjaxValidation && !$this->enableClientValidation)
            return CustomCHtml::errorSummary($models, $header, $footer, $htmlOptions);

        if (!isset($htmlOptions['id']))
            $htmlOptions['id'] = $this->id . '_es_';
        $html = CustomCHtml::errorSummary($models, $header, $footer, $htmlOptions);
        if ($html === '') {
            if ($header === null)
                $header = '<p>' . Yii::t('yii', 'Please fix the following input errors:') . '</p>';
            if (!isset($htmlOptions['class']))
                $htmlOptions['class'] = CustomCHtml::$errorSummaryCss;
            $htmlOptions['style'] = isset($htmlOptions['style']) ? rtrim($htmlOptions['style'], ';') . ';display:none' : 'display:none';
            $html = CHtml::tag('div', $htmlOptions, $header . "\n<ul><li>dummy</li></ul>" . $footer);
        }

        $this->summaryID = $htmlOptions['id'];
        foreach (is_array($models) ? $models : array($models) as $model)
            foreach ($model->getSafeAttributeNames() as $attribute)
                $this->_summaryAttributes[] = CHtml::activeId($model, $attribute);

        return $html;
    }
}
