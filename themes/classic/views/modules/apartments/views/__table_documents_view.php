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
$columns = array(
	array(
		'header' => tt('Original_document_name', 'apartments'),
		'value' => 'CHtml::link(CHtml::encode($data->original_name), Yii::app()->controller->createUrl("/apartments/main/downloadDocument", array("id" => $data->id)), array("target" => "_blank"))',
		'type' => 'html',
		'sortable' => false,
		'filter' => false,
	),
);

$CGridViewClass = (param('useBootstrap', false)) ? 'CustomGridView' : 'NoBootstrapGridView';

$this->widget($CGridViewClass, array(
	'id'=>'apartment-documents-grid',
	'dataProvider' => $dataProvider,
	'emptyText' => tt('No_documents', 'apartments'),
	'columns' => $columns,
	'template' => (isset($showDeleteButton) && $showDeleteButton) ? "{summary}\n{pager}\n{items}\n{pager}" : "{pager}\n{items}\n{pager}",
));
?>
<div class="clear"></div>