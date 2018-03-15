<?php
$this->breadcrumbs = array(
    tt("FAQ") => array('index'),
    tt("Manage FAQ") => array('admin'),
    $model['page_title'],
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt("Manage FAQ"), array('admin')),
    AdminLteHelper::getAddMenuLink(tt("Add FAQ"), array('create')),
    AdminLteHelper::getEditMenuLink(tt("Update FAQ"), array('/articles/backend/main/update', 'id' => $model->id)),
    AdminLteHelper::getDeleteMenuLink(tt('Delete FAQ'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = $model['page_title'];

$this->renderPartial('//modules/articles/views/view', array(
    'model' => $model,
    'articles' => $articles,
));
