<?php
$this->breadcrumbs = array(
    Yii::t('common', 'References') => array('/site/viewreferences'),
    tt('Manage reference categories') => array('admin'),
    tt('Edit category:') . ' ' . $model->getTitle(),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage reference categories'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add reference category'), array('/referencecategories/backend/main/create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete category'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);
$this->adminTitle = tt('Edit category:') . ' ' . $model->getTitle();

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>