<?php
namespace Voilab\Slim\Middleware;

 /**
  * I18n
  *
  * This is middleware for a Slim application that reorganize
  * the view templates directories to load first the chosen
  * language, then the fallback.
  *
  * @package    Slim
  * @author     Joel Poulin
  * @since      1.0.0
  */
class I18n extends \Slim\Middleware
{

    private $settings = array();

    /**
     * Constructor
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        $defaults = array(
            'view' => 'twig',
            'langParam' => 'lang',
            'basePath' => '',
            'translatedPath' => '',
            'defaultLang' => 'fr'
        );
        $this->settings = array_merge($defaults, $settings);
    }

    /**
     * Call
     */
    public function call()
    {
        $lang = $this->settings['defaultLang'];

        // check if a lang is already defined
        if (isset($_SESSION[$this->settings['langParam']])) {
            $lang = $_SESSION[$this->settings['langParam']];
        }

        // check if a lang was sent (overriding the already setted lang)
        if ($this->app->request()->get($this->settings['langParam']) !== null) {
            $_SESSION[$this->settings['langParam']] = $this->app->request()->get($this->settings['langParam']);
            $lang = $_SESSION[$this->settings['langParam']];
        }
        $view = $this->app->view();
        switch ($this->settings['view']) {
            case 'twig' :
                if ($lang != $this->settings['defaultLang']) {
                    $view->twigTemplateDirs = array(
                        $this->settings['translatedPath'] . $lang,
                        $this->settings['basePath']
                    );
                } else {
                    $view->twigTemplateDirs = $this->settings['basePath'];
                }
                break;
            default:
                break;
        }
        $this->next->call();
    }
}
