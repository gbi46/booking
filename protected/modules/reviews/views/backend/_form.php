<div class="form">
    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <p class="note">
        <?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?>
    </p>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('class' => 'width200')); ?>
        <div class="clear"></div>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('class' => 'width200')); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'body'); ?>
        <?php echo $form->textArea($model, 'body', array('class' => 'width500 height100 form-control')); ?>
        <div class="clear"></div>
        <?php echo $form->error($model, 'body'); ?>
    </div>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

        ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->