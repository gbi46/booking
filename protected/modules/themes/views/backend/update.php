<?php
$this->pageTitle = Yii::app()->name . ' - ' . tt('Edit theme');

$this->menu = array(
    //array('label' => tt('Manage themes'), 'url' => array('admin')),
    AdminLteHelper::getBackMenuLink(tt('Manage themes'), array('admin')),
);
$this->adminTitle = tt('Edit theme') . ' "' . ucfirst($model->title) . '"';

?>

<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => 'Slider-form',
        'enableClientValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'additional_view'); ?>
        <?php echo $form->dropDownList($model, 'additional_view', Themes::getAdditionalViewList(), array('style' => 'width: 400px;')); ?>
        <div class="padding-bottom10">
            <span class="label label-info">
                <?php echo Yii::t('module_themes', 'additional_map_help'); ?>
            </span>
        </div>
        <?php echo $form->error($model, 'additional_view'); ?>
    </div><br />

    <div class="form-group">
        <?php echo $form->labelEx($model, 'color_theme'); ?>
        <?php echo $form->dropDownList($model, 'color_theme', Themes::getColorThemesList(), array('style' => 'width: 400px;')); ?>
        <?php echo $form->error($model, 'color_theme'); ?>
    </div><br />

    <div class="form-group">
        <?php echo $form->labelEx($model, 'upload_img'); ?>
        <div class="padding-bottom10">
            <span class="label label-info">
                <?php echo Yii::t('module_slider', 'Supported file: {supportExt}.', array('{supportExt}' => $model->supportExt)); ?>
            </span>
        </div>
        <?php echo $form->fileField($model, 'upload_img'); ?>
        <?php echo $form->error($model, 'upload_img'); ?>
    </div><br />

    <?php
    $bgUrl = Themes::getBgUrl($model->bg_image);
    if ($bgUrl) {
        echo CHtml::image($bgUrl, '', array('class' => 'width200'));
        echo '&nbsp;&nbsp;' . CHtml::link(tc('Delete'), array('deleteImg', 'id' => $model->id), array('class' => 'btn btn-mini'));
    }

    ?>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

        ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->



