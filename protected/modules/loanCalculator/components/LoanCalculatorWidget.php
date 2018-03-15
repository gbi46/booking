<?php

class LoanCalculatorWidget extends CWidget
{

    public $amount = 300000; // сумма кредита
    public $term = 36; // кол-во месяцев
    public $rate = 7; // процентная ставка

    public function getViewPath($checkTheme = true)
    {
        return Yii::getPathOfAlias('application.modules.loanCalculator.views');
    }

    public function run()
    {
        $this->render('widget');
    }
}
