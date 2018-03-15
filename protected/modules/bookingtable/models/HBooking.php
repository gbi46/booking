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

class HBooking
{

    private static $_rows = array();
    private static $_price = 0;
    private static $_minPriceRow;
    private static $_maxPriceRow;
    public static $calculateHtml = '';
    public static $_priceTotal = 0;
    // the number of booked days
    public static $bookedDays;
    public static $calculateDay;
    private static $iRow = 0;

    const TIME_BEFORE_NOON = '12:00:00'; # до полудня
    const TIME_AFTER_NOON = '13:00:00'; # после полудня

    public static function getChangeBookingStatus($data)
    {
        $changeUrl = Yii::app()->createUrl('/bookingtable/backend/main/changeStatus', array(
            'id' => $data->id,
        ));

        $link = CHtml::link(Bookingtable::getStatus($data->active), $changeUrl, array(
                //'class' => 'tempModal',
                'data-original-title' => tt('Change status')
        ));

        $status = CHtml::tag('div', array('id' => 'cs_el_' . $data->id), $link);
        if ($data->details || $data->comment_admin) {
            $status .= '<br>' . CHtml::link(tt('Details', 'booking'), Yii::app()->createUrl('/bookingtable/backend/main/details', array('id' => $data->id)), array(
                    'class' => 'tempModal',
                    'data-original-title' => tt('Booking details', 'booking'),
            ));
        }
        return $status;
    }

