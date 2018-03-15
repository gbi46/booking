<?php
$this->pageTitle = Yii::app()->name . ' - ' . EntriesModule::t('Add entry');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage entries'), array('admin')),
);

$this->adminTitle = EntriesModule::t('Add entry');

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model)); ?>