<?php
$this->pageTitle .= ' - ' . tc('News product');
$this->breadcrumbs = array(
    tc('News product'),
);
$this->menu = array(
    array(),
);
$this->adminTitle = tc('News product');

?>

<?php
if ($items) {
    if ($pages) {

        $this->widget('BsPager', array('pages' => $pages));
        echo '<div class="clear">&nbsp;</div>';
    }
    echo '<div style="direction: ltr;">';
    foreach ($items as $item):

        ?>		
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $item->title; ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="<?php echo tc('Collapse'); ?>"><i class="fa fa-minus"></i></button>
                    <!--<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <div>
                    <?php echo $item->pubDate; ?>
                </div>
                <div>
                    <?php echo $item->description; ?>
                </div>
                <div>
                    <?php echo CHtml::link(EntriesModule::t('Read more &raquo;'), $item->link); ?>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    <?php endforeach; ?>
    <?php echo '</div>'; ?>
<?php } ?>

<?php
if (!$items) {
    echo EntriesModule::t('News list is empty.');
}

if ($pages) {
    $this->widget('BsPager', array('pages' => $pages));
    echo '<div class="clear">&nbsp;</div>';
}