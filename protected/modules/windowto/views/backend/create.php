<?php
$this->breadcrumbs = array(
    Yii::t('common', 'References') => array('/site/viewreferences'),
    tt('Manage reference (window to..)') => array('admin'),
    tt('Add value'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage reference (window to..)'), array('admin')),
);

$this->adminTitle = tt('Add value');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>