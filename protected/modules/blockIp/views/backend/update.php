<?php
$this->breadcrumbs = array(
    tt('Manage blockIp', 'blockIp') => array('admin'),
    tt('Update blockIp', 'blockIp'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage blockIp', 'blockIp'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add blockIp', 'blockIp'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete blockIp', 'blockIp'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt('Update blockIp', 'blockIp');

echo $this->renderPartial('/backend/_form', array('model' => $model));
