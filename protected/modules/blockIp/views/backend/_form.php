<div class="form">
    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableClientValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'ip'); ?>
        <?php echo $form->textField($model, 'ip', array('size' => 10)); ?>
        <?php echo $form->error($model, 'ip'); ?>
    </div>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton(tc('Save'));

        ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->