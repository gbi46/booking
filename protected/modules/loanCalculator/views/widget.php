<style>
    #credit label { width: 200px; float: left; }
    .credit_text { clear: both; margin: 10px 0; }
    .submit { clear: both;   margin: 10px 0px 10px 200px; }
    button {   border: none;    cursor: pointer; }

    #schedule   table a:link { color: #666; font-weight: bold; text-decoration:none; }
    #schedule table a:visited { 	color: #999999; 	font-weight:bold; 	text-decoration:none; }
    #schedule table a:active, #schedule table a:hover { 	color: #bd5a35; 	text-decoration:underline; }
    #schedule table {   	color:#666;  	font-size:13px;	text-shadow: 1px 1px 0px #fff; 	background:#eaebec; 	border:#ccc 1px solid;	-moz-border-radius:0px; 	-webkit-border-radius:0px; 	border-radius:0px; 	-moz-box-shadow: 0 1px 2px #d1d1d1; 	-webkit-box-shadow: 0 1px 2px #d1d1d1; 	box-shadow: 0 1px 2px #d1d1d1;}
    #schedule table th { 	padding:21px 25px 22px 25px;	border-top:1px solid #fafafa;	border-bottom:1px solid #e0e0e0;	background: #ededed;	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);}
    #schedule table tr:first-child th:first-child { 	-moz-border-radius-topleft:0px; 	-webkit-border-top-left-radius:0px; 	border-top-left-radius:0px; }
    #schedule table tr:first-child th:last-child { 	-moz-border-radius-topright:0px; 	-webkit-border-top-right-radius:0px; 	border-top-right-radius:0px; }
    #schedule table tr { 	text-align: center; 	padding-left:20px; }
    #schedule table td:first-child {  	border-left: 0; }
    #schedule table td { 	padding:18px; 	border-top: 1px solid #ffffff; 	border-bottom:1px solid #e0e0e0; 	border-left: 1px solid #e0e0e0; 	background: #fafafa; 	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa)); 	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa); }
    #schedule table tr.even td { 	background: #f6f6f6; 	background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6)); 	background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6); }
    #schedule table tr:last-child td { 	border-bottom:0; }
    #schedule table tr:last-child td:first-child { -moz-border-radius-bottomleft:3px;  	-webkit-border-bottom-left-radius:0px; 	border-bottom-left-radius:0px; }
    #schedule table tr:last-child td:last-child { 	-moz-border-radius-bottomright:3px; 	-webkit-border-bottom-right-radius:0px; 	border-bottom-right-radius:0px; }
    #schedule table tr:hover td { 	background: #f2f2f2; 	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0)); 	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0); }

    .submit button {
        background: #7ccb40 none repeat scroll 0 0;
        border-bottom: 3px solid #4c9b00;
        border-radius: 0px;
        box-sizing: border-box;
        color: #fff;
        display: block;
        font-size: 20px;
        padding: 5px 5px;
        text-align: center;
        text-decoration: none;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
    }

    .submit button:hover {
        background: #8cdb50;
    }
</style>

<?php
$month_array = HDate::getListMonth();

$currency = param('siteCurrency', '$');
if (issetModule('currency')) {
    $currency = Currency::getCurrentCurrencyName();
}

?>

<hr>

<h2><?= tt('Loan calculator', 'loanCalculator') ?></h2>

<form id="credit">
    <div class="form">
        <div class="credit_text form-group">
            <label><?php echo tt('Amount of credit', 'loanCalculator'); ?> (<?php echo $currency ?>)</label> 
            <input type="text" name="amount" id="amount" value="<?php echo $this->amount ?>" />
        </div>
        <div class="credit_text form-group">
            <label><?php echo tt('Amount of credit (month)', 'loanCalculator'); ?></label>
            <input type="text" name="term" id="term" value="<?php echo $this->term ?>" />
        </div>
        <div class="credit_text form-group">
            <label><?php echo tt('Interest rate', 'loanCalculator'); ?> (%)</label>
            <input type="text" name="rate" id="rate" value="<?php echo $this->rate ?>" />
        </div>
        <div class="select form-group">
            <label><?php echo tt('Getting payments', 'loanCalculator'); ?></label>
            <select name="startmonth" id="startmonth" class="width100 form-control noblock">
                <?php
                $current_month = date("n");
                foreach ($month_array as $key => $value) {

                    ?>
                    <option value="<?php echo $key + 1; ?>" <?php if ($current_month == $key + 1) { ?>selected="selected"<?php } ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
            <select name="startyear" id="startyear" class="width100 form-control noblock">
                <?php
                $current_year = date("Y");
                for ($i = $current_year - 10; $i <= $current_year + 10; $i++) {

                    ?>
                    <option value="<?php echo $i; ?>" <?php if ($current_year == $i) { ?>selected="selected"<?php } ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="submit row buttons">
            <input class="button-blue submit-button" type="submit" value="<?php echo tt('Calculate', 'loanCalculator'); ?>">
        </div>
    </div>
</form>

<p><strong><?php echo tt('Monthly payment', 'loanCalculator'); ?>:</strong> <span id="payment"></span> <?php echo $currency ?></p>
<p><strong><?php echo tt('Overpayment', 'loanCalculator'); ?>:</strong> <span id="overpay"></span> <?php echo $currency ?></p>
<div id="schedule"></div>

<script>
    $(document).ready(function () {
        $("#credit").submit(function (e) {

            e.preventDefault();

            var amount = $("#amount").val();
            var term = $("#term").val();
            var rate = $("#rate").val();
            var startmonth = $("#startmonth").val();
            var startyear = $("#startyear").val();

            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('/loanCalculator/main/ajaxCalc'); ?>",
                data: {amount: amount, term: term, rate: rate, startmonth: startmonth, startyear: startyear}
            })
                    .done(function (json) {
                        var obj = JSON.parse(json);
                        $("#overpay").text(obj.overpay);
                        $("#payment").text(obj.payment);
                        $("#schedule").html(obj.schedule);
                    });
        });
    });
</script>