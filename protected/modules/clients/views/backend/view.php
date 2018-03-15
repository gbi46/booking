<?php
$this->breadcrumbs = array(
    tt('Manage clients', 'clients') => array('admin'),
    tt('View client', 'clients'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage clients', 'clients'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add client', 'clients'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete client', 'clients'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt('View client', 'clients');

?>

<?php
$this->widget('CustomDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        array(
            'label' => CHtml::encode($model->getAttributeLabel('state')),
            'value' => Clients::getClientsState($model->state),
            'template' => "<tr class=\"{class}\"><th>{label}</th><td>{value}</td></tr>\n"
        ),
        'contract_number',
        'first_name',
        'second_name',
        'middle_name',
        'birthdate',
        'phone',
        'additional_phone',
        'acts',
        'additional_info',
        'date_created',
    ),
));

?>