<div class="form-group">
    <div class=""><?php echo tc('Square') ?>:</div>
    <div>
        <?php echo CHtml::textField('Apartment[square_min]', $model->square_min, array('class' => 'width100 form-control noblock', 'placeholder' => tc('Square from'), 'id' => 'square_min')); ?>
        <?php echo CHtml::textField('Apartment[square_max]', $model->square_max, array('class' => 'width100 form-control noblock', 'placeholder' => tc('Square to'), 'id' => 'square_max')); ?>
        <span class=""><?php echo tc("site_square"); ?></span>
    </div>
</div>