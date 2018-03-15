<?php
$this->breadcrumbs = array(tc('Overview'));
$this->menu = array();

$this->adminTitle = tc('Overview');

$langPrefix = (Yii::app()->language == 'ru') ? 'ru' : 'en';
$baseUrl = Yii::app()->request->baseUrl;

?>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-files-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text ore-custom-info-box-text"><?php echo tc('Listings awaiting confirmation'); ?></span>
                <span class="info-box-number"><?php echo ($adminStatsBage && isset($adminStatsBage['countApartmentModeration'])) ? $adminStatsBage['countApartmentModeration'] : 0; ?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->

    <?php if (issetModule('messages')): ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-envelope-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text ore-custom-info-box-text"><?php echo tc('New private messages'); ?></span>
                    <span class="info-box-number"><?php echo ($adminStatsBage && isset($adminStatsBage['countMessagesUnread'])) ? $adminStatsBage['countMessagesUnread'] : 0; ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    <?php endif; ?>

    <?php if (issetModule('comments')): ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-comment-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text ore-custom-info-box-text"><?php echo tc('Comments awaiting confirmation'); ?></span>
                    <span class="info-box-number"><?php echo ($adminStatsBage && isset($adminStatsBage['countCommentPending'])) ? $adminStatsBage['countCommentPending'] : 0; ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    <?php endif; ?>

    <?php if (issetModule('bookingtable')): ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-olive"><i class="fa fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text ore-custom-info-box-text"><?php echo tc('New bookings'); ?></span>
                    <span class="info-box-number"><?php echo ($adminStatsBage && isset($adminStatsBage['countNewPending'])) ? $adminStatsBage['countNewPending'] : 0; ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    <?php endif; ?>

    <?php if (param('allowCustomCities', 0)): ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fa fa-globe"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text ore-custom-info-box-text"><?php echo tc('Cities awaiting moderation'); ?></span>
                    <span class="info-box-number"><?php echo ($adminStatsBage && isset($adminStatsBage['countCitiesModeration'])) ? $adminStatsBage['countCitiesModeration'] : 0; ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    <?php endif; ?>

    <?php if (issetModule('payment')): ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-teal"><i class="fa fa-money"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text ore-custom-info-box-text"><?php echo tc('New payments'); ?></span>
                    <span class="info-box-number"><?php echo ($adminStatsBage && isset($adminStatsBage['countPaymentWait'])) ? $adminStatsBage['countPaymentWait'] : 0; ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    <?php endif; ?>
</div>


<h3><?php echo tc('Getting started'); ?></h3>
<div class="row">    
    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-aqua-active">
            <div class="inner">
                <h4><?php echo tc('Add web counter'); ?></h4>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="https://open-real-estate.info/<?php echo $langPrefix; ?>/blog/instructions/open-real-estate-settings-after-the-installation" target="_blank" class="small-box-footer"><?php echo tc('Go to'); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-green-active">
            <div class="inner">
                <h4><?php echo tc('Change the site logo'); ?></h4>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="https://open-real-estate.info/<?php echo $langPrefix; ?>/blog/instructions/open-real-estate-settings-after-the-installation" target="_blank" class="small-box-footer"><?php echo tc('Go to'); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-yellow-active">
            <div class="inner">
                <h4><?php echo tc('Set up sending letters from the site'); ?></h4>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo $baseUrl . '/configuration/backend/main/admin?ConfigurationModel[section]=mail'; ?>" class="small-box-footer"><?php echo tc('Go to'); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>


    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-olive-active">
            <div class="inner">
                <h4><?php echo tc('Add property types'); ?></h4>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo $baseUrl . '/apartmentObjType/backend/main/admin'; ?>" class="small-box-footer"><?php echo tc('Go to'); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-purple-active">
            <div class="inner">
                <h4><?php echo tc('Add apartment properties'); ?></h4>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo $baseUrl . '/formdesigner/backend/main/admin'; ?>" class="small-box-footer"><?php echo tc('Go to'); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-light-blue-active">
            <div class="inner">
                <h4><?php echo tc('Set up watermark in objects photo'); ?></h4>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo $baseUrl . '/images/backend/main/index'; ?>" class="small-box-footer"><?php echo tc('Go to'); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-blue-active">
            <div class="inner">
                <h4><?php echo tc('Add listings'); ?></h4>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo $baseUrl . '/apartments/backend/main/create'; ?>" class="small-box-footer"><?php echo tc('Go to'); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-teal-active">
            <div class="inner">
                <h4><?php echo tc('Post news about website startup'); ?></h4>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo $baseUrl . '/entries/backend/main/admin'; ?>" class="small-box-footer"><?php echo tc('Go to'); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<?php if (!empty($newsProductItems)): ?>
    <h3><?php echo tc('News product'); ?></h3>
    <div style="direction: ltr;">
        <?php foreach ($newsProductItems as $item): ?>		
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
                        <?php echo CHtml::link(tt('Read more &raquo;', 'entries'), $item->link); ?>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        <?php endforeach; ?>
    </div>
<?php endif; ?>
