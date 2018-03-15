<?php
$this->pageTitle = Yii::app()->name . ' - ' . tc('Manage settings');

$this->breadcrumbs = array(
);

$this->adminTitle = Yii::t('common', 'Update param "{name}"', array('{name}' => $model->title));

$required = true;
if ($model->allowEmpty)
    $required = false;

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
    <input type="hidden" id="config_required" value="<?php echo $required ?>">

    <?php
    echo '<div class="form-group">';
    echo CHtml::activeLabel($model, 'value', array('required' => $required));
    if ($model->type == 'enum' && $list = ConfigurationModel::getEnumListForKey($model->name)) {
        echo $form->dropDownList($model, 'value', $list, array('class' => 'width450 form-control', 'id' => 'config_value'));
    } else {
        echo $form->textArea($model, 'value', array('class' => 'width450 form-control', 'id' => 'config_value'));
    }
    echo $form->error($model, 'value');
    echo '</div>';

    ?>

    <?php if (!$ajax) { ?>
        <div class="form-group buttons">
            <?php
            echo AdminLteHelper::getSubmitButton(tc('Save'));

            ?>
        </div>
    <?php } ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->

