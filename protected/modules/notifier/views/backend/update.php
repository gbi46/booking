<?php
$this->pageTitle = Yii::app()->name . ' - ' . tc('Mail editor');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tc('Mail editor'), array('admin')),
);
$this->adminTitle = tc('Edit');

?>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model)); ?>