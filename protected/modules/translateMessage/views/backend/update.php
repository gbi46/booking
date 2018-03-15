<?php
$this->breadcrumbs = array(
    //Yii::t('common', 'References') => array('/site/viewreferences'),
    tt('Manage lang messages') => array('admin'),
    tt('Edit lang message:'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage lang messages'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add message'), array('create')),
);

$this->adminTitle = tt('Edit lang message:');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>