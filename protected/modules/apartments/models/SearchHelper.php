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

class SearchHelper
{

    public static $filterName = null;

    public static function getCriteriaForMainSearch()
    {
        $countAjax = Yii::app()->request->getParam('countAjax');
        $model = Apartment::model();
        $controller = Yii::app()->controller;

        $criteria = new CDbCriteria;
        $criteria->addCondition('t.active = ' . Apartment::STATUS_ACTIVE);
        $criteria->addCondition('t.deleted = 0');
        if (param('useUserads')) {
            $criteria->addCondition('t.owner_active = ' . Apartment::STATUS_ACTIVE);
        }

        $listExclude = ApartmentObjType::getListExclude('search');
        if ($listExclude) {
            $criteria->addNotInCondition('t.obj_type_id', $listExclude);
        }

        $criteria->addInCondition('t.type', HApartment::availableApTypesIds());
        $criteria->addCondition('(t.price_type IN (' . implode(',', array_keys(HApartment::getPriceArray(Apartment::PRICE_SALE, true))) . ') OR t.is_price_poa = 1)');

        $controller->sApId = (int) Yii::app()->request->getParam('sApId');
        if ($controller->sApId) {
            $criteria->addCondition('t.id = :sApId');
            $criteria->params[':sApId'] = $controller->sApId;

            $apCount = Apartment::model()->count($criteria);
            if ($countAjax && Yii::app()->request->isAjaxRequest) {
                $controller->echoAjaxCount($apCount);
            }

            if ($apCount) {
                $apartmentModel = Apartment::model()->findByPk($controller->sApId);
                Yii::app()->controller->redirect($apartmentModel->getUrl());
                Yii::app()->end();
            }
        }

        $landSquare = Yii::app()->request->getParam('land_square');
        if ($landSquare) {
            $criteria->addCondition('land_square <= :land_square');
            $criteria->params[':land_square'] = $landSquare;

            $controller->landSquare = $landSquare;
        }

        $controller->selectedCity = Yii::app()->request->getParam('city', array());
        if (isset($controller->selectedCity[0]) && $controller->selectedCity[0] == 0) {
            $controller->selectedCity = array();
        }

        if (is_array($controller->selectedCity) && !empty($controller->selectedCity))
            $controller->selectedCity = array_map("intval", $controller->selectedCity);
        elseif (is_numeric($controller->selectedCity) && !empty($controller->selectedCity))
            $controller->selectedCity = (int) $controller->selectedCity;

        if (issetModule('location')) {
            $country = (int) Yii::app()->request->getParam('country');
            if ($country) {
                $controller->selectedCountry = $country;
                $criteria->compare('loc_country', $country);
            }

            $region = (int) Yii::app()->request->getParam('region');
            if ($region) {
                $controller->selectedRegion = $region;
                $criteria->compare('loc_region', $region);
            }

            if ($controller->selectedCity) {
                $criteria->compare('t.loc_city', $controller->selectedCity);
            }
        } else {
            if ($controller->selectedCity) {
                $criteria->compare('t.city_id', $controller->selectedCity);
            }
        }

        $controller->objType = (int) Yii::app()->request->getParam('objType');
        if ($controller->objType) {
            $criteria->compare('obj_type_id', $controller->objType);
        }

        // rooms
        if (issetModule('selecttoslider') && param('useRoomSlider') == 1) {
            $roomsMin = Yii::app()->request->getParam('room_min');
            $roomsMax = Yii::app()->request->getParam('room_max');

            if ($roomsMin || $roomsMax) {
                $criteria->addCondition('num_of_rooms >= :roomsMin AND num_of_rooms <= :roomsMax');
                $criteria->params[':roomsMin'] = $roomsMin;
                $criteria->params[':roomsMax'] = $roomsMax;

                $controller->roomsCountMin = $roomsMin;
                $controller->roomsCountMax = $roomsMax;
            }
        } else {
            $rooms = Yii::app()->request->getParam('rooms');
            if ($rooms) {
                if ($rooms == 4) {
                    $criteria->addCondition('num_of_rooms >= :rooms');
                } else {
                    $criteria->addCondition('num_of_rooms = :rooms');
                }
                $criteria->params[':rooms'] = $rooms;

                $controller->roomsCount = $rooms;
            }
        }

        // поиск объявлений владельца
        $controller->userListingId = Yii::app()->request->getParam('userListingId');
        if ($controller->userListingId) {
            $criteria->addCondition('owner_id = :userListingId');
            $criteria->params[':userListingId'] = $controller->userListingId;
        }

        self::$filterName = null;
        // Поиск по справочникам - клик в просмотре профиля анкеты
        if (param('useReferenceLinkInView')) {
            if (Yii::app()->request->getQuery('serviceId', false)) {
                $serviceId = Yii::app()->request->getQuery('serviceId', false);
                if ($serviceId) {
                    $serviceIdArray = explode('-', $serviceId);
                    if (is_array($serviceIdArray) && count($serviceIdArray) > 0) {
                        Yii::app()->getModule('referencevalues');
                        $value = (int) $serviceIdArray[0];

                        $sql = 'SELECT DISTINCT apartment_id FROM {{apartment_reference}} WHERE reference_value_id = ' . $value;
                        $criteria->addCondition('(t.id IN(' . $sql . '))');

                        $sql = 'SELECT title_' . Yii::app()->language . ' FROM {{apartment_reference_values}} WHERE id = ' . $value;
                        self::$filterName = Yii::app()->db->cache(param('cachingTime', 1209600), ReferenceValues::getDependency())->createCommand($sql)->queryScalar();

                        if (self::$filterName) {
                            self::$filterName = CHtml::encode(self::$filterName);
                        }
                    }
                }
            }
        }

        // param for SearchHelper
        $controller->bStart = Yii::app()->request->getParam('b_start');
        $controller->bEnd = Yii::app()->request->getParam('b_end');
        if ($controller->bStart) {
            $model->bStart = $controller->bStart;
            $model->bEnd = $controller->bEnd;
        }

        // type
        $model->apType = $controller->apType = Yii::app()->request->getParam('apType');

        $model->type = (int) Yii::app()->request->getParam('type');

        // price
        $model->price_min = $controller->priceSlider['min'] = Yii::app()->request->getParam("price_min");
        $model->price_max = $controller->priceSlider['max'] = Yii::app()->request->getParam("price_max");

        // ключевые слова
        $model->term = Yii::app()->request->getParam('term');

        // floor
        $controller->floorCountMin = Yii::app()->request->getParam('floor_min');
        $controller->floorCountMax = Yii::app()->request->getParam('floor_max');

        if ($controller->floorCountMin || $controller->floorCountMax) {
            $model->floor_min = $controller->floorCountMin;
            $model->floor_max = $controller->floorCountMax;
        }

        $controller->squareCountMin = Yii::app()->request->getParam('square_min');
        $controller->squareCountMax = Yii::app()->request->getParam('square_max');

        if ($controller->squareCountMin || $controller->squareCountMax) {
            $model->square_min = $controller->squareCountMin;
            $model->square_max = $controller->squareCountMax;
        }

        $controller->wp = $model->wp = Yii::app()->request->getParam('wp');
        $controller->ot = $model->ot = Yii::app()->request->getParam('ot');

        if (issetModule('metroStations')) {
            $model->metroSrc = $controller->selectedMetroStations = Yii::app()->request->getParam('metro', array());
        }

        $criteria = SearchHelper::genCriteria($model, $criteria);

        return $criteria;
    }

