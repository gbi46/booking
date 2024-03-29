<?php
/**
 * ECompressHtmlFilter class file.
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 * @version 1.1
 * @package filters
 * @license BSD
 */
/**
 * <p>Filter to compress the HTML output - to reduce bandwidth.</p>
 *
 * @author Stefan Volkmar <volkmar_yii@email.de>
 */
Yii::setPathOfAlias('ECompressHtmlFilter', dirname(__FILE__));

class ECompressHtmlFilter extends CFilter
{

    /**
     * @var boolean enable GZIP compression for the filter.
     * If false, we remove only extra white space within the text.
     * Otherwise, we use GZIP compression.
     * Defaults to true.
     */
    public $gzip = true;

    /**
     * @var string list of actions seperated by comma
     * The default option is that the output from any action will NOT compressed.
     */
    public $actions = '*,all';

    /**
     * @var boolean enable strip new lines.
     * If true, we remove new lines within the text.     
     * Defaults to true.
     */
    public $doStripNewlines = false;
    protected $doCompress = true;

    public function init()
    {
        $this->attachBehaviors(array(
            'compactor' => array(
                'class' => 'ECompressHtmlFilter.ETrimWhitespaceBehavior',
            )
        ));
    }

    protected function preFilter($filterChain)
    {
        $this->actions = str_replace(' ', '', strtolower($this->actions));
        $actionId = $filterChain->action->id;

        if ($this->actions != '*' && $this->actions != 'all' && !in_array($actionId, explode(',', $this->actions))) {
            $this->doCompress = false;
            return parent::preFilter($filterChain);
        }

        if ($this->gzip) {
            if (!self::isBuggyIe() && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
                @ob_start('ob_gzhandler');
                header('Content-type: text/html; charset: ' . Yii::app()->charset);
                header('Cache-Control: must-revalidate');
                header("Expires: " . gmdate('D, d M Y H:i:s', time() - 1) . ' GMT');
            } else {
                ob_start();
                $this->gzip = false;
            }
        } else {
            ob_start();
        }

        return parent::preFilter($filterChain);
    }

    protected function postFilter($filterChain)
    {
        if ($this->doCompress) {
            if (!$this->gzip) {
                $content = ob_get_clean();

                $content = $this->compressHtml($content);

                if ($this->doStripNewlines) {
                    //$content = str_replace(array("\n", "\r"), ' ', $content);

                    $dom = new DOMDocument();
                    libxml_use_internal_errors(true);
                    # https://stackoverflow.com/questions/8218230/php-domdocument-loadhtml-not-encoding-utf-8-correctly
                    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
                    //$dom->loadHTML($content);
                    libxml_clear_errors();

                    $metas = $dom->getElementsByTagName('meta');

                    # в javascript оставим пробелы - необходимо для корректной работы всех inline js-скриптов					
                    $script = $dom->getElementsByTagName('script');
                    if (!empty($script)) {
                        foreach ($script as $key => $script) {
                            if (!empty($script->nodeValue)) {
                                $script->nodeValue = str_replace(array("\n", "\r"), 'scriptrneol', $script->nodeValue);
                            }
                        }
                    }

                    if (empty($metas)) { # ajax modal
                        $content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
                    } else {
                        $content = $dom->saveHTML();
                    }
                    unset($dom);

                    $content = str_replace(array("\n", "\r"), ' ', $content);
                    $content = str_replace('scriptrneolscriptrneol', 'scriptrneol', $content);
                    $content = str_replace('scriptrneol', "\r\n", $content);

                    //$content = preg_replace('~[[:cntrl:]]~', ' ', $content);
                    //$content = preg_replace('~[[:cntrl:]]~', ' ', $content);
                }

                echo $content;
            }
        }
        parent::postFilter($filterChain);
    }

    /**
     * Is the browser an IE version earlier than 6 SP2?
     *
     * Code borrowed from the HTTP_Encoder class in http://code.google.com/p/minify/
     *
     * @return bool
     */
    public static function isBuggyIe()
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ') || false !== strpos($ua, 'Opera')) {
            return false;
        }
        $version = (float) substr($ua, 30);
        return ($version < 6 || ($version == 6 && false === strpos($ua, 'SV1')));
    }
}
