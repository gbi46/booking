<?php
$this->pageTitle = Yii::app()->name . ' - ' . InfoPagesModule::t('Edit infopage');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage infopages'), array('admin')),
    AdminLteHelper::getAddMenuLink(InfoPagesModule::t('Add infopage'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete infopage'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);
$this->adminTitle = InfoPagesModule::t('Edit infopage');

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model, 'addedFields' => $addedFields)); ?>