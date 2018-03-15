<?php
$this->breadcrumbs = array(
    tt('Manage clients', 'clients') => array('admin'),
    tt('Update client', 'clients'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage clients', 'clients'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add client', 'clients'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete client', 'clients'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt('Update client', 'clients');

?>

<?php
if (isset($show) && $show) {
    /* Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/scrollto.js', CClientScript::POS_END);
      Yii::app()->clientScript->registerScript('scroll-to','
      scrollto("'.CHtml::encode($show).'");
      ',CClientScript::POS_READY
      ); */
}

$this->renderPartial('_form', array(
    'model' => $model,
));

?>