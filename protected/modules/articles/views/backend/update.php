<?php
$this->breadcrumbs = array(
    tt("FAQ") => array('index'),
    tt("Manage FAQ") => array('admin'),
    $model->page_title => array('view', 'id' => $model->id),
    tt("Update FAQ"),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt("Manage FAQ"), array('admin')),
    AdminLteHelper::getAddMenuLink(tt("Add FAQ"), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete FAQ'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt("Update FAQ");

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model)); ?>