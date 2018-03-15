<div class="flashes" style="display: none;"></div>

<?php
$childs = new Apartment('search');
$childs->unsetAttributes();  // clear any default values
if (isset($_GET['Apartment'])) {
    $childs->attributes = $_GET['Apartment'];
}
$childs->notDeleted()->forParent($model->id);

Yii::app()->clientScript->registerScript('ajaxSetStatus', "
		function ajaxSetStatus(elem, id, isUpdate){
			var isUpdate = isUpdate || 1;

			$.ajax({
				url: $(elem).attr('href'),
				success: function(result){
					$('.flashes').hide();

					if (isUpdate == 2) {
						$('.flashes').show();
						$('.flashes').html(result);
						$('#'+id).yiiGridView.update(id);
					}
					else {
						$('#'+id).yiiGridView.update(id);
					}
				}
			});
		}
	", CClientScript::POS_HEAD);


$columns = array(
    array(
        'name' => 'id',
        'headerHtmlOptions' => array(
            'class' => 'apartments_id_column',
        ),
        'sortable' => false,
        'filter' => false,
    ),
    array(
        'class' => 'editable.EditableColumn',
        'name' => 'active',
        'type' => 'raw',
        'value' => 'Yii::app()->controller->returnApartmentActiveHtml($data)',
        'editable' => array(
            'url' => Yii::app()->controller->createUrl('/apartments/backend/main/ajaxEditColumn', array('model' => 'Apartment', 'field' => 'active')),
            'emptytext' => '',
            'savenochange' => 'true',
            'title' => tt('Status', 'apartments'),
            'type' => 'select',
            'source' => Apartment::getAvalaibleStatusArray(),
            'placement' => 'top',
            'options' => array(
                'ajaxOptions' => array('dataType' => 'json')
            ),
            'success' => 'js: function(response, newValue) {
				if (response.msg == "ok") {
					message("' . tc("Success") . '");
				}
				else if (response.msg == "save_error") {
					var newValField = "' . tt("Error. Repeat attempt later", 'blockIp') . '";

					return newValField;
				}
				else if (response.msg == "no_value") {
					var newValField = "' . tt("Enter the required value", 'configuration') . '";

					return newValField;
				}
			}',
        ),
        'sortable' => false,
        'filter' => Apartment::getModerationStatusArray(),
        'htmlOptions' => array(
            'class' => 'status_column',
        ),
    ),
    array(
        'name' => 'owner_active',
        'type' => 'raw',
        'value' => 'Apartment::getApartmentsStatus($data->owner_active)',
        'htmlOptions' => array(
            'class' => 'status_column',
        ),
        'sortable' => false,
        'filter' => Apartment::getApartmentsStatusArray(),
    ),
    array(
        'header' => tc('Name'),
        'name' => 'title_' . Yii::app()->language,
        'type' => 'raw',
        'value' => 'HApartment::getNameForGrid($data)',
        'sortable' => false,
        'filter' => false,
    ),
//    array(
//        'name' => 'obj_type_id',
//        'type' => 'raw',
//        'value' => '(isset($data->objType) && $data->objType) ? $data->objType->name : ""',
//        /*'htmlOptions' => array(
//            'style' => 'width: 100px;',
//        ),*/
//        'filter' => false,
//        'sortable' => false,
//    ),
    array(
        'name' => 'price',
        'type' => 'raw',
        'value' => '$data->getPrettyPrice(false)',
//		'htmlOptions' => array(
//			'style' => 'width: 100px;',
//		),
        'filter' => false,
        'sortable' => false,
    ),
);

$columns[] = array(
    'class' => 'bootstrap.widgets.BsButtonColumn',
    'template' => '{clone}&nbsp;&nbsp;&nbsp;{view} {update} {delete}',
    'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
    'viewButtonUrl' => '$data->getUrl()',
    'afterDelete' => 'function(link,success,data){ /*$(".flashes").show(); $(".flashes").html(data);*/ $(".flashes").hide(); }',
    'buttons' => array(
        'clone' => array(
            'label' => '',
            'url' => 'Yii::app()->createUrl("/apartments/backend/main/clone", array("id" => $data->id))',
            //'imageUrl' => Yii::app()->request->baseUrl. '/images/interface/copy_admin.png',
            'options' => array('class' => 'glyphicon glyphicon-copy', 'title' => tc('Clone')),
            'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'ap-view-table-list'); return false;}",
        ),
    ),
    'htmlOptions' => array(
        'class' => 'width70 center',
    ),
);

$this->widget('CustomGridView', array(
    'id' => 'ap-view-table-list',
    'afterAjaxUpdate' => 'function(){attachStickyTableHeader();}',
    'dataProvider' => $childs->search(),
    //'filter'=>false,
    'columns' => $columns,
    'template' => "{items}\n{pager}",
    /* 	'htmlOptions' => array(
      'class' => 'picker__table',
      ), */
));

echo CHtml::link(tc('child_add_' . $model->objType->id), Yii::app()->createUrl('/apartments/backend/main/create', array('for' => $model->id)), array('class' => 'btn btn-info floatright'));
