<?php
$this->breadcrumbs = array(
    tt('Complains'),
);

$this->menu = array(
    AdminLteHelper::getEditMenuLink(tt('Reasons of complain'), array('/apartmentsComplain/backend/complainreason/admin')),
);

$this->adminTitle = tt('Complains');

$this->widget('CustomGridView', array(
    'id' => 'complains-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader();}',
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
            'name' => 'user_ip',
            'value' => 'BlockIp::displayUserIP($data)',
            'headerHtmlOptions' => array('style' => 'width: 110px'),
            'editable' => array(
                'apply' => '$data->user_ip != "" && Yii::app()->user->checkAccess("blockip_admin")',
                'url' => Yii::app()->controller->createUrl('/blockIp/backend/main/ajaxAdd'),
                'placement' => 'right',
                'emptytext' => '',
                'savenochange' => 'true',
                'title' => tt('Add the IP address to the list of blocked', 'blockIp'),
                'options' => array(
                    'ajaxOptions' => array('dataType' => 'json')
                ),
                'onShown' => 'js: function() {
					var input = $(this).parent().find(".input-medium");

					$(input).attr("disabled", "disabled");
				}',
                'success' => 'js: function(response, newValue) {
					if (response.msg == "ok") {
						message("' . tt("Ip was success added", 'blockIp') . '");
					}
					else if (response.msg == "already_exists") {
						var newValField = "' . tt("Ip was already exists", 'blockIp') . '";

						return newValField;
					}
					else if (response.msg == "save_error") {
						var newValField = "' . tc("Error. Repeat attempt later") . '";

						return newValField;
					}
					else if (response.msg == "no_value") {
						var newValField = "' . tt("Enter Ip", 'blockIp') . '";

						return newValField;
					}
				}',
            ),
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('User IP', 'blockIp'),
            ),
        ),
        array(
            'name' => 'name',
            'headerHtmlOptions' => array('style' => 'width:150px;'),
            'type' => 'raw',
            'value' => 'ApartmentsComplain::getUserEmailLink($data)',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Name', 'apartmentsComplain'),
            ),
        ),
        array(
            'name' => 'complain_id',
            'headerHtmlOptions' => array('style' => 'width:150px;'),
            'value' => 'ApartmentsComplainReason::getAllReasons($data->complain_id)',
            'filter' => ApartmentsComplainReason::getAllReasons(),
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Cause of complaint', 'apartmentsComplain'),
            ),
        ),
        'body',
        array(
            'name' => 'apartment_id',
            'headerHtmlOptions' => array('style' => 'width:150px;'),
            'type' => 'raw',
            'value' => '(isset($data->apartment) && $data->apartment) ? CHtml::link($data->apartment->id, $data->apartment->getUrl()) : "-"',
            'filter' => false,
            'sortable' => true,
            'htmlOptions' => array(
                'data-title' => tt('Apartment_id', 'apartmentsComplain'),
            ),
        ),
        array(
            'name' => 'date_created',
            'headerHtmlOptions' => array('style' => 'width:130px;'),
            'filter' => false,
            'sortable' => true,
            'htmlOptions' => array(
                'data-title' => tt('Creation date', 'apartmentsComplain'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{delete}',
            'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
            'viewButtonUrl' => '',
            'htmlOptions' => array('class' => 'width50 button_column_actions'),
        ),
    ),
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/apartmentsComplain/backend/main/itemsSelected',
    'id' => 'complains-grid',
    'model' => $model,
    'options' => array(
        'delete' => Yii::t('common', 'Delete')
    ),
));
