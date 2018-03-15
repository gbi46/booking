<div class="">
    <div class=""><?php echo Yii::t('common', 'City') ?>:</div>
    <?php
    $cities = (isset($cities) && count($cities)) ? $cities : ApartmentCity::getAllCity();
    if (issetModule('metroStations')) {
        echo CHtml::dropDownList(
            'Apartment[city_id]', $model->city_id, $cities, array(
            'class' => ' searchField form-control',
            'id' => 'ap_city',
            'empty' => Yii::t('common', 'City'),
            'ajax' => array(
                'type' => 'GET',
                'url' => $this->createUrl('/metroStations/main/getMetroStations'),
                'data' => 'js:"city="+$("#ap_city").val()+"&type=0"',
                'dataType' => 'json',
                'success' => 'function(result){
							if (result.dropdownMetro) {
								//$("#metro-block").show();
								$("#metro").html(result.dropdownMetro);
								$("#metro").trigger("chosen:updated");
								//$("#metro")[0].sumo.reload();
							}
							else {
								//$("#metro-block").hide();
								$("#metro").html("");
								$("#metro").trigger("chosen:updated");
								//$("#metro")[0].sumo.reload();
							}
						}'
            ),
            ) //, 'multiple' => 'multiple'
        );
    } else {
        echo CHtml::dropDownList(
            'Apartment[city_id]', $model->city_id, $cities, array('class' => ' searchField form-control', 'id' => 'ap_city', 'empty' => Yii::t('common', 'City')) //, 'multiple' => 'multiple'
        );
    }

    ?>
</div>