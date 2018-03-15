<?php
$this->breadcrumbs = array(tt('View statistics', 'stats'));
$this->menu = array();

$this->adminTitle = Yii::t('module_stats', 'Statistics of last {n} days', 7);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/fastclick/fastclick.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/flot/jquery.flot.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/flot/jquery.flot.resize.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/flot/jquery.flot.categories.min.js', CClientScript::POS_END);

$labelBookingRequests = tt('Booking requests', 'stats');
$labelListings = tt('Added listings', 'stats');
$labelUsers = tt('Registered users', 'stats');
$labelComments = tt('Added comments', 'stats');
$labelReviews = tt('Added reviews', 'stats');
$labelBookingPayments = tt('Done payments', 'stats');

?>

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $labelBookingRequests; ?></h3>
                </div>
                <div class="box-body">
                    <div id="bookingRequestsChart" style="height: 230px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $labelListings; ?></h3>
                </div>
                <div class="box-body">
                    <div id="listingsChart" style="height: 230px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $labelUsers; ?></h3>
                </div>
                <div class="box-body">
                    <div id="usersChart" style="height: 230px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $labelComments; ?></h3>
                </div>
                <div class="box-body">
                    <div id="commentsChart" style="height: 230px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $labelReviews; ?></h3>
                </div>
                <div class="box-body">
                    <div id="reviewsChart" style="height: 230px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $labelBookingPayments; ?></h3>
                </div>
                <div class="box-body">
                    <div id="paymentsChart" style="height: 230px;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$dataBookingRequests = CJSON::encode($dataBookingRequests);
$dataListings = CJSON::encode($dataListings);
$dataPayments = CJSON::encode($dataPayments);
$dataUsers = CJSON::encode($dataUsers);
$dataComments = CJSON::encode($dataComments);
$dataReviews = CJSON::encode($dataReviews);

$contentJs = <<<JS
	var previousPoint = null;
				
	$(function () {		
		drawChart("#bookingRequestsChart", $dataBookingRequests, "$maxValBookingRequests", "#f15a22", "$labelBookingRequests");
		drawChart("#listingsChart", $dataListings, "$maxValListings", "#4285F4", "$labelListings");
		drawChart("#usersChart", $dataUsers, "$maxValUsers", "#F4B400", "$labelUsers");
		drawChart("#commentsChart", $dataComments, "$maxValComments", "#109618", "$labelComments");
		drawChart("#reviewsChart", $dataReviews, "$maxValReviews", "#990099", "$labelReviews");
		drawChart("#paymentsChart", $dataPayments, "$maxValPayments", "#DB4437", "$labelBookingPayments");
		
		$("#bookingRequestsChart, #listingsChart, #paymentsChart, #usersChart, #commentsChart, #reviewsChart").on("plothover", function (event, pos, item) {
			if (item) {		
				if ((previousPoint != item.dataIndex)) {
					$("#tooltip").remove();
					previousPoint = item.dataIndex;
					
					var x = item.datapoint[0];
					var y = item.datapoint[1];
					var color = item.series.color;            

					/*showTooltip(item.pageX+80, item.pageY+10, color, item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong>");*/
					showTooltip(item.pageX+80, item.pageY+10, color, "<strong>" + item.series.xaxis.ticks[x].label + "</strong><br>" + "<strong>" + item.series.label + "</strong>: " + y);
				}
			} 
			else {
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
	});
		
	function drawChart(chart_elem, data, maxValue, color, label) {
		var maxValue = maxValue || 1;
		var color = color || "#3c8dbc";
		var label = label || "";
		
		var chart_data = {data: data, color: color, label: label};
		$.plot(chart_elem, [chart_data], {
			grid: {
				borderWidth: 1,
				borderColor: "#f3f3f3",
				tickColor: "#f3f3f3",
				hoverable: true,
				clickable: true
			},
			series: {
				bars: {
					show: true,
					barWidth: 0.5,
					align: "center"
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0,
			},
			yaxis: {
				min: 0,
				/*max: maxValue*/
			},
		});
	}
		
	function showTooltip(x, y, color, contents) {
		$('<div id="tooltip">' + contents + '</div>').css({
			position: 'absolute',
			display: 'none',
			top: y - 40,
			left: x - 120,
			border: '2px solid ' + color,
			padding: '3px',
				'font-size': '9px',
				'border-radius': '5px',
				'background-color': '#fff',
				'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
			opacity: 0.9
		}).appendTo("body").fadeIn(200);
	}
JS;

Yii::app()->clientScript->registerScript('search-params-index-search', $contentJs, CClientScript::POS_END, array(), true);
