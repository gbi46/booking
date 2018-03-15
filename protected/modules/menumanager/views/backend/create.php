<?php
$this->breadcrumbs = array(
    tt('Manage of the top menu') => array('admin'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage of the top menu'), array('admin')),
);

$this->renderPartial('_form', array('model' => $model));
