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

class ApartmentObjType extends ParentModel
{

    public $iconsMapPath = 'uploads/iconsmap';
    public $supportExt = 'jpg, png, gif, jpeg';
    public $fileMaxSize = 2097152; /* 1024 * 1024 * 2 - 2 MB */
    public $iconUpload;

    const MAP_ICON_MAX_HEIGHT = 37;
    const MAP_ICON_MAX_WIDTH = 32;

    private static $_cache;
    public static $_cacheChildIds = array();

    public function init()
    {
        $fileMaxSize['postSize'] = toBytes(ini_get('post_max_size'));
        $fileMaxSize['uploadSize'] = toBytes(ini_get('upload_max_filesize'));

        $this->fileMaxSize = min($fileMaxSize);
        parent ::init();
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{apartment_obj_type}}';
    }

    public function seoFields()
    {
        return array(
            'fieldTitle' => 'name',
        );
    }

    public function rules()
    {
        return array(
            array('name', 'i18nRequired'),
            array(
                'icon_file', 'file',
                'types' => "{$this->supportExt}",
                'maxSize' => $this->fileMaxSize,
                'tooLarge' => Yii::t('module_slider', 'The file was larger than {size}MB. Please upload a smaller file.', array('{size}' => $this->fileMaxSize)),
                'allowEmpty' => true
            ),
            array('sorter, parent_id, ya_type, with_obj, show_in_search, show_in_grid', 'numerical', 'integerOnly' => true),
            array('ya_subtype', 'length', 'max' => 255),
            array('name', 'i18nLength', 'max' => 255),
            array('id, sorter, date_updated', 'safe', 'on' => 'search'),
            array($this->getI18nFieldSafe(), 'safe'),
        );
    }

    public function i18nFields()
    {
        return array(
            'name' => 'varchar(255) not null default ""',
        );
    }

    public function getName()
    {
        return $this->getStrByLang('name');
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => tt('Name'),
            'sorter' => 'Sorter',
            'date_updated' => 'Date Updated',
            'icon_file' => tt('icon_file_maps'),
            'parent_id' => tt("Is located", 'apartments'),
            'ya_type' => tt("Type", "yandexRealty"),
            'ya_subtype' => tt("Subtype", "yandexRealty"),
            'show_in_search' => tt("Show in search", "apartments"),
            'show_in_grid' => tt("Show in grid", "apartments"),
            'with_obj' => tt("Contains objects", "apartments"),
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare($this->getTableAlias() . '.name_' . Yii::app()->language, $this->{'name_' . Yii::app()->language}, true);

        return new CustomActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => $this->getTableAlias() . '.sorter ASC',
            ),
            'pagination' => array(
                'pageSize' => param('adminPaginationPageSize', 20),
            ),
        ));
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $maxSorter = Yii::app()->db->createCommand()
                ->select('MAX(sorter) as maxSorter')
                ->from($this->tableName())
                ->queryScalar();
            $this->sorter = $maxSorter + 1;
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        if ($this->isNewRecord) {
            if (issetModule('formdesigner')) {
                Yii::import('application.modules.formdesigner.models.*');
                $forms = FormDesigner::model()->findAll();
                foreach ($forms as $form) {
                    $formType = new FormDesignerObjType();
                    $formType->formdesigner_id = $form->id;
                    $formType->obj_type_id = $this->id;
                    $formType->save();
                }
            }

            $searchFields = SearchFormModel::model()->sort()->findAllByAttributes(array('obj_type_id' => SearchFormModel::OBJ_TYPE_ID_DEFAULT));
            foreach ($searchFields as $field) {
                $newSearch = new SearchFormModel();
                $newSearch->attributes = $field->attributes;
                $newSearch->obj_type_id = $this->id;
                $newSearch->save();
            }
        }

        if (issetModule('seo')) {
            SeoFriendlyUrl::getAndCreateForModel($this);
        }

        return parent::afterSave();
    }

    public function beforeDelete()
    {
        if ($this->model()->count() <= 1) {
            echo 1;
            return false;
        }

        if ($this->icon_file) {
            $iconPath = Yii::getPathOfAlias('webroot') . '/' . $this->model()->iconsMapPath . '/' . $this->icon_file;
            if (file_exists($iconPath))
                @unlink($iconPath);
        }

        $db = Yii::app()->db;

        $sql = "SELECT id FROM " . $this->tableName() . " WHERE id != " . $this->id . " ORDER BY sorter ASC";
        $type_id = (int) $db->createCommand($sql)->queryScalar();

        $sql = "UPDATE {{apartment}} SET obj_type_id={$type_id}, active=0 WHERE obj_type_id=" . $this->id;
        $db->createCommand($sql)->execute();

        if (issetModule('formdesigner')) {
            $sql = "DELETE FROM {{formdesigner_obj_type}} WHERE obj_type_id=" . $this->id;
            $db->createCommand($sql)->execute();
        }

        $sql = "DELETE FROM {{search_form}} WHERE obj_type_id=" . $this->id;
        $db->createCommand($sql)->execute();

        if (issetModule('seo')) {
            $sql = 'DELETE FROM {{seo_friendly_url}} WHERE model_id="' . $this->id . '" AND model_name = "ApartmentObjType"';
            Yii::app()->db->createCommand($sql)->execute();
        }

        return parent::beforeDelete();
    }

    private static $_cacheList;

    public static function getList()
    {
        if (empty(self::$_cacheList)) {
            $listData = self::getListData();
            self::$_cacheList = CHtml::listData($listData, 'id', 'name_' . Yii::app()->language);
        }

        return self::$_cacheList;
    }

    public static function getYANTypes($short = true)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('ya_type != 0 AND ya_subtype != ""');
        $criteria->select = array('id', 'ya_type', 'ya_subtype');
        $types = ApartmentObjType::model()->findAll($criteria);

        $result = array();
        foreach ($types as $type) {
            if ($short) {
                $pos = strpos($type->ya_subtype, ",");
                $subtype = trim($pos ? substr($type->ya_subtype, 0, $pos) : $type->ya_subtype);
            } else
                $subtype = $type->ya_subtype;

            $result[$type->id] = array('ya_type' => $type->ya_type, 'ya_subtype' => $subtype);
        }


        return($result);
    }

    public static function getNameById($id)
    {
        $list = self::getList();

        return isset($list[$id]) ? $list[$id] : '';
    }

    public function getUrlIcon()
    {
        if ($this->icon_file) {
            $iconUrl = Yii::app()->getBaseUrl() . '/' . $this->iconsMapPath . '/' . $this->icon_file;
        } else {
            $iconUrl = Yii::app()->theme->baseUrl . "/images/house.png";
        }
        return $iconUrl;
    }

    public function getImageIcon()
    {
        return CHtml::image($this->getUrlIcon());
    }

    public static function getChildIds()
    {
        if (!isset(self::$_cache))
            self::getListData();
        return self::$_cacheChildIds;
    }

    public static function getListData()
    {
        if (!isset(self::$_cache)) {
            $rows = Yii::app()->db->createCommand("SELECT * FROM {{apartment_obj_type}}")->queryAll();
            foreach ($rows as $row) {
                self::$_cache[$row['id']] = $row;
                if ($row['parent_id'] > 0) {
                    self::$_cacheChildIds[] = $row['id'];
                }
            }
        }

        return self::$_cache;
    }

    public static function getListExclude($in)
    {
        $objTypeData = ApartmentObjType::getListData();
        $listExclude = array();
        foreach ($objTypeData as $objTypeId => $data) {
            if ($in == 'grid' && !$data['show_in_grid']) {
                $listExclude[] = $objTypeId;
            }

            if ($in == 'search' && !$data['show_in_search']) {
                $listExclude[] = $objTypeId;
            }
        }
        return $listExclude;
    }

    public static function getChildIdFor($id)
    {
        return Yii::app()->db
                ->createCommand("SELECT id FROM {{apartment_obj_type}} WHERE parent_id = :id")
                ->queryScalar(array(':id' => $id));
    }
}
