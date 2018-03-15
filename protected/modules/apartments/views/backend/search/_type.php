<?php
$types = HApartment::getTypesArray(false, HApartment::isDisabledType());

?>

<div class="form-group">
    <div class=""><?php echo tc('Type') ?>:</div>
    <?php
    echo CHtml::dropDownList('Apartment[type]', $model->type, $types, array(
        'empty' => '',
        'id' => 'type_f',
        'class' => 'form-control'
    ));

    ?>
</div>