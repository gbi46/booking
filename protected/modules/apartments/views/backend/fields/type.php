<?php
if ($model->parent_id && $model->type && $model->isChild()) {
    return;
}

?>

<div class="form-group">
    <?php echo CHtml::activeLabelEx($model, 'type'); ?>
    <?php echo CHtml::activeDropDownList($model, 'type', HApartment::getTypesArray(), array('class' => 'span3 form-control', 'id' => 'ap_type')); ?>
    <?php echo CHtml::error($model, 'type'); ?>
</div>