<?php
$photoList = SearchHelper::getPhotoList()

?>
<div class="form-group">
    <div class=""><?php echo tc('Photo') ?>:</div>
    <?php
    echo CHtml::dropDownList('Apartment[photo]', $model->photo, $photoList, array(
        //'empty' => tc('Photo'),
        'id' => 'search_photo',
        'class' => 'form-control'
    ));

    ?>
</div>