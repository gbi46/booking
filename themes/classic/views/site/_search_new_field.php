
<div class="<?php echo $divClass; ?>">
    <span class="search"><div class="<?php echo $textClass; ?>"><?php echo $search->getLabel(); ?>:</div> </span>
    <?php
    if($search->formdesigner->type == FormDesigner::TYPE_INT){
        $width = 'search-input-new width70';
    } elseif($search->formdesigner->type == FormDesigner::TYPE_REFERENCE || $search->formdesigner->type == FormDesigner::TYPE_MULTY){
        $width = 'search-input-new width290';
    } else {
        $width = 'search-input-new width285';
    }

    if($search->formdesigner->type == FormDesigner::TYPE_MULTY) {
	    $value = (isset($this->newFields[$search->field]) && is_array($this->newFields[$search->field])) ? $this->newFields[$search->field] : array();
    } elseif ($search->formdesigner->compare_type == FormDesigner::COMPARE_FROM_TO) {
	    $value = (isset($this->newFields[$search->field]) && is_array($this->newFields[$search->field])) ? $this->newFields[$search->field] : array('min'=>'','max'=>'');
    } else {
	    $value = isset($this->newFields[$search->field]) ? CHtml::encode($this->newFields[$search->field]) : '';
    }

    echo '<span class="search sumo-pos-abs">';

    if($search->formdesigner->type == FormDesigner::TYPE_REFERENCE){
        echo CHtml::dropDownList($search->field, $value, CMap::mergeArray(array(0 => Yii::t('common', 'Please select')), $references),
            array('class' => 'searchField ' . $fieldClass)
        );
    }
	elseif($search->formdesigner->type == FormDesigner::TYPE_MULTY) {
        /*echo Chosen::multiSelect($search->field, $value, FormDesigner::getListByCategoryID($search->formdesigner->reference_id),
            array('class' => 'searchField ' . $fieldClass, 'data-placeholder' => $search->getLabel())
        );
        echo "<script>$('#" . $search->field . "').chosen();</script>";*/

		echo CHtml::dropDownList($search->field, $value, $references,
			array('class' => 'searchField ' . $fieldClass, 'placeholder' => tc('Please select'), 'multiple' => 'multiple')
		);
		echo "<script>$('#".$search->field."').SumoSelect({captionFormat:'".tc('{0} Selected')."', selectAlltext: '".tc('check all')."', csvDispCount:1, filter: true, filterText: '".tc('enter initial letters')."', filter: true, filterText: '".tc('enter initial letters')."'});</script>";
    }
	else{
		if($search->formdesigner->compare_type == FormDesigner::COMPARE_FROM_TO) {
			echo tc('field_from').'&nbsp;';
			echo CHtml::textField($search->field.'_min', $value['min'], array(
				'class' => 'search-input-new width70',
				'onChange' => 'changeSearch();',
			));
			echo '&nbsp;'.tc('field_to').'&nbsp;';
			echo CHtml::textField($search->field.'_max', $value['max'], array(
				'class' => 'search-input-new width70',
				'onChange' => 'changeSearch();',
			));
		} else {
			echo CHtml::textField($search->field, $value, array(
				'class' => $width,
				'onChange' => 'changeSearch();',
			));
		}


        if(($search->formdesigner->type == FormDesigner::TYPE_INT || $search->formdesigner->type == FormDesigner::TYPE_RANGE) && $search->formdesigner->measure_unit){
            echo '&nbsp;<span>' . $search->formdesigner->measure_unit.'</span>';
        }
    }

    echo '</span>';
    ?>
</div>