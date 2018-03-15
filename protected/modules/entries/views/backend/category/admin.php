<?php
$this->breadcrumbs = array(
    tt('Categories of entries', 'entries'),
);

$this->menu = array(
    AdminLteHelper::getAddMenuLink(tt('Add category', 'entries'), array('/entries/backend/category/create')),
);

$this->adminTitle = tt('Categories of entries', 'entries');

$this->widget('CustomGridView', array(
    'id' => 'categories-entries-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader();}',
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
            'header' => tt('Link', 'menumanager'),
            'value' => '$data->getUrl()',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('Link', 'menumanager'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{up} {down}<br /><br />{update} {delete}',
            'deleteConfirmation' => tt('All materials for this category will be deleted. Are you sure?', 'entries'),
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions'),
            'buttons' => array(
                'up' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/entries/backend/category/move", array("id"=>$data->id, "direction" => "up"))',
                    'options' => array('class' => 'infopages_arrow_image_up glyphicon glyphicon-menu-up', 'title' => tc('Move an item up')),
                    'visible' => '$data->sorter > "' . $minSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'categories-entries-grid'); return false;}",
                ),
                'down' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/entries/backend/category/move", array("id"=>$data->id, "direction" => "down"))',
                    'options' => array('class' => 'infopages_arrow_image_down glyphicon glyphicon-menu-down', 'title' => tc('Move an item down')),
                    'visible' => '$data->sorter < "' . $maxSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'categories-entries-grid'); return false;}",
                ),
            ),
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }'
        ),
    ),
));

?>

<?php
$this->renderPartial('//site/admin-select-items', array(
    'url' => '/entries/backend/category/itemsSelected',
    'id' => 'categories-entries-grid',
    'model' => $model,
    'options' => array(
        'delete' => Yii::t('common', 'Delete')
    ),
));
