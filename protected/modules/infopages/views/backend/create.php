<?php
$this->pageTitle = Yii::app()->name . ' - ' . InfoPagesModule::t('Add infopage');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage infopages'), array('admin')),
);

$this->adminTitle = InfoPagesModule::t('Add infopage');

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model, 'addedFields' => $addedFields)); ?>