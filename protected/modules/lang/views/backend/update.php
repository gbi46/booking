<?php
$this->breadcrumbs = array(
    tt('Manage lang') => array('admin'),
    tt('Update lang'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage lang'), array('admin')),
    /* array('label'=>tt('Create lang'), 'url'=>array('create')),
      array('label'=>tt('Delete lang'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>tc('Are you sure you want to delete this item?'))), */
    AdminLteHelper::getAddMenuLink(tt('Create new lang'), array('create')),
);

$this->adminTitle = tt('Update lang');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>