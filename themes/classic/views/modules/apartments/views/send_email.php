<?php
/**
 * @var $apartment Apartment
 */
?>
<?php
$this->pageTitle .= ' - '.tt("Message for the listing's owner №", 'notifier'). ' '. $apartment->id;

$this->breadcrumbs=array(
	Yii::t('common', 'Apartment search') => array('/quicksearch/main/mainsearch'),
	truncateText(CHtml::encode($apartment->getStrByLang('title')), 8) => $apartment->getUrl(),
	tt("Message for the listing's owner №", 'notifier'). ' '. $apartment->id,
);
?>

<div class="form min-fancy-width <?php echo (isset($isFancy) && $isFancy) ? 'white-popup-block' : ''; ?>">
	<h2><?php echo tt("Message for the listing's owner №", 'notifier'). ' '. CHtml::link($apartment->id, $apartment->getUrl()); ?></h2>

	<?php
		if(!Yii::app()->user->isGuest){
			if(!$model->senderName)
				$model->senderName = Yii::app()->user->username;
			if(!$model->senderPhone)
				$model->senderPhone = Yii::app()->user->phone;
			if(!$model->senderEmail)
				$model->senderEmail = Yii::app()->user->email;
		}
	?>

	<div class="form">
		<?php $form=$this->beginWidget('CustomActiveForm', array(
		'action' => Yii::app()->controller->createUrl('/apartments/main/sendEmail', array('id' => $apartment->id)),
		'id'=>'contact-form',
		'enableClientValidation'=>false,
		'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
	));
		?>

		<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'senderName'); ?>
			<?php echo $form->textField($model,'senderName', array('size' => 60,'maxlength' => 128, 'class' => 'width240')); ?>
			<?php echo $form->error($model,'senderName'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'senderPhone'); ?>
			<?php echo $form->textField($model,'senderPhone', array('size' => 60,'maxlength' => 128, 'class' => 'width240')); ?>
			<?php echo $form->error($model,'senderPhone'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'senderEmail'); ?>
			<?php echo $form->textField($model,'senderEmail', array('size' => 60,'maxlength' => 128, 'class' => 'width240')); ?>
			<?php echo $form->error($model,'senderEmail'); ?>
		</div>

		<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
			<?php echo $form->textArea($model,'body',array('rows' => 3, 'cols' => 50, 'class' => 'contact-textarea')); ?>
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
						$this->widget('CustomCaptchaFactory',
							array(
								'captchaAction' => '/apartments/main/captcha', 
								'buttonOptions' => array('class' => 'get-new-ver-code'),
								'imageOptions'=>array('id'=>'send_email_captcha'),
								'clickableImage' => true,
								'model' => $model,
								'attribute' => 'verifyCode',
							)
						);
					?>
					<br />
				</div>
				<?php
			}
		?>

		<div class="row buttons">
			<div class="block-afree-to-user-afreement">
				<?php echo Yii::t('common', 'By clicking "{buttonName}", you agree to our <a href="{licenceUrl}" target="_blank">User agreement</a>', array('{buttonName}' => tt('send_request', 'apartments'), '{licenceUrl}' => InfoPages::getUrlById(InfoPages::LICENCE_PAGE_ID)));?>
			</div>
			<?php echo CHtml::submitButton(tt('send_request', 'apartments'), array('class' => 'submit-button')); ?>
		</div>

		<?php $this->endWidget(); ?>
	</div>
</div>