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

class apartmentsHelper
{

    public static function getApartments($limit = 10, $usePagination = 1, $all = 1, $criteria = null, $showChild = false)
    {
        $pages = array();

        Yii::app()->getModule('apartments');

        if ($criteria === null)
            $criteria = new CDbCriteria;

        if (!$all) {
            $criteria->addCondition('t.deleted = 0');
            $criteria->addCondition('t.active = ' . Apartment::STATUS_ACTIVE);
            $criteria->addCondition('t.owner_active = 1');
        }

        $sort = new CSort('Apartment');
        $sort->attributes = array(
            'price' => array('asc' => 'price', 'desc' => 'price DESC', 'default' => 'desc'),
            'date_created' => array('asc' => 'date_created', 'desc' => 'date_created DESC', 'default' => 'desc'),
            'rating' => array('asc' => 'rating', 'desc' => 'rating DESC', 'default' => 'desc'),
        );

        if (!$criteria->order)
            $sort->defaultOrder = 't.date_up_search DESC, t.sorter DESC';
        $sort->applyOrder($criteria);

        if (issetModule('seasonalprices')) {
            $criteria->with = CMap::mergeArray($criteria->with, array('seasonalPrices'));

            if ($criteria->order) {
                if ($criteria->order == '`t`.`price`') {
                    $criteria->order = 't.price, seasonalPrices_sort_asc.price';
                    $criteria->with = CMap::mergeArray($criteria->with, array('seasonalPrices_sort_asc'));
                }
                if ($criteria->order == '`t`.`price` DESC') {
                    $criteria->order = 't.price DESC, seasonalPrices_sort_desc.price DESC';
                    $criteria->with = CMap::mergeArray($criteria->with, array('seasonalPrices_sort_desc'));
                }
            }
        }

        $sorterLinks = self::getSorterLinks($sort);

        $criteria->addInCondition('t.type', HApartment::availableApTypesIds());
        //$criteria->addInCondition('t.price_type', array_keys(HApartment::getPriceArray(Apartment::PRICE_SALE, true)));
        $criteria->addCondition('(t.price_type IN (' . implode(',', array_keys(HApartment::getPriceArray(Apartment::PRICE_SALE, true))) . ') OR t.is_price_poa = 1)');

        if ($showChild == false) {
            $listExclude = ApartmentObjType::getListExclude('search');
            if ($listExclude) {
                $criteria->addNotInCondition('t.obj_type_id', $listExclude);
            }
        }

        // find count
        $apCount = Apartment::model()->count($criteria);

        if ($usePagination && $limit) {
            $pages = new CPagination($apCount);
            $pages->pageSize = $limit;
            $pages->applyLimit($criteria);
        } else {
            if ($limit)
                $criteria->limit = $limit;
        }

        if (issetModule('seo')) {
            $criteria->with = CMap::mergeArray($criteria->with, array('seo'));
        }

        return array(
            'pages' => $pages,
            'sorterLinks' => $sorterLinks,
            'apCount' => $apCount,
            'criteria' => $criteria
        );
    }

    public static function getSorterLinks($sort)
    {
        $HtmlOption = array('onClick' => 'reloadApartmentList(this.href); return false;');
        $return = array(
            $sort->link('price', tt('Sorting by price', 'quicksearch'), $HtmlOption),
            $sort->link('date_created', tt('Sorting by date created', 'quicksearch'), $HtmlOption),
            $sort->link('rating', tt('Sorting by rating', 'quicksearch'), $HtmlOption),
        );
        return $return;
    }
}
