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

class ViewallonmapWidget extends CWidget
{

    public $usePagination = 1;
    public $selectedIds = array();
    public $count = null;
    public $filterOn = true;
    public $withCluster = true;
    public $filterPriceType;
    public $filterObjType;
    public $scrollWheel = true;
    public $draggable = true;
    public $lazyLoadMarkers = true;
    public $showWidgetTitle = true;
    public $customWidgetTitle;
    public $widgetTitles;

    const MAX_RESULT = 600;

    public function run()
    {
        Yii::app()->getModule('apartments');

        $this->filterPriceType = Yii::app()->request->getParam('filterPriceType');
        $this->filterObjType = (int) Yii::app()->request->getParam('filterObjType');

        $subTitleKey = InfoPages::getWidgetSubTitleKey('viewallonmap');
        $this->customWidgetTitle = InfoPages::getWidgetSubTitle($subTitleKey, $this->widgetTitles);

        if (empty($this->customWidgetTitle)) {
            $this->showWidgetTitle = false;
        }

        if ($this->showWidgetTitle) {
            if (Yii::app()->theme->name == 'classic') {
                echo '<h2>' . $this->customWidgetTitle . '</h2>';
            } else {
                echo '<div class="title highlight-left-right"><div><h1>' . $this->customWidgetTitle . '</h1></div></div>';
            }
        }

        if ($this->filterOn) {
            Yii::app()->controller->aData['searchUrl'] = Yii::app()->request->url;
            //$this->renderFilter($this->lazyLoadMarkers);
        }

        if (param('useYandexMap', 1)) {
            echo $this->render('application.modules.apartments.views.backend._ymap', '', true);
            echo '<div id="mapWarningBox" style="display:none;">' . tc('Please zoom in.') . '</div>';
            CustomYMap::init()->createMap($this->scrollWheel, $this->draggable, true);
        } elseif (param('useGoogleMap', 1)) {
            //Yii::app()->getClientScript()->registerScriptFile('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer_compiled.js', CClientScript::POS_BEGIN);
            //Yii::app()->getClientScript()->registerScriptFile('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer_packed.js', CClientScript::POS_BEGIN);
            Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/markerclusterer_packed.js', CClientScript::POS_BEGIN);

            CustomGMap::createMap(false, $this->scrollWheel, $this->draggable, true);
        } elseif (param('useOSMMap', 1)) {
            echo '<div id="osmap"></div>';
            echo '<div id="mapWarningBox" style="display:none;">' . tc('Please zoom in.') . '</div>';
            CustomOSMap::createMap(false, $this->scrollWheel, $this->draggable, true, $this->lazyLoadMarkers);
        }

        if ($this->lazyLoadMarkers) {
            // чтобы проставились значения в поиске
            SearchHelper::getCriteriaForMainSearch();

            if (param('useYandexMap', 1)) {
                CustomYMap::init()->setLazyLoadListeners();
                CustomYMap::init()->processScripts(false);
            } elseif (param('useGoogleMap', 1)) {
                CustomGMap::setLazyLoadListeners();
                CustomGMap::render();
            } elseif (param('useOSMMap', 1)) {
                CustomOSMap::setLazyLoadListeners();
                CustomOSMap::render();
            }
        } else {
            $addCondition = '';
            if (!empty($this->selectedIds)) {
                $idsArr = array_map("intval", $this->selectedIds);
                $addCondition = ' AND t.id IN ('. implode(',', $idsArr).') ';
                unset($idsArr);
            }
            
            $apartments = self::getViewAllMapApartments($addCondition);
            /*
              if (empty($apartments)) {
              echo '<h3>'.tt('Apartments list is empty.', 'apartments').'</h3>';
              return false;
              }
             */
            if (param('useYandexMap', 1)) {
                $lats = array();
                $lngs = array();
                foreach ($apartments as $apartment) {
                    $lats[] = $apartment['lat'];
                    $lngs[] = $apartment['lng'];
                    CustomYMap::init()->addMarker(
                        $apartment['lat'], $apartment['lng'], null, /* $this->render('application.modules.apartments.views.backend._marker', array('model' => $apartment), true), */ true, $apartment
                    );
                }

                if ($lats && $lngs) {
                    CustomYMap::init()->setBounds(min($lats), max($lats), min($lngs), max($lngs));
                    if ($this->withCluster) {
                        CustomYMap::init()->setClusterer();
                    } else {
                        CustomYMap::init()->withoutClusterer();
                    }
                } else {
                    $minLat = param('module_apartments_ymapsCenterX') - param('module_apartments_ymapsSpanX') / 2;
                    $maxLat = param('module_apartments_ymapsCenterX') + param('module_apartments_ymapsSpanX') / 2;

                    $minLng = param('module_apartments_ymapsCenterY') - param('module_apartments_ymapsSpanY') / 2;
                    $maxLng = param('module_apartments_ymapsCenterY') + param('module_apartments_ymapsSpanY') / 2;

                    CustomYMap::init()->setBounds($minLng, $maxLng, $minLat, $maxLat);
                }
                CustomYMap::init()->changeZoom(0, '+');
                CustomYMap::init()->processScripts(true);
            } elseif (param('useGoogleMap', 1)) {
                foreach ($apartments as $apartment) {
                    CustomGMap::addMarker($apartment, null /* $this->render('application.modules.apartments.views.backend._marker', array('model' => $apartment), true) */
                    );
                }
                if ($this->withCluster) {
                    CustomGMap::clusterMarkers();
                }
                CustomGMap::setCenter();
                CustomGMap::render();
            } elseif (param('useOSMMap', 1)) {
                foreach ($apartments as $apartment) {
                    CustomOSMap::init()->addMarker($apartment, null /* $this->render('application.modules.apartments.views.backend._marker', array('model' => $apartment), true) */
                    );
                }
                if ($this->withCluster) {
                    CustomOSMap::clusterMarkers();
                }
                CustomOSMap::setCenter();
                CustomOSMap::render();
            }
        }
    }

