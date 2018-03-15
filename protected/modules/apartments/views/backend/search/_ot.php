<?php
$ownersList = SearchHelper::getOwnerList();

?>

<div class="form-group">
    <div class=""><?php echo tc('Listing from') ?>:</div>
    <?php
    echo CHtml::dropDownList('Apartment[ot]', $model->ot, $ownersList, array(
        'id' => 'search_ot',
        'class' => 'form-control'
        //'empty' => tc('Listing from'),
    ));

    ?>
</div>