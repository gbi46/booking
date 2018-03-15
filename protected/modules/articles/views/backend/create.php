<?php
$this->breadcrumbs = array(
    tt("FAQ") => array('index'),
    tt("Manage FAQ") => array('admin'),
    tt("Add FAQ"),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt("Manage FAQ"), array('admin')),
);

$this->adminTitle = tt("Add FAQ");

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model)); ?>