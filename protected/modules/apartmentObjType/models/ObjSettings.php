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

class ObjSettings
{

    public $keys = array('child_section', 'child_add', 'child_success_add');
    public $keysLabel = array();
    public $models = array();

    public function __construct($id)
    {
        foreach ($this->keys as $pk => $key) {
            $newKey = $key . '_' . $id;
            $this->keys[$pk] = $newKey;

            $model = TranslateMessage::model()->findByAttributes(array(
                'category' => 'common',
                'message' => $newKey,
            ));

            if (!$model) {
                $model = new TranslateMessage();
                $model->category = 'common';
                $model->message = $newKey;
                $model->save(false);
            }

            $this->models[] = $model;
        }

        $this->keysLabel = array(
            'child_section_' . $id => tt('Section'),
            'child_add_' . $id => tt('The caption on the add button'),
            'child_success_add_' . $id => tt('The message about the successful addition'),
        );
    }

    public function getLabel($key)
    {
        return isset($this->keysLabel[$key]) ? $this->keysLabel[$key] : null;
    }
}
