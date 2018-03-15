<?php
/* @var $this Controller */
/* @var $model CustomHtml */
/* @var $form CustomForm */

?>

<div class="form">
    <?php if ($model->id) { ?>
        <div class="alert alert-info">
            <strong><?php echo tt('Code') . ': ' ?></strong>
            <?php echo $model->getCode(); ?>
            <br/>
            <strong><?php echo tt('Code for view tempalte') . ': ' ?></strong>
            <?php echo $model->getCodeForInsertTpl(); ?>
        </div>
    <?php } ?>

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => 'custom html-form-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary(array($model)); ?>


    <div class="form-group">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>


    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'body',
        'type' => 'text-editor',
    ));

    ?>
    <br/>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->