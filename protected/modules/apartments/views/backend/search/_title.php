<?php $tmp = 'title_' . Yii::app()->language; ?>
<div class="form-group">
    <div class=""><?php echo tt('Apartment title', 'apartments') ?>:</div>
    <div>
        <?php echo CHtml::textField('Apartment[' . $tmp . ']', $model->$tmp, array('class' => 'width220 form-control', 'id' => 'ap_find_title')); ?>
    </div>
</div>