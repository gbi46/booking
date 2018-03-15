<?php
$this->breadcrumbs = array(
    Yii::t('common', 'User managment') => array('admin'),
    CHtml::encode($model->email) . (CHtml::encode($model->username) != '' ? ' (' . CHtml::encode($model->username) . ')' : '') => array('view', 'id' => $model->id),
    tt('Edit user'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(Yii::t('common', 'User managment'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add user'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete user'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
        'visible' => $model->role != User::ROLE_ADMIN,
    )),
);
$model->scenario = 'update';

$this->adminTitle = $model->email . (CHtml::encode($model->username) != '' ? ' (' . CHtml::encode($model->username) . ')' : '');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>