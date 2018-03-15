<?php
$this->breadcrumbs = array(
    Yii::t('common', 'References') => array('/site/viewreferences'),
    tt('Manage reference (window to..)') => array('admin'),
    tt('Update value'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage reference (window to..)'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add value'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete value'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt('Update value');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>