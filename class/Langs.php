<?php

/**
 * Class Langs
 */
class Langs 
{

    /** @var string $currentLang for detecting current language */
    private $currentLang = '';

    /** @var array $allLangs all possible languages */
    private $allLangs = ['lt', 'en'];

    /**
     * Langs constructor.
     * @noinspection SpellCheckingInspection
     */
    public function __construct() 
    {
        // get lang from url
        if(isset($_GET['lang']) && $this->_langIsAvailable($_GET['lang'])) {
            setcookie("lang", $_GET['lang'], time() + 3600 * 24 * 30);

            // store current language
            $this->currentLang = $_GET['lang'];

            return;
        }

        // get lang from cookie
        if(isset($_COOKIE['lang']) && $this->_langIsAvailable($_COOKIE['lang'])) {

            // store current language from COOKIE
            $this->currentLang = $_COOKIE['lang'];
            return;
        }

        // if no lang is set
        if(empty($this->currentLang)) {
            // get first two letters from string
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            // tries to detect browser language, if browser language exists in possible languages array and if language file exists
            if($this->_langIsAvailable($browserLang)) {

                // set current language from browser language
                $this->currentLang = $browserLang;
                return;
            }
        }

        // set it to english by default
        $this->currentLang = 'en';
    }

    /**
     * @return string
     */
    public function getCurrentLang()
    {
        return $this->currentLang;
    }

    /**
     * @param string $lang
     * @return bool
     */
    private function _langIsAvailable($lang) 
    {
        return in_array($lang, $this->allLangs) && $this->_langFileExist($lang);
    }

    /**
     * @param string $lang
     * @return bool
     */
    private function _langFileExist($lang) 
    {
        return file_exists('langs/'.strtolower($lang.'.php'));

    }
}
