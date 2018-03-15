<?php
$this->pageTitle = Yii::app()->name . ' - ' . tt('Booking apartment', 'booking');


$this->menu = array(
    array(),
);
$this->adminTitle = tt('Booking apartment', 'booking');

?>

<?php
if (issetModule('bookingcalendar')) {
    echo "<div class='flash-notice'>" . tt('booking_table_to_calendar', 'booking') . "</div>";
}

?>

<?php
$this->widget('CustomGridView', array(
    'id' => 'admin-booking-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'ajaxUpdate' => false,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); jQuery("#Bookingtable_date_start").datepicker(jQuery.extend(jQuery.datepicker.regional["' . Yii::app()->controller->datePickerLang . '"],{"showAnim":"fold","dateFormat":"yy-mm-dd","changeMonth":"true","showButtonPanel":"true","changeYear":"true"})); jQuery("#Bookingtable_date_end").datepicker(jQuery.extend(jQuery.datepicker.regional["' . Yii::app()->controller->datePickerLang . '"],{"showAnim":"fold","dateFormat":"yy-mm-dd","changeMonth":"true","showButtonPanel":"true","changeYear":"true"})); jQuery("#Bookingtable_date_created").datepicker(jQuery.extend(jQuery.datepicker.regional["' . Yii::app()->controller->datePickerLang . '"],{"showAnim":"fold","dateFormat":"yy-mm-dd","changeMonth":"true","showButtonPanel":"true","changeYear":"true"})); attachStickyTableHeader();}',
    'columns' => array(
        array(
            'name' => 'id',
            'htmlOptions' => array(
                'class' => 'id_column',
                'data-title' => tt('ID', 'apartments'),
            ),
        ),
        array(
            'class' => 'editable.EditableColumn',
            'name' => 'user_ip',
            'value' => 'BlockIp::displayUserIP($data)',
            'headerHtmlOptions' => array('style' => 'width: 110px'),
            'editable' => array(
                'apply' => '$data->user_ip != "" && Yii::app()->user->checkAccess("blockip_admin")',
                'url' => Yii::app()->controller->createUrl('/blockIp/backend/main/ajaxAdd'),
                'placement' => 'right',
                'emptytext' => '',
                'savenochange' => 'true',
                'title' => tt('Add the IP address to the list of blocked', 'blockIp'),
                'options' => array(
                    'ajaxOptions' => array('dataType' => 'json')
                ),
                'onShown' => 'js: function() {
					var input = $(this).parent().find(".input-medium");

					$(input).attr("disabled", "disabled");
				}',
                'success' => 'js: function(response, newValue) {
					if (response.msg == "ok") {
						message("' . tt("Ip was success added", 'blockIp') . '");
					}
					else if (response.msg == "already_exists") {
						var newValField = "' . tt("Ip was already exists", 'blockIp') . '";

						return newValField;
					}
					else if (response.msg == "save_error") {
						var newValField = "' . tc("Error. Repeat attempt later") . '";

						return newValField;
					}
					else if (response.msg == "no_value") {
						var newValField = "' . tt("Enter Ip", 'blockIp') . '";

						return newValField;
					}
				}',
            ),
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('User IP', 'blockIp'),
            ),
        ),
        array(
            'name' => 'active',
            'type' => 'raw',
            'value' => 'HBooking::getChangeBookingStatus($data)',
            'htmlOptions' => array(
                'class' => 'width150',
                'data-title' => tc('Status'),
            ),
            'sortable' => false,
            'filter' => Bookingtable::getAllStatuses(),
        ),
        array(
            'header' => tt('Apartment ID', 'booking') . ' / ' . tt('Type', 'apartments'),
            'type' => 'raw',
            'value' => '$data->getIdType()',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'class' => 'width100',
                'data-title' => tt('Apartment ID', 'booking') . ' / ' . tt('Type', 'apartments'),
            ),
        ),
        array(
            'name' => 'username',
            'value' => '(isset($data->sender) && $data->sender->role != "admin") ? CHtml::link(CHtml::encode($data->sender->username), array("/users/backend/main/view","id" => $data->sender->id)) : $data->username',
            'type' => 'raw',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('User name', 'booking'),
            ),
        ),
        array(
            'name' => 'email',
            'value' => '$data->email',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => Yii::t('common', 'E-mail'),
            ),
        ),
        array(
            'name' => 'phone',
            'value' => '$data->phone',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => Yii::t('common', 'Phone number'),
            ),
        ),
        array(
            'name' => 'comment',
            'value' => '$data->comment',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Comment', 'booking'),
            ),
        ),
        array(
            'name' => 'date_start',
            'value' => '(isset($data->timein) && $data->time_in) ? $data->dateStartShort . " (". $data->getTimeInName().")" : "" ',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'htmlOptions' => array('class' => 'form-control'),
                'model' => $model,
                'attribute' => 'date_start',
                'language' => Yii::app()->controller->datePickerLang,
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                ),
                ), true),
            'sortable' => true,
            'htmlOptions' => array(
                'class' => 'width150',
                'data-title' => tt('Check-in date', 'booking'),
            ),
        ),
        array(
            'name' => 'date_end',
            'value' => '(isset($data->timeout) && $data->time_out) ? $data->dateEndShort . " (". $data->getTimeOutName().")" : "" ',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'htmlOptions' => array('class' => 'form-control'),
                'model' => $model,
                'attribute' => 'date_end',
                'language' => Yii::app()->controller->datePickerLang,
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                ),
                ), true),
            'sortable' => true,
            'htmlOptions' => array(
                'class' => 'width150',
                'data-title' => tt('Check-out date', 'booking'),
            ),
        ),
        array(
            'name' => 'num_guest',
            'value' => '$data->num_guest ? $data->num_guest : "-"',
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tt('Number of guests', 'booking'),
            ),
        ),
        array(
            'name' => 'date_created',
            'value' => '$data->date_created',
            'sortable' => true,
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'htmlOptions' => array('class' => 'form-control'),
                'model' => $model,
                'attribute' => 'date_created',
                'language' => Yii::app()->controller->datePickerLang,
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth' => 'true',
                    'changeYear' => 'true',
                    'showButtonPanel' => 'true',
                ),
                ), true),
            'htmlOptions' => array(
                'data-title' => tt('Creation date', 'booking'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{delete}',
            'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
            'htmlOptions' => array('class' => 'button_column_actions width50'),
        )
    ),
));

?>

<?php if (issetModule('bookingcalendar')): ?>
    <?php $this->widget('FullCalendarWidget'); ?>
<?php endif; ?>