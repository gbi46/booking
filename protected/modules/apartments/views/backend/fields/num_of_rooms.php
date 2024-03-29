<?php if ($model->canShowInForm('num_of_rooms')) { ?>
    <div class="form-group">
        <?php echo CHtml::activeLabelEx($model, 'num_of_rooms'); ?>
        <?php echo HApartment::getTip('num_of_rooms'); ?>
        <?php
        echo CHtml::activeDropDownList($model, 'num_of_rooms', array_merge(
                array(0 => ''), range(1, param('moduleApartments_maxRooms', 8))
            ), array('class' => 'width70 form-control'));

        ?>
        <?php echo CHtml::error($model, 'num_of_rooms'); ?>
    </div>
    <div class="clear5"></div>
<?php } ?>
