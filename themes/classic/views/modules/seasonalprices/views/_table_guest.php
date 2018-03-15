<?php
$dataProvider = new CArrayDataProvider(array());
if (Yii::app()->user->hasState('guest_ad_seasonal_prices')) {
	$prices = unserialize(Yii::app()->user->getState('guest_ad_seasonal_prices'));

	foreach($prices as $key=>&$price) {
		$price['id'] = $key;
	}
	$prices = array_values($prices);
	$dataProvider->setData($prices);
	$dataProvider->keyField = 'id';
	$dataProvider->setTotalItemCount(count($prices));

}
?>

<?php
$CGridViewClass = (param('useBootstrap', false)) ? 'CustomGridView' : 'NoBootstrapGridView';
$CButtonClass = (param('useBootstrap', false)) ? 'bootstrap.widgets.BsButtonColumn' : 'CButtonColumn';
$javaScriptMethod = (param('useBootstrap', false)) ? 'ajaxMoveRequest' : 'ajaxRequest';

$columns = array(
	array(
		'header' => tt('Name', 'seasonalprices'),
		'value' => 'CHtml::encode($data["name_".Yii::app()->language])',
		'sortable' => false,
		'filter' => false,
		'htmlOptions' => array('style'=>'width:120px;'),
	),
	array(
		'header' => tt('Price', 'seasonalprices'),
		'value' => 'SeasonalPrices::makePriceWithType($data["price"],$data["price_type"])',
		'sortable' => false,
		'filter' => false,
	),
	array(
		'header' => tt('Min_rental_period', 'seasonalprices'),
		'value' => '(!$data["min_rental_period"]) ? "-" : $data["min_rental_period"]." ".Seasonalprices::rentalPeriodNames($data["price_type"])',
		'sortable' => false,
		'filter' => false,
	),
	array(
		'header' => tt('From', 'seasonalprices'),
		'value' => 'SeasonalPrices::makeDate($data["date_start"], $data["month_start"])',
		'sortable' => false,
		'filter' => false,
		'htmlOptions' => array('style'=>'width:90px;'),
	),
	array(
		'header' => tt('To', 'seasonalprices'),
		'value' => 'SeasonalPrices::makeDate($data["date_end"], $data["month_end"])',
		'sortable' => false,
		'filter' => false,
		'htmlOptions' => array('style'=>'width:90px;'),
	)
);

if (isset($showDeleteButton) && $showDeleteButton) {
	$columns[] = array(
		'template' => '{delete}',
		'class' => $CButtonClass,
		'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
		'htmlOptions' => array('style'=>'width: 60px; min-width: 60px; text-align: center;'),
		'headerHtmlOptions' => array('style'=>'width: 60px; min-width: 60px;'),
		'buttons' => array(
			'delete' => array(
				'url'=>'Yii::app()->createUrl("/seasonalprices/main/deletepriceguest", array("id"=>$data["id"], "apId" => "'. Yii::app()->user->getState('guest_ad_sessionid').'"))',
				'options' => array('rel' => ''),
			),
		),
	);
}

$this->widget($CGridViewClass, array(
	'id'=>'apartment-seasonal-prices-grid',
	'dataProvider' => $dataProvider,
	'emptyText' => tt('No_prices', 'seasonalprices'),
	'columns' => $columns,
	'template' => (isset($showDeleteButton) && $showDeleteButton) ? "{summary}\n{pager}\n{items}\n{pager}" : "{pager}\n{items}\n{pager}",
));
?>