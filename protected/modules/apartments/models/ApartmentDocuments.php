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

class ApartmentDocuments extends ParentModel
{

    public $supportExt = 'pdf, doc, docx, txt, xls, xlsx, rtf, odt';
    public $fileMaxSize = 5085760; /* 1024 * 1024 * 10 - 5 MB */
    public $path = 'webroot.uploads.document';
    public $url = 'uploads/document';

    const MAX_DOCUMENTS_TO_AD = 5;

    public function init()
    {
        $fileMaxSize['postSize'] = toBytes(ini_get('post_max_size'));
        $fileMaxSize['uploadSize'] = toBytes(ini_get('upload_max_filesize'));
        $fileMaxSize['forceLimit'] = toBytes('5M');

        $this->fileMaxSize = min($fileMaxSize);

        parent::init();
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{apartment_document}}';
    }

    public function rules()
    {
        return array(
            array('apartment_id', 'required'),
            array('original_name, modified_name', 'length', 'max' => 255),
            array('apartment_id', 'numerical', 'integerOnly' => true),
            array('id, apartment_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        Yii::import('application.modules.apartments.models.Apartment');
        return array(
            'apartment' => array(self::BELONGS_TO, 'Apartment', 'apartment_id'),
        );
    }

    public function behaviors()
    {
        $arr = array();
        $arr['AutoTimestampBehavior'] = array(
            'class' => 'zii.behaviors.CTimestampBehavior',
            'createAttribute' => 'date_updated',
            'updateAttribute' => 'date_updated',
        );
        /* if (issetModule('historyChanges')) {
          $arr['ArLogBehavior'] = array(
          'class' => 'application.modules.historyChanges.components.ArLogBehavior',
          );
          } */

        return $arr;
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'apartment_id' => tt('apartment_id', 'apartments'),
            'original_name' => tt('document_original_name', 'apartments'),
            'modified_name' => tt('document_modified_name', 'apartments'),
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('apartment_id', $this->apartment_id);
        $criteria->compare('original_name', $this->original_name);
        $criteria->compare('modified_name', $this->modified_name);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeDelete()
    {
        if ($this->modified_name) {
            $pathVideo = Yii::getPathOfAlias($this->path) . DIRECTORY_SEPARATOR . $this->apartment_id . DIRECTORY_SEPARATOR;
            deleteFile($pathVideo, $this->modified_name);
        }

        return parent::beforeDelete();
    }

    public function getFileUrl()
    {
        return Yii::app()->getBaseUrl() . '/' . $this->url . '/' . $this->apartment_id . '/' . $this->modified_name;
    }

    public static function saveDocument(Apartment $ad)
    {
        $className = get_class($ad);
        if ((isset($_FILES[$className]['name']['document_file']) && $_FILES[$className]['name']['document_file'])) {
            $ad->scenario = 'document_file';

            $ad->documentUpload = CUploadedFile::getInstance($ad, 'document_file');

            if (!$ad->documentUpload) {
                $ad->addError('documentUpload', tt('Upload document error', 'apartments'));
                return false;
            }

            #max 5 documents
            if (self::getCountDocumentsToAd($ad->id) >= self::MAX_DOCUMENTS_TO_AD) {
                $ad->addError('documentUpload', Yii::t("module_apartments", "You are trying to download more than {num} documents", array("{num}" => self::MAX_DOCUMENTS_TO_AD)));
                return false;
            }

            $originalName = $ad->documentUpload->name;
            $originalName = filter_var($originalName, FILTER_SANITIZE_STRING);

            $extension = $ad->documentUpload->extensionName;
            $modifiedName = md5(uniqid());

            $documentFile = $modifiedName . '.' . $extension;

            $pathDocument = Yii::getPathOfAlias('webroot.uploads.document') . DIRECTORY_SEPARATOR . $ad->id;

            if (newFolder($pathDocument)) {
                $ad->documentUpload->saveAs($pathDocument . '/' . $documentFile);

                $sql = 'INSERT INTO {{apartment_document}} (apartment_id, original_name, modified_name, date_updated)
                            VALUES ("' . $ad->id . '", "' . $originalName . '", "' . $documentFile . '", NOW())';
                Yii::app()->db->createCommand($sql)->execute();

                if (issetModule('historyChanges')) {
                    HistoryChanges::addApartmentInfoToHistory('add_document', $ad->id, 'create');
                }

                //return true;
            } else {
                $ad->addError('documentUpload', tt('not_create_folder_to_save.', 'apartments'));
                return false;
            }
        }

        return true;
    }

    public static function getCountDocumentsToAd($id = 0)
    {
        if ($id) {
            $sql = 'SELECT COUNT(id) FROM {{apartment_document}} WHERE apartment_id =:id ';
            return Yii::app()->db->createCommand($sql)->queryScalar(array(':id' => $id));
        }

        return 0;
    }
}
