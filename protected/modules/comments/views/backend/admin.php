<?php
$this->breadcrumbs = array(
    Yii::t('module_comments', 'Comments'),
);

$this->menu = array(
    array(),
);

$this->adminTitle = Yii::t('module_comments', 'Comments');

$this->widget('CustomGridView', array(
    'id' => 'comment-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$(".rating-block input").rating({"readOnly":true}); $("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader();}',
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
            'name' => 'status',
            'type' => 'raw',
            'value' => 'Yii::app()->controller->returnStatusHtml($data, "comment-grid")',
            'headerHtmlOptions' => array('class' => 'infopages_status_column'),
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tc('Status'),
            ),
        ),
        array(
            'header' => tc('Sections'),
            'type' => 'raw',
            'value' => '$data->getLinkForSection()',
            'htmlOptions' => array(
                'data-title' => tc('Sections'),
            ),
        ),
        array(
            'header' => Yii::t('module_comments', 'Name'),
            'type' => 'raw',
            'value' => '$data->getUser()',
            'htmlOptions' => array(
                'data-title' => Yii::t('module_comments', 'Name'),
            ),
        ),
        array(
            'name' => 'body',
            'htmlOptions' => array(
                'data-title' => Yii::t('module_comments', 'Comment'),
            ),
        ),
        array(
            'name' => 'dateCreated',
            'header' => Yii::t('module_comments', 'Creation date'),
            'headerHtmlOptions' => array('style' => 'width:130px;'),
            'filter' => false,
            'sortable' => true,
            'htmlOptions' => array(
                'data-title' => Yii::t('module_comments', 'Creation date'),
            ),
        ),
        array(
            'name' => 'rating',
            'type' => 'raw',
            'value' => '$this->grid->controller->widget("CStarRating", array(
				"name" => $data->id,
				"id" => $data->id,
				"value" => $data->rating,
				"readOnly" => true,
				"minRating" => Comment::MIN_RATING,
				"maxRating" => Comment::MAX_RATING,
			), true)',
            'headerHtmlOptions' => array('style' => 'width:100px;'),
            'htmlOptions' => array(
                'class' => 'rating-block',
                'data-title' => Yii::t('module_comments', 'Rate'),
            ),
            'filter' => false,
        //'sortable' => false,
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{update} {delete}',
            'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
            'viewButtonUrl' => '',
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions'),
        ),
    ),
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/comments/backend/main/itemsSelected',
    'id' => 'comment-grid',
    'model' => $model,
    'options' => array(
        'activate' => Yii::t('common', 'Activate'),
        'delete' => Yii::t('common', 'Delete')
    ),
));
