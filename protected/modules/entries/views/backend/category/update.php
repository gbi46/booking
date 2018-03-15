<?php
$this->breadcrumbs = array(
    tt('Edit category', 'entries')
);

$this->menu = array(
    //array('label'=> tt('Entries', 'entries'), 'url'=>array('/entries/backend/main/admin')),
    AdminLteHelper::getBackMenuLink(tt('Categories of entries', 'entries'), array('/entries/backend/category/admin')),
);

$this->adminTitle = tt('Edit category', 'entries');

$this->renderPartial('_form', array(
    'model' => $model,
));

?>