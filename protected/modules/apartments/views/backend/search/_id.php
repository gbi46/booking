<div class="form-group">
    <div class=""><?php echo tt('ID', 'apartments') ?>:</div>
    <div>
        <?php echo CHtml::textField('Apartment[id]', $model->id, array('class' => 'width100 form-control', 'placeholder' => tt('ID', 'apartments'), 'id' => 'ap_find_id')); ?>
    </div>
</div>