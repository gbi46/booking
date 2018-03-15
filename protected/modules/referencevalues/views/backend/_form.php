<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));
    echo CHtml::hiddenField('addMore', 0, array('id' => 'addMore'));

    ?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'reference_category_id'); ?>
        <?php echo $form->dropDownList($model, 'reference_category_id', $this->getCategories(1)); ?>
        <?php echo $form->error($model, 'reference_category_id'); ?>
    </div>

    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'title',
        'type' => 'string'
    ));

    ?>

    <div class="clear"></div>

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

    <div class="clear"></div>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

        ?>
        <?php
        if ($model->isNewRecord) {
            echo AdminLteHelper::getSubmitButton(tc('Add and continue'), array('onclick' => '$("#addMore").val(1)'));
        }

        ?>
   	</div>

    <?php $this->endWidget(); ?>

</div><!-- form -->