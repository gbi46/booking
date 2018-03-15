
<?php ?>
<div class="form">
    <?php
    $this->adminTitle = tt("Change admin password");
    $this->pageTitle = Yii::app()->name . ' - ' . tt("Change admin password");
    $this->menu = array(
        array(),
    );

    $model->scenario = 'changeAdminPass';
    $model->password = '';
    $model->password_repeat = '';

    $form = $this->beginWidget('CustomForm', array(
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>
    <div class="form-group">&nbsp;</div>
    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'old_password'); ?>
        <?php echo $form->passwordField($model, 'old_password', array('size' => 20, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'old_password'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('size' => 20, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'password_repeat'); ?>
        <?php echo $form->passwordField($model, 'password_repeat', array('size' => 20, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'password_repeat'); ?>
    </div>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton(tc('Change'));

        ?>
    </div>

    <?php $this->endWidget(); ?>

</div>