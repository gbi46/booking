<?php
$this->pageTitle = Yii::app()->name . ' - ' . SocialauthModule::t('Manage social settings');

$this->breadcrumbs = array(
    SocialauthModule::t('Social settings') => array('/socialauth/backend/main'),
    SocialauthModule::t('Update {name}', array('{name}' => $model->title)),
);

$this->adminTitle = SocialauthModule::t('Update param "{name}"', array('{name}' => $model->title));

if ($ajax) {

    ?>
    <script>$(".modal-header h3").html("<?php echo CHtml::encode($this->adminTitle); ?>")</script>
<?php } ?>

<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => true,
        'htmlOptions' => array('class' => 'white_noborder well form-disable-button-after-submit')
    ));

    ?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <input type="hidden" name="config_id" id="config_id" value="<?php echo $model->id; ?>">

    <div class="form-group">
        <?php echo $form->labelEx($model, 'value'); ?>
        <?php echo $form->textArea($model, 'value', array('class' => 'width450 form-control', 'id' => 'config_value')); ?>
        <?php echo $form->error($model, 'value'); ?>
    </div>

    <?php if (!$ajax) { ?>
        <div class="form-group buttons">
            <?php
            echo AdminLteHelper::getSubmitButton(tc('Save'));

            ?>
        </div>
    <?php } ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->

