<?php
$this->breadcrumbs = array(
    tt('Edit reasons of complain')
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Complains'), array('/apartmentsComplain/backend/main/admin')),
    AdminLteHelper::getPrimaryMenuLink(tt('Reasons of complain'), array('/apartmentsComplain/backend/complainreason/admin')),
);

$this->adminTitle = tt('Edit reasons of complain');

$this->renderPartial('_form', array(
    'model' => $model,
));

?>