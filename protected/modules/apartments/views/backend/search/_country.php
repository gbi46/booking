<div class="form-group">
    <div class=""><?php echo tc('Country') ?>:</div>
    <?php
    echo CHtml::dropDownList(
        'Apartment[loc_country]', $model->loc_country, Country::getCountriesArray(2), array(
        'class' => 'searchField form-control',
        'id' => 'county_f',
        'ajax' => array(
            'type' => 'GET',
            'url' => $this->createUrl('/location/main/getRegions'),
            'data' => 'js:"country="+$("#county_f").val()+"&type=2"',
            'success' => 'function(result){
							$("#region").html(result);
							$("#region").change();
						}'
        )
        )
    );

    ?>
</div>