<?php
$this->pageTitle = Yii::app()->name . ' - ' . ReviewsModule::t('Add_feedback');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Reviews_management'), array('admin')),
);

$this->adminTitle = ReviewsModule::t('Add_feedback');

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model)); ?>