<?php
$this->breadcrumbs = array(
    tt('Manage reference values') => array('admin'),
    tt('Update reference'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage reference values'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Create value'), array('create')),
    AdminLteHelper::getAddMenuLink(tt('Create multiple reference values'), array('createMulty')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete reference value'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt('Update reference');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>