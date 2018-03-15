<?php
$this->adminTitle = tt('Add value', 'windowto');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage reference', 'windowto'), array('admin')),
);

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>