    public static function genCriteria($model, CDbCriteria $criteria)
    {
        //$criteria = new CDbCriteria();

        if ($model->floor_min || $model->floor_max) {
            if ($model->floor_min) {
                $criteria->addCondition('floor >= :floorMin');
                $criteria->params[':floorMin'] = $model->floor_min;
            }
            if ($model->floor_max) {
                $criteria->addCondition('floor <= :floorMax');
                $criteria->params[':floorMax'] = $model->floor_max;
            }
        }

        if ($model->square_min || $model->square_max) {
            if ($model->square_min) {
                $criteria->addCondition('square >= :squareMin');
                $criteria->params[':squareMin'] = $model->square_min;
            }
            if ($model->square_max) {
                $criteria->addCondition('square <= :squareMax');
                $criteria->params[':squareMax'] = $model->square_max;
            }
        }

        if ($model->ot) {
            $criteria->join = 'INNER JOIN {{users}} AS u ON u.id = t.owner_id';
            if ($model->ot == User::TYPE_PRIVATE_PERSON) {
                $ownerTypes = array(
                    User::TYPE_PRIVATE_PERSON,
                    User::TYPE_ADMIN
                );
            }
            if ($model->ot == User::TYPE_AGENCY) {
                $ownerTypes = array(
                    User::TYPE_AGENT,
                    User::TYPE_AGENCY
                );
            }
            if (isset($ownerTypes) && $ownerTypes)
                $criteria->compare('u.type', $ownerTypes);
        }

        if (issetModule('metroStations')) {
            $selectedMetroStations = $model->metroSrc;

            if (isset($selectedMetroStations[0]) && $selectedMetroStations[0] == 0) {
                $selectedMetroStations = array();
            }

            if (!empty($selectedMetroStations)) {
                if (is_array($selectedMetroStations))
                    $selectedMetroStations = array_map("intval", $selectedMetroStations);
                else
                    $selectedMetroStations = (int) $selectedMetroStations;

                if ($selectedMetroStations) {
                    if (!is_array($selectedMetroStations))
                        $selectedMetroStations = array($selectedMetroStations);

                    $sqlMetro = 'SELECT DISTINCT apartment_id FROM {{apartment_metro_stations}} WHERE metro_id IN (' . implode(',', $selectedMetroStations) . ')';
                    $criteria->addCondition('(t.id IN(' . $sqlMetro . '))');
                }
            }
        }

        $useSeasonalPrices = issetModule('seasonalprices');

        if ($model->price_min || $model->price_max) {

            if (issetModule('currency')) {
                $model->price_min = floor(Currency::convertToDefault($model->price_min));
                $model->price_max = ceil(Currency::convertToDefault($model->price_max));
            } else {
                $model->price_min = (int) $model->price_min;
                $model->price_max = (int) $model->price_max;
            }

            if ($model->price_min && $model->price_max) {
                if ($useSeasonalPrices) {
                    // for non rent items
                    $or = '
				(
					t.price_type NOT IN(' . Apartment::PRICE_PER_HOUR . ', ' . Apartment::PRICE_PER_DAY . ', ' . Apartment::PRICE_PER_WEEK . ', ' . Apartment::PRICE_PER_MONTH . ')
					AND t.price >= :priceMin AND t.price <= :priceMax
				)';

                    $criteria->addCondition(
                        '
					(t.id IN(SELECT apartment_id FROM {{seasonal_prices}} WHERE price >= ' . $model->price_min . ' AND price <= ' . $model->price_max . ')
					OR (is_price_poa = 1)
					OR ' . $or . '
					)
					'
                    );
                    unset($or);
                } else {
                    $criteria->addCondition('(price >= :priceMin AND price <= :priceMax) OR (is_price_poa = 1)');
                }

                $criteria->params[':priceMin'] = $model->price_min;
                $criteria->params[':priceMax'] = $model->price_max;
            } elseif ($model->price_min) {
                if ($useSeasonalPrices) {
                    // for non rent items
                    $or = '
					(
						t.price_type NOT IN(' . Apartment::PRICE_PER_HOUR . ', ' . Apartment::PRICE_PER_DAY . ', ' . Apartment::PRICE_PER_WEEK . ', ' . Apartment::PRICE_PER_MONTH . ')
						AND t.price >= :priceMin
					)';

                    $criteria->addCondition(
                        '
					(t.id IN (SELECT apartment_id FROM {{seasonal_prices}} WHERE price >= :priceMin)
					OR (is_price_poa = 1)
					OR ' . $or . '
					)
					'
                    );
                    unset($or);
                } else {
                    $criteria->addCondition('price >= :priceMin OR is_price_poa = 1');
                }
                $criteria->params[':priceMin'] = $model->price_min;
            } elseif ($model->price_max) {
                if ($useSeasonalPrices) {
                    // for non rent items
                    $or = '
					(
						t.price_type NOT IN(' . Apartment::PRICE_PER_HOUR . ', ' . Apartment::PRICE_PER_DAY . ', ' . Apartment::PRICE_PER_WEEK . ', ' . Apartment::PRICE_PER_MONTH . ')
						AND t.price <= :priceMax
					)';

                    $criteria->addCondition(
                        '
					(t.id IN (SELECT apartment_id FROM {{seasonal_prices}} WHERE price <= :priceMax)
					OR (is_price_poa = 1)
					OR ' . $or . '
					)
					'
                    );
                    unset($or);
                } else {
                    $criteria->addCondition('price <= :priceMax OR is_price_poa = 1');
                }
                $criteria->params[':priceMax'] = $model->price_max;
            }
        }

        if ($model->apType) {
            if (strpos($model->apType, '-') !== false) {
                $typeArr = explode('-', $model->apType);
                $type = (int) $typeArr[0];
                $priceType = (int) $typeArr[1];
                if ($useSeasonalPrices) {
                    $criteria->addCondition('( t.id IN(SELECT apartment_id FROM {{seasonal_prices}} WHERE price_type = :price_type ) OR (is_price_poa = 1) )');
                } else {
                    $criteria->addCondition('t.price_type = :price_type');
                }
                $criteria->params[':price_type'] = $priceType;
                $criteria->addCondition('t.type = :apType');
                $criteria->params[':apType'] = $type;
            } else {
                $criteria->addCondition('t.type = :apType');
                $criteria->params[':apType'] = (int) $model->apType;
            }
        }

        //booking
        if ($model->bStart) {
            $dateStart = Yii::app()->dateFormatter->format('yyyy-MM-dd', CDateTimeParser::parse($model->bStart, Booking::getYiiDateFormat()));
            if ($model->bEnd) {
                $dateEnd = Yii::app()->dateFormatter->format('yyyy-MM-dd', CDateTimeParser::parse($model->bEnd, Booking::getYiiDateFormat()));
            } else {
                $dateEnd = $dateStart;
            }

            if ($dateStart && $dateEnd) {
                $criteria->addCondition('t.id NOT IN (
                    SELECT DISTINCT b.apartment_id
                        FROM {{booking_calendar}} AS b
                        WHERE b.date_start BETWEEN :b_start AND :b_end
                            OR :b_start BETWEEN b.date_start AND b.date_end
                )');
                $criteria->params['b_start'] = $dateStart;
                $criteria->params['b_end'] = $dateEnd;
            }
        }

        //with photo
        if ($model->wp) {
            $criteria->addCondition('count_img > 0');
        }

        if ($model->photo) {
            if ($model->photo == 1) {
                $criteria->addCondition('count_img > 0');
            } elseif ($model->photo == 2) {
                $criteria->addCondition('count_img = 0');
            }
        }

        //$doTermSearch = Yii::app()->request->getParam('do-term-search');
        $term = $model->term;
        if ($term /* && $doTermSearch */) {
            $term = mb_substr($term, 0, 50);
            $term = cleanPostData($term);

            if ($term && mb_strlen($term) >= param('minLengthSearch', 4)) {
                Yii::app()->controller->term = $term;

                $words = explode(' ', $term);
                foreach ($words as $key => $value) {
                    if (mb_strlen($value) < param('minLengthSearch', 4)) {
                        unset($words[$key]);
                    }
                }


                $searchString = '+' . implode('* +', $words) . '* '; # https://dev.mysql.com/doc/refman/5.5/en/fulltext-boolean.html
                /*
                  if (issetModule('location')) {
                  $sqlStr = ' OR t.loc_country IN (
                  SELECT id FROM {{location_country}}
                  WHERE MATCH name_' . Yii::app()->language . ' AGAINST ("' . $searchString . '" IN BOOLEAN MODE)
                  ) OR t.loc_region IN (
                  SELECT id FROM {{location_region}}
                  WHERE MATCH name_' . Yii::app()->language . ' AGAINST ("' . $searchString . '" IN BOOLEAN MODE)
                  ) OR t.loc_city IN (
                  SELECT id FROM {{location_city}}
                  WHERE MATCH name_' . Yii::app()->language . ' AGAINST ("' . $searchString . '" IN BOOLEAN MODE)
                  )';
                  }
                 */
                if (issetModule('location')) {
                    $sql = 'SELECT ap.id
						FROM {{apartment}} ap 
							LEFT JOIN {{location_country}} lco ON ap.loc_country = lco.id
							LEFT JOIN {{location_region}} lre ON ap.loc_region = lre.id
							LEFT JOIN {{location_city}} lci ON ap.loc_city = lci.id
						WHERE ( MATCH
							(title_' . Yii::app()->language . ', description_' . Yii::app()->language . ', description_near_' . Yii::app()->language . ',
							 lco.name_' . Yii::app()->language . ', lre.name_' . Yii::app()->language . ', lci.name_' . Yii::app()->language . ', address_' . Yii::app()->language . ')
							AGAINST ("' . $searchString . '" IN BOOLEAN MODE) )';
                } else {
                    $sql = 'SELECT ap.id
						FROM {{apartment}} ap 
							LEFT JOIN {{apartment_city}} apc ON ap.city_id = apc.id
						WHERE ( MATCH
							(title_' . Yii::app()->language . ', description_' . Yii::app()->language . ', description_near_' . Yii::app()->language . ',
							 apc.name_' . Yii::app()->language . ', address_' . Yii::app()->language . ')
							AGAINST ("' . $searchString . '" IN BOOLEAN MODE) )';
                }

                $criteria->addCondition('(t.id IN(' . $sql . '))');
            }
        }


        if (issetModule('formeditor')) {
            $newFieldsAll = FormDesigner::getNewFields();
            $apps = $appsLike = array();
            foreach ($newFieldsAll as $field) {
                if ($field->type == FormDesigner::TYPE_MULTY) {
                    $value = Yii::app()->request->getParam($field->field);
                    if (!$value || !is_array($value))
                        continue;

                    $fieldString = $field->field;
                    Yii::app()->controller->newFields[$fieldString] = $value;
                    foreach ($value as $val) {
                        if ($field->compare_type == FormDesigner::COMPARE_LIKE) {
                            $appsLike[] = CHtml::listData(Reference::model()->findAllByAttributes(array('reference_value_id' => $val), array('select' => 'apartment_id')), 'apartment_id', 'apartment_id');
                        } else {
                            $apps[] = CHtml::listData(Reference::model()->findAllByAttributes(array('reference_value_id' => $val), array('select' => 'apartment_id')), 'apartment_id', 'apartment_id');
                        }
                    }

                    if ($appsLike) {
                        $appsLike = (count($appsLike) > 1) ? call_user_func_array('array_merge', $appsLike) : $appsLike[0];
                        $criteria->addInCondition('t.id', $appsLike);
                    }
                } else {
                    $fieldString = $field->field;

                    if ($field->type == FormDesigner::TYPE_RANGE) {
                        $field_from = $fieldString . '_from';
                        $field_to = $fieldString . '_to';
                    }

                    if ($field->compare_type == FormDesigner::COMPARE_FROM_TO) {
                        $value = array();
                        $value['min'] = CHtml::encode(Yii::app()->request->getParam($field->field . '_min'));
                        $value['max'] = CHtml::encode(Yii::app()->request->getParam($field->field . '_max'));

                        if ($value['min'] && $value['max'] && $value['max'] < $value['min']) {
                            $value_tmp = $value['min'];
                            $value['min'] = $value['max'];
                            $value['max'] = $value_tmp;
                        }

                        Yii::app()->controller->newFields[$fieldString] = $value;

                        $value['min'] = intval($value['min']);
                        $value['max'] = intval($value['max']);

                        if ($field->type != FormDesigner::TYPE_RANGE) {
                            if ($value['min']) {
                                $criteria->compare($fieldString, ">={$value['min']}");
                            }
                            if ($value['max']) {
                                $criteria->compare($fieldString, "<={$value['max']}");
                            }
                        } else {
                            $value_min = ':' . $fieldString . '_from_value';
                            $value_max = ':' . $fieldString . '_to_value';


                            if ($value['min'] && $value['max']) {
                                $criteria->addCondition("($field_to >= $value_min AND $field_from <= $value_max) 
                                OR ($field_to = 0 AND $field_from <= $value_max AND $field_from != 0)");

                                $criteria->params[$value_min] = $value['min'];
                                $criteria->params[$value_max] = $value['max'];
                            } elseif ($value['min']) {
                                $criteria->addCondition("($field_to >= $value_min) 
                                OR ($field_to = 0 AND $field_from != 0)");
                                $criteria->params[$value_min] = $value['min'];
                            } elseif ($value['max']) {
                                $criteria->addCondition("($field_from <= $value_max AND $field_from != 0) 
                                OR ($field_from = 0 AND $field_to != 0)");
                                $criteria->params[$value_max] = $value['max'];
                            }
                        }
                    } else {
                        $value = CHtml::encode(Yii::app()->request->getParam($field->field));
                        if (!$value) {
                            continue;
                        }

                        Yii::app()->controller->newFields[$fieldString] = $value;

                        if ($field->type != FormDesigner::TYPE_RANGE) {
                            switch ($field->compare_type) {
                                case FormDesigner::COMPARE_EQUAL:
                                    $criteria->compare($fieldString, $value);
                                    break;

                                case FormDesigner::COMPARE_LIKE:
                                    $criteria->compare($fieldString, $value, true);
                                    break;

                                case FormDesigner::COMPARE_FROM:
                                    $value = intval($value);
                                    $criteria->compare($fieldString, ">={$value}");
                                    break;

                                case FormDesigner::COMPARE_TO:
                                    $value = intval($value);
                                    $criteria->compare($fieldString, "<={$value}");
                                    break;
                            }
                        } else {
                            $value_name = ':' . $fieldString . '_from_value';

                            $criteria->params[$value_name] = $value;
                            switch ($field->compare_type) {
                                case FormDesigner::COMPARE_EQUAL:
                                case FormDesigner::COMPARE_LIKE:
                                    $criteria->addCondition("($field_to >= $value_name AND $field_from <= $value_name) 
                                    OR ($field_to = 0 AND $field_from <= $value_name AND $field_from != 0)");
                                    break;


                                case FormDesigner::COMPARE_FROM:
                                    $criteria->addCondition("($field_to >= $value_name) 
                                     OR ($field_to = 0 AND $field_from != 0)");
                                    break;

                                case FormDesigner::COMPARE_TO:
                                    $criteria->addCondition("($field_from <= $value_name AND $field_from != 0) 
                                    OR ($field_from = 0 AND $field_to != 0)");
                                    break;
                            }
                        }
                    }
                }
            }
            if ($apps) {
                $apps = (count($apps) > 1) ? call_user_func_array('array_intersect', $apps) : $apps[0];
                $criteria->addInCondition('t.id', $apps);
            }
        }

        if ($model->rooms) {
            if ($model->rooms == 4) {
                $criteria->addCondition('num_of_rooms >= :rooms');
            } else {
                $criteria->addCondition('num_of_rooms = :rooms');
            }
            $criteria->params[':rooms'] = (int) $model->rooms;
        }

        return $criteria;
    }

    public static function badge($label, $onclick = '')
    {
        return '<div class="badge">' . CHtml::encode($label) . '&nbsp;&nbsp;<i class="close-filter glyphicon glyphicon-remove" onclick="' . $onclick . '"></i></div>';
    }

    public static function getBageForMetro($metroSel, $city)
    {
        $metros = MetroStations::getMetrosArray($city, 0);
        $label = array();
        foreach ($metroSel as $id) {
            if (isset($metros[$id]))
                $label[] = $metros[$id];
        }
        return $label ? self::badge(implode(', ', $label), 'filter.clearMetro();') : '';
    }

    public static function getRoomsList()
    {
        return array(
            '0' => '',
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => Yii::t('common', '4 and more'),
        );
    }

    public static function getOwnerList()
    {
        return array(
            0 => '',
            User::TYPE_PRIVATE_PERSON => tc('Private person'),
            User::TYPE_AGENCY => tc('Company'),
        );
    }

    public static function getPhotoList()
    {
        return array(
            0 => '',
            1 => tc('With photo'),
            2 => tc('Without photo'),
        );
    }
}
