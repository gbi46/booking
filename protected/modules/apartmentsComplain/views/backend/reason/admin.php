<?php
$this->breadcrumbs = array(
    tt('Reasons of complain'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Complains'), array('/apartmentsComplain/backend/main/admin')),
    AdminLteHelper::getAddMenuLink(tc('Add'), array('/apartmentsComplain/backend/complainreason/create')),
);

$this->adminTitle = tt('Reasons of complain');

$this->widget('CustomGridView', array(
    'id' => 'complain-reason-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); reInstallSortable(); attachStickyTableHeader();}',
    'rowCssClassExpression' => '"items[]_{$data->id}"',
    'rowHtmlOptionsExpression' => 'array("data-bid"=>"items[]_{$data->id}")',
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'id' => 'itemsSelected',
            'selectableRows' => '2',
            'htmlOptions' => array(
                'class' => 'center',
                'data-title' => tc('Actions'),
            ),
        ),
        array(
            'header' => tc('Name'),
            'name' => 'name_' . Yii::app()->language,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tc('Name'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{up} {down}<br /><br />{update} {delete}',
            'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions'),
            'buttons' => array(
                'up' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/apartmentsComplain/backend/complainreason/move", array("id"=>$data->id, "direction" => "up"))',
                    'options' => array('class' => 'infopages_arrow_image_up glyphicon glyphicon-menu-up', 'title' => tc('Move an item up')),
                    'visible' => '$data->sorter > "' . $minSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'complain-reason-grid'); return false;}",
                ),
                'down' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/apartmentsComplain/backend/complainreason/move", array("id"=>$data->id, "direction" => "down"))',
                    'options' => array('class' => 'infopages_arrow_image_down glyphicon glyphicon-menu-down', 'title' => tc('Move an item down')),
                    'visible' => '$data->sorter < "' . $maxSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'complain-reason-grid'); return false;}",
                ),
            ),
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }'
        ),
    ),
));

?>

<?php
$this->renderPartial('//site/admin-select-items', array(
    'url' => '/apartmentsComplain/backend/complainreason/itemsSelected',
    'id' => 'complain-reason-grid',
    'model' => $model,
    'options' => array(
        'delete' => Yii::t('common', 'Delete')
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
			installSortable();
		}

		function updateGrid() {
			$.fn.yiiGridView.update('complain-reason-grid');
		}

		function installSortable() {
			if ($(window).width() > 767) {
				$('#complain-reason-grid table.items tbody').sortable({
					forcePlaceholderSize: true,
					forceHelperSize: true,
					items: 'tr',
					update : function () {
						serial = $('#complain-reason-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'data-bid'}) + '&{$csrf_token_name}={$csrf_token}';
						$.ajax({
							'url': '" . $this->createUrl('/apartmentsComplain/backend/complainreason/sortitems') . "',
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

		installSortable();
";

$cs->registerScript('sortable-project', $str_js);