    public function renderFilter()
    {

        // start set filter
        $this->filterPriceType = Yii::app()->request->getParam('filterPriceType');
        $this->filterObjType = (int) Yii::app()->request->getParam('filterObjType');
        // end set filter
        // echo filter form
        echo '<div class="block-filter-viewallonmap">';
        echo '<form method="GET" action="" id="form-filter-viewallonmap">';
        echo CHtml::dropDownList('filterPriceType', isset($this->filterPriceType) ? CHtml::encode($this->filterPriceType) : '', HApartment::getTypesForSearch(true)
        );

        echo CHtml::dropDownList('filterObjType', isset($this->filterObjType) ? CHtml::encode($this->filterObjType) : 0, CMap::mergeArray(array(0 => Yii::t('common', 'Please select')), Apartment::getObjTypesArray()
            )
        );

        echo CHtml::button(tc('Filter'), array('onclick' => '$("#form-filter-viewallonmap").submit();',
            'id' => 'click-filter-viewallonmap',
            'class' => 'inline button-blue',
        ));
        echo '</form>';
        echo '</div>';
    }

    public static function getViewAllMapApartments($addCondition = '', $limit = null)
    {
        if ($limit === null) {
            $limit = mt_rand(3000, 3200);
        }

        $criteria = SearchHelper::getCriteriaForMainSearch();

        $lang = Yii::app()->language;
        $select = $ownerActiveCond = '';

        $joinTables = '
			LEFT JOIN {{apartment_obj_type}} objType ON (t.obj_type_id = objType.id)
		';

        $whereCondition = ' lat <> "" AND lat <> "0"';

        if (issetModule('seo')) {
            $select .= ' ,seo.url_' . $lang . ' as seoUrl ';
            $joinTables .= ' LEFT JOIN {{seo_friendly_url}} seo ON (seo.model_id = t.id) AND (seo.model_name = "Apartment")';
        }

        $types = HApartment::availableApTypesIds();
        if ($types) {
            $whereCondition .= ' AND t.type IN (' . implode(', ', $types) . ') ';
        }

        if ($criteria->condition) {
            $whereCondition .= ' AND ' . $criteria->condition;
        }
        if ($criteria->join) {
            $joinTables .= ' ' . $criteria->join;
        }

        $sqlApartments = '
			SELECT
				t.id, t.type, t.address_' . $lang . ', t.title_' . $lang . ', t.owner_id, t.lat, t.lng,
				objType.name_' . $lang . ' as objTypeName, objType.icon_file as objTypeIconFile
				' . $select . '
			FROM {{apartment}} t 
			' . $joinTables . '
			WHERE 
			' . $whereCondition . ' 
			' . $addCondition . ' 
			LIMIT ' . $limit . '
		';

        $apartments = Yii::app()->db->createCommand($sqlApartments)->queryAll(true, $criteria->params);

        if (is_array($apartments) && !empty($apartments)) {
            foreach ($apartments as $r) {
                $allIds[] = $r['id'];
            }

            if (isset($allIds) && is_array($allIds)) {
                $sqlImages = 'SELECT id, id_object, id_owner, file_name, file_name_modified, is_main, sorter FROM {{images}} WHERE id_object IN(' . implode(', ', $allIds) . ') AND is_main = 1';
                $resImages = Yii::app()->db->createCommand($sqlImages)->queryAll();

                $apartments = array_combine($allIds, $apartments);
                unset($allIds);

                if ($resImages && is_array($resImages)) {
                    foreach ($resImages as $rImage) {
                        if (isset($apartments[$rImage['id_object']])) {
                            $apartments[$rImage['id_object']]['images'][] = $rImage;
                        }
                    }

                    unset($resImages);
                }
            }
        }

        return $apartments;
    }
}
