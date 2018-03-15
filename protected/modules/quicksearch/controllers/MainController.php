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

class MainController extends ModuleUserController
{

    public function actionIndex()
    {
        $href = Yii::app()->getBaseUrl(true) . '/' . Yii::app()->request->getPathInfo();
        $page = (int) Yii::app()->request->getParam('page');
        if (isset($page) && $page > 1) {
            $href .= '?page=' . $page;
        }
        Yii::app()->clientScript->registerLinkTag('canonical', null, $href);
        unset($href);

        $criteria = new CDbCriteria;
        $criteria->addCondition('active = ' . Apartment::STATUS_ACTIVE);
        if (param('useUserads')) {
            $criteria->addCondition('owner_active = ' . Apartment::STATUS_ACTIVE);
        }

        if (Yii::app()->request->isAjaxRequest) {
            $this->excludeJs();
            $this->renderPartial('index', array(
                'criteria' => $criteria,
                'apCount' => null,
                ), false, true);
        } else {
            $this->render('index', array(
                'criteria' => $criteria,
                'apCount' => null,
            ));
        }
    }

    public function getExistRooms()
    {
        return Apartment::getExistsRooms();
    }

    public function actionMainsearch($rss = null)
    {
        $countAjax = Yii::app()->request->getParam('countAjax');

        $href = Yii::app()->getBaseUrl(true) . '/' . Yii::app()->request->getPathInfo();
        $page = (int) Yii::app()->request->getParam('page');
        if (isset($page) && $page > 1) {
            $href .= '?page=' . $page;
        }
        Yii::app()->clientScript->registerLinkTag('canonical', null, $href);
        unset($href);

        if (Yii::app()->request->getParam('currency')) {
            setCurrency();
            $this->redirect(array('mainsearch'));
        }

        $criteria = SearchHelper::getCriteriaForMainSearch();

        if ($rss && issetModule('rss')) {
            $this->widget('application.modules.rss.components.RssWidget', array(
                'criteria' => $criteria,
            ));
        }

        // find count
        $apCount = Apartment::model()->count($criteria);

        if ($countAjax && Yii::app()->request->isAjaxRequest) {
            $this->echoAjaxCount($apCount);
        }

        $searchParams = $_GET;
        if (isset($searchParams['is_ajax'])) {
            unset($searchParams['is_ajax']);
        }
        Yii::app()->user->setState('searchUrl', Yii::app()->createUrl('/search', $searchParams));
        unset($searchParams);

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('index', array(
                'criteria' => $criteria,
                'apCount' => $apCount,
                'filterName' => SearchHelper::$filterName,
            ));
        } else {
            $this->render('index', array(
                'criteria' => $criteria,
                'apCount' => $apCount,
                'filterName' => SearchHelper::$filterName,
            ));
        }
    }

    public function echoAjaxCount($apCount)
    {
        echo CJSON::encode(array(
            'count' => $apCount,
            'string' => Yii::t('common', '{n} listings', array($apCount, '{n}' => $apCount)),
        ));
        Yii::app()->end();
    }

    public function actionLoadForm()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw404();
        }

        $this->objType = CHtml::encode(Yii::app()->request->getParam('obj_type_id'));
        $isInner = CHtml::encode(Yii::app()->request->getParam('is_inner'));

        $roomsMin = CHtml::encode(Yii::app()->request->getParam('room_min'));
        $roomsMax = CHtml::encode(Yii::app()->request->getParam('room_max'));
        if ($roomsMin || $roomsMax) {
            $this->roomsCountMin = $roomsMin;
            $this->roomsCountMax = $roomsMax;
        }

        $this->sApId = CHtml::encode(Yii::app()->request->getParam('sApId'));

        $this->bStart = CHtml::encode(Yii::app()->request->getParam('b_start'));
        $this->bEnd = CHtml::encode(Yii::app()->request->getParam('b_end'));

        $floorMin = CHtml::encode(Yii::app()->request->getParam('floor_min'));
        $floorMax = CHtml::encode(Yii::app()->request->getParam('floor_max'));
        if ($floorMin || $floorMax) {
            $this->floorCountMin = $floorMin;
            $this->floorCountMax = $floorMax;
        }

        $this->wp = CHtml::encode(Yii::app()->request->getParam('wp'));
        $this->ot = CHtml::encode(Yii::app()->request->getParam('ot'));

        $squareMin = CHtml::encode(Yii::app()->request->getParam('square_min'));
        $squareMax = CHtml::encode(Yii::app()->request->getParam('square_max'));
        if ($squareMin || $squareMax) {
            $this->squareCountMin = $squareMin;
            $this->squareCountMax = $squareMax;
        }

        $this->selectedCity = Yii::app()->request->getParam('city', array());
        if (isset($this->selectedCity[0]) && $this->selectedCity[0] == 0) {
            $this->selectedCity = array();
        }

        if (issetModule('location')) {
            $country = CHtml::encode(Yii::app()->request->getParam('country'));
            if ($country) {
                $this->selectedCountry = $country;
            }

            $region = CHtml::encode(Yii::app()->request->getParam('region'));
            if ($region) {
                $this->selectedRegion = $region;
            }
        }

        if (issetModule('metroStations')) {
            $this->selectedMetroStations = Yii::app()->request->getParam('metro', array());
            if (isset($this->selectedMetroStations[0]) && $this->selectedMetroStations[0] == 0) {
                $this->selectedMetroStations = array();
            }
        }

        $this->objType = CHtml::encode(Yii::app()->request->getParam('objType'));
        $this->apType = CHtml::encode(Yii::app()->request->getParam('apType'));


        $this->term = CHtml::encode(Yii::app()->request->getParam('term'));

        if (issetModule('formeditor')) {
            $newFieldsAll = FormDesigner::getNewFields();
            foreach ($newFieldsAll as $field) {
                $value = CHtml::encode(Yii::app()->request->getParam($field->field));
                if (!$value) {
                    continue;
                }
                $fieldString = $field->field;
                $this->newFields[$fieldString] = $value;
            }
        }

        $compact = CHtml::encode(Yii::app()->request->getParam('compact', 0));

        HAjax::jsonOk('', array(
            'html' => $this->renderPartial('//site/_search_form', array('isInner' => $isInner, 'compact' => $compact), true),
            'sliderRangeFields' => SearchForm::getSliderRangeFields(),
            'cityField' => SearchForm::getCityField(),
            'countFiled' => SearchForm::getCountFiled(),
            'compact' => $compact,
        ));
    }
}
