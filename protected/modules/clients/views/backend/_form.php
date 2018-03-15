<div class="form">
    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <?php if (!$model->isNewRecord) { ?>
        <p>
            <strong><?php echo tt('Client ID', 'clients'); ?></strong>: <?php echo $model->id; ?>
        </p>
    <?php } ?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'state'); ?>
        <?php
        echo $form->dropDownList(
            $model, 'state', Clients::getClientsStatesArray(), array('class' => 'form-control')
        );

        ?>
        <?php echo $form->error($model, 'state'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'contract_number'); ?>
        <?php echo $form->textField($model, 'contract_number', array('class' => 'form-control ', 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'contract_number'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'first_name'); ?>
        <?php echo $form->textField($model, 'first_name', array('class' => 'form-control', 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'first_name'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'second_name'); ?>
        <?php echo $form->textField($model, 'second_name', array('class' => 'form-control', 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'second_name'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'middle_name'); ?>
        <?php echo $form->textField($model, 'middle_name', array('class' => 'form-control', 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'middle_name'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'birthdate'); ?>
        <?php echo $form->textField($model, 'birthdate', array('class' => 'form-control', 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'birthdate'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'phone'); ?>
        <?php echo $form->textField($model, 'phone', array('class' => 'form-control', 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'phone'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'additional_phone'); ?>
        <?php echo $form->textField($model, 'additional_phone', array('class' => 'form-control', 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'additional_phone'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'acts'); ?>
        <?php echo $form->textArea($model, 'acts', array('class' => 'width500 height100 form-control')); ?>
        <?php echo $form->error($model, 'acts'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'additional_info'); ?>
        <?php echo $form->textArea($model, 'additional_info', array('class' => 'width500 height100 form-control')); ?>
        <?php echo $form->error($model, 'additional_info'); ?>
    </div>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : Yii::t('common', 'Save'));

        ?>
    </div>
    <?php $this->endWidget(); ?><!-- form -->
</div>