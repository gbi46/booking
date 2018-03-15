<?php
$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Reviews_management'), array('admin')),
    AdminLteHelper::getAddMenuLink(ReviewsModule::t('Add_feedback'), array('create')),
    AdminLteHelper::getEditMenuLink(ReviewsModule::t('Edit_review'), array('update', 'id' => $model->id)),
    AdminLteHelper::getDeleteMenuLink(tt('Delete_review'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt('View_review');

$this->widget('CustomDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'name',
        'email',
        array(
            'label' => CHtml::encode($model->getAttributeLabel('body')),
            'value' => CHtml::encode($model->body),
            'type' => 'raw',
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n"
        ),
        'date_created',
    ))
);

?>