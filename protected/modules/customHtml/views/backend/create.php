<?php
$this->breadcrumbs = array(
    tt("Manage custom html") => array('admin'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt("Manage custom html"), array('admin')),
);

$this->adminTitle = tt("Add custom html");

?>

<?php
echo $this->renderPartial('/backend/_form', array(
    'model' => $model,
));

?>