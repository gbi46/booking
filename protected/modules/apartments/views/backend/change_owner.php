<?php
$this->breadcrumbs = array(
    tt('Manage apartments') => array('/apartments/backend/main/admin'),
);

$this->menu = array(
    array('label' => tt('Manage apartments'), 'url' => array('/apartments/backend/main/admin')),
);
$this->adminTitle = tt('Set the owner of the listing', 'apartments') . ' ' . $modelApartment->getStrByLang('title');

$form = $this->beginWidget('CustomForm', array(
    'id' => $this->modelName . '-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));

?>

<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

<?php
echo $form->errorSummary($model);

echo $form->labelEx($model, 'futureOwner');

$columns = array(
    array(
        'class' => 'CCheckBoxColumn',
        'id' => 'itemsSelected',
        'selectableRows' => '1',
        'htmlOptions' => array(
            'class' => 'center',
            'data-title' => tc('Actions'),
        ),
    ),
    array(
        'name' => 'type',
        'value' => '$data->getTypeName()',
        'filter' => User::getTypeList(),
        'htmlOptions' => array(
            'data-title' => tc('Type'),
        ),
    ),
    array(
        'name' => 'role',
        'value' => '$data->getRoleName()',
        'filter' => User::$roles,
        'htmlOptions' => array(
            'data-title' => tt('Role', 'users'),
        ),
    ),
    array(
        'name' => 'username',
        'header' => tt('User name', 'users'),
        'htmlOptions' => array(
            'data-title' => tt('User name', 'users'),
        ),
    ),
    'email',
);

$this->widget('CustomGridView', array(
    'id' => 'change-owner-grid',
    'dataProvider' => $modelUser->search(),
    'afterAjaxUpdate' => 'function(){attachStickyTableHeader();}',
    'filter' => $modelUser,
    'columns' => $columns
));

?>

<div class="clear">&nbsp;</div>
<div id="submit" class="form-group buttons">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => tc('Change'),
        'htmlOptions' => array(
            'class' => 'submit-button',
        ),
    ));

    ?>
</div>

<?php $this->endWidget(); ?>