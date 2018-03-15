<?php
$isRTL = Lang::isRTLLang(Yii::app()->language);
$baseUrl = Yii::app()->request->baseUrl;
$baseThemeUrl = Yii::app()->theme->baseUrl;
$metaTitle = CHtml::encode($this->pageTitle);
if (isset($this->adminTitle) && $this->adminTitle) {
    $metaTitle .= ' - ' . CHtml::encode($this->adminTitle);
}

?>
<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title><?php echo $metaTitle; ?></title>
        <meta name="description" content="<?php echo CHtml::encode($this->pageDescription); ?>"/>
        <meta name="keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>"/>

        <link rel="stylesheet" href="<?php echo $baseUrl . '/common/css/font-awesome.min.css'; ?>"/>

        <link rel="icon" href="<?php echo $baseUrl; ?>/favicon.ico" type="image/x-icon"/>
        <link rel="shortcut icon" href="<?php echo $baseUrl; ?>/favicon.ico" type="image/x-icon"/>

        <?php
        /*
          Yii::app()->bootstrap->registerAllCss();
          Yii::app()->bootstrap->registerCoreScripts();
          Yii::app()->clientScript->registerScriptFile($baseThemeUrl . '/js/scrollto.js', CClientScript::POS_END);

          $this->renderPartial('//layouts/_common');

          if ($isRTL) {
          Yii::app()->clientScript->registerCssFile($baseUrl . '/common/css/bootstrap.rtl.css');
          Yii::app()->clientScript->registerCssFile($baseUrl . '/common/css/bootstrap-responsive.rtl.css');
          Yii::app()->clientScript->registerCssFile($baseUrl . '/common/css/bootstrap-rtl_2x_css_rtl-xtra.min.css');

          HSite::LTRToRTLCssContent('/common/css/admin-styles.css', '', Yii::getPathOfAlias('webroot'));
          Yii::app()->clientScript->registerCssFile($baseUrl . '/common/css/admin-styles_img.css');
          }
          else {
          Yii::app()->clientScript->registerCssFile($baseUrl . '/common/css/admin-styles.css');
          Yii::app()->clientScript->registerCssFile($baseUrl . '/common/css/admin-styles_img.css');
          }
         */
        $cs = Yii::app()->clientScript;
        $min = YII_DEBUG ? '' : 'min.';


        if ($isRTL) {
            HSite::LTRToRTLCssContent('/common/css/bootstrap.' . $min . 'css', '', Yii::getPathOfAlias('webroot'));
            HSite::LTRToRTLCssContent('/common/css/AdminLTE.' . $min . 'css', '', Yii::getPathOfAlias('webroot'));
            HSite::LTRToRTLCssContent('/common/css/skins/skin-blue.' . $min . 'css', '', Yii::getPathOfAlias('webroot'));
            HSite::LTRToRTLCssContent('/common/css/admin-styles.css', '', Yii::getPathOfAlias('webroot'));
            $cs->registerCssFile($baseUrl . '/common/css/admin-styles-rtl.css');
        } else {
            $cs->registerCssFile($baseUrl . '/common/css/bootstrap.' . $min . 'css');
            $cs->registerCssFile($baseUrl . '/common/css/AdminLTEFonts.css');
            $cs->registerCssFile($baseUrl . '/common/css/AdminLTE.' . $min . 'css');
            $cs->registerCssFile($baseUrl . '/common/css/skins/skin-blue.' . $min . 'css');
            $cs->registerCssFile($baseUrl . '/common/css/admin-styles.css');
        }

        $cs->registerScriptFile($baseThemeUrl . '/js/scrollto.js', CClientScript::POS_END);

        AdminLteHelper::loadLadda();

        ?>
    </head>

    <body class="skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <?php
                $leftItems = $rightItems = array();

                //$leftItems[] = array('label' => tc('Menu'), 'url' => '#', 'items' => $this->infoPages);

                if (Lang::getActiveLangs() > 1) {
                    $rightItems[] = array('label' => tc('Language'), 'url' => '#', 'items' => Lang::getAdminMenuLangs());
                }
                if (issetModule('currency')) {
                    $rightItems[] = array('label' => tc('Currency'), 'url' => '#', 'items' => Currency::getActiveCurrencyArray(4));
                }

                $rightItems[] = array('label' => tc('Log out'), 'url' => $baseUrl . '/site/logout');

                $items = array(
                    '<a class="sidebar-toggle" id="left-menu-link" href="#" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>',
                    array(
                        'class' => 'bootstrap.widgets.BsNav',
                        'type' => 'navbar',
                        'activateParents' => true,
                        'items' => $leftItems,
                        'htmlOptions' => array(
                            'pull' => BsHtml::NAVBAR_NAV_PULL_LEFT
                        )
                    ),
                    //'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
                    array(
                        'class' => 'bootstrap.widgets.BsNav',
                        'type' => 'navbar',
                        'activateParents' => true,
                        'items' => $rightItems,
                        'htmlOptions' => array(
                            'class' => 'navbar-custom-menu'
                        )
                    )
                );

                if (isFree() || demo()) {
                    $items[] = array(
                        //'class' => 'bootstrap.widgets.TbMenu',
                        'class' => 'bootstrap.widgets.BsNav',
                        'type' => 'navbar',
                        'activateParents' => true,
                        'items' => array(
                            array('label' => Yii::t('module_install', 'PRO version demo', array(), 'messagesInFile', Yii::app()->language), 'url' => 'https://demo-pro.open-real-estate.info/', 'linkOptions' => array('class' => 'advert-pro', 'target' => '_blank'), 'visible' => isFree()),
                            array('label' => Yii::t('module_install', 'Add-ons', array(), 'messagesInFile', Yii::app()->language),
                                'url' => (Yii::app()->language == 'ru') ? 'https://open-real-estate.info/ru/open-real-estate-modules' : 'https://open-real-estate.info/en/open-real-estate-modules', 'linkOptions' => array('class' => 'advert-add', 'target' => '_blank'), 'visible' => isFree()),
                            array('label' => Yii::t('module_install', 'Other_author_scripts', array(), 'messagesInFile', Yii::app()->language),
                                'url' => (Yii::app()->language == 'ru') ? 'https://monoray.ru/products' : 'https://monoray.net/products', 'linkOptions' => array('class' => 'advert-author-scripts', 'target' => '_blank')),
                        ),
                        'htmlOptions' => array(
                            'pull' => BsHtml::NAVBAR_NAV_PULL_LEFT,
                        )
                    );
                }

                ?>
                <a href="<?php echo Yii::app()->controller->createAbsoluteUrl('/'); ?>" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><?php echo firstLettes(CHtml::encode($this->pageTitle)); ?></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><?php echo CHtml::encode($this->pageTitle); ?></span>
                </a>

                <?php
                $this->widget('bootstrap.widgets.BsNavbar', array(
                    'position' => BsHtml::NAVBAR_POSITION_STATIC_TOP,
                    'collapse' => false,
                    'brandLabel' => false,
                    'brandUrl' => '',
                    'container' => false,
                    'items' => $items,
                ));

                $adminStatsBage = Yii::app()->controller->adminStatsBage;

                $countApartmentModeration = ($adminStatsBage && isset($adminStatsBage['countApartmentModeration'])) ? $adminStatsBage['countApartmentModeration'] : 0;
                $bageListings = ($countApartmentModeration > 0) ? "<small class=\"label pull-right bg-green\">{$countApartmentModeration}</small>" : '';

                $bagePayments = '';
                if (issetModule('payment')) {
                    $countPaymentWait = ($adminStatsBage && isset($adminStatsBage['countPaymentWait'])) ? $adminStatsBage['countPaymentWait'] : 0;
                    $bagePayments = ($countPaymentWait > 0) ? "<small class=\"label pull-right bg-green\">{$countPaymentWait}</small>" : '';
                }

                $bageCities = '';
                if (param('allowCustomCities', 0)) {
                    $countCitiesModeration = ($adminStatsBage && isset($adminStatsBage['countCitiesModeration'])) ? $adminStatsBage['countCitiesModeration'] : 0;
                    $bageCities = ($countCitiesModeration > 0) ? "<small class=\"label pull-right bg-yellow\">{$countCitiesModeration}</small>" : '';
                }

                $bageComments = '';
                if (issetModule('comments')) {
                    $countCommentPending = ($adminStatsBage && isset($adminStatsBage['countCommentPending'])) ? $adminStatsBage['countCommentPending'] : 0;
                    $bageComments = ($countCommentPending > 0) ? "<small class=\"label pull-right bg-olive\">{$countCommentPending}</small>" : '';
                }

                $bageComplain = '';
                if (issetModule('apartmentsComplain')) {
                    $countComplainPending = ($adminStatsBage && isset($adminStatsBage['countComplainPending'])) ? $adminStatsBage['countComplainPending'] : 0;
                    $bageComplain = ($countComplainPending > 0) ? "<small class=\"label pull-right bg-red\">{$countComplainPending}</small>" : '';
                }

                $bageReviews = '';
                if (issetModule('reviews')) {
                    $countReviewsPending = ($adminStatsBage && isset($adminStatsBage['countReviewsPending'])) ? $adminStatsBage['countReviewsPending'] : 0;
                    $bageReviews = ($countReviewsPending > 0) ? "<small class=\"label pull-right bg-aqua\">{$countReviewsPending}</small>" : '';
                }

                $bageBooking = '';
                if (issetModule('bookingtable')) {
                    $countNewPending = ($adminStatsBage && isset($adminStatsBage['countNewPending'])) ? $adminStatsBage['countNewPending'] : 0;
                    $bageBooking = ($countNewPending > 0) ? "<small class=\"label pull-right bg-teal\">{$countNewPending}</small>" : '';
                }

                $bageMessages = '';
                if (issetModule('messages')) {
                    $countMessagesUnread = ($adminStatsBage && isset($adminStatsBage['countMessagesUnread'])) ? $adminStatsBage['countMessagesUnread'] : 0;
                    $bageMessages = ($countMessagesUnread > 0) ? "<small class=\"label pull-right bg-blue\">{$countMessagesUnread}</small>" : '';
                }

                $countNewsProduct = Yii::app()->controller->countNewsProduct;
                $bageNewsProduct = ($countNewsProduct > 0) ? "<small class=\"label pull-right bg-orange\">{$countNewsProduct}</small>" : '';

                ?>

            </header>

            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel">
                        <div class="pull-left image">&nbsp;</div>
                        <div class="pull-left info">
                            <p>
                                <?php
                                $user = HUser::getModel();
                                echo CHtml::encode($user->username);

                                ?>
                            </p>
                        </div>
                    </div>

                    <!-- search form (Optional) -->
                    <div class="sidebar-form">
                        <div class="input-group">
                            <input type="text" placeholder="<?php echo tc('quick search'); ?>" id="search_menu_lables" class="form-control" />
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <!-- /.search form -->

                    <!-- Sidebar Menu -->

                    <?php
                    echo BsHtml::sidemenu('', array(
                        array('label' => tc('Home'), 'icon' => 'home', 'visible' => (Yii::app()->user->checkAccess('stats_admin') || Yii::app()->user->checkAccess('historyChanges_admin')), 'items' =>
                            array(
                                array('label' => tc('Overview'), 'icon' => 'home', 'url' => $baseUrl . '/stats/backend/main/admin', 'active' => isActive('stats.admin'), 'visible' => Yii::app()->user->checkAccess('stats_admin')),
                                array('label' => tc('Statistics'), 'icon' => 'stats', 'url' => $baseUrl . '/stats/backend/main/graph', 'active' => isActive('stats.graph'), 'visible' => Yii::app()->user->checkAccess('stats_admin')),
                                array('label' => tc('News about Open Real Estate CMS'), 'bage' => $bageNewsProduct, 'icon' => 'inbox', 'url' => $baseUrl . '/entries/backend/main/product', 'active' => isActive('entries.product'), 'visible' => Yii::app()->user->checkAccess('entries_news_product_admin')),
                            )
                        ),
                        array('label' => tc('Listings'), 'icon' => 'th-large', 'notify' => ($bageListings || $bageComplain || $bageBooking), 'visible' => (Yii::app()->user->checkAccess('apartments_admin') || Yii::app()->user->checkAccess('comments_admin') || Yii::app()->user->checkAccess('apartmentsComplain_admin')), 'items' =>
                            array(
                                array('label' => tc('Listings'), 'icon' => 'list-alt', 'bage' => $bageListings, 'url' => $baseUrl . '/apartments/backend/main/admin?resetFilters=1', 'active' => isActive('apartments'), 'visible' => Yii::app()->user->checkAccess('apartments_admin')),
                                array('label' => tc('List your property'), 'icon' => 'plus-sign', 'url' => $baseUrl . '/apartments/backend/main/create', 'active' => isActive('apartments.create'), 'visible' => Yii::app()->user->checkAccess('apartments_admin')),
                                array('label' => tc('Complains'), 'bage' => $bageComplain, 'icon' => 'list-alt', 'url' => $baseUrl . '/apartmentsComplain/backend/main/admin', 'active' => isActive('apartmentsComplain'), 'visible' => issetModule('apartmentsComplain') && Yii::app()->user->checkAccess('apartmentsComplain_admin')),
                                array('label' => tt('Booking apartment', 'booking'), 'bage' => $bageBooking, 'icon' => 'bookmark', 'url' => $baseUrl . '/bookingtable/backend/main/admin', 'active' => isActive('bookingtable'), 'visible' => issetModule('bookingtable') && Yii::app()->user->checkAccess('bookingtable_admin')),
                            ),
                        ),
                        array('label' => tc('Users'), 'icon' => 'user', 'visible' => Yii::app()->user->checkAccess('users_admin'), 'items' =>
                            array(
                                array('label' => tc('Users'), 'icon' => 'user', 'url' => $baseUrl . '/users/backend/main/admin', 'active' => isActive('users'), 'visible' => Yii::app()->user->checkAccess('users_admin')),
                                array('label' => tt('Clients', 'clients'), 'icon' => 'list-alt', 'url' => $baseUrl . '/clients/backend/main/admin', 'active' => isActive('clients'), 'visible' => Yii::app()->user->checkAccess('clients_admin')),
                                array('label' => tc('Blockip'), 'icon' => 'ban-circle', 'url' => $baseUrl . '/blockIp/backend/main/admin', 'active' => isActive('blockIp'), 'visible' => Yii::app()->user->checkAccess('blockip_admin')),
                            //array('label' => tt('Add user', 'users'), 'icon' => 'plus-sign', 'url' => $baseUrl . '/users/backend/main/create', 'active' => isActive('users.create'), 'visible' => Yii::app()->user->checkAccess('users_admin')),
                            )
                        ),
                        array('label' => tc('Content'), 'icon' => 'folder-open', 'notify' => ($bageComments || $bageReviews), 'visible' => (Yii::app()->user->checkAccess('entries_admin') || Yii::app()->user->checkAccess('articles_admin') || Yii::app()->user->checkAccess('menumanager_admin')), 'items' =>
                            array(
                                array('label' => tc('Top menu items'), 'icon' => 'file', 'url' => $baseUrl . '/menumanager/backend/main/admin', 'active' => isActive('menumanager'), 'visible' => Yii::app()->user->checkAccess('menumanager_admin')),
                                array('label' => tc('Info pages'), 'icon' => 'file', 'url' => $baseUrl . '/infopages/backend/main/admin', 'active' => isActive('infopages'), 'visible' => Yii::app()->user->checkAccess('infopages_admin')),
                                array('label' => '---', 'url' => '#', 'visible' => true),
                                array('label' => tt('Entries', 'entries'), 'icon' => 'file', 'url' => $baseUrl . '/entries/backend/main/admin', 'active' => isActive('entries'), 'visible' => Yii::app()->user->checkAccess('entries_admin')),
                                array('label' => tt('Categories of entries', 'entries'), 'icon' => 'file', 'url' => $baseUrl . '/entries/backend/category/admin', 'active' => isActive('entries.category'), 'visible' => Yii::app()->user->checkAccess('entries_category_admin')),
                                array('label' => tc('Q&As'), 'icon' => 'file', 'url' => $baseUrl . '/articles/backend/main/admin', 'active' => isActive('articles'), 'visible' => Yii::app()->user->checkAccess('articles_admin')),
                                array('label' => '---', 'url' => '#', 'visible' => true),
                                array('label' => tc('Comments'), 'bage' => $bageComments, 'icon' => 'list-alt', 'url' => $baseUrl . '/comments/backend/main/admin', 'active' => isActive('comments'), 'visible' => Yii::app()->user->checkAccess('comments_admin')),
                                array('label' => tt('Reviews_management', 'reviews'), 'bage' => $bageReviews, 'icon' => 'heart', 'url' => $baseUrl . '/reviews/backend/main/admin', 'active' => isActive('reviews'), 'visible' => Yii::app()->user->checkAccess('reviews_admin')),
                            //array('label' => tt('Add_feedback', 'reviews'), 'icon' => 'plus-sign', 'url' => $baseUrl . '/reviews/backend/main/create', 'active' => isActive('reviews.create'), 'visible' => Yii::app()->user->checkAccess('reviews_admin')),
                            )
                        ),
                        array('label' => tt('Messages', 'messages'), 'icon' => 'envelope', 'notify' => $bageMessages, 'visible' => issetModule('messages') && Yii::app()->user->checkAccess('messages_admin'), 'items' =>
                            array(
                                array('label' => tt('Messages', 'messages'), 'bage' => $bageMessages, 'icon' => 'list-alt', 'url' => $baseUrl . '/messages/backend/main/admin', 'active' => (isActive('messages') || isActive('messages.read')), 'visible' => issetModule('messages') && Yii::app()->user->checkAccess('messages_admin')),
                                array('label' => tt('Mailing messages', 'messages'), 'icon' => 'list-alt', 'url' => $baseUrl . '/messages/backend/mailing/admin', 'active' => isActive('messages.mailing'), 'visible' => issetModule('messages') && Yii::app()->user->checkAccess('messages_admin')),
                            )
                        ),
                        array('label' => tc('Payments'), 'icon' => 'shopping-cart', 'notify' => $bagePayments, 'visible' => issetModule('payment') && (Yii::app()->user->checkAccess('payment_admin') || Yii::app()->user->checkAccess('paidservices_admin')), 'items' =>
                            array(
                                array('label' => tc('Manage payments'), 'bage' => $bagePayments, 'icon' => 'list-alt', 'url' => $baseUrl . '/payment/backend/main/admin', 'active' => isActive('payment'), 'visible' => issetModule('payment') && Yii::app()->user->checkAccess('payment_admin')),
                                array('label' => tc('Payment systems'), 'icon' => 'wrench', 'url' => $baseUrl . '/payment/backend/paysystem/admin', 'active' => isActive('payment.paysystem'), 'visible' => issetModule('payment') && Yii::app()->user->checkAccess('payment_admin')),
                                array('label' => tc('Paid services'), 'icon' => 'shopping-cart', 'url' => $baseUrl . '/paidservices/backend/main/admin', 'active' => isActive('paidservices'), 'visible' => issetModule('payment') && Yii::app()->user->checkAccess('paidservices_admin')),
                                array('label' => tc('Tariff Plans'), 'icon' => 'briefcase', 'url' => $baseUrl . '/tariffPlans/backend/main/admin', 'active' => isActive('tariffPlans'), 'visible' => issetModule('tariffPlans') && issetModule('paidservices') && Yii::app()->user->checkAccess('tariff_plans_admin')),
                            )
                        ),
                        array('label' => tc('References'), 'icon' => 'asterisk', 'notify' => $bageCities, 'visible' => Yii::app()->user->checkAccess('all_reference_admin'), 'items' =>
                            array(
                                array('label' => tc('Reference "Property types"'), 'icon' => 'asterisk', 'url' => $baseUrl . '/apartmentObjType/backend/main/admin', 'active' => isActive('apartmentObjType'), 'visible' => Yii::app()->user->checkAccess('all_reference_admin')),
                                array('label' => tc('Categories of references'), 'icon' => 'asterisk', 'url' => $baseUrl . '/referencecategories/backend/main/admin', 'active' => isActive('referencecategories'), 'visible' => Yii::app()->user->checkAccess('all_reference_admin')),
                                array('label' => tc('Values of references'), 'icon' => 'asterisk', 'url' => $baseUrl . '/referencevalues/backend/main/admin', 'active' => isActive('referencevalues'), 'visible' => Yii::app()->user->checkAccess('all_reference_admin')),
                                array('label' => tc('Reference "View:"'), 'icon' => 'asterisk', 'url' => $baseUrl . '/windowto/backend/main/admin', 'active' => isActive('windowto'), 'visible' => Yii::app()->user->checkAccess('all_reference_admin')),
                                array('label' => tc('Reference "Check-in"'), 'icon' => 'asterisk', 'url' => $baseUrl . '/timesin/backend/main/admin', 'active' => isActive('timesin'), 'visible' => Yii::app()->user->checkAccess('all_reference_admin')),
                                array('label' => tc('Reference "Check-out"'), 'icon' => 'asterisk', 'url' => $baseUrl . '/timesout/backend/main/admin', 'active' => isActive('timesout'), 'visible' => Yii::app()->user->checkAccess('all_reference_admin')),
                                array('label' => tc('Reference "City/Cities"'), 'icon' => 'asterisk', 'url' => $baseUrl . '/apartmentCity/backend/main/admin', 'active' => isActive('apartmentCity'), 'visible' => (!issetModule('location') && Yii::app()->user->checkAccess('all_reference_admin'))),
                                array('label' => tc('Awaiting moderation'), 'bage' => $bageCities, 'icon' => 'time', 'url' => $baseUrl . '/apartmentCity/backend/main/admin?ApartmentCity[active]=' . ApartmentCity::STATUS_MODERATION, 'visible' => (!issetModule('location') && Yii::app()->user->checkAccess('all_reference_admin') && $bageCities), 'linkOptions' => array('class' => 'lcatsub')),
                                array('label' => tc('Reference "Subway stations"'), 'icon' => 'asterisk', 'url' => $baseUrl . '/metroStations/backend/main/admin', 'active' => isActive('metroStations') && !issetModule('location'), 'visible' => (!issetModule('location') && issetModule('metroStations') && Yii::app()->user->checkAccess('metro_stations_admin'))),
                            )
                        ),
                        array('label' => tc('Settings'), 'icon' => 'wrench', 'visible' => Yii::app()->user->checkAccess('all_settings_admin'), 'items' =>
                            array(
                                array('label' => tc('Settings'), 'icon' => 'wrench', 'url' => $baseUrl . '/configuration/backend/main/admin', 'active' => isActive('configuration'), 'visible' => Yii::app()->user->checkAccess('all_settings_admin')),
                                array('label' => tc('Manage SEO settings'), 'icon' => 'wrench', 'url' => $baseUrl . '/seo/backend/main/admin', 'active' => isActive('seo'), 'visible' => Yii::app()->user->checkAccess('all_settings_admin') && issetModule('seo')),
                                array('label' => tc('Manage modules'), 'icon' => 'wrench', 'url' => $baseUrl . '/modules/backend/main/admin', 'active' => isActive('modules'), 'visible' => Yii::app()->user->checkAccess('all_settings_admin')),
                                array('label' => tc('Manage themes'), 'icon' => 'wrench', 'url' => $baseUrl . '/themes/backend/main/admin', 'active' => isActive('themes'), 'visible' => issetModule('themes'), 'visible' => Yii::app()->user->checkAccess('all_settings_admin')),
                                array('label' => tc('Images'), 'icon' => 'wrench', 'url' => $baseUrl . '/images/backend/main/index', 'active' => isActive('images'), 'visible' => Yii::app()->user->checkAccess('all_settings_admin')),
                                array('label' => tc('Site service'), 'icon' => 'wrench', 'url' => $baseUrl . '/service/backend/main/admin', 'active' => isActive('service'), 'visible' => issetModule('service'), 'visible' => Yii::app()->user->checkAccess('all_settings_admin')),
                                array('label' => tc('Change admin password'), 'icon' => 'lock', 'url' => $baseUrl . '/adminpass/backend/main/index', 'active' => isActive('adminpass'), 'visible' => Yii::app()->user->checkAccess('all_settings_admin')),
                            )
                        ),
                        array('label' => tc('Languages and currency'), 'icon' => 'globe', 'visible' => Yii::app()->user->checkAccess('all_lang_and_currency_admin'), 'items' =>
                            array(
                                array('label' => tc('Languages'), 'icon' => 'globe', 'url' => $baseUrl . '/lang/backend/main/admin', 'active' => isActive('lang'), 'visible' => !isFree() && Yii::app()->user->checkAccess('all_lang_and_currency_admin')),
                                array('label' => tc('Translations'), 'icon' => 'wrench', 'url' => $baseUrl . '/translateMessage/backend/main/admin', 'active' => isActive('translateMessage'), 'visible' => Yii::app()->user->checkAccess('all_lang_and_currency_admin')),
                                //array('label' => tc('Translate Csv'), 'icon' => 'wrench', 'url' => $baseUrl . '/translateCsv/backend/main/admin', 'active' => isActive('translateCsv'), 'visible' => Yii::app()->user->checkAccess('all_lang_and_currency_admin')),
                                array('label' => tc('Currencies'), 'icon' => 'wrench', 'url' => $baseUrl . '/currency/backend/main/admin', 'active' => isActive('currency'), 'visible' => issetModule('currency') && Yii::app()->user->checkAccess('all_lang_and_currency_admin')),
                            )
                        ),
                        array('label' => tc('Modules'), 'icon' => 'th', 'visible' => Yii::app()->user->checkAccess('all_modules_admin') && (issetModule('notifier') || issetModule('slider') || issetModule('advertising') || issetModule('iecsv') || issetModule('formdesigner') || issetModule('socialposting') || issetModule('socialauth') || ((issetModule('location') && Yii::app()->user->checkAccess('all_reference_admin')))), 'items' =>
                            array(
                                array('label' => tc('The forms designer'), 'icon' => 'edit', 'url' => $baseUrl . '/formdesigner/backend/main/admin', 'active' => (isActive('formdesigner') || isActive('formeditor')), 'visible' => issetModule('formdesigner') && Yii::app()->user->checkAccess('all_modules_admin')),
                                array('label' => tc('Mail editor'), 'icon' => 'envelope', 'url' => $baseUrl . '/notifier/backend/main/admin', 'active' => isActive('notifier'), 'visible' => issetModule('notifier') && Yii::app()->user->checkAccess('all_modules_admin')),
                                array('label' => tc('Manage carousel'), 'icon' => 'picture', 'url' => $baseUrl . '/slider/backend/carousel/admin', 'active' => isActive('slider.carousel'), 'visible' => issetModule('slider') && Yii::app()->user->checkAccess('all_modules_admin')),
                                array('label' => tc('Slide-show on the Home page'), 'icon' => 'picture', 'url' => $baseUrl . '/slider/backend/main/admin', 'active' => isActive('slider'), 'visible' => issetModule('slider') && Yii::app()->user->checkAccess('all_modules_admin')),
                                array('label' => tc('Import / Export'), 'icon' => 'transfer', 'url' => $baseUrl . '/iecsv/backend/main/admin', 'active' => isActive('iecsv'), 'visible' => issetModule('iecsv') && Yii::app()->user->checkAccess('all_modules_admin')),
                                array('label' => tc('Advertising banners'), 'icon' => 'star', 'url' => $baseUrl . '/advertising/backend/advert/admin', 'active' => isActive('advertising'), 'visible' => issetModule('advertising') && Yii::app()->user->checkAccess('all_modules_admin')),
                                array('label' => tc('Authentication services'), 'icon' => 'wrench', 'url' => $baseUrl . '/socialauth/backend/main/admin', 'active' => isActive('socialauth'), 'visible' => issetModule('socialauth'), 'visible' => Yii::app()->user->checkAccess('all_settings_admin')),
                                array('label' => tt('Services of automatic posting', 'socialposting'), 'icon' => 'export', 'url' => $baseUrl . '/socialposting/backend/main/admin', 'active' => isActive('socialposting'), 'visible' => issetModule('socialposting') && Yii::app()->user->checkAccess('all_modules_admin')),
                                array('label' => tt('History changes', 'historyChanges'), 'icon' => 'time', 'url' => $baseUrl . '/historyChanges/backend/main/admin', 'active' => isActive('historyChanges'), 'visible' => issetModule('historyChanges') && Yii::app()->user->checkAccess('historyChanges_admin')),
                                array('label' => tt('Custom HTML', 'customHtml'), 'icon' => 'edit', 'url' => $baseUrl . '/customHtml/backend/main/admin', 'active' => isActive('customHtml'), 'visible' => issetModule('customHtml') && Yii::app()->user->checkAccess('all_modules_admin')),
                                array('label' => tc('Location module'), 'icon' => 'map-marker', 'notify' => $bageCities, 'visible' => (issetModule('location') && Yii::app()->user->checkAccess('all_reference_admin')), 'active' => (isActive('location.country') || isActive('location.region') || isActive('location.city') || isActive('metroStations')), 'items' =>
                                    array(
                                        array('label' => tc('Countries'), 'icon' => 'globe', 'url' => $baseUrl . '/location/backend/country/admin', 'visible' => (issetModule('location') && Yii::app()->user->checkAccess('all_reference_admin')), 'active' => isActive('location.country')),
                                        array('label' => tc('Regions'), 'icon' => 'globe', 'url' => $baseUrl . '/location/backend/region/admin', 'visible' => (issetModule('location') && Yii::app()->user->checkAccess('all_reference_admin')), 'active' => isActive('location.region')),
                                        array('label' => tc('Cities'), 'icon' => 'globe', 'url' => $baseUrl . '/location/backend/city/admin', 'visible' => (issetModule('location') && Yii::app()->user->checkAccess('all_reference_admin')), 'active' => isActive('location.city')),
                                        array('label' => tc('Awaiting moderation'), 'bage' => $bageCities, 'icon' => 'time', 'url' => $baseUrl . '/location/backend/city/admin?City[active]=' . ApartmentCity::STATUS_MODERATION, 'visible' => (issetModule('location') && Yii::app()->user->checkAccess('all_reference_admin') && $bageCities), 'linkOptions' => array('class' => 'lcatsub')),
                                        array('label' => tc('Subway stations'), 'icon' => 'globe', 'url' => $baseUrl . '/metroStations/backend/main/admin', 'active' => isActive('metroStations'), 'visible' => issetModule('metroStations') && (issetModule('location') && Yii::app()->user->checkAccess('metro_stations_admin'))),
                                    )
                                ),
                            )
                        ),
                        ), array(
                        'id' => 'left-main-admin-menu'
                    ));

                    ?>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <?php echo $content; ?>

            </div>
            <!-- /.content-wrapper -->

            <footer class="main-footer">
                <?php echo getGA(); ?>
                <?php echo getJivo(); ?>
                <p>&copy;&nbsp;<?php echo ORE_VERSION_NAME . ' ' . ORE_VERSION . ', ' . date('Y'); ?></p>

                <div id="footer">
                    <div id="footer-links">&nbsp;</div>
                    <div id="footer-two-links">
                        <div class="wrapper">
                            <div class="copyright">
                                <a href="https://open-real-estate.info" target="_blank">Powered by Open Real Estate CMS</a>
                            </div>
                        </div>	
                    </div>
                </div>			
            </footer>
        </div>
        <div id="loading" style="display:none;"><?php echo Yii::t('common', 'Loading content...'); ?></div>
        <div id="loading-blocks" style="display:none;"></div>
        <div id="long-loading-text" style="display:none;"><?php echo Yii::t('common', 'Please wait until the process is complete.'); ?></div>
        <div id="overlay-content" style="display:none;"></div>
        <div id="toTop">^ <?php echo tc('Go up'); ?></div>
        <?php
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseThemeUrl . '/js/jquery.dropdownPlain.js', CClientScript::POS_BEGIN);
        $cs->registerScriptFile($baseThemeUrl . '/js/adminCommon.js', CClientScript::POS_BEGIN);
        $cs->registerScriptFile($baseThemeUrl . '/js/habra_alert.js', CClientScript::POS_END);
        $cs->registerScriptFile($baseThemeUrl . '/js/jquery.cookie.js', CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . '/common/js/bootstrap.' . $min . 'js', CClientScript::POS_BEGIN);
        $cs->registerScriptFile($baseUrl . '/common/js/app.' . $min . 'js', CClientScript::POS_BEGIN);

        Yii::app()->clientScript->registerScript('main-admin-vars', '
			var BASE_URL = ' . CJavaScript::encode(Yii::app()->baseUrl) . ';
			var INDICATOR = "' . Yii::app()->theme->baseUrl . "/images/pages/indicator.gif" . '";
			var LOADING_NAME = "' . tc('Loading ...') . '";
		', CClientScript::POS_BEGIN, array(), true);

        $this->widget('application.modules.fancybox.EFancyBox', array(
            'target' => 'a.fancy',
            'config' => array(
                'ajax' => array('data' => "isFancy=true"),
                'titlePosition' => 'inside',
                'onClosed' => 'js:function(){
						var capClick = $(".get-new-ver-code");
						if(typeof capClick !== "undefined")	{ 
							capClick.click(); 
						}
					}'
            ),
            )
        );

        /* $this->widget('ext.magnific-popup.EMagnificPopup', array(
          'target'=>'a.fancy',
          'type' => 'image',
          'options' => array(
          'closeOnContentClick' => true,
          'mainClass' => 'mfp-img-mobile',
          ),
          ));

          $this->widget('ext.magnific-popup.EMagnificPopup', array(
          'target'=>'.mgp-open-inline',
          'type' => 'inline',
          'options' => array(
          'preloader' => false,
          'focus' => '#name',
          'callbacks' => array(
          'beforeOpen' => 'js:function() {
          if($(window).width() < 700) {
          this.st.focus = false;
          } else {
          this.st.focus = "#name";
          }
          }
          ',
          'close' => 'js:function(){
          var capClick = $(".get-new-ver-code");
          if(typeof capClick !== "undefined")	capClick.click();
          }
          ',
          ),
          ),
          )
          );

          $this->widget('ext.magnific-popup.EMagnificPopup', array(
          'target'=>'.mgp-open-ajax',
          'type' => 'ajax',
          'options' => array(
          'preloader' => false,
          'focus' => '#name',
          'callbacks' => array(
          'beforeOpen' => 'js:function() {
          if($(window).width() < 700) {
          this.st.focus = false;
          } else {
          this.st.focus = "#name";
          }
          }
          ',
          'close' => 'js:function(){
          var capClick = $(".get-new-ver-code");
          if(typeof capClick !== "undefined")	capClick.click();
          }
          ',
          ),
          ),
          )
          ); */

        ?>

        <?php $this->widget('bootstrap.widgets.BsModal', array('id' => 'temp_modal')); ?>


        <script type="text/javascript">
            var tempModal = {
                setContent: function (content) {
                    $('.modal-body').html(content);
                },
                open: function () {
                    $("#temp_modal").modal("show");
                },
                close: function () {
                    tempModal.setTitle('');
                    tempModal.setContent('');
                    $("#temp_modal").modal("hide");
                },
                init: function () {
                    $('a.tempModal').each(function (el) {
                        var objUrl = $(this).attr('href');
                        if (objUrl != '') {
                            $(this).on('click', function (event) {
                                //$('#temp_modal_loading').show();
                                $('.modal-body').load(objUrl, function () {
                                    //$('#temp_modal_loading').hide();
                                });
                                var title = $(this).attr('data-original-title');

                                if (!title || title.length < 1)
                                    title = $(this).attr('title');

                                if (title) {
                                    tempModal.setTitle(title);
                                }
                                tempModal.open();
                                //event.preventDefault();
                                return false;
                            })
                        }
                    });
                },
                setTitle: function (title) {
                    $('.modal-header h3').html(title);
                }
            }

            $(function () {
                tempModal.init();

                $("#search_menu_lables").keyup(function (e) {
                    var searchString = $(this).val();
                    $('ul#left-main-admin-menu > li').each(function () {
                        var currentLiText = $(this).text();
                        var showCurrentLi = currentLiText.toLowerCase().indexOf(searchString.toLowerCase()) !== -1;
                        $(this).toggle(showCurrentLi);
                    });
                });
            });
        </script>
    </body>
</html>