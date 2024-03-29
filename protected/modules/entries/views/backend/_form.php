<div class="form">
    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => 'Entries-form',
        'enableClientValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'well form-disable-button-after-submit'),
    ));

    ?>
    <p class="note">
        <?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?>
    </p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'active'); ?>
    <?php
    echo $form->dropDownList($model, 'active', array(
        Entries::STATUS_ACTIVE => tc('Active'),
        Entries::STATUS_INACTIVE => tc('Inactive'),
        ), array('class' => 'width150'));

    ?>
    <?php echo $form->error($model, 'active'); ?>
    <br />

    <div class="form-group">
        <?php echo $form->labelEx($model, 'category_id'); ?>
        <?php echo $form->dropDownList($model, 'category_id', EntriesCategory::getAllCategories(), array('class' => 'width150')); ?>
        <?php echo $form->error($model, 'category_id'); ?>

        <?php
        echo CHtml::link(tt('Categories of entries', 'entries'), array('/entries/backend/category/admin'));

        ?>
    </div>

    <br />
    <div>
        <?php
        if ($model->image) {
            $src = $model->image->getSmallThumbLink();
            if ($src) {
                echo CHtml::link(CHtml::image($src, $model->getStrByLang('title')), $model->image->fullHref(), array('class' => 'fancy'));

                if (issetModule('seo') && !$model->isNewRecord) {
                    $this->widget('application.modules.seo.components.SeoImageWidget', array(
                        'model' => $model->image,
                        'showLink' => true,
                        'showForm' => false,
                        'showJS' => false,
                    ));
                }

                echo '<div style="padding-top: 3px;">' . CHtml::button(tc('Delete'), array(
                    'onclick' => 'document.location.href="' . $this->createUrl('deleteimg', array('id' => $model->id, 'imId' => $model->image->id)) . '";'
                )) . '</div>';
            }

            echo '
					<div class="clear"></div>
					<br />
				';
        }

        ?>

        <?php echo $form->fileFieldControlGroup($model, 'entriesImage', array()); ?>
        <div class="padding-bottom10">
            <span class="label label-info">
                <?php echo Yii::t('module_apartments', 'Supported file: {supportExt}.', array('{supportExt}' => $model->supportedExt)); ?>
            </span>
        </div>
    </div>
    <br />

    <div class="clear"></div>
    <br />
    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'title',
        'type' => 'string'
    ));

    ?>

    <div class="clear"></div>
    <br />
    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'announce',
        'type' => 'text-editor'
    ));

    ?>

    <div class="clear"></div>
    <br />
    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'body',
        'type' => 'text-editor'
    ));

    ?>
    <div class="clear"></div>
    <br />

    <div class="form-group">
        <?php echo $form->labelEx($model, 'tags'); ?>
        <?php
        $this->widget('CAutoComplete', array(
            'model' => $model,
            'attribute' => 'tags',
            'url' => array('/entries/backend/main/suggestTags'),
            'multiple' => true,
            'htmlOptions' => array('size' => 50),
        ));

        ?>
        <div><span class="label label-info"><?php echo tt('Please separate different tags with commas.', 'entries'); ?></span></div>
        <?php echo $form->error($model, 'tags'); ?>
    </div>
    <div class="clear"></div>
    <br />

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton(tc('Save'), array(), true, 'fa fa-check-circle-o') . ' ';
        echo AdminLteHelper::getSubmitButton(tc('Save and close'), array('name' => 'save_close_btn'));

        ?>
    </div>

    <?php $this->endWidget(); ?>

    <div class="clear"></div>
    <?php
    if (issetModule('seo') && !$model->isNewRecord) {
        $this->widget('application.modules.seo.components.SeoWidget', array(
            'model' => $model,
        ));
    }

    if (issetModule('seo') && !$model->isNewRecord) {
        $this->widget('application.modules.seo.components.SeoImageWidget', array(
            'model' => $model->image,
            'showLink' => false,
            'showForm' => true,
            'showJS' => true,
        ));
    }

    ?>
</div><!-- form -->