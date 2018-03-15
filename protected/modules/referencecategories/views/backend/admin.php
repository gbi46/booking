<?php
$this->breadcrumbs = array(
    Yii::t('common', 'References') => array('/site/viewreferences'),
    tt('Manage reference categories')
);

$this->menu = array(
    AdminLteHelper::getAddMenuLink(tt('Add reference category'), array('/referencecategories/backend/main/create')),
);

$this->adminTitle = tt('Manage reference categories');

?>

<?php
$this->widget('CustomGridView', array(
    'id' => 'reference-categories-grid',
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
            'class' => 'editable.EditableColumn',
            'header' => tc('Name'),
            'name' => 'title_' . Yii::app()->language,
            'value' => '$data->getStrByLang("title")',
            'editable' => array(
                'url' => Yii::app()->controller->createUrl('/referencecategories/backend/main/ajaxEditColumn', array('model' => 'ReferenceCategories', 'field' => 'title_' . Yii::app()->language)),
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
            'buttons' => array(
                'up' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/referencecategories/backend/main/move", array("id"=>$data->id, "direction" => "up"))',
                    'options' => array('class' => 'infopages_arrow_image_up glyphicon glyphicon-menu-up', 'title' => tc('Move an item up')),
                    'visible' => '$data->sorter > "' . $minSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'reference-categories-grid'); return false;}",
                ),
                'down' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/referencecategories/backend/main/move", array("id"=>$data->id, "direction" => "down"))',
                    'options' => array('class' => 'infopages_arrow_image_down glyphicon glyphicon-menu-down', 'title' => tc('Move an item down')),
                    'visible' => '$data->sorter < "' . $maxSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'reference-categories-grid'); return false;}",
                ),
            ),
        ),
    ),
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/referencecategories/backend/main/itemsSelected',
    'id' => 'reference-categories-grid',
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
			$.fn.yiiGridView.update('reference-categories-grid');
		}

		function installSortable() {
			if ($(window).width() > 767) {
				$('#reference-categories-grid table.items tbody').sortable({
					forcePlaceholderSize: true,
					forceHelperSize: true,
					items: 'tr',
					update : function () {
						serial = $('#reference-categories-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'data-bid'}) + '&{$csrf_token_name}={$csrf_token}';
						$.ajax({
							'url': '" . $this->createUrl('/referencecategories/backend/main/sortitems') . "',
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
