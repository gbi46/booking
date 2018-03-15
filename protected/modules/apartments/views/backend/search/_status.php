<?php
$activeList = Apartment::getModerationStatusArray();

?>

<div class="form-group">
    <div class=""><?php echo tt('Status', 'apartments') ?>:</div>
    <?php
    echo CHtml::dropDownList('Apartment[active]', $model->active, $activeList, array(
        'empty' => '',
        'id' => 'active',
        'class' => 'form-control'
    ));

    ?>
</div>