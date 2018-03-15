<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => 'Service-form',
        'enableClientValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>
    <!--<p class="note">
    <?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?>
    </p>-->

    <?php echo $form->errorSummary($model); ?>

    <!--<div class="form-group padding-bottom10">-->
    <div class="form-group">
        <?php echo $form->checkboxControlGroup($model, 'is_offline'); ?>
        <?php echo $form->error($model, 'is_offline'); ?>
    </div>
    <!--</div>-->

    <div class="form-group">
        <?php echo $form->labelEx($model, 'allow_ip'); ?>
        <?php echo '<div class="padding-bottom10"><sub>' . tt("Through_comma") . '</sub></div>'; ?>
        <?php echo $form->textField($model, 'allow_ip', array('size' => 100)); ?>
        <?php echo $form->error($model, 'allow_ip'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'page'); ?>
        <?php
        $this->widget('CustomEditor', array(
            'model' => $model,
            'attribute' => 'page',
            'htmlOptions' => array('id' => 'page')
        ));

        ?>
        <?php echo $form->error($model, 'page'); ?>
    </div>

    <?php
    echo AdminLteHelper::getSubmitButton(tc('Save'));

    ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->