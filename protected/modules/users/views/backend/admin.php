<?php
$this->breadcrumbs = array(
    Yii::t('common', 'User managment'),
);

$this->menu = array(
    AdminLteHelper::getAddMenuLink(tt('Add user'), array('create')),
);

$this->adminTitle = Yii::t('common', 'User managment');

$columns = array(
    array(
        'class' => 'CCheckBoxColumn',
        'id' => 'itemsSelected',
        'selectableRows' => '2',
        'htmlOptions' => array(
            'class' => 'center',
            'data-title' => tc('Actions'),
        ),
        'disabled' => '$data->role == "' . User::ROLE_ADMIN . '"',
    ),
    'id',
    array(
        'name' => 'active',
        'header' => tt('Status'),
        'type' => 'raw',
        'value' => 'Yii::app()->controller->returnStatusHtml($data, "user-grid", 1, 1)',
        'headerHtmlOptions' => array(
            'class' => 'infopages_status_column',
            'data-title' => tt('Status'),
        ),
        'filter' => array(0 => tt('Inactive'), 1 => tt('Active')),
    ),
    array(
        'name' => 'type',
        'value' => '$data->getTypeName()',
        'filter' => User::getTypeList(),
        'htmlOptions' => array(
            'data-title' => tc('Type'),
        ),
    ),
    array(
        'name' => 'role',
        'value' => '$data->getRoleName()',
        'filter' => User::$roles,
        'htmlOptions' => array(
            'data-title' => tt('Role', 'users'),
        ),
    ),
    array(
        'name' => 'username',
        'header' => tt('User name'),
        'htmlOptions' => array(
            'data-title' => tt('User name'),
        ),
    ),
    array(
        'name' => 'phone',
        'htmlOptions' => array(
            'data-title' => Yii::t('common', 'Your phone number'),
        ),
    ),
    array(
        'name' => 'email',
        'htmlOptions' => array(
            'data-title' => tt('E-mail', 'users'),
        ),
    ),
    /* array(
      'header' => '',
      'value' => 'HUser::getLinkForRecover($data)',
      'type' => 'raw',
      'htmlOptions' => array(
      'data-title' =>'',
      ),
      ), */
    array(
        'name' => 'date_created',
        'htmlOptions' => array(
            'data-title' => tc('Registration date'),
        ),
        'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'htmlOptions' => array('class' => 'form-control'),
            'model' => $model,
            'attribute' => 'date_created',
            'language' => Yii::app()->controller->datePickerLang,
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'changeMonth' => 'true',
                'changeYear' => 'true',
                'showButtonPanel' => 'true',
            ),
            ), true),
    ),
);

if (issetModule('paidservices')) {
    //$columns[] = 'balance';
}

if (issetModule('tariffPlans') && issetModule('paidservices') && Yii::app()->user->checkAccess('tariff_plans_admin')) {
    // for modal apply
    $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery.ui');
    $cs->registerScriptFile($cs->getCoreScriptUrl() . '/jui/js/jquery-ui-i18n.min.js');
    $cs->registerCssFile($cs->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');

    $columns[] = array(
        'name' => 'searchWithTariffPlan',
        'filter' => false,
        'sortable' => true,
        'value' => 'TariffPlans::getTariffPlansHtml(true, true, $data)',
        'type' => 'raw',
        'htmlOptions' => array(
            'class' => 'width200',
            'data-title' => tc('Tariff Plans'),
        ),
    );
}

$columns[] = array(
    'class' => 'editable.EditableColumn',
    'name' => 'balance',
    'value' => '$data->balance',
    'editable' => array(
        'url' => Yii::app()->controller->createUrl('/apartments/backend/main/ajaxEditColumn', array('model' => 'User', 'field' => 'balance')),
        'placement' => 'right',
        'emptytext' => '',
        'savenochange' => 'true',
        'title' => tc('balance'),
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
    'sortable' => true,
    'filter' => false,
    'htmlOptions' => array(
        'data-title' => tc('balance'),
    ),
);

$columns[] = array(
    'class' => 'bootstrap.widgets.BsButtonColumn',
    'template' => '{listings} {send} {password}<br /><br />{view} {update} {delete}',
    'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
    'htmlOptions' => array('style' => 'width: 100px; min-width: 100px;', 'class' => 'button_column_actions'),
    'headerHtmlOptions' => array('style' => 'width: 100px; min-width: 100px;'),
    'buttons' => array(
        'update' => array(
            'visible' => 'User::allowAdminEdit($data)',
        ),
        'view' => array(
            'visible' => 'User::allowAdminEdit($data)',
        ),
        'delete' => array(
            'visible' => 'User::allowAdminEdit($data)',
        ),
        'listings' => array(
            'label' => '',
            'url' => 'Yii::app()->createUrl("/apartments/backend/main/admin", array("Apartment[ownerEmail]" => $data->email))',
            'options' => array('class' => 'glyphicon glyphicon-th-list', 'title' => tt('member_listings', 'apartments')),
            'visible' => 'Yii::app()->user->checkAccess("apartments_admin") ? true : false',
        ),
        'send' => array(
            'label' => '',
            'url' => 'Yii::app()->createUrl("/messages/backend/main/read", array("id" => $data->id))',
            'options' => array('class' => 'glyphicon glyphicon-envelope', 'title' => tt('Message', 'messages')),
            'visible' => '(issetModule("messages") && Yii::app()->user->checkAccess("messages_admin") && $data->id != Yii::app()->user->id && $data->role == "registered" && $data->active == 1) ? true : false',
        ),
        'password' => array(
            'label' => '',
            'url' => 'Yii::app()->createUrl("/users/backend/main/recover", array("id" => $data->id))',
            'options' => array(
                'class' => 'glyphicon glyphicon-lock',
                'title' => tc('Recover password'),
                'onclick' => 'changePass(this); return false;',
            ),
        ),
    )
);

Yii::app()->clientScript->registerScript('recover-password', '
	function changePass(elem){
		var text = "' . Yii::t('module_users', 'Restore password for {email}?', array('{email}' => '{email}')) . '";
		text = text.replace("{email}", $(elem).closest("tr").find("td:eq(7)").html());
		if(confirm(text)){
			$.ajax({
				type: "get",
				dataType: "json",
				url: $(elem).attr("href"),
				success: function(data){
					message(data.msg, "message", 10000);
				}
			});
		}
	}
', CClientScript::POS_END);

$this->widget('CustomGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); jQuery("#User_date_created").datepicker(jQuery.extend(jQuery.datepicker.regional["' . Yii::app()->controller->datePickerLang . '"],{"showAnim":"fold","dateFormat":"yy-mm-dd","changeMonth":"true","showButtonPanel":"true","changeYear":"true"})); attachStickyTableHeader();}',
    'columns' => $columns,
    'ajaxUpdate' => false,
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/users/backend/main/itemsSelected',
    'id' => 'user-grid',
    'model' => $model,
    'options' => array(
        'activate' => Yii::t('common', 'Activate'),
        'deactivate' => Yii::t('common', 'Deactivate'),
        'delete' => Yii::t('common', 'Delete')
    ),
));
