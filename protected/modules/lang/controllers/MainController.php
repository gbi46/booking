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

    public $modelName = 'Lang';

    public function actionAjaxTranslate()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw404();

        $fromLang = Yii::app()->request->getPost('fromLang');
        $fields = Yii::app()->request->getPost('fields');
        $errors = false;
        $translateField = array();

        if (!$fromLang || !$fields)
            throw new CException('Lang no req data');

        $translate = new MyMemoryTranslated();
        $fromVal = $fields[$fromLang];

        foreach ($fields as $lang => $val) {
            if ($lang == $fromLang)
                continue;

            if ($answer = $translate->translateText($fromVal, $fromLang, $lang))
                $translateField[$lang] = $answer;
            else
                $errors = true;
        }

        if ($errors) {
            echo json_encode(array(
                'result' => 'no',
                'fields' => ''
            ));
        } else {
            echo json_encode(array(
                'result' => 'ok',
                'fields' => $translateField
            ));
        }
        Yii::app()->end();
    }

    public function actionDeleteLang()
    {
        exit;

        $lang = 'lang_prefix';

        $db = Yii::app()->db;

        Yii::import('application.modules.referencecategories.models.ReferenceCategories');
        Yii::import('application.modules.referencevalues.models.ReferenceValues');
        Yii::import('application.modules.windowto.models.WindowTo');
        Yii::import('application.modules.articles.models.Article');
        Yii::import('application.modules.formdesigner.models.FormDesigner');
        Yii::import('application.modules.notifier.models.NotifierModel');

        $langModel = new Lang; // init()
        $modelNameI18nArr = $langModel->_modelNameI18nArr;

        foreach ($modelNameI18nArr as $modelName) {
            $model = new $modelName;
            $table = $model->tableName();
            $i18nFields = $model->i18nFields();

            foreach ($i18nFields as $field => $type) {
                $columnName = $field . '_' . $lang;

                $sql = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME='{$table}' AND COLUMN_NAME='{$columnName}' AND table_schema = DATABASE()";
                $fieldExist = $db->createCommand($sql)->queryScalar();

                if ($fieldExist) {
                    $sql = "ALTER TABLE {$table} DROP `$columnName` ";
                    $db->createCommand($sql)->execute();

                    // delete fulltext index
                    if ($modelName == 'Apartment' && is_numeric(array_search($field, Lang::$apartmentIndexedFields))) {
                        $deleteIndex = false;

                        $allIndexes = $db->createCommand('SHOW INDEX FROM ' . $table)->queryAll();
                        if ($allIndexes) {
                            $resIndex = CHtml::listData($allIndexes, 'Key_name', 'Index_type');

                            if (array_key_exists($columnName, $resIndex))
                                $deleteIndex = true;
                        }

                        if ($deleteIndex)
                            $db->createCommand('ALTER TABLE ' . $table . ' DROP INDEX ( ' . $columnName . ' );')->execute();
                    }
                }
            }
        }

        Yii::app()->cache->flush();

        echo 'ok';
        exit;
    }
}
