<?php
$this->breadcrumbs = array(
    //Yii::t('common', 'Object type') => array('/site/viewreferences'),
    tt('Manage apartment object types') => array('admin'),
    tt('Add object type'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage apartment object types'), array('admin')),
);

$this->adminTitle = tt('Add object type');

?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'settings' => $settings)); ?>