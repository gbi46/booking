<div class="row">
    <?php echo $form->labelEx($model, 'username'); ?>
    <?php echo $form->textField($model, 'username', array('autocomplete' => 'off')); ?>
    <?php echo $form->error($model, 'username'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'password'); ?>
    <?php echo $form->passwordField($model, 'password', array('autocomplete' => 'off')); ?>
    <?php echo $form->error($model, 'password'); ?>
</div>

<?php if ($model->scenario == 'withCaptcha'): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'verifyCode'); ?>
        <?php $display = (param('useReCaptcha', 0)) ? 'none;' : 'block;' ?>
        <?php echo $form->textField($model, 'verifyCode', array('autocomplete' => 'off', 'style' => "display: {$display}")); ?><br/>
        <?php echo $form->error($model, 'verifyCode'); ?>
        <?php
        $this->widget('CustomCaptchaFactory', array(
            'captchaAction' => '/guestad/main/captcha',
            'buttonOptions' => array('class' => 'get-new-ver-code'),
            'clickableImage' => true,
            'imageOptions' => array('id' => 'login_guestad_captcha'),
            'model' => $model,
            'attribute' => 'verifyCode',
            )
        );

        ?><br/>
    </div>
<?php endif; ?>