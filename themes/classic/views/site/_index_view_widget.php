<?php
echo '<div class="clear"></div><div>';

	$widgetData = $widgetSummaryData = array('widgetTitles' => $widgetTitles);

	if ($widget == 'seosummaryinfo') {
		Yii::import('application.modules.seo.components.SeosummaryinfoWidget');
		Yii::import('application.modules.apartments.components.ApartmentsWidget');
	}
	elseif ($widget == 'seosummarycities') {
		Yii::import('application.modules.seo.components.SeosummaryinfoWidget');
	}
	else {
		Yii::import('application.modules.'.$widget.'.components.*');
	}

	switch($widget){
		case 'contactform':
			$widgetData = CMap::mergeArray($widgetData, array('page' => 'index'));
			break;

		case 'seosummaryinfo':
			$criteria = $page->getCriteriaForAdList();
			$criteria = HGeo::setForIndexCriteria($criteria);

			$widgetData = CMap::mergeArray($widgetData, array('criteria' => $criteria));

			$widgetSummaryData = CMap::mergeArray($widgetSummaryData, array('params' => $page->getParamsForSummaryCitiesList(), 'showWidgetTitle' => true));
			break;

		case 'apartments':
			$criteria = $page->getCriteriaForAdList();
			$criteria = HGeo::setForIndexCriteria($criteria);

			$widgetData = CMap::mergeArray($widgetData, array('criteria' => $criteria));
			break;

		case 'seosummarycities':
			$widgetSummaryData = CMap::mergeArray($widgetSummaryData, array('params' => $page->getParamsForSummaryCitiesList(), 'showWidgetTitle' => true));
			break;

		case 'entries':
			$widgetData = CMap::mergeArray($widgetData, array('criteria' => $page->getCriteriaForEntriesList()));
			break;
	}

	if ($widget == 'seosummaryinfo') {
		$this->widget('SeosummaryinfoWidget', $widgetSummaryData);
		$this->widget('ApartmentsWidget', $widgetData);
	}
	elseif ($widget == 'seosummarycities') {
		$this->widget('SeosummaryinfoWidget', $widgetSummaryData);
	}
	else {
		$this->widget(ucfirst($widget).'Widget', $widgetData);
	}

echo '</div><div class="clear"></div>';