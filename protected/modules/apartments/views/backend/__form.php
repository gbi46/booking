<?php $this->renderPartial('//../modules/apartments/views/backend/__form_general', array('model' => $model, 'form' => $form, 'seasonalPricesModel' => $seasonalPricesModel)); ?>

<div class="tab-pane" id="tab-extended">
    <?php
    if (empty($model->is_free_to) || is_null($model->is_free_to)) {
        $model->is_free_to = '';
    }

    if (Yii::app()->user->checkAccess('backend_access')) {

        ?>
        <div class="form-group">
            <?php echo $form->checkboxControlGroup($model, 'is_special_offer'); ?>
        </div>
        <?php
    }

    if (Yii::app()->user->checkAccess('backend_access')) {

        ?>
        <div class="special-calendar">
            <?php echo $form->labelEx($model, 'is_free_to', array('class' => 'noblock')); ?><br/>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'is_free_to',
                'language' => Yii::app()->controller->datePickerLang,
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'minDate' => 'new Date()',
                ),
                'htmlOptions' => array(
                    'class' => 'width100 eval_period'
                ),
            ));

            ?>
            <?php echo $form->error($model, 'is_free_to'); ?>
        </div>

        <?php
    }

    if (!isset($element)) {
        $element = 0;
    }

    if (issetModule('bookingcalendar') && $model->allowShowBookingCalendar()) {
        $this->renderPartial('//modules/bookingcalendar/views/_form', array('apartment' => $model, 'element' => $element));
    }

    $rows = HFormEditor::getExtendedFields();
    HFormEditor::renderFormRows($rows, $model, $form);

    ?>
</div>

<?php
echo '<div class="form-group button-save-row">';
if (param('stepByStepAd', 0)) {
    echo CHtml::link(tc('Previous'), 'javascript:;', array(
        'class' => 'btn btn-primary button-blue btnPrevious',
    ));
    echo '&nbsp;&nbsp;';
    echo CHtml::link(tc('Next'), 'javascript:;', array(
        'class' => 'btn btn-primary button-blue btnNext',
    ));
    echo '&nbsp;&nbsp;&nbsp;';
    HApartment::renderSaveButton($model, true);
} else {
    HApartment::renderSaveButton($model);
}
echo '</div>';

$idTabs = param('useBootstrap') ? '#myTabs' : '#tabs ul';
$activeTabClass = param('useBootstrap') ? 'active' : 'ui-state-active';

?>

<?php if (param('stepByStepAd', 0)) { ?>
    <script type="text/javascript">
        var currentTab = 0;
        var countTab = <?php echo (in_array($model->type, array(Apartment::TYPE_RENTING, Apartment::TYPE_BUY))) ? 1 : 3; ?>;
        var idTabs = '<?php echo $idTabs ?>';
        var activeTabClass = '<?php echo $activeTabClass ?>';
        var IS_DRAFT = '<?php echo $model->active == Apartment::STATUS_DRAFT ? 1 : 0; ?>';

        $('.btnNext').click(function () {
            $(idTabs + ' li.' + activeTabClass).next('li').find('a').trigger('click');
            checkLast();
        });

        $('.btnPrevious').click(function () {
            $(idTabs + ' li.' + activeTabClass).prev('li').find('a').trigger('click');
            checkLast();
        });

        function checkLast() {
            $active = $(idTabs + ' li.' + activeTabClass);
            if ($active.is(':last-child')) {
                $('#save_button, #save_button2').show();
                $('.btnNext').hide();
            } else {
                $('#save_button, #save_button2').hide();
                $('.btnNext').show();
            }

            if ($active.is(':first-child')) {
                $('.btnPrevious').hide();
            } else {
                $('.btnPrevious').show();
            }

            if (IS_DRAFT == 0) {
                $('#save_button, #save_button2').css('display', 'inline-block');
            }
        }

        $(function () {
            setTimeout(checkLast, 500);

            $(idTabs + ' li').on('click', function () {
                setTimeout(checkLast, 500);
            });
        });
    </script>
<?php } ?>


