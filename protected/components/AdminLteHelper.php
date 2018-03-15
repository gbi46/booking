<?php

class AdminLteHelper
{

    public static function getMenuLink($title, $url, $icon = '', $options = array())
    {
        $iconCode = '';
        if ($icon) {
            $iconCode = '<span class="' . $icon . '"></span> ';
        }
        $ret = array(
            'label' => $iconCode . CHtml::encode($title),
            'url' => $url,
        );
        return CMap::mergeArray($ret, $options);
    }

    public static function getButton($title, $icon = '', $options = array(), $useLadda = false)
    {
        $iconCode = '';
        if ($icon) {
            $iconCode = '<span class="' . $icon . '"></span> &nbsp; ';
        }

        $resTitle = $iconCode . CHtml::encode($title);
        if ($useLadda) {
            $options['data-style'] = 'slide-up';
            if (!isset($options['class'])) {
                $options['class'] = '';
            }
            $options['class'] .= ' ladda-button';
            $resTitle = '<span class="ladda-label">' . $resTitle . '</span>';
        }

        return CHtml::tag('button', $options, $resTitle);
    }

    public static function getLink($title, $url = '#', $icon = '', $options = array(), $useLadda = false)
    {
        $iconCode = '';
        if ($icon) {
            $iconCode = '<span class="' . $icon . '"></span> &nbsp; ';
        }

        $resTitle = $iconCode . CHtml::encode($title);
        if ($useLadda) {
            $options['data-style'] = 'slide-up';
            if (!isset($options['class'])) {
                $options['class'] = '';
            }
            $options['class'] .= ' ladda-button';
            $resTitle = '<span class="ladda-label">' . $resTitle . '</span>';
        }

        return CHtml::link($resTitle, $url, $options);
    }

    public static function getAddMenuLink($title, $url, $options = array())
    {
        $icon = 'fa fa-plus';
        $options = self::applyClass($options, 'btn btn-primary');
        return self::getMenuLink($title, $url, $icon, $options);
    }

    public static function applyClass($options, $class)
    {
        if (!isset($options['linkOptions']['class']) || !$options['linkOptions']['class']) {
            $options['linkOptions']['class'] = $class;
        }
        return $options;
    }

    public static function getFilterMenuLink($title, $url, $options = array())
    {
        $icon = 'fa fa-filter';
        $options = self::applyClass($options, 'btn btn-warning bg-yellow');
        return self::getMenuLink($title, $url, $icon, $options);
    }

    public static function getBackMenuLink($title, $url, $options = array())
    {
        $icon = 'fa fa-angle-double-left';
        $options = self::applyClass($options, 'btn btn-warning bg-yellow');
        return self::getMenuLink($title, $url, $icon, $options);
    }

    public static function getDeleteMenuLink($title, $url, $options = array())
    {
        $icon = 'fa fa-trash';
        $options = self::applyClass($options, 'btn btn-danger bg-red');
        return self::getMenuLink($title, $url, $icon, $options);
    }

    public static function getEditMenuLink($title, $url, $options = array())
    {
        $icon = 'fa fa-pencil';
        $options = self::applyClass($options, 'btn btn-success bg-green');
        return self::getMenuLink($title, $url, $icon, $options);
    }

    public static function getPrimaryMenuLink($title, $url, $options = array(), $icon = '')
    {
        $options = self::applyClass($options, 'btn btn-primary');
        return self::getMenuLink($title, $url, $icon, $options);
    }

    public static function getSubmitButton($title, $options = array(), $ladda = true, $icon = 'fa fa-check')
    {
        $options['type'] = 'submit';
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        $options['class'] .= ' btn btn-primary';

        return self::getButton($title, $icon, $options, $ladda);
    }

    public static function getEditButton($title, $url, $options = array())
    {
        $options['class'] = 'btn btn-success';

        return self::getLink($title, $url, 'fa fa-pencil', $options);
    }

    public static function loadLadda()
    {
        Yii::import('application.extensions.ladda.Ladda');
        $ladda = new Ladda();
        $ladda->init()->registerScripts();
    }
}
