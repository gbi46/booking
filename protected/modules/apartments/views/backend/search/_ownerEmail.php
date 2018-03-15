<div class="form-group">
    <div class=""><?php echo tt('Owner email', 'apartments') ?>:</div>
    <div>
        <?php echo CHtml::textField('Apartment[ownerEmail]', $model->ownerEmail, array('class' => 'width220 form-control', 'placeholder' => tt('Owner email', 'apartments'), 'id' => 'ap_find_ownerEmail')); ?>
    </div>
</div>