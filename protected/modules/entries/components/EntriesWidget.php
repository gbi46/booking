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

class EntriesWidget extends CWidget
{

    public $usePagination = 1;
    public $criteria;
    public $showWidgetTitle = true;
    public $customWidgetTitle;
    public $widgetTitles;

    public function getViewPath($checkTheme = true)
    {
        if ($checkTheme && ($theme = Yii::app()->getTheme()) !== null) {
            if (is_dir($theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'entries' . DIRECTORY_SEPARATOR . 'views'))
                return $theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'entries' . DIRECTORY_SEPARATOR . 'views';
        }
        return Yii::getPathOfAlias('application.modules.entries.views');
    }

    public function run()
    {
        $entries = new Entries;
        $result = $entries->getAllWithPagination($this->criteria);

        $subTitleKey = InfoPages::getWidgetSubTitleKey('entries');
        $this->customWidgetTitle = InfoPages::getWidgetSubTitle($subTitleKey, $this->widgetTitles);

        if (empty($this->customWidgetTitle)) {
            $this->showWidgetTitle = false;
        }

        $this->render('widgetEntries_list', array(
            'entries' => $result['items'],
            'pages' => $result['pages'],
            'showWidgetTitle' => $this->showWidgetTitle,
            'customWidgetTitle' => $this->customWidgetTitle,
        ));
    }
}
