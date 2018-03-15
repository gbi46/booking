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
    'class' => 'CButtonColumn',
    'template' => '<div class="t-but"><div class="t-but"><span class="t-but-u">{update}</span></div><div class="t-but"><span class="t-but-d">{delete}</span></div><div class="t-but"><span class="t-but-c">{clone}</span></div></div>',
    'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
    'viewButtonUrl' => '$data->getUrl()',
    'afterDelete' => 'function(link,success,data){ /*$(".flashes").show(); $(".flashes").html(data);*/ $(".flashes").hide(); }',
    'buttons' => array(
        'clone' => array(
            'label' => tc('Clone'),
            'imageUrl' => Yii::app()->theme->baseUrl . '/images/default/copy.png',
            'url' => 'Yii::app()->createUrl("/userads/main/clone", array("id" => $data->id))',
            'visible' => 'param("enableUserAdsCopy",0)',
            'click' => "js: function() { ajaxRequest($(this).attr('href'), 'ap-view-table-list'); return false;}",
        ),

    ),
    'htmlOptions' => array(
        'class' => 'width70 center',
    ),
);

$this->widget('NoBootstrapGridView', array(
    'id' => 'ap-view-table-list',
    'afterAjaxUpdate' => 'function(){attachStickyTableHeader();}',
    'dataProvider' => $childs->search(),
    //'filter'=>false,
    'columns' => $columns,
    'template' => "{items}\n{pager}",
));

echo CHtml::link(tc('child_add_'.$model->objType->id), Yii::app()->createUrl('/userads/main/create', array('for' => $model->id)), array('class' => 'btn btn-info floatright'));
