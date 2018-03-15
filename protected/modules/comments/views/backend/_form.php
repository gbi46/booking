<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
    ));

    if (!Yii::app()->user->checkAccess('backend_access') && !Yii::app()->user->isGuest) {
        $model->name = Yii::app()->user->username;
        $model->email = Yii::app()->user->email;
    }

    ?>
    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php
    if ($model->owner_id && $model->user) {
        echo Yii::t('module_comments', 'Name') . ': ' . $model->getUser();
    } else {
        echo '<div class="form-group">';
        echo $form->labelEx($model, 'user_name');
        echo $form->textField($model, 'user_name', array('size' => 60, 'maxlength' => 128, 'class' => 'width500'));
        echo $form->error($model, 'user_name');
        echo '</div>';

        echo '<div class="form-group">';
        echo $form->labelEx($model, 'user_email');
        echo $form->textField($model, 'user_email', array('size' => 60, 'maxlength' => 128, 'class' => 'width500'));
        echo $form->error($model, 'user_email');
        echo '</div>';
    }

    ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'body'); ?>
        <?php echo $form->textArea($model, 'body', array('class' => 'width500 height100 form-control')); ?>
        <?php echo $form->error($model, 'body'); ?>
    </div>

    <?php if ($model->rating > -1) { ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'rating'); ?>
            <br />
            <?php
            $this->widget(
                'CStarRating', array(
                'name' => 'Comment[rating]',
                'value' => $model->rating,
                'resetText' => tt('Remove the rate', 'comments'),
                'minRating' => Comment::MIN_RATING,
                'maxRating' => Comment::MAX_RATING,
                )
            );

            ?>
            <?php echo $form->error($model, 'rating'); ?>
        </div>
    <?php } ?>

    <div class="clear">&nbsp;</div>

    <?php
    echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

    ?>
    <?php $this->endWidget(); ?>

</div><!-- form -->