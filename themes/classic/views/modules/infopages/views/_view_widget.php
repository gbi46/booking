<?php
echo '<div class="clear"></div><div>';
$widgetData = $widgetSummaryData = array('widgetTitles' => $widgetTitles);

if ($model->widget == 'seosummaryinfo') {
	Yii::import('application.modules.seo.components.SeosummaryinfoWidget');
	Yii::import('application.modules.apartments.components.ApartmentsWidget');
}
elseif ($model->widget == 'seosummarycities') {
	Yii::import('application.modules.seo.components.SeosummaryinfoWidget');
}
else {
	Yii::import('application.modules.'.$model->widget.'.components.*');
}

switch($model->widget){
    case 'contactform':
        $widgetData = CMap::mergeArray($widgetData, array());
        break;
	
	case 'seosummaryinfo':
		$widgetData = CMap::mergeArray($widgetData, array('criteria' => $model->getCriteriaForAdList(), 'showWidgetTitle' => false));
		$widgetSummaryData = CMap::mergeArray($widgetSummaryData, array('params' => $model->getParamsForSummaryCitiesList(), 'showWidgetTitle' => true));
		break;
	
    case 'apartments':
        $widgetData = CMap::mergeArray($widgetData, array('criteria' => $model->getCriteriaForAdList(), 'showWidgetTitle' => false));
        break;
	
	case 'seosummarycities':
        $widgetSummaryData = CMap::mergeArray($widgetSummaryData, array('params' => $model->getParamsForSummaryCitiesList(), 'showWidgetTitle' => true));
        break;
		
	case 'entries':
        $widgetData = CMap::mergeArray($widgetData, array('criteria' => $model->getCriteriaForEntriesList()));
        break;
}

if ($model->widget == 'seosummaryinfo') {
	$this->widget('SeosummaryinfoWidget', $widgetSummaryData);
	$this->widget('ApartmentsWidget', $widgetData);
}
elseif ($model->widget == 'seosummarycities') {
	$this->widget('SeosummaryinfoWidget', $widgetSummaryData);
}
else {
	$this->widget(ucfirst($model->widget).'Widget', $widgetData);
}

echo '</div>';
echo '<div class="clear"></div>';