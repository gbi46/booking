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

Yii::import('ext.groupgridview.BootGroupGridView');

class CustomBootStrapGroupGridView extends BootGroupGridView
{

    //public $pager = array('class'=>'objectPaginator');
    public $template = "{summary}\n{pager}\n{items}\n{pager}";
    //public $extraRowColumns = array('reference_category_id');
    public $mergeType = 'nested';
    public $type = 'striped bordered condensed';
    public $pager = array('class' => 'bootstrap.widgets.BsPager');

    public function init()
    {
        parent::init();

        # no-more-tables - адаптивные таблицы в админке. Поэтому attachStickyTableHeader выключен ниже
        $this->htmlOptions['class'] = 'grid-view no-more-tables table-responsive';

        $excludeTabelIdsFromStickyTableHeader = array(
            'admin-booking-grid',
            'history-changes-grid',
            'user-grid',
        );

        # исключаем таблицы, где есть zii.widgets.jui.CJuiDatePicker - иначе ломается
        $allowStickyTableHeader = (in_array($this->getId(), $excludeTabelIdsFromStickyTableHeader)) ? 0 : 1;

        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/stickytableheaders/jquery.stickytableheaders.js', CClientScript::POS_END);

        Yii::app()->clientScript->registerScript('sticky-table-headers-func-' . $this->getId(), '
			function attachStickyTableHeader() {				
				if ($(window).width() < 768) {
					var styleSheetIndex = 2;
					if (document.styleSheets.length > 0) {
						for (var i = 0; i < document.styleSheets.length; i++) {
							if(document.styleSheets[i].cssRules && document.styleSheets[i].cssRules.length > 1 && document.styleSheets[i].type == "text/css") {
								styleSheetIndex = i;
								break;
							}
						}
					}

					/* no dubles */
					$(".no-more-tables tr.filters").find("select").find("option.mobile-placeholder").remove();
															
					/* add "header" */
					addCSSRule(document.styleSheets[styleSheetIndex], ".no-more-tables tr.filters:before", "content: \'' . tc('Filter') . '\';");
					
					$("#' . $this->getId() . '").find("tr.filters").find("td").each(function(i,elem) {
						var indexTR = i+1;

						if ($(elem).html().length > 50) {
							var str = $("#' . $this->getId() . '").find("thead").find("th").eq(indexTR-1).text();
							
							/*addCSSRule(document.styleSheets[styleSheetIndex], ".no-more-tables tr.filters td:nth-child("+indexTR+"):before", "content: \'"+str+"\';");*/
							
							$(".no-more-tables tr.filters td:nth-child("+indexTR+")").find("input").attr("placeholder", str);
							
							if ($(".no-more-tables tr.filters td:nth-child("+indexTR+")").find("select option:selected").text()) {
								$(".no-more-tables tr.filters td:nth-child("+indexTR+")").find("select").prepend("<option value=\'\' disabled=\'disabled\' class=\'mobile-placeholder\'>"+str+"</option>");
							}
							else {
								$(".no-more-tables tr.filters td:nth-child("+indexTR+")").find("select").prepend("<option value=\'\' disabled=\'disabled\' selected=\'selected\' class=\'mobile-placeholder\'>"+str+"</option>");
							}
						}
						else {
							$(elem).hide();
						}
					});
				}
				else {
					$(".no-more-tables tr.filters").find("input").removeAttr("placeholder");
					$(".no-more-tables tr.filters").find("select").find("option.mobile-placeholder").remove();
				}

				/*var allowStickyTableHeader = ' . $allowStickyTableHeader . ';
				if (allowStickyTableHeader) {
					if ($(window).width() < 768) {
						$("#' . $this->getId() . '").stickyTableHeaders("destroy");
					}
					else {
						if( $(".navbar-static-top").css("position").toLowerCase() == "fixed") {
							$("#' . $this->getId() . '").stickyTableHeaders({fixedOffset: $(".navbar-fixed-top")});
						}
						else {
							$("#' . $this->getId() . '").stickyTableHeaders({fixedOffset: 0});
						}
					}
				}*/
			}
		', CClientScript::POS_END);

        Yii::app()->clientScript->registerScript('call-sticky-table-headers-' . $this->getId(), '
			attachStickyTableHeader();
			
			$(window).bind("load", attachStickyTableHeader);
			$(window).bind("resize", attachStickyTableHeader);
			$(window).bind("orientationchange", attachStickyTableHeader);
		', CClientScript::POS_READY);
    }
}
