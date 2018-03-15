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

class UserAds extends Apartment
{

    public function search()
    {
        $this->owner_id = Yii::app()->user->id;

        return parent::search();
    }

    public static function returnStatusHtml($data, $tableId, $onclick = 0, $ignore = 0, $returnName = false, $nameWithColor = false)
    {
        if ($ignore && $data->id == $ignore) {
            return '';
        }

        $name = tc('Inactive');
        if ($data->active == Apartment::STATUS_MODERATION) {
            $name = tc('Awaiting moderation');
        } elseif ($data->active == Apartment::STATUS_ACTIVE) {
            $name = tc('Active');
        }

        if ($returnName) {
            if ($nameWithColor) {
                $class = 'usercpanel-listing-status';
                switch ($data->active) {
                    case Apartment::STATUS_ACTIVE:
                        $class .= ' text-success';
                        break;
                    case Apartment::STATUS_INACTIVE:
                        $class .= ' text-danger';
                        break;
                    case Apartment::STATUS_MODERATION:
                        $class .= ' text-warning';
                        break;
                }
                return "<span class='$class'>$name</span>";
            }
            return $name;
        }

        return '<div class="center">' . $name . '</div>';
    }

    public static function returnStatusOwnerActiveHtml($data, $tableId, $onclick = 0, $ignore = 0, $returnName = false, $nameWithColor = false)
    {
        if ($ignore && $data->id == $ignore) {
            return '';
        }
        $url = Yii::app()->controller->createUrl("/userads/main/activate", array("id" => $data->id, 'action' => ($data->owner_active == 1 ? 'deactivate' : 'activate')));
        $img = CHtml::image(
                Yii::app()->theme->baseUrl . '/images/' . ($data->owner_active ? '' : 'in') . 'active.png', Yii::t('common', $data->owner_active ? 'Inactive' : 'Active'), array('title' => Yii::t('common', $data->owner_active ? 'Deactivate' : 'Activate'))
        );
        $options = array();
        if ($onclick) {

            if ($data->owner_active) {
                $options = array(
                    'onclick' => 'ajaxSetStatus(this, "' . $tableId . '", 1); return false;',
                );
            } else {
                $options = array(
                    'onclick' => 'ajaxSetStatus(this, "' . $tableId . '", 2); return false;',
                );
            }
        }

        $name = tc('Inactive');
        if ($data->owner_active == Apartment::STATUS_MODERATION) {
            $name = tc('Awaiting moderation');
        } elseif ($data->owner_active == Apartment::STATUS_ACTIVE) {
            $name = tc('Active');
        }

        if ($returnName) {
            if ($nameWithColor) {
                $class = 'usercpanel-listing-owner-status';
                switch ($data->owner_active) {
                    case Apartment::STATUS_ACTIVE:
                        $class .= ' text-success';
                        break;
                    case Apartment::STATUS_INACTIVE:
                        $class .= ' text-danger';
                        break;
                    case Apartment::STATUS_MODERATION:
                        $class .= ' text-warning';
                        break;
                }
                return "<span class='$class'>$name</span>";
            }
            return $name;
        }

        $return = '<div class="center">' . CHtml::link($img, $url, $options) . '</div>';

        return $return;
    }

    public function beforeSave()
    {
        if (!$this->isNewRecord && !$this->isOwner()) {
            throw404();
        }

        return parent::beforeSave();
    }
}
