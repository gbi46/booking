<?php
$this->breadcrumbs = array(
    tt('Manage themes'),
);

$this->adminTitle = tt('Manage themes');

$this->widget('CustomGridView', array(
    'id' => 'themes-grid',
    'dataProvider' => $model->search(),
    //'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader();}',
    'columns' => array(
        array(
            'name' => 'is_default',
            'type' => 'raw',
            'value' => '$data->getIsDefaultHtml()',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'class' => 'width100 center',
                'data-title' => tt('Is Default'),
            ),
        ),
        array(
            'name' => 'title',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('title'),
            ),
        ),
        array(
            'name' => 'color_theme',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Color theme', 'themes'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{update}',
            'htmlOptions' => array('class' => 'width50 button_column_actions'),
            'buttons' => array(
                'update' => array(
                    'visible' => '$data->title == "atlas"',
                ),
            ),
        ),
    ),
));

Yii::app()->clientScript->registerScript('setDefThemes', "
    var demo = " . (demo() ? 1 : 0) . ";
	function changeDefault(id){
	    if(demo){
	        alert(" . CJavaScript::encode(tc('Sorry, this action is not allowed on the demo server.')) . ");
	        $('#currency-grid').yiiGridView.update('themes-grid');
	        return false;
	    }

		$.ajax({
			type: 'POST',
			url: '" . Yii::app()->request->baseUrl . "/themes/backend/main/setDefault',
			data: { 'id' : id },
			success: function(msg){
				$('#currency-grid').yiiGridView.update('themes-grid');
			}
		});
		return;
	}", CClientScript::POS_END);
