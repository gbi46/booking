<?php
$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage reference', 'windowto'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add value', 'windowto'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete value', 'windowto'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt('Update value', 'windowto');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>