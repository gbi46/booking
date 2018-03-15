<?php
$this->breadcrumbs = array(
    Yii::t('common', 'References') => array('/site/viewreferences'),
    tt('Manage reference values'),
);

$this->menu = array(
    //array('label'=>tt('Create value'), 'url'=>array('/referencevalues/backend/main/create')),
    AdminLteHelper::getAddMenuLink(tt('Create value'), array('create')),
    AdminLteHelper::getAddMenuLink(tt('Create multiple reference values'), array('createMulty')),
);

$this->adminTitle = tt('Manage reference values');

$this->widget('CustomBootStrapGroupGridView', array(
    'extraRowColumns' => array('reference_category_id'),
    'extraRowExpression' => '"<strong>{$data->category->getTitle()}</strong>"',
    //'mergeColumns' => array('reference_category_id'),
    'id' => 'reference-values-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(id, data){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); reInstallSortable(id, data); attachStickyTableHeader();}',
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
            'name' => 'reference_category_id',
            'header' => tt('Category'),
            'sortable' => false,
            'value' => '$data->category->getTitle()',
            'filter' => $this->getCategories(1),
            'htmlOptions' => array(
                'data-title' => tt('Category'),
            ),
        ),
        array(
            'class' => 'editable.EditableColumn',
            'header' => tc('Name'),
            'name' => 'title_' . Yii::app()->language,
            'value' => '$data->getStrByLang("title")',
            'editable' => array(
                'url' => Yii::app()->controller->createUrl('/referencevalues/backend/main/ajaxEditColumn', array('model' => 'ReferenceValues', 'field' => 'title_' . Yii::app()->language)),
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
            'name' => 'for_sale',
            'type' => 'raw',
            'value' => 'ReferenceValues::returnForStatusHtml($data, "for_sale", "reference-values-grid")',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('For sale'),
            ),
        ),
        array(
            'name' => 'for_rent',
            'type' => 'raw',
            'value' => 'ReferenceValues::returnForStatusHtml($data, "for_rent", "reference-values-grid")',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('For rent'),
            ),
        ),
        array(
            'name' => 'buy',
            'type' => 'raw',
            'value' => 'ReferenceValues::returnForStatusHtml($data, "buy", "reference-values-grid")',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('Buy'),
            ),
        ),
        array(
            'name' => 'rent',
            'type' => 'raw',
            'value' => 'ReferenceValues::returnForStatusHtml($data, "rent", "reference-values-grid")',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('Rent'),
            ),
        ),
        array(
            'name' => 'exchange',
            'type' => 'raw',
            'value' => 'ReferenceValues::returnForStatusHtml($data, "exchange", "reference-values-grid")',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('Exchange'),
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
                    'url' => 'Yii::app()->createUrl("/referencevalues/backend/main/move", array("id"=>$data->id, "direction" => "up", "catid"=>$data->category->id))',
                    'options' => array('class' => 'infopages_arrow_image_up glyphicon glyphicon-menu-up', 'title' => tc('Move an item up')),
                    'visible' => '$data->sorter > Yii::app()->controller->minSorters[$data->reference_category_id]',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'reference-values-grid'); return false;}",
                ),
                'down' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/referencevalues/backend/main/move", array("id"=>$data->id, "direction" => "down", "catid"=>$data->category->id))',
                    'options' => array('class' => 'infopages_arrow_image_down glyphicon glyphicon-menu-down', 'title' => tc('Move an item down')),
                    'visible' => '$data->sorter < Yii::app()->controller->maxSorters[$data->reference_category_id]',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'reference-values-grid'); return false;}",
                ),
            ),
        ),
    ),
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/referencevalues/backend/main/itemsSelected',
    'id' => 'reference-values-grid',
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
			installSortable($(data).find('#ReferenceValues_category_filter option:selected').val());
		}

		function updateGrid() {
			$.fn.yiiGridView.update('reference-values-grid');
		}

		function installSortable(areaIdSel) {
			if (areaIdSel > 0) {
				$('#reference-values-grid table.items tbody').sortable({
					forcePlaceholderSize: true,
					forceHelperSize: true,
					items: 'tr',
					update : function () {
						serial = $('#reference-values-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'data-bid'}) + '&{$csrf_token_name}={$csrf_token}';
						$.ajax({
							'url': '" . $this->createUrl('/referencevalues/backend/main/sortitems') . "',
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

		installSortable('" . intval($model->reference_category_id) . "');
";

$cs->registerScript('sortable-project', $str_js);
