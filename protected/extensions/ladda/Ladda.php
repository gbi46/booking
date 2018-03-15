<?php
    class Ladda
    {
        private $assetsDir;

        public function init()
        {
            $dir = dirname(__FILE__) . '/assets';
            $this->assetsDir = Yii::app()->assetManager->publish($dir);
            return $this;
        }

        public function registerScripts()
        {
            $cs = Yii::app()->getClientScript();

            $cs->registerScriptFile($this->assetsDir . '/spin.min.js', CClientScript::POS_END);
            $cs->registerScriptFile($this->assetsDir . '/ladda.min.js', CClientScript::POS_END);
            $cs->registerCssFile($this->assetsDir . '/ladda-themeless.min.css');
            $cs->registerScript('ladda-init', '
                Ladda.bind(".ladda-button");
            ', CClientScript::POS_READY);
        }
    }