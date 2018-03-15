<?php
$this->pageTitle = Yii::app()->name . ' - ' . tc('Manage settings');
$this->breadcrumbs = array(
    tc('Settings'),
);
$this->menu = array(
    array(),
);
$this->adminTitle = tc('Manage settings');

$this->widget('CustomGridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader(); showHideAdditionalSettingsBlock($("select[name=\'ConfigurationModel[section]\'] option:selected").val())}',
    'id' => 'config-table',
    'columns' => array(
        array(
            'header' => tt('Section'),
            'name' => 'section',
            'value' => 'tt($data->section)',
            'filter' => $this->getSections(false),
            'htmlOptions' => array(
                'data-title' => tt('Section'),
            ),
        ),
        array(
            'header' => tt('Setting'),
            'value' => '$data->title',
            'type' => 'raw',
            'htmlOptions' => array(
                'class' => 'width250',
                'data-title' => tt('Setting'),
            ),
        ),
        array(
            'header' => tt('Value'),
            'name' => 'value',
            'type' => 'raw',
            'value' => 'ConfigurationModel::getAdminValue($data)',
            'htmlOptions' => array(
                'class' => 'width150',
                'data-title' => tt('Value'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'htmlOptions' => array('class' => 'width50 button_column_actions'),
            'template' => '{update}',
            'buttons' => array(
                'update' => array(
                    'visible' => 'ConfigurationModel::getVisible($data->type)',
                    /* 'options' => array('data-toggle' => 'modal'), */
                    'click' => 'js: function() { updateConfig($(this).attr("href")); return false; }',
                ),
            ),
        ),
    ),
));

?>

<div class="additinal-settings-block well settings-mail">
    <div class="form-group">
        <h2><?php echo tt('Testing settings', 'configuration'); ?></h2>
    </div>
    <div class="form-group">
        <?php echo tt('To email', 'configuration'); ?>:&nbsp;
        <input type="text" value="" id="toEmail" name="toEmail" class="span3"/>
    </div>
    <div class="form-group">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => tt('Send', 'messages'),
            'htmlOptions' => array(
                'onclick' => "sendTestEmail(); return false;",
            )
        ));

        ?>
    </div>
</div>


<?php
$this->widget('bootstrap.widgets.BsModal', array(
    'id' => 'myModal',
    'content' => '<div id="form_param"></div>',
    /* 'footer' => '<a href="#" class="btn btn-primary" onclick="saveChanges(this); return false;">'.tc('Save').'</a>'.
      '<a href="#" class="btn btn-default" onclick="return false;", data-dismiss = "modal">'.tc('Close').'</a>' */
    'footer' => AdminLteHelper::getButton(tc('Save'), 'fa fa-check', array(
        'onclick' => 'saveChanges(this); return false;',
        'class' => 'btn btn-primary',
        ), true) .
    AdminLteHelper::getButton(tc('Close'), 'fa fa-close', array(
        'data-dismiss' => 'modal',
        'class' => 'btn btn-default',
    )),
));

?>


<script type="text/javascript">
    function sendTestEmail() {
        if ($("#toEmail").val().length) {
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('/configuration/backend/main/sendTestEmail'); ?>',
                dataType: 'json',
                data: {"toEmail": $("#toEmail").val()},
                success: function (data) {
                    if (data.result == 'passed') {
                        message(data.message);
                    } else {
                        error(data.message);
                    }
                }
            });
        } else {
            error('<?php echo tc('Enter the required value'); ?>');
        }
    }

    function hideAllAdditionalSettingsBlocks() {
        $(".additinal-settings-block").hide();
    }

    function showHideAdditionalSettingsBlock(currentSection) {
        hideAllAdditionalSettingsBlocks();

        $(".settings-" + currentSection).show();
    }

    function updateConfig(href) {
        $('#myModal').modal('show');
        $('#form_param').html('<img src="<?php echo Yii::app()->theme->baseUrl . "/images/pages/indicator.gif"; ?>" alt="<?php echo tc('Content is loading ...'); ?>" style="position:absolute;margin: 10px;">');
        $('#form_param').load(href + '&ajax=1');
    }

    function saveChanges(elem) {
        var val = $('#config_value').val();
        var required = $('#config_required').val();

        if (!val && required) {
            alert('<?php echo tt('Enter the required value'); ?>');
            return false;
        }

        var id = $('#config_id').val();

        var l = Ladda.create(elem);
        l.start();

        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->request->baseUrl . '/configuration/backend/main/updateAjax'; ?>",
            data: {"id": id, "val": val},
            success: function (msg) {
                $('#config-table').yiiGridView.update('config-table');
                $('#myModal').modal('hide');
                l.stop();
                if (msg == 'error_save') {
                    document.location.href = '<?php echo Yii::app()->createUrl("/configuration/backend/main/admin"); ?>';
                }
            }
        });
    }

</script>