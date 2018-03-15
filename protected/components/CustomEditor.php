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

Yii::import('application.extensions.editMe.widgets.ExtEditMe');

class CustomEditor extends ExtEditMe
{

    public function init()
    {
        if (empty($this->toolbar)) {
            // даем пользователям ограниченый набор форматирования
            $this->toolbar = array(
                array('Source', '-', 'Bold', 'Italic', 'Underline', 'Strike'),
                array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'),
                array('NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                //array('Styles', 'Format', 'Font', 'FontSize', 'TextColor', 'BGColor'),
                array('Link', 'Unlink'),
            );

            if (Yii::app()->user->checkAccess('backend_access')) {
                $this->toolbar = array(
                    array('Source', '-', 'Bold', 'Italic', 'Underline', 'Strike'),
                    array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'),
                    array('NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                    array('Styles', 'Format', 'Font', 'FontSize', 'TextColor', 'BGColor'),
                    array('Image', 'Link', 'Unlink', 'SpecialChar'),
                );

                $this->allowedContent = true;
            }
        }

        if (Yii::app()->user->checkAccess('upload_from_wysiwyg')) { // if admin - enable upload image
            //$this->filebrowserImageUploadUrl = Yii::app()->createAbsoluteUrl('/site/uploadimage', array('type' => 'imageUpload', Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken));
            $this->filebrowserBrowseUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/browse.php?opener=ckeditor&type=files';
            $this->filebrowserImageBrowseUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/browse.php?opener=ckeditor&type=images';
            $this->filebrowserFlashBrowseUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/browse.php?opener=ckeditor&type=flash';

            $this->filebrowserUploadUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/upload.php?opener=ckeditor&type=files';
            $this->filebrowserImageUploadUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/upload.php?opener=ckeditor&type=images';
            $this->filebrowserFlashUploadUrl = Yii::app()->getBaseUrl(true) . '/re_kcfinder/upload.php?opener=ckeditor&type=flash';
        }

        return parent::init();
    }
}
