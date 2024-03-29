<?php
$this->pageTitle .= ' - '. tt('do_complain', 'apartmentsComplain');
$this->breadcrumbs=array(
	Yii::t('common', 'Apartment search') => array('/quicksearch/main/mainsearch'),
	truncateText(CHtml::encode($modelApartment->getStrByLang('title')), 8) => $modelApartment->getUrl(),
	tt('do_complain', 'apartmentsComplain'),
);
?>

<?php
if (!Yii::app()->user->isGuest) {
	if (!$model->name) {
		$model->name = Yii::app()->user->username;
	}
	if (!$model->email) {
		$model->email = Yii::app()->user->email;
	}
}
?>

<div class="form min-fancy-width <?php echo (isset($isFancy) && $isFancy) ? 'white-popup-block' : ''; ?>">
	<?php $form = $this->beginWidget('CustomActiveForm', array(
		'action' => Yii::app()->controller->createUrl('/apartmentsComplain/main/complain', array('id' => $apId)),
		'id'=>$this->modelName.'-form',
		'enableAjaxValidation'=>false,
		'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
	));
	?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
		<?php echo $form->labelEx($model, 'complain_id'); ?>
		<?php echo $form->dropDownList($model,'complain_id', ApartmentsComplainReason::getAllReasons(), array('class' => 'width300')); ?>
		<?php echo $form->error($model, 'complain_id'); ?>
    </div>

    <div class="row">
		<?php echo $form->labelEx($model, 'body'); ?>
		<?php echo $form->textArea($model, 'body', array('rows' => 3, 'cols' => 50, 'class' => 'width240')); ?>
		<?php echo $form->error($model, 'body'); ?>
    </div>

    <div class="row">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 128, 'class' => 'width240')); ?>
		<?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
		<?php echo $form->labelEx($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128, 'class' => 'width240')); ?>
		<?php echo $form->error($model, 'email'); ?>
    </div>

	<?php if (Yii::app()->user->isGuest) : ?>
    <div class="row">
		<?php echo $form->labelEx($model, 'verifyCode');?>
		<?php $display = (param('useReCaptcha', 0)) ? 'none;' : 'block;'?>
		<?php echo $form->textField($model, 'verifyCode', array('autocomplete' => 'off', 'style' => "display: {$display}"));?><br/>
		<?php echo $form->error($model, 'verifyCode');?>
		<?php
		$cAction = '/apartmentsComplain/main/captcha';
		$this->widget('CustomCaptchaFactory',
			array(
				'captchaAction' => $cAction, 
				'buttonOptions' => array('class' => 'get-new-ver-code'), 
				'imageOptions'=>array('id'=>'complain_captcha'),
				'clickableImage' => true,
				'model' => $model,
				'attribute' => 'verifyCode',
			)
		);?>
        <br/>
    </div>
	<?php endif; ?>

    <div class="row buttons">
		<div class="block-afree-to-user-afreement">
			<?php echo Yii::t('common', 'By clicking "{buttonName}", you agree to our <a href="{licenceUrl}" target="_blank">User agreement</a>', array('{buttonName}' => tt('Send complain', 'apartmentsComplain'), '{licenceUrl}' => InfoPages::getUrlById(InfoPages::LICENCE_PAGE_ID)));?>
		</div>
		<?php echo CHtml::submitButton(tt('Send complain', 'apartmentsComplain'), array('class' => 'submit-button')); ?>
    </div>

	<?php $this->endWidget(); ?>

</div>