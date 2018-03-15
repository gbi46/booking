<?php
$this->breadcrumbs = array(
    tt('Add category', 'entries')
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Categories of entries', 'entries'), array('/entries/backend/category/admin')),
);

$this->adminTitle = tt('Add category', 'entries');

$this->renderPartial('_form', array(
    'model' => $model,
));

?>