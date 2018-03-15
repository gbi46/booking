<?php
$this->breadcrumbs = array(
    tt("Manage custom html") => array('admin'),
    tt("Update custom html"),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt("Manage custom html"), array('admin')),
    AdminLteHelper::getAddMenuLink(tt("Add custom html"), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete custom html'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt("Update custom html");

?>

<?php
echo $this->renderPartial('/backend/_form', array(
    'model' => $model,
));

?>