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

class HDate
{

    public static function formatDateTime($dateTime, $format = 'default')
    {
        $dateFormat = param('dateFormat', 'd.m.Y H:i:s');

        if ($format == 'default') {
            return date($dateFormat, strtotime($dateTime));
        } else {
            return Yii::app()->dateFormatter->format(Yii::app()->locale->getDateFormat('long'), CDateTimeParser::parse($dateTime, 'yyyy-MM-dd hh:mm:ss'));
        }
    }

    public static function formatForDatePicker($time)
    {
        if (Yii::app()->language != 'ru') {
            return date('m/d/Y', $time);
        } else {
            return date('d.m.Y', $time);
            //return Yii::app()->dateFormatter->formatDateTime($time, 'medium', null);
        }
    }

    public static function getListMonth()
    {
        return array(
            tt('January', 'loanCalculator'),
            tt('February', 'loanCalculator'),
            tt('March', 'loanCalculator'),
            tt('April', 'loanCalculator'),
            tt('may', 'loanCalculator'),
            tt('June', 'loanCalculator'),
            tt('July', 'loanCalculator'),
            tt('August', 'loanCalculator'),
            tt('September', 'loanCalculator'),
            tt('October', 'loanCalculator'),
            tt('November', 'loanCalculator'),
            tt('December', 'loanCalculator')
        );
    }
}
