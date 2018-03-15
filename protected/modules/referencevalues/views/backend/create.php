<?php
$this->breadcrumbs = array(
    tt('Manage reference values') => array('admin'),
    Yii::t('common', 'Create'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage reference values'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Create multiple reference values'), array('createMulty')),
);
$this->adminTitle = tt('Create reference value');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>