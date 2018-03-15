<?php
$this->breadcrumbs = array(
    //Yii::t('common', 'objects') => array('/site/viewobjects'),
    tt('Manage apartment object types')
);

$this->menu = array(
    AdminLteHelper::getAddMenuLink(tt('Add object type'), array('create')),
);

$this->adminTitle = tt('Manage apartment object types');

?>

<?php
$this->widget('CustomGridView', array(
    'id' => 'object-categories-grid',
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
            'name' => 'icon_file',
            'value' => '$data->getImageIcon()',
            'type' => 'raw',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'class' => 'width200',
                'data-title' => tt('icon_file_maps'),
            ),
        ),
        array(
            'class' => 'editable.EditableColumn',
            'header' => tc('Name'),
            'name' => 'name_' . Yii::app()->language,
            'value' => '$data->getStrByLang("name")',
            'editable' => array(
                'url' => Yii::app()->controller->createUrl('/apartmentObjType/backend/main/ajaxEditColumn', array('model' => 'ApartmentObjType', 'field' => 'name_' . Yii::app()->language)),
                'placement' => 'right',
                'emptytext' => '',
                'savenochange' => 'true',
                'title' => tc('Name'),
                'options' => array(
                    'ajaxOptions' => array('dataType' => 'json')
                ),
                'success' => 'js: function(response, newValue) {
					if (response.msg == "ok") {
						message("' . tc("Success") . '");
					}
					else if (response.msg == "save_error") {
						var newValField = "' . tc("Error. Repeat attempt later") . '";

						return newValField;
					}
					else if (response.msg == "no_value") {
						var newValField = "' . tt("Enter the required value", 'configuration') . '";

						return newValField;
					}
				}',
            ),
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
            'afterDelete' => 'function(link, success, data){ data = parseInt(data); if (data) {alert("' . tt('backend_apartmentObjType_main_admin_NoDeleteLastElement') . '"); } }',
            'buttons' => array(
                'up' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/apartmentObjType/backend/main/move", array("id"=>$data->id, "direction" => "up"))',
                    'options' => array('class' => 'infopages_arrow_image_up glyphicon glyphicon-menu-up', 'title' => tc('Move an item up')),
                    'visible' => '$data->sorter > "' . $minSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'object-categories-grid'); return false;}",
                ),
                'down' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/apartmentObjType/backend/main/move", array("id"=>$data->id, "direction" => "down"))',
                    'options' => array('class' => 'infopages_arrow_image_down glyphicon glyphicon-menu-down', 'title' => tc('Move an item down')),
                    'visible' => '$data->sorter < "' . $maxSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'object-categories-grid'); return false;}",
                ),
            ),
        ),
    ),
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/apartmentObjType/backend/main/itemsSelected',
    'id' => 'object-categories-grid',
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
			$.fn.yiiGridView.update('object-categories-grid');
		}

		function installSortable() {
			if ($(window).width() > 767) {
				$('#object-categories-grid table.items tbody').sortable({
					forcePlaceholderSize: true,
					forceHelperSize: true,
					items: 'tr',
					update : function () {
						serial = $('#object-categories-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'data-bid'}) + '&{$csrf_token_name}={$csrf_token}';
						$.ajax({
							'url': '" . $this->createUrl('/apartmentObjType/backend/main/sortitems') . "',
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
