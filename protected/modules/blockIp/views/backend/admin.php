<?php
$this->menu = array(
    AdminLteHelper::getAddMenuLink(tt('Add blockIp', 'blockIp'), array('create')),
);
$this->adminTitle = tt('Manage blockIp', 'blockIp');

echo "<div class='flash-notice'>" . tt('help_admin', 'blockIp') . "</div> <br />";

?>

<div class="form">
    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableClientValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'deleteIpAfterDays'); ?>
        <div>
            <?php echo $form->textField($model, 'deleteIpAfterDays', array('size' => 3, 'class' => 'form-control width200 noblock')); ?>&nbsp;<?php echo tt('days', 'blockIp'); ?>
        </div>
        <?php echo $form->error($model, 'deleteIpAfterDays'); ?>
    </div>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton(Yii::t('common', 'Save'));

        ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<br />

<?php
$this->widget('CustomGridView', array(
    'id' => 'block-ip-grid',
    'afterAjaxUpdate' => 'function(){attachStickyTableHeader();}',
    'dataProvider' => $model->search(),
    //'filter'=>$model,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'id' => 'itemsSelected',
            'selectableRows' => '2',
            'htmlOptions' => array(
                'class' => 'center',
                'data-title' => tc('Actions'),
            ),
        ),
        array(
            'header' => tt('IP', 'blockIp'),
            'name' => 'ip',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('IP', 'blockIp'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{update} {delete}',
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions'),
        ),
    ),
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/blockIp/backend/main/itemsSelected',
    'id' => 'block-ip-grid',
    'model' => $model,
    'options' => array(
        'delete' => Yii::t('common', 'Delete')
    ),
));
