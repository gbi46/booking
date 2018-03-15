<?php
$widgetsSubTitles = InfoPages::getWidgetOptions(null, true, true);
$widgetsSubTitlesJS = CJSON::encode($widgetsSubTitles);

?>
<div class="form">
    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => 'InfoPages-form',
        'enableClientValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <p class="note">
        <?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?>
    </p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'active'); ?>
    <?php
    echo $form->dropDownList($model, 'active', array(
        InfoPages::STATUS_ACTIVE => tc('Active'),
        InfoPages::STATUS_INACTIVE => tc('Inactive'),
        ), array('class' => 'width150'));

    ?>
    <?php echo $form->error($model, 'active'); ?>
    <br />

    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'title',
        'type' => 'string'
    ));

    ?>
    <div class="clear"></div>

    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'body',
        'type' => 'text-editor'
    ));

    ?>
    <div class="clear"></div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'widget'); ?>
        <?php echo $form->dropDownList($model, 'widget', InfoPages::getWidgetOptions()); ?>
        <?php echo $form->error($model, 'widget'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'widget_position'); ?>
        <?php echo $form->dropDownList($model, 'widget_position', InfoPages::getPositionList()); ?>
        <?php echo $form->error($model, 'widget_position'); ?>
    </div>

    <div class="form-group widget-custom-titles-block" id="apartmentsSubTitle-block">
        <?php InfoPages::generateWidgetTitlesTabs($model, 'apartmentsSubTitle'); ?>
    </div>

    <div class="form-group widget-custom-titles-block" id="summaryCitiesSubTitle-block">
        <?php InfoPages::generateWidgetTitlesTabs($model, 'summaryCitiesSubTitle'); ?>
    </div>

    <div class="form-group widget-custom-titles-block" id="entriesSubTitle-block">
        <?php InfoPages::generateWidgetTitlesTabs($model, 'entriesSubTitle'); ?>
    </div>

    <div class="form-group widget-custom-titles-block" id="contactformSubTitle-block">
        <?php InfoPages::generateWidgetTitlesTabs($model, 'contactformSubTitle'); ?>
    </div>

    <?php echo $this->renderPartial('_form_apartments_filter', array('addedFields' => $addedFields, 'model' => $model)); ?>
    <?php echo $this->renderPartial('_form_seosummatiescities_filter', array('model' => $model)); ?>
    <?php echo $this->renderPartial('_form_entries_filter'); ?>

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
            'canUseDirectUrl' => true,
        ));
    }

    ?>
</div><!-- form -->

<script type="text/javascript">
    var widgetsSubTitlesJS = $.parseJSON('<?php echo $widgetsSubTitlesJS; ?>');

    $(function () {
        checkWidget();
        showWidgetSubTitles();

        $('#InfoPages_widget').change(function () {
            checkWidget();
            showWidgetSubTitles();
        });
    });

    function hideAllFilters() {
        $('#apartments_filter').hide();
        $('#seosummariescities_filter').hide();
        $('#entries_filter').hide();
    }

    function checkWidget() {
        hideAllFilters();

        var el = $('#InfoPages_widget');
        if (el.val() == 'apartments' || el.val() == 'seosummaryinfo') {
            if (el.val() == 'seosummaryinfo') {
                $('#seosummariescities_filter').show();
            }
            $('#apartments_filter').show();
        } else if (el.val() == 'seosummarycities') {
            $('#seosummariescities_filter').show();
        } else if (el.val() == 'entries') {
            $('#entries_filter').show();
        }
    }

    function hideAllSubTitles() {
        $('.widget-custom-titles-block').hide();
    }

    function showWidgetSubTitles() {
        hideAllSubTitles();

        var el = $('#InfoPages_widget');
        var el_selected_val = $('#InfoPages_widget').val();

        if (el && (el_selected_val in widgetsSubTitlesJS) && typeof widgetsSubTitlesJS[el_selected_val] !== 'undefined') {
            var widgetSubTitles = widgetsSubTitlesJS[el_selected_val];

            if (typeof widgetSubTitles !== 'undefined' && widgetSubTitles && typeof widgetSubTitles === 'object') {
                $.each(widgetSubTitles, function (index, value) {
                    $('#' + index + '-block').show();
                });
            }
        }
    }
</script>