<?php if ($showWidgetTitle):?>
	<h2><?php echo $customWidgetTitle; ?></h2>
<?php endif;?>
	
<?php
	if(!Yii::app()->user->isGuest){
		if(!$model->name)
			$model->name = Yii::app()->user->username;
		if(!$model->phone)
			$model->phone = Yii::app()->user->phone;
		if(!$model->email)
			$model->email = Yii::app()->user->email;
	}

	if(param('adminPhone')){
		echo '<p>'.tt('Phone', 'contactform').': '.param('adminPhone').'</p>';
	}
	if(param('adminSkype')){
		$lenght = utf8_strlen(param('adminSkype'));
		$k = 15;

		if ($lenght < 5)
			$k = 25;
		if ($lenght > 10)
			$k = 11;
		if ($lenght > 20)
			$k = 10;

		$left = $lenght * $k;
		echo '<p>'.tt('Skype', 'contactform').': '.param('adminSkype').'</p>';
	}
	if(param('adminICQ')){
		echo '<p>'.tt('ICQ', 'contactform').': '.param('adminICQ').'</p>';
	}
	if(param('adminAddress')){
		echo '<p>'.tt('Address', 'contactform').': '.param('adminAddress').'</p>';
	}
?>

<div class="form">
<?php $form=$this->beginWidget('CustomActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>false,
	'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
));
?>
	<p>
		<?php echo tt('You can fill out the form below to contact us.', 'contactform'); ?>
	</p>

	<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name', array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email', array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone', array('size'=>60,'maxlength'=>128, 'class' => 'width240')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>3, 'cols'=>50, 'class' => 'contact-textarea')); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<?php
	if (Yii::app()->user->isGuest){
	?>
		<div class="row">
			<?php echo $form->labelEx($model, 'verifyCode');?>
			<?php $display = (param('useReCaptcha', 0)) ? 'none;' : 'block;'?>
			<?php echo $form->textField($model, 'verifyCode', array('autocomplete' => 'off', 'style' => "display: {$display}"));?><br/>
			<?php echo $form->error($model, 'verifyCode');?>
			<?php
			$cAction = '/infopages/main/captcha';
			if($this->page == 'index'){
				$cAction = '/site/captcha';
			} elseif ($this->page == 'contactForm'){
				$cAction = '/contactform/main/captcha';
			}
			$this->widget('CustomCaptchaFactory',
				array(
					'captchaAction' => $cAction, 
					'buttonOptions' => array('class' => 'get-new-ver-code'),
					'clickableImage' => true,
					'imageOptions'=>array('id'=>'contactform_captcha'),
					'model' => $model,
					'attribute' => 'verifyCode',
				)
			);?>
			<br/>
		</div>
	<?php
	}
	?>

	<div class="row buttons">
		<div class="block-afree-to-user-afreement">
			<?php echo Yii::t('common', 'By clicking "{buttonName}", you agree to our <a href="{licenceUrl}" target="_blank">User agreement</a>', array('{buttonName}' => tt('Send message', 'contactform'), '{licenceUrl}' => InfoPages::getUrlById(InfoPages::LICENCE_PAGE_ID)));?>
		</div>
		<?php echo CHtml::submitButton(tt('Send message', 'contactform'), array('class' => 'submit-button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
