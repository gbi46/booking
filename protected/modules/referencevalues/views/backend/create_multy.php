<?php
$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage reference values'), array('admin')),
    //array('label'=>tt('Create value'), 'url'=>array('/referencevalues/backend/main/create')),
);
$this->adminTitle = tt('Create multiple reference values');

?>
<div class="flash-notice"><?php echo tt('Add multiple reference values help'); ?></div>
<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'reference_category_id'); ?>
        <?php echo $form->dropDownList($model, 'reference_category_id', $this->getCategories(1)); ?>
        <?php echo $form->error($model, 'reference_category_id'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->checkboxControlGroup($model, 'for_sale'); ?>
        <?php echo $form->error($model, 'for_sale'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->checkboxControlGroup($model, 'for_rent'); ?>
        <?php echo $form->error($model, 'for_rent'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->checkboxControlGroup($model, 'buy'); ?>
        <?php echo $form->error($model, 'buy'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->checkboxControlGroup($model, 'rent'); ?>
        <?php echo $form->error($model, 'rent'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->checkboxControlGroup($model, 'exchange'); ?>
        <?php echo $form->error($model, 'exchange'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'multy'); ?>
        <?php echo $form->textArea($model, 'multy', array('class' => 'width500 height100 form-control')); ?>
        <?php echo $form->error($model, 'multy'); ?>
    </div>

    <div class="clear"></div>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->