    public static function calculateAdvancePayment($booking, $border = 0)
    {
        self::$_rows = array();
        self::$_price = 0;
        self::$_minPriceRow = null;
        self::$_maxPriceRow = null;

        Yii::app()->getModule('seasonalprices');

        $amount = 0;

        if ($booking instanceof Bookingtable) {
            $apartment = $booking->apartment;
        } elseif ($booking instanceof Booking) {
            $apartment = Apartment::model()->findByPk($booking->apartment_id);
        } else {
            return 0;
        }
        if (!$apartment) {
            return 0;
        }

        if (issetModule('paidservices')) {
            $paidService = PaidServices::model()->findByPk(PaidServices::ID_BOOKING_PAY);
            if ($paidService && $paidService->isActive() && $apartment->id) {
                $percent = $paidService->getFromJson('percent');

                if (param('booking_half_day')) {
                    // timestamp field					
                    $bookingStartDatetime = new DateTime(date('Y-m-d', strtotime($booking->date_start)) . ' ' . self::getTimeInOutHours($booking->time_in));
                    $bookingEndDatetime = new DateTime(date('Y-m-d', strtotime($booking->date_end)) . ' ' . self::getTimeInOutHours($booking->time_out));

                    $interval = date_diff($bookingStartDatetime, $bookingEndDatetime);

                    # минимум 1 день. Даже если вьезд до обеда, выезд после обеда в этот же день.
                    $bookDays = 1;

                    if ($interval->days > 0) {
                        $bookDays = $interval->days;

                        if ($interval->h > 0) {
                            $bookDays++;
                        }
                    }

                    self::$bookedDays = $bookDays;
                } else {
                    $bookingStartDatetime = new DateTime($booking->date_start);
                    $bookingEndDatetime = new DateTime($booking->date_end);

                    $interval = date_diff($bookingStartDatetime, $bookingEndDatetime);

                    self::$bookedDays = $interval->days + 1;
                }

                if (issetModule('seasonalprices')) {
                    $seasonsRows = Yii::app()->db
                        ->createCommand("SELECT id, name_" . Yii::app()->language . " AS name, month_start, date_start, month_end, date_end, price FROM {{seasonal_prices}}
						WHERE price_type = :t AND (min_rental_period <= :days OR min_rental_period = 0) AND apartment_id=:id ORDER BY price ASC")
                        ->queryAll(true, array(
                        ':t' => Apartment::PRICE_PER_DAY,
                        ':days' => self::$bookedDays,
                        ':id' => $apartment->id,
                    ));
                    if (!$seasonsRows) {
                        return 0;
                    }

                    self::$_price = 0;

                    $yearBookingStart = date('Y', strtotime($booking->date_start));
                    $yearBookingEnd = date('Y', strtotime($booking->date_end));

                    $yearStart = $yearBookingStart;
                    $yearEnd = $yearBookingEnd;

                    self::$calculateHtml = '<div class="grid-view">';
                    self::$calculateHtml .= '<table class="items booking-calculate table table-striped" ' . ($border ? 'border="1"' : '') . '>';
                    self::$calculateHtml .= '<tr><th colspan="2">' . tt('Name', 'seasonalprices') . '</th><th>' . tt('Amount of days * Price per day', 'bookingtable') . '</th><th>' . tt('Price', 'seasonalprices') . '</th></tr>';
                    self::$calculateDay = 0;
                    self::$iRow = 0;
                    $minPrice = 0;
                    $maxPrice = 0;
                    foreach ($seasonsRows as $season) {
                        // for correct calculate days
                        // если месяц начала сезона больше чем месяц окончания
                        if ($season['month_start'] > $season['month_end'] && $yearStart == $yearEnd) {
                            self::calcRow($season, $yearStart, $yearEnd + 1, $booking, $bookingStartDatetime, $bookingEndDatetime, $minPrice, $maxPrice);
                            self::calcRow($season, $yearStart - 1, $yearEnd, $booking, $bookingStartDatetime, $bookingEndDatetime, $minPrice, $maxPrice);
                            continue;
                        } elseif ($season['month_start'] > $season['month_end'] && $yearStart < $yearEnd) {
                            self::calcRow($season, $yearStart, $yearStart, $booking, $bookingStartDatetime, $bookingEndDatetime, $minPrice, $maxPrice);
                            self::calcRow($season, $yearEnd, $yearEnd, $booking, $bookingStartDatetime, $bookingEndDatetime, $minPrice, $maxPrice);
                            continue;
                        } elseif ($season['month_start'] <= $season['month_end'] && $yearStart != $yearEnd) {
                            self::calcRow($season, $yearStart, $yearStart, $booking, $bookingStartDatetime, $bookingEndDatetime, $minPrice, $maxPrice);
                            self::calcRow($season, $yearEnd, $yearEnd, $booking, $bookingStartDatetime, $bookingEndDatetime, $minPrice, $maxPrice);
                            continue;
                        }

                        self::calcRow($season, $yearStart, $yearEnd, $booking, $bookingStartDatetime, $bookingEndDatetime, $minPrice, $maxPrice);
                    }

                    // Если посчитали не за все дни или даже больше чем надо
                    if (self::$calculateDay != self::$bookedDays) {
                        $emptyFlag = $paidService->getFromJson('empty_flag');

                        if ($emptyFlag && self::$calculateDay < self::$bookedDays) {
                            $emptyDays = self::$bookedDays - self::$calculateDay;

                            if ($emptyFlag == PaidBooking::EMPTY_FLAG_PAY_MAX && self::$_maxPriceRow) {
                                self::addRow(self::$_maxPriceRow, $emptyDays);
                            } elseif ($emptyFlag == PaidBooking::EMPTY_FLAG_PAY_MIN && self::$_minPriceRow) {
                                self::addRow(self::$_minPriceRow, $emptyDays);
                            } else {
                                self::$calculateHtml = '';
                                return 0;
                            }
                        } else {
                            self::$calculateHtml = '';
                            return 0;
                        }
                    }

                    self::generateRowHtml();

                    self::addRowTable(tt('In total', 'booking'), '', '', self::$_price);

                    self::$calculateHtml .= '</table>';
                    self::$calculateHtml .= '</div>';
                } elseif ($apartment->price_type == Apartment::PRICE_PER_DAY) {
                    self::$_price = self::$bookedDays * $apartment->price;
                } else {
                    self::$_price = $apartment->price;
                }

                if (!self::$_price) {
                    self::$calculateHtml = '';
                    return 0;
                }

                if ($booking->num_guest && $paidService->getFromJson('consider_num_guest')) {
                    self::$_priceTotal = self::$_price * $booking->num_guest;
                    self::$calculateHtml .= tt('Taking account of number of guests the fee is') . ': ' . self::$_price . ' * ' . $booking->num_guest . ' = ' . self::$_priceTotal . Currency::getDefaultCurrencyName() . '<br>';
                    self::$_price = self::$_priceTotal;

                    $discount = $paidService->getFromJson('discount_guest');
                    if ($booking->num_guest > 1 && $discount) {
                        $discountCalc = self::$_price * ( $discount / 100 );
                        self::$calculateHtml .= tt('Discount') . ': ' . $discount . '% = ' . $discountCalc . Currency::getDefaultCurrencyName() . '<br>';
                        self::$_price = self::$_price - $discountCalc;
                    }
                }

                self::$calculateHtml .= '<div class="total_booking_cost">' . Yii::t('common', 'The total cost of the booking: {cost}{currency}', array(
                        '{cost}' => '<span class="booking_calc_cost">' . self::$_price . '</span>',
                        '{currency}' => Currency::getDefaultCurrencyName(),
                    )) . '</div>';

                $amount = self::$_price * ( $percent / 100 );

                self::$calculateHtml .= '<div class="mst_booking_pay">' . Yii::t('common', 'You must pay {percent}% : {cost}{currency}', array(
                        '{percent}' => $percent,
                        '{cost}' => '<span class="booking_calc_cost">' . $amount . '</span>',
                        '{currency}' => Currency::getDefaultCurrencyName(),
                    )) . '</div>';
            }
        }

        return round($amount);
    }

    private static function calcRow($row, $yearStart, $yearEnd, $booking, $bookingStart, $bookingEnd, &$minPrice, &$maxPrice)
    {
        if (param('booking_half_day')) {
            $strStart = $row['date_start'] . '-' . $row['month_start'] . '-' . $yearStart . ' ' . self::getTimeInOutHours($booking->time_in);
            $strEnd = $row['date_end'] . '-' . $row['month_end'] . '-' . $yearEnd . ' ' . self::getTimeInOutHours($booking->time_out);
            $seasonStart = DateTime::createFromFormat('d-m-Y H:i:s', $strStart);
            $seasonEnd = DateTime::createFromFormat('d-m-Y H:i:s', $strEnd);

            if ($seasonStart instanceof DateTime && $seasonEnd instanceof DateTime) {
                $daysOverlap = self::datesOverlapHalfDayBooking($bookingStart, $bookingEnd, $seasonStart, $seasonEnd);
            } else {
//				deb('calcRow error');
//				deb($row);
//				deb('strStart: '.$strStart);
//				deb('strEnd: '.$strEnd);
                return;
            }
        } else {
            $seasonStart = DateTime::createFromFormat('d-m-Y', $row['date_start'] . '-' . $row['month_start'] . '-' . $yearStart);
            $seasonEnd = DateTime::createFromFormat('d-m-Y', $row['date_end'] . '-' . $row['month_end'] . '-' . $yearEnd);

            $daysOverlap = self::datesOverlap($bookingStart, $bookingEnd, $seasonStart, $seasonEnd);
        }

//		$str = $row['id'].' сезон '. $seasonStart->format('Y-m-d H:i:s').' - '.$seasonEnd->format('Y-m-d H:i:s');
//		$str .= ' бронь '. $bookingStart->format('Y-m-d H:i:s').' - '.$bookingEnd->format('Y-m-d H:i:s');
//		$str .= ' посчитало дней = ' . $daysOverlap;
//		deb($str);

        if ($row['price'] < $minPrice || $minPrice == 0) {
            $minPrice = $row['price'];
            self::$_minPriceRow = $row;
        }
        if ($row['price'] > $maxPrice || $maxPrice == 0) {
            $maxPrice = $row['price'];
            self::$_maxPriceRow = $row;
        }

        if ($daysOverlap) {
            self::$calculateDay += $daysOverlap;
            self::addRow($row, $daysOverlap);
        }
    }

    private static function addRow($row, $daysOverlap)
    {
        if (empty(self::$_rows[$row['id']])) {
            self::$_rows[$row['id']] = $row;
            self::$_rows[$row['id']]['daysOverlap'] = $daysOverlap;
        } else {
            self::$_rows[$row['id']]['daysOverlap'] = self::$_rows[$row['id']]['daysOverlap'] + $daysOverlap;
        }
    }

    private static function generateRowHtml()
    {
        foreach (self::$_rows as $id => $row) {
            //$seasonString = self::formatDate($seasonStart->getTimestamp()) . ' - ' . self::formatDate($seasonEnd->getTimestamp());
            $seasonString = Seasonalprices::makeDate($row["date_start"], $row["month_start"]) . ' - ' . Seasonalprices::makeDate($row["date_end"], $row["month_end"]);
            $dayPrice = $row['daysOverlap'] . ' * ' . $row['price'] . Currency::getDefaultCurrencyName();
            $dayPriceCalc = $row['daysOverlap'] * $row['price'];
            self::$_price += $dayPriceCalc;

            self::addRowTable($row['name'], $seasonString, $dayPrice, $dayPriceCalc);
        }
    }

    private static function addRowTable($name, $seasonString, $dayPrice, $price)
    {
        $tableClass = self::$iRow % 2 ? 'odd' : 'even';
        self::$calculateHtml .= '<tr class="' . $tableClass . '"><td>' . $name . '</td><td>' . $seasonString . '</td><td>' . $dayPrice . '</td><td>' . $price . Currency::getDefaultCurrencyName() . '</td></tr>';
        self::$iRow++;
    }

    public static function formatDate($dateTime)
    {
        return Yii::app()->dateFormatter->format(Yii::app()->locale->getDateFormat('long'), $dateTime);
    }

    /** http://stackoverflow.com/questions/14202687/how-can-i-find-overlapping-dateperiods-date-ranges-in-php
     * @param $start_one
     * @param $end_one
     * @param $start_two
     * @param $end_two
     * @return int
     */
    public static function datesOverlap(DateTime $start_one, DateTime $end_one, DateTime $start_two, DateTime $end_two, $plus = 1)
    {
        if ($start_one <= $end_two && $end_one >= $start_two) { //If the dates overlap
            $start_one = $start_one->format('Y-m-d');
            $start_one = new DateTime($start_one);

            $end_one = $end_one->format('Y-m-d');
            $end_one = new DateTime($end_one);

            $start_two = $start_two->format('Y-m-d');
            $start_two = new DateTime($start_two);

            $end_two = $end_two->format('Y-m-d');
            $end_two = new DateTime($end_two);

            return min($end_one, $end_two)->diff(max($start_two, $start_one))->days + $plus; //return how many days overlap
        }

        return 0;
    }

    public static function datesOverlapHalfDayBooking(DateTime $start_one, DateTime $end_one, DateTime $start_two, DateTime $end_two)
    {
        if ($start_one <= $end_two && $end_one >= $start_two) { //If the dates overlap
            $start_one = $start_one->format('Y-m-d H:i:s');
            $start_one = new DateTime($start_one);

            $end_one = $end_one->format('Y-m-d H:i:s');
            $end_one = new DateTime($end_one);

            $start_two = $start_two->format('Y-m-d H:i:s');
            $start_two = new DateTime($start_two);

            $end_two = $end_two->format('Y-m-d H:i:s');
            $end_two = new DateTime($end_two);

            $minDate = min($end_one, $end_two);
            $maxDate = max($start_two, $start_one);

            $interval = $minDate->diff($maxDate);

            $bookDays = 1;

            if ($interval->days > 0) {
                $bookDays = $interval->days;

                if ($interval->h > 0) {
                    $bookDays++;
                }
            }

            return $bookDays;
        }

        return 0;
    }

    public static function renderDetails($model, $showTitle = true)
    {
        if ($showTitle) {
            echo '<h1>' . tt('Booking details', 'booking') . '</h1>';
        }


        $echos = array();
        if ($model->comment_admin) {
            $echos[] = $model->comment_admin . '<hr />';
        }
        if ($model->details) {
            $echos[] = $model->details . '<hr />';
        }
        if ($model->payment && $model->payment->status == Payments::STATUS_PAYMENTCOMPLETE) {
            $echos[] = $model->payment->amount . Currency::getNameByCharCode($model->payment->currency_charcode) . ' ' . $model->payment->returnStatusHtml();
        }

        echo '<p>';
        if (!empty($echos)) {
            echo implode("<br />", $echos);
        } else {
            echo tt('Nothing', 'menumanager');
        }
        echo '</p>';

        Yii::app()->end();
    }

    public static function getTimesIn()
    {
        if (param('booking_half_day')) {
            return Booking::getTimeList();
        }

        $sql = 'SELECT id, title_' . Yii::app()->language . ' as title FROM {{apartment_times_in}}';

        $results = Yii::app()->db->createCommand($sql)->queryAll();
        $return = array();
        if ($results) {
            foreach ($results as $result) {
                $return[$result['id']] = $result['title'];
            }
        }
        return $return;
    }

    public static function getTimesOut()
    {
        if (param('booking_half_day')) {
            return Booking::getTimeList();
        }
        $sql = 'SELECT id, title_' . Yii::app()->language . ' as title FROM {{apartment_times_out}}';

        $results = Yii::app()->db->createCommand($sql)->queryAll();
        $return = array();
        if ($results) {
            foreach ($results as $result) {
                $return[$result['id']] = $result['title'];
            }
        }
        return $return;
    }

    public static function getTimeInOutHours($time)
    {
        if ($time == Booking::TIME_BEFORE_NOON) {
            return self::TIME_BEFORE_NOON;
        }
        return self::TIME_AFTER_NOON;
    }
}
