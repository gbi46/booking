<?php
$this->adminTitle = tc('The forms designer');

if (issetModule('formeditor')) {
    $this->menu = array(
        AdminLteHelper::getAddMenuLink(tt('Add field', 'formeditor'), array('/formeditor/backend/main/create')),
        AdminLteHelper::getEditMenuLink(tt('Edit search form', 'formeditor'), array('/formeditor/backend/search/editSearchForm')),
    );
}

Yii::app()->clientScript->registerScript('search', "
$('#form-designer-filter').submit(function(){
    $('#form-designer-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});

function ajaxSetVisible(elem){
	$.ajax({
		url: $(elem).attr('href'),
		success: function(){
			$('#form-designer-grid').yiiGridView.update('form-designer-grid');
		}
	});
}
");

$this->widget('CustomGridView', array(
    'id' => 'form-designer-grid',
    'dataProvider' => $model->search(),
    'afterAjaxUpdate' => 'function(id, data){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); reInstallSortable(id, data); attachStickyTableHeader();}',
    'rowCssClassExpression' => '"items[]_{$data->id}"',
    'rowHtmlOptionsExpression' => 'array("data-bid"=>"items[]_{$data->id}")',
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'field',
            'value' => '$data->getLabel()',
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('Field', 'formdesigner'),
            ),
        ),
        array(
            'name' => 'view_in',
            'value' => '$data->getViewInName()',
            'filter' => FormDesigner::getViewInList(),
            'htmlOptions' => array(
                'data-title' => tt('Display in', 'formdesigner'),
            ),
        ),
        array(
            'header' => tt('Show for type of listing', 'formdesigner'),
            'value' => '$data->getApTypesHtml()',
            'type' => 'raw',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Show for type of listing', 'formdesigner'),
            ),
        ),
        array(
            'header' => tt('Show for property types', 'formdesigner'),
            'value' => '$data->getTypesHtml()',
            'type' => 'raw',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Show for property types', 'formdesigner'),
            ),
        ),
        array(
            'name' => 'tip',
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('Tip', 'formdesigner'),
            ),
        ),
        array(
            'name' => 'visible',
            'value' => '$data->getVisibleName()',
            'type' => 'raw',
            'sortable' => false,
            'filter' => FormDesigner::getVisibleList(),
            'htmlOptions' => array(
                'data-title' => tt('Visibility', 'formdesigner'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{up} {down} {fast_up} {fast_down}<br /><br />{update} {delete}',
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions', 'style' => 'width:160px;'),
            'buttons' => array(
                'up' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/formeditor/backend/main/move", array("id"=>$data->id, "direction" => "up", "view_in"=>$data->view_in))',
                    'options' => array('class' => 'infopages_arrow_image_up glyphicon glyphicon-menu-up', 'title' => tc('Move an item up')),
                    'visible' => '($data->sorter > "' . $model->minSorter . '") && ' . intval($model->view_in),
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'form-designer-grid'); return false;}",
                ),
                'down' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/formeditor/backend/main/move", array("id"=>$data->id, "direction" => "down", "view_in"=>$data->view_in))',
                    'options' => array('class' => 'infopages_arrow_image_down glyphicon glyphicon-menu-down', 'title' => tc('Move an item down')),
                    'visible' => '($data->sorter < "' . $model->maxSorter . '") && ' . intval($model->view_in),
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'form-designer-grid'); return false;}",
                ),
                'fast_up' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/formeditor/backend/main/move", array("id"=>$data->id, "direction" => "fast_up", "view_in"=>$data->view_in))',
                    'options' => array('class' => 'infopages_arrow_image_fast_up glyphicon glyphicon-triangle-top', 'title' => tc('Move to the beginning of the list')),
                    'visible' => '($data->sorter > "' . $model->minSorter . '") && ' . intval($model->view_in),
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'form-designer-grid'); return false;}",
                ),
                'fast_down' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/formeditor/backend/main/move", array("id"=>$data->id, "direction" => "fast_down", "view_in"=>$data->view_in))',
                    'options' => array('class' => 'infopages_arrow_image_fast_down glyphicon glyphicon-triangle-bottom', 'title' => tc('Move to end of list')),
                    'visible' => '($data->sorter < "' . $model->maxSorter . '") && ' . intval($model->view_in),
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'form-designer-grid'); return false;}",
                ),
                'update' => array(
                    'url' => '$data->getUpdateUrl()',
                ),
                'delete' => array(
                    'visible' => '$data->standard_type == 0',
                    'url' => 'Yii::app()->createUrl("/formeditor/backend/main/delete", array("id" => $data->id))'
                ),
            )
        ),
    ),
));

?>

<?php
$csrf_token_name = Yii::app()->request->csrfTokenName;
$csrf_token = Yii::app()->request->csrfToken;

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery.ui');

$str_js = "
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};

		function reInstallSortable(id, data) {
			installSortable($(data).find('select[name=\"FormDesigner[view_in]\"] option:selected').val());
		}

		function updateGrid() {
			$.fn.yiiGridView.update('form-designer-grid');
		}

		function installSortable(areaIdSel) {
			if (areaIdSel > 0) {
				$('#form-designer-grid table.items tbody').sortable({
					forcePlaceholderSize: true,
					forceHelperSize: true,
					items: 'tr',
					update : function () {
						serial = $('#form-designer-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'data-bid'}) + '&{$csrf_token_name}={$csrf_token}&area_id=' + areaIdSel;
						$.ajax({
							'url': '" . $this->createUrl('/formeditor/backend/main/sortitems') . "',
							'type': 'post',
							'data': serial,
							'success': function(data){
								updateGrid();
							},
							'error': function(request, status, error){
								alert('We are unable to set the sort order at this time.  Please try again in a few minutes.');
							}
						});
					},
					helper: fixHelper
				}).disableSelection();
			}
		}

		installSortable('" . intval($model->view_in) . "');
";

$cs->registerScript('sortable-project', $str_js);
