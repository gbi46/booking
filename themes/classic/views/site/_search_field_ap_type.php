<div class="<?php echo $divClass; ?>">
    <span class="search"><div class="<?php echo $textClass; ?>"><?php echo tt('Search in section', 'common'); ?>:</div></span>
	<span class="search">
		<?php
        echo CHtml::dropDownList(
            'apType',
            isset($this->apType) ? CHtml::encode($this->apType) : '',
            HApartment::getTypesForSearch(true),
            array('class' => $fieldClass)
        );

        Yii::app()->clientScript->registerScript('currency-name-init', '
				focusSubmit($("select#apType"));
			', CClientScript::POS_READY);
        ?>
	</span>
</div>
