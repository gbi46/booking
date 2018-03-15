<?php
$this->breadcrumbs = array(
    tt('Manage apartments') => array('admin'),
    tt('Update apartment'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage apartments'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add apartment'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete apartment'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        )
    )),
);

$this->adminTitle = tt('Update apartment');

?>

<?php
if (isset($show) && $show) {
    /*
      Yii::app()->clientScript->registerScript('scroll-to','
      scrollto("'.CHtml::encode($show).'");
      ',CClientScript::POS_READY
      ); */
}

$this->renderPartial('_form', array(
    'model' => $model,
    'supportvideoext' => $supportvideoext,
    'supportvideomaxsize' => $supportvideomaxsize,
    'seasonalPricesModel' => $seasonalPricesModel,
    'supportdocumentext' => $supportdocumentext,
));

?>