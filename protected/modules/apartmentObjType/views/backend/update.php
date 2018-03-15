<?php
$this->breadcrumbs = array(
    //Yii::t('common', 'References') => array('/site/viewreferences'),
    tt('Manage apartment object types') => array('admin'),
    tt('Edit object type'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage apartment object types'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add object type'), array('create')),
);

$this->adminTitle = tt('Edit object type');

?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'settings' => $settings)); ?>