<?php
$this->pageTitle = Yii::app()->name . ' - ' . EntriesModule::t('Manage entries');


$this->menu = array(
    AdminLteHelper::getAddMenuLink(EntriesModule::t('Add entry'), array('create')),
);
$this->adminTitle = EntriesModule::t('Manage entries');

?>

<?php
$this->widget('CustomBootStrapGroupGridView', array(
    'extraRowColumns' => array('category_id'),
    'extraRowExpression' => '"<strong>{$data->category->getName()}</strong>"',
    'id' => 'entries-grid',
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
            'name' => 'active',
            'type' => 'raw',
            'value' => 'Yii::app()->controller->returnStatusHtml($data, "entries-grid", 1)',
            'headerHtmlOptions' => array(
                'class' => 'apartments_status_column',
            ),
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tc('Status'),
            ),
        ),
        array(
            'header' => tc('Name'),
            'name' => 'title_' . Yii::app()->language,
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->getStrByLang("title")), $data->url)',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Entry title', 'entries'),
            ),
        ),
        array(
            'name' => 'category_id',
            'type' => 'raw',
            'value' => '($data->category_id && isset($data->category) && $data->category) ? $data->category->name : ""',
            'filter' => EntriesCategory::getAllCategories(),
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Category', 'entries'),
            ),
        ),
        array(
            'name' => 'tags',
            'value' => '$data->tags',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Tags', 'entries'),
            ),
        ),
        array(
            'name' => 'dateCreated',
            'type' => 'raw',
            'filter' => false,
            'htmlOptions' => array(
                'class' => 'width130',
                'data-title' => tt('Creation date', 'entries'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
            'viewButtonUrl' => '$data->url',
            'htmlOptions' => array(
                'class' => 'infopages_buttons_column button_column_actions',
            ),
        ),
    ),
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/entries/backend/main/itemsSelected',
    'id' => 'entries-grid',
    'model' => $model,
    'options' => array(
        'activate' => Yii::t('common', 'Activate'),
        'deactivate' => Yii::t('common', 'Deactivate'),
        'delete' => Yii::t('common', 'Delete'),
    ),
));
