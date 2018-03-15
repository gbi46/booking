<?php
$activeListOwner = Apartment::getApartmentsStatusArray();

?>

<div class="form-group">
    <div class=""><?php echo tt('Status (owner)', 'apartments') ?>:</div>
    <?php
    echo CHtml::dropDownList('Apartment[owner_active]', $model->owner_active, $activeListOwner, array(
        'empty' => '',
        'id' => 'owner_active',
        'class' => 'form-control'
    ));

    ?>
</div>