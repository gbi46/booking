<?php
$this->pageTitle = Yii::app()->name . ' - ' . tc('Mail editor');
$this->adminTitle = tc('Mail editor');

?>

<?php
$this->widget('CustomGridView', array(
    'id' => 'mail-editor-grid',
    'dataProvider' => $model->active()->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader();}',
    'columns' => array(
        array(
            'name' => 'subject_' . Yii::app()->language,
            'header' => tc('Subject'),
            'value' => '$data->subject',
            'htmlOptions' => array(
                'data-title' => tc('Subject'),
            ),
        ),
        array(
            'header' => tc('Event (name in code)'),
            'name' => 'event',
            'value' => '$data->event',
            'htmlOptions' => array(
                'data-title' => tc('Event (name in code)'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{update}',
            'htmlOptions' => array('class' => 'width50 button_column_actions'),
        ),
    ),
));
