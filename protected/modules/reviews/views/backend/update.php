<?php
$this->pageTitle = Yii::app()->name . ' - ' . ReviewsModule::t('Edit_review');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Reviews_management'), array('admin')),
    AdminLteHelper::getAddMenuLink(ReviewsModule::t('Add_feedback'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete_review'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);
$this->adminTitle = ReviewsModule::t('Edit_review');

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model)); ?>