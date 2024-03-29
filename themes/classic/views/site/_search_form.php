<?php
$compact = isset($compact) ? $compact : 0;
$isInner = isset($isInner) ? $isInner : 0;
$objType = isset($this->objType) ? $this->objType : 0;
$searchFields = SearchFormModel::getFields($isInner, $objType);
$i = 1;
foreach($searchFields as $search){
    if($isInner){
        $divClass = 'small-header-form-line width450';
    }else{
        $divClass = 'header-form-line';
    }

    if($search->status <= SearchFormModel::STATUS_NOT_REMOVE){
        $this->renderPartial('//site/_search_field_' . $search->field, array(
                'divClass' => $divClass,
                'textClass' => 'width135',
                'fieldClass' => 'width290 search-input-new',
                'minWidth' => '290',
                'isInner' => $isInner,
            ));
    } else {
        if ($search->formdesigner) {
            $references = '';
            if ($search->formdesigner->type == FormDesigner::TYPE_REFERENCE || $search->formdesigner->type == FormDesigner::TYPE_MULTY) {
                $references = FormDesigner::getListByCategoryID($search->formdesigner->reference_id, Apartment::convertType($this->apType));
            };

            if (!($search->formdesigner->type == FormDesigner::TYPE_REFERENCE || $search->formdesigner->type == FormDesigner::TYPE_MULTY)
                || $references
            ) {
                $this->renderPartial('//site/_search_new_field', array(
                    'divClass' => $divClass,
                    'textClass' => 'width135',
                    'fieldClass' => 'width290 search-input-new',
                    'minWidth' => '290',
                    'search' => $search,
                    'isInner' => $isInner,
                    'references' => $references
                ));
            } else {
                continue;
            }
        }
    }

    if($isInner && $i == 1) {
        $displayLink = $compact ? '' : 'style="display: none;"';
        $displayForm = $compact ? 'style="display: none;"' : '';
        echo '<a href="javascript:;" id="more-options-link-inner" '.$displayLink.'>'.tc('More options').'</a>';
        echo '<div tt="1" '.$displayForm.' id="search-more-fields">';
    }
    $i++;

    SearchForm::increaseJsCounter();
}

if($isInner){
    echo '</div tt="1">';
}
?>
