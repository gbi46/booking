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

class GridHelper
{

    public static function getSummary(Apartment $ad, $from = 'backend')
    {
        $html = '<div class="summary_info">';
        $html .= '<div class="col-sm-12 col-md-1 summaryApImage">';
        $html .= CHtml::link(Apartment::returnMainThumbForGrid($ad, 'thumbnail', $from), $ad->getUrl(), array('target' => '_blank'));
        $html .= '</div>';

        if ($from == 'backend') {
            $html .= '<div class="col-sm-12 col-md-8">';
        } else {
            $html .= '<div class="col-sm-12 col-md-9">';
        }

        $html .= '<div class="title">' . $ad->getTitle() . '</div>';

        $location = array();
        if (issetModule('location')) {
            if (isset($ad->locCountry) && $ad->locCountry) {
                $location[] = $ad->locCountry->getStrByLang('name');
            }
            //              if(isset($ad->locRegion) && $ad->locRegion) {
            //                    $location[] = $ad->locRegion->getStrByLang('name');
            //				}
            if (isset($ad->locCity) && $ad->locCity) {
                $location[] = $ad->locCity->getStrByLang('name');
            }
        } else {
            if (isset($ad->city) && $ad->city) {
                $location[] = $ad->city->getStrByLang('name');
            }
        }
        if ($ad->getAddress())
            $location[] = $ad->getAddress();


        if ($from == 'backend') {
            $data = self::getColoredType($ad);
        } else {
            $data = HApartment::getNameByType($ad->type);
        }
        if (isset($ad->objType) && $ad->objType) {
            $data .= ', ' . $ad->objType->getStrByLang('name');
        }
        if (!empty($location)) {
            $data .= ', ' . implode(', ', $location) . ', <strong>' . $ad->getPrettyPrice(false) . '</strong>';
        }

        $html .= '<div class="summary_info_row">' . $data . '</div>';

        if ($from == 'backend') {
            $ownerData = (isset($ad->user) && $ad->user->role != User::ROLE_ADMIN) ? CHtml::link(CHtml::encode($ad->user->email), array("/users/backend/main/view", "id" => $ad->user->id)) : tt("administrator", "common");
            $data = tc('Owner') . ': ' . $ad->user->username . ' - ' . $ownerData;
            $data .= ', ' . tc('Date created') . ': ' . HDate::formatDateTime($ad->date_created);
            $html .= '<div class="summary_info_row">' . $data . '</div>';
        } else {
            $data = '<strong>' . tt('Status', 'apartments') . '</strong>: ' . UserAds::returnStatusHtml($ad, '', 0, 0, true, true);
            $data .= '<br /><strong>' . tt('Status (owner)', 'apartments') . '</strong>: ' . UserAds::returnStatusOwnerActiveHtml($ad, '', 0, 0, true, true);
            $html .= '<div class="summary_info_row">' . $data . '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    public static function getColoredType(Apartment $ad)
    {
        $css = '';
        switch ($ad->type) {
            case Apartment::TYPE_SALE:
                $css = 'label-info';
                break;

            case Apartment::TYPE_RENT;
                $css = 'label-success';
                break;

            case Apartment::TYPE_BUY;
                $css = 'label-';
                break;

            case Apartment::TYPE_CHANGE;
                $css = 'label-danger';
                break;

            case Apartment::TYPE_RENTING;
                $css = 'label-warning';
                break;
        }

        return '<span class="label ' . $css . '">' . HApartment::getNameByType($ad->type) . '</span>';
    }
}
