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

class MainController extends Controller
{

    public function actionAjaxCalc()
    {
        $term = $_POST['term'];
        $rate = $_POST['rate'];
        $amount = $_POST['amount'];
        $startmonth = $_POST['startmonth'];
        $startyear = $_POST['startyear'];

        $credit = self::credit($term, $rate, $amount, $startmonth, $startyear);

        echo json_encode($credit);
    }

    public static function renderTable($array)
    {
        $return = '';

        $return .= '<div class="no-more-tables loan-calculator-results">';
        $return .= '<table>
			<thead>
				<tr>
					<th>№</th>
					<th>' . tt('Payment date', 'loanCalculator') . '</th>
					<th>' . tt('The balance of debt', 'loanCalculator') . '</th>
					<th>' . tt('Interest payments', 'loanCalculator') . '</th>
					<th>' . tt('Loan payment', 'loanCalculator') . '</th>
					<th>' . tt('Annuity payment', 'loanCalculator') . '</th>
				</tr>
			</thead>
			<tbody>';

        foreach ($array as $key => $value) {
            $return .= '
					<tr>
						<td data-title="№">' . $key . '</td>
						<td data-title="' . tt('Payment date', 'loanCalculator') . '">' . $value['month'] . '</td>
						<td data-title="' . tt('The balance of debt', 'loanCalculator') . '">' . $value['dept'] . '</td>
						<td data-title="' . tt('Interest payments', 'loanCalculator') . '">' . $value['percent_pay'] . '</td>
						<td data-title="' . tt('Loan payment', 'loanCalculator') . '">' . $value['credit_pay'] . '</td>
						<td data-title="' . tt('Annuity payment', 'loanCalculator') . '">' . $value['payment'] . '</td>
					</tr>';
        }

        $return .= '</tbody></table>';
        $return .= '</div>';

        return $return;
    }

    public static function credit($term, $rate, $amount, $month, $year, $round = 2)
    {
        // $term - срок кредита (в месяцах), $rate процентная ставка, $amount - сумма кредита (в рублях)
        // $month - месяц начала выплат, $year - год начала выплат, $round - округление сумм
        $month_array = HDate::getListMonth();

        $result = array();

        $term = (int) $term;
        $rate = (float) str_replace(",", ".", $rate);
        $amount = (float) str_replace(",", ".", $amount);
        $round = (int) $round;

        $month_rate = ($rate / 100 / 12);   //  месячная процентная ставка по кредиту (= годовая ставка / 12)
        $k = ($month_rate * pow((1 + $month_rate), $term)) / ( pow((1 + $month_rate), $term) - 1 ); // коэффициент аннуитета
        $payment = round($k * $amount, $round);   // Размер ежемесячных выплат
        $overpay = ($payment * $term) - $amount;
        $debt = $amount;

        $schedule = array();
        for ($i = 1; $i <= $term; $i++) {
            $schedule[$i] = array();

            $percent_pay = round($debt * $month_rate, $round);
            $credit_pay = round($payment - $percent_pay, $round);

            $schedule[$i]['month'] = $month_array[$month - 1] . ' ' . $year;
            $schedule[$i]['dept'] = number_format($debt, $round, ',', ' ');
            $schedule[$i]['percent_pay'] = number_format($percent_pay, $round, ',', ' ');
            $schedule[$i]['credit_pay'] = number_format($credit_pay, $round, ',', ' ');
            $schedule[$i]['payment'] = number_format($payment, $round, ',', ' ');

            $debt = $debt - $credit_pay;

            if ($month++ >= 12) {
                $month = 1;
                $year++;
            }
        }

        $result['overpay'] = number_format($overpay, $round, ',', ' ');
        ;
        $result['payment'] = number_format($payment, $round, ',', ' ');
        ;
        $result['schedule'] = self::renderTable($schedule);

        return $result;
    }
}
