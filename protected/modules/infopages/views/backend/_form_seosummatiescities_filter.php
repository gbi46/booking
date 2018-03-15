<?php
$objTypes = CArray::merge(array(0 => ''), ApartmentObjType::getList());
//$typeList = CArray::merge(array(0 => ''), HApartment::getTypesArray());
$typeList = HApartment::getTypesForSearch(true, false);
$cities = array();

?>

<div id="seosummariescities_filter" style="display: none;" class="well">
    <h4><?php echo tt('Filter for summary infopage') ?></h4>

    <div class="form-group">
        <div class=""><?php echo tc('Type') ?>:</div>
        <?php echo CHtml::dropDownList('filterSummaryCities[type]', $this->getFilterSummaryCitiesValue('type'), $typeList, array('class' => 'form-control')); ?>
    </div>

    <div class="form-group">
        <div class=""><?php echo tc('Property type') ?>:</div>
        <?php echo CHtml::dropDownList('filterSummaryCities[obj_type_id]', $this->getFilterSummaryCitiesValue('obj_type_id'), $objTypes, array('class' => 'form-control')); ?>
    </div>

    <?php if (issetModule('location')) { ?>
        <div class="">
            <div class=""><?php echo tc('Country') ?>:</div>
            <?php
            echo CHtml::dropDownList(
                'filterSummaryCities[country_id]', $this->getFilterSummaryCitiesValue('country_id'), Country::getCountriesArray(2), array('class' => 'searchField form-control', 'id' => 'summary_filter_country',
                'ajax' => array(
                    'type' => 'GET',
                    'url' => $this->createUrl('/location/main/getRegions'),
                    'data' => 'js:"country="+$("#summary_filter_country").val()+"&type=2"',
                    'success' => 'function(result){
							$("#summary_filter_region").html(result);
							$("#summary_filter_region").change();
						}'
                )
                )
            );

            ?>
        </div>

        <div class="">
            <div class=""><?php echo tc('Region') ?>:</div>
            <?php
            echo CHtml::dropDownList(
                'filterSummaryCities[region_id]', $this->getFilterSummaryCitiesValue('region_id'), Region::getRegionsArray($this->getFilterSummaryCitiesValue('country_id'), 2), array('class' => 'searchField form-control', 'id' => 'summary_filter_region',
                'ajax' => array(
                    'type' => 'GET',
                    'url' => $this->createUrl('/location/main/getCities'),
                    'data' => 'js:"region="+$("#summary_filter_region").val()+"&type=4"',
                    'success' => 'function(result){
							$("#summarycities_filter_ap_city").html(result);
							$("#summarycities_filter_ap_city").trigger("chosen:updated");
							//$("#summarycities_filter_ap_city")[0].sumo.reload();
						}'
                )
                )
            );

            ?>
        </div>
        <?php
        $cities = City::getCitiesArray($this->getFilterSummaryCitiesValue('region_id'), 4);
    }

    ?>

    <div class="">
        <div class=""><?php echo Yii::t('common', 'City') ?>:</div>
        <?php
        echo Chosen::multiSelect('filterSummaryCities[city_id][]', $this->getFilterSummaryCitiesValue('city_id'), $cities, array('id' => 'summarycities_filter_ap_city', 'class' => ' searchField span3 form-control', 'data-placeholder' => tc('select city'))
        );
        echo "<script>$('#summarycities_filter_ap_city').chosen();</script>";

        ?>
    </div>	
</div>