<?php
$this->pageTitle = Yii::app()->name . ' - ' . InfoPagesModule::t('Manage infopages');


$this->menu = array(
    AdminLteHelper::getAddMenuLink(InfoPagesModule::t('Add infopage'), array('create')),
);
$this->adminTitle = InfoPagesModule::t('Manage infopages');

?>

<div class="flash-notice"><?php echo tt('help_infopages_backend_main_admin'); ?></div>

<?php
$this->widget('CustomGridView', array(
    'id' => 'infopages-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader();}',
    'columns' => array(
        array(
            'name' => 'active',
            'type' => 'raw',
            'value' => 'Yii::app()->controller->returnStatusHtml($data, "infopages-grid", 1, array(1,4))',
            'headerHtmlOptions' => array(
                'class' => 'apartments_status_column',
                'data-title' => tc('Status'),
            ),
            'filter' => false,
            'sortable' => false,
        ),
        array(
            'header' => tc('Name'),
            'name' => 'title_' . Yii::app()->language,
            'type' => 'raw',
            'value' => 'CHtml::encode($data->getStrByLang("title"))',
            'htmlOptions' => array(
                'data-title' => tc('Name'),
            ),
        ),
        array(
            'header' => tt('Link', 'menumanager'),
            'type' => 'raw',
            'value' => '($data->special == 0 || $data->id == InfoPages::LICENCE_PAGE_ID) ? $data->getUrl() : ""',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Link', 'menumanager'),
            ),
        ),
        array(
            'header' => tt('Widget', 'infopages'),
            'name' => 'widget',
            'value' => '($data->widget) ? InfoPages::getWidgetOptions($data->widget) : ""',
            'filter' => InfoPages::getWidgetOptions(null, false),
            'sortable' => false,
            'htmlOptions' => array(
                'class' => 'width400',
                'data-title' => tt('Widget', 'infopages'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
            'template' => '{update} {delete}',
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions'),
            'buttons' => array(
                'delete' => array(
                    'visible' => '$data->special == 0',
                ),
            ),
        ),
    ),
));
/*
$this->renderPartial('//site/admin-select-items', array(
	'url' => '/infopages/backend/main/itemsSelected',
	'id' => 'infopages-grid',
	'model' => $model,
	'options' => array(
		'delete' => Yii::t('common', 'Delete')
	),
));*/