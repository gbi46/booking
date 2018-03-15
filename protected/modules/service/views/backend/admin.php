<?php
$this->pageTitle = Yii::app()->name . ' - ' . tt('Site service', 'common');
$this->adminTitle = tt('Site service', 'common');
$this->menu = array(
    array(),
);

?>
<div class="form well form-vertical">
    <div class="row-fluid">
        <div id="result"></div>
    </div>

    <div class="row-fluid">
        <div class="span8">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'link',
                //'type'=>'warning',
                'type' => 'warning',
                'icon' => 'repeat white',
                'label' => '<span class="fa fa-folder-open"></span> &nbsp; ' . CHtml::encode(tt('Clear assets', 'service')),
                'encodeLabel' => false,
                'htmlOptions' => array('class' => 'confirm-reset', 'value' => 'assets')
            ));

            ?>&nbsp;
            <?php
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'link',
                'type' => 'inverse',
                //'icon'=>'off white',
                'icon' => 'repeat white',
                'label' => '<span class="fa fa-bolt"></span> &nbsp; ' . tt('Clear runtime', 'service'),
                'encodeLabel' => false,
                'htmlOptions' => array('class' => 'confirm-reset', 'value' => 'runtime')
            ));

            ?>
        </div>
    </div>
</div>

<div class="clear"></div>

<div class="form well form-vertical">	
    <div class="row-fluid">
        <div class="span12">
            <?php if ($allowCustomMaxExecTime): ?>
                <div class="flash-error">
                    <?php
                    echo Yii::t(
                        'module_service', "Warning: This is an experimental function. For any questions or suggestions don't hesitate to contact us: <a href='{contact_us_link}' target='_blank'>{contact_us_link}</a>", array('{contact_us_link}' => (Yii::app()->language == 'ru') ? 'https://open-real-estate.info/ru/contact-us' : 'https://open-real-estate.info/en/contact-us'));

                    ?>
                </div>
                <div class="flash-notice">
                    <p>
                        <?php if ($uploadFolderSize > 50): ?>
                            <?php echo Yii::t('module_service', 'The size of the folder uploads is {uploadFolderSize}', array('{uploadFolderSize}' => $uploadFolderSize . 'MB')); ?>
                            <br />
                            <?php echo tt("You'd rather use hosting features to create a backup.", 'service'); ?>
                        <?php endif; ?>
                    </p>
                    <p>
                        <?php echo tt("We don't recommend you to use the backup feature with the help of CMS to avoid long uploading and buzzing of the site, and also because this process may take a long time.", 'service'); ?>
                        <br />
                        <?php echo tt("The backup process and the site restoring can be done much faster in the Cpanel of the hosting provider.", 'service'); ?>
                        <br />
                        <?php echo tt("You can also set the backup timetable, if your hosting provider has such a feature.", 'service'); ?>
                    </p>
                    <p>
                        <?php echo Yii::t('module_service', "We recommend you to use our partners' hosting: {hostingPartnerUrl}", array('{hostingPartnerUrl}' => $hostingPartnerUrl)); ?>
                    </p>
                </div>

                <div>
                    <div>
                        <p>
                            <?php
                            echo AdminLteHelper::getLink(
                                tt('Create backup'), array('/service/backend/main/createbackup'), 'fa fa-plus', array(
                                'class' => 'btn btn-primary',
                                'onclick' =>
                                'return confirmCreateBackup();'
                                )
                            );

                            ?>
                        </p>
                    </div>
                    <div>
                        <?php
                        $this->widget('CustomGridView', array(
                            'id' => 'backup-grid',
                            'dataProvider' => $dataProviderBackups,
                            'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader();}',
                            'columns' => array(
                                array(
                                    'name' => 'name',
                                    'header' => tt('Name'),
                                    'htmlOptions' => array(
                                        'data-title' => tt('Name'),
                                    ),
                                ),
                                array(
                                    'name' => 'size',
                                    'header' => tt('Size'),
                                    'htmlOptions' => array(
                                        'data-title' => tt('Size'),
                                    ),
                                ),
                                array(
                                    'name' => 'create_time',
                                    'header' => tt('Create date'),
                                    'htmlOptions' => array(
                                        'data-title' => tt('Create date'),
                                    ),
                                ),
                                array(
                                    'class' => 'bootstrap.widgets.BsButtonColumn',
                                    'template' => '{download} {restore} {delete}',
                                    'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions'),
                                    'deleteButtonUrl' => 'Yii::app()->createUrl("/service/backend/main/deletebackup", array("file"=>$data["name"]))',
                                    'buttons' => array(
                                        'download' => array(
                                            'label' => '',
                                            'options' => array('class' => 'glyphicon glyphicon-download', 'title' => tc('Download')),
                                            'url' => 'Yii::app()->createUrl("service/backend/main/downloadbackup", array("file"=>$data["name"]))',
                                        ),
                                        'restore' => array(
                                            'label' => '',
                                            'options' => array(
                                                'class' => 'glyphicon glyphicon-refresh',
                                                'title' => tc('Restore'),
                                                'onclick' => 'return confirmRestoreBackup();',
                                            ),
                                            'url' => 'Yii::app()->createUrl("service/backend/main/restorebackup", array("file"=>$data["name"]))',
                                        ),
                                    ),
                                ),
                            ),
                        ));

                        ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="flash-error">
                    <?php echo tt('The change of the variable  "max_execution_time" is forbidden by your hosting provider. The backup feature is unavailable.', 'service'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="clear"></div>

<?php echo $this->renderPartial('/backend/_form', array('model' => $model)); ?>

<?php
Yii::app()->clientScript->registerScript('confirm-create-backup', '
	function confirmCreateBackup() {
		if (confirm("' . tc('Are you sure?') . '")) {
			$("#loading").show();
			$("#overlay-content").show();
			$("#long-loading-text").show();
			
			return true;
		} else {
			return false;
		}
	}
', CClientScript::POS_END);

Yii::app()->clientScript->registerScript('confirm-restore-backup', '	
	function confirmRestoreBackup() {
		if (confirm("' . tt('Are you sure restore backup? This process may fail and the website will become unavailable!') . '")) {
			$("#loading").show();
			$("#overlay-content").show();
			$("#long-loading-text").show();
			
			return true;
		} else {
			return false;
		}
	}
', CClientScript::POS_END);

Yii::app()->clientScript->registerScript('confirm-clear-cache-maintenance', '	
	$("a.confirm-reset").on("click", function () {
		if ($(this).attr("disabled") == "disabled" || !confirm("' . tt('Are you sure you want to empty the cache?', 'service') . '")) {
			return false;
		}

		$(this).attr("disabled", "disabled");

		$.ajax({
			url: "' . Yii::app()->createUrl('/service/backend/main/doclear') . '",
			data: {target:$(this).attr("value")},
			success: function(result){
				$("#result").html(result);
				$("a.confirm-reset").removeAttr("disabled");
			}
		});
	});
', CClientScript::POS_READY);
