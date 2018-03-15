<?php
$this->breadcrumbs = array(
    //Yii::t('common', 'References') => array('/site/viewreferences'),
    tt('Manage lang messages') => array('admin'),
    tt('Add message'),
);


$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage lang messages'), array('admin')),
);

$this->adminTitle = tt('Add message');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>