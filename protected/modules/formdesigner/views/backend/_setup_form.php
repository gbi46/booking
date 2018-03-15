<p>
    <strong><?php echo tt('Settings for the field', 'formdesigner'); ?></strong>: <?php echo Apartment::model()->getAttributeLabel($model->field); ?>
</p>

<?php
/** @var CustomForm $form */
$form = $this->beginWidget('CustomForm', array(
    'id' => 'form-designer-filter',
    'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

echo $form->errorSummary($model);

echo CHtml::hiddenField('id', $model->id);

echo CHtml::hiddenField('FormDesigner[save]', $model->id);

echo $form->dropDownListControlGroup($model, 'view_in', FormDesigner::getViewInList(), array('class' => 'span3'));
echo '<br>';

if ($model->not_hide == 0) {
    echo $form->dropDownListControlGroup($model, 'visible', FormDesigner::getVisibleList(), array('class' => 'span3'));
    echo '<br>';

    echo $form->checkBoxListControlGroup($model, 'apTypesArray', HApartment::getTypesArray());
    echo $form->checkBoxListControlGroup($model, 'objTypesArray', ApartmentObjType::getList());
    echo '<br>';
}

$withoutTip = FormDesigner::getFieldsWithoutTip();

if (!in_array($model->field, $withoutTip)) {
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'tip',
        'type' => 'string',
    ));
}

echo '<div class="clear"></div>';
echo '<br>';


echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));


$this->endWidget();

?>