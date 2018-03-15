<div class="clear"></div>
<?php
$dataProvider = new CArrayDataProvider(array());
if (isset($apartment) && isset($apartment->apDocuments) && $apartment->apDocuments) {
	$dataProvider = new CArrayDataProvider('apDocuments');
	$dataProvider->setData($apartment->apDocuments);
	$dataProvider->setTotalItemCount(count($apartment->apDocuments));
}
?>

<?php
$CGridViewClass = (param('useBootstrap', false)) ? 'CustomGridView' : 'NoBootstrapGridView';
$CButtonClass = (param('useBootstrap', false)) ? 'bootstrap.widgets.BsButtonColumn' : 'CButtonColumn';

$columns = array();

if (param('useBootstrap', false)) {
	$columns[] = array(
		'header' => tt('Original_document_name', 'apartments'),
		'class' => 'editable.EditableColumn',
		'name' => 'original_name',
		'value' => 'CHtml::encode($data->original_name)',
		'sortable' => false,
		'filter' => false,
		'editable' => array(
			'type' => 'textarea',
			'url' => Yii::app()->controller->createUrl('/apartments/main/renameDocument', array()),
			'placement' => 'right',
			'emptytext' => '',
			'savenochange' => 'true',
			'title' => tt('Original_document_name', 'apartments'),
			'options' => array(
				'ajaxOptions' => array('dataType' => 'json')
			),
			'success' => 'js: function(response, newValue) {
				if (response.msg == "ok") {
					message("'.tc("Success").'");
				}
				else if (response.msg == "save_error") {
					var newValField = "'.tt("Error. Repeat attempt later", 'blockIp').'";

					return newValField;
				}
				else if (response.msg == "no_value") {
					var newValField = "'.tt("Enter the required value", 'configuration').'";

					return newValField;
				}
			}',
		),
	);
}
else {
	$columns[] = array(
		'header' => tt('Original_document_name', 'apartments'),
		'name' => 'original_name',
		'value' => 'CHtml::encode($data->original_name)',
		'sortable' => false,
		'filter' => false,
	);
}


if (isset($showDeleteButton) && $showDeleteButton) {
	$columns[] = array(
		'template' => '{delete}',
		'class' => $CButtonClass,
		'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
		'buttons' => array(
			'delete' => array(
				'url'=>'Yii::app()->createUrl("/apartments/main/deleteDocument", array("id"=>$data->id))',
				'options' => array('rel' => ''),
			),
		),
	);
}

$this->widget($CGridViewClass, array(
	'id'=>'apartment-documents-grid',
	'dataProvider' => $dataProvider,
	'emptyText' => tt('No_documents', 'apartments'),
	'columns' => $columns,
	'template' => (isset($showDeleteButton) && $showDeleteButton) ? "{summary}\n{pager}\n{items}\n{pager}" : "{pager}\n{items}\n{pager}",
));
?>
<div class="clear"></div>