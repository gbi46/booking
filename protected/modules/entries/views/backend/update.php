<?php
$this->pageTitle = Yii::app()->name . ' - ' . EntriesModule::t('Edit entry');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage entries'), array('admin')),
    AdminLteHelper::getAddMenuLink(EntriesModule::t('Add entry'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete entry', 'entries'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);
$this->adminTitle = EntriesModule::t('Edit entry') . ': <i>' . CHtml::encode($model->title) . '</i>';

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model)); ?>