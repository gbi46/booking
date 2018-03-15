<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.23.0
 * 	copyright			:	(c) 2015 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */

class YandexTranslater
{

    const BASE_URL = 'https://translate.yandex.net/api/v1.5/tr.json/';
    const MESSAGE_UNKNOWN_ERROR = 'Unknown error';
    const MESSAGE_JSON_ERROR = 'JSON parse error';
    const MESSAGE_INVALID_RESPONSE = 'Invalid response from service';

    protected $key;
    protected $handler;

    public function __construct()
    {
        $this->key = param('yandexTranslateKey');
        if (!function_exists('curl_init')) {
            throw new Exception('No CURL support');
        }
        $this->handler = curl_init();

        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
    }

    public function getSupportedLanguages($culture = null)
    {
        return $this->execute('getLangs', array(
                'ui' => $culture
        ));
    }

    public function translate($text, $language, $html = false, $options = 0)
    {
        $data = $this->execute('translate', array(
            'text' => $text,
            'lang' => $language,
            'format' => $html ? 'html' : 'plain',
            'options' => $options
        ));

        if (isset($data['code']) && $data['code'] == 200) {
            return $data['text'];
        }
        return null;
    }

    public function detect($text)
    {
        $data = $this->execute('detect', array(
            'text' => $text,
        ));

        if (isset($data['code']) && $data['code'] == 200) {
            return $data['lang'];
        }
        return null;
    }

    protected function execute($uri, array $parameters)
    {
        $parameters['key'] = $this->key;
        curl_setopt($this->handler, CURLOPT_URL, static::BASE_URL . $uri);
        curl_setopt($this->handler, CURLOPT_POST, true);
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, false);

        $remoteResult = curl_exec($this->handler);
        if ($remoteResult === false) {
            throw new Exception(curl_error($this->handler), curl_errno($this->handler));
        }
        $result = json_decode($remoteResult, true);
        if (!$result) {
            $errorMessage = self::MESSAGE_UNKNOWN_ERROR;
            if (version_compare(PHP_VERSION, '5.3', '>=')) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    if (version_compare(PHP_VERSION, '5.5', '>=')) {
                        $errorMessage = json_last_error_msg();
                    } else {
                        $errorMessage = self::MESSAGE_JSON_ERROR;
                    }
                }
            }
            throw new Exception(sprintf('%s: %s', self::MESSAGE_INVALID_RESPONSE, $errorMessage));
        } elseif (isset($result['code']) && $result['code'] > 200) {
            throw new Exception($result['message'], $result['code']);
        }
        return $result;
    }

    public function translateText($text, $fromLanguage = 'en', $toLanguage = 'ru', $html = false)
    {
        return $this->translate($text, $fromLanguage . '-' . $toLanguage, $html);
    }

    public function detectText($text)
    {
        return $this->detect($text);
    }
}
