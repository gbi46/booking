<?php
$this->pageTitle = Yii::app()->name . ' - ' . tc('Manage modules');
$this->breadcrumbs = array(
    tc('Modules'),
);
$this->menu = array(
    array(),
);
$this->adminTitle = tc('Manage modules');

$proModules = ConfigurationModel::getProModules();
$freeModules = ConfigurationModel::getFreeModules();

$modules = CMap::mergeArray($proModules, $freeModules);

if ($modules) {
    foreach ($modules as $module) {

        $enabled = false;
        $isFree = true;
        if (param('module_enabled_' . $module)) {
            $type = 'success';
            $enabled = true;
        } else {
            $type = 'warning';
        }

        if (in_array($module, $proModules)) {
            $isFree = false;
        }

        if (!$isFree) {
            if (!issetModule($module, true)) {
                $type = 'danger';
                $enabled = false;
            }
        }

        echo '<div class="alert in alert-block fade alert-light-' . $type . '">';

        echo '<div class="row-fluid">';

        echo '<div class="col-md-3 col-lg-2">';

        echo '<strong>';
        if ($enabled) {
            echo tc('Module is enabled');
        } else {
            if ($type == 'danger') {
                echo tc('Module is not installed');
            } else {
                echo tc('Module is disabled');
            }
        }
        echo '</strong></br>';

        if ($type != 'danger') {
            if ($enabled) {
                echo AdminLteHelper::getLink(tc('Disable'), $this->createUrl('manipulate', array('type' => 'disable', 'module' => $module)), 'fa fa-stop', array(
                    'class' => 'btn btn-warning',
                    ), true);
            } else {
                echo AdminLteHelper::getLink(tc('Enable'), $this->createUrl('manipulate', array('type' => 'enable', 'module' => $module)), 'fa fa-play', array(
                    'class' => 'btn btn-success',
                    ), true);
            }
        } else {
            if (Yii::app()->language == 'ru') {
                $url = 'http://open-real-estate.info/ru/open-real-estate-modules';
            } else {
                $url = 'http://open-real-estate.info/en/open-real-estate-modules';
            }

            $anchor = '';
            switch ($module) {
                case 'seo':
                    $anchor = '#seo';
                    break;
                case 'formeditor':
                    $anchor = '#formeditor';
                    break;
                case 'location':
                    $anchor = '#location';
                    break;
                case 'rbac':
                    $anchor = '#accesscontrol';
                    break;
                case 'seasonalprices':
                    $anchor = '#seasonalprices';
                    break;
                case 'tariffPlans':
                    $anchor = '#tariffplans';
                    break;
                case 'bookingcalendar':
                    $anchor = '#booking';
                    break;
                case 'metroStations':
                    $anchor = '#metrostations';
                    break;
                case 'historyChanges':
                    $anchor = '#history';
                    break;
                case 'messages':
                    $anchor = '#messages';
                    break;
                case 'advertising':
                    $anchor = '#advertisement';
                    break;
                case 'socialposting':
                    $anchor = '#social';
                    break;
                case 'slider':
                    $anchor = '#slider';
                    break;
                case 'yandexRealty':
                    $anchor = '#yan';
                    break;
                case 'iecsv':
                    $anchor = '#sync';
                    break;
                case 'sitemap':
                    $anchor = '#sitemap';
                    break;
                case 'geo':
                    $anchor = '#geo';
                    break;
                default:
                    break;
            }

            $url = $url . $anchor;

            echo AdminLteHelper::getLink(tc('Buy module'), $url, 'fa fa-cart-plus', array(
                'class' => 'btn btn-primary',
                'target' => '_blank',
            ));
        }
        echo '</div>'; // span3
        echo '<div class="col-md-9 col-lg-10"><strong>' . tc('module_name_' . $module) . '</strong><br/>' . tc('module_description_' . $module) . '</div>';
        echo '<div class="clear"></div>';
        echo '</div>'; // class=row
        echo '</div>'; // class=alert
    }
}




