<?php $this->beginContent('//layouts/main-admin', array('adminView' => 1)); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo $this->adminTitle; ?>
        <!--<small>Optional description</small>-->
    </h1>
    <!--<ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol>-->
</section>

<!-- Main content -->
<section class="content">
    <?php
    if ($this->menu) {
        $this->widget('bootstrap.widgets.BsNav', array(
            'type' => 'pills', // '', 'tabs', 'pills' (or 'list')
            'stacked' => false, // whether this is a stacked menu
            'items' => $this->menu,
            'htmlOptions' => array('id' => 'pageTopMenu'),
            'encodeLabel' => false,
        ));
    }
    $this->widget('bootstrap.widgets.BsAlert');

    ?>

    <?php echo $content; ?>

</section>
<!-- /.content -->
<?php $this->endContent(); ?>