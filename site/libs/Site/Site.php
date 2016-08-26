<?php

class Site {
    private $config;
    private $redirect;

    public function __construct(&$config, &$redirect) {
        $this->config = $config;
        $this->redirect = $redirect;
    }

    public function start() {
        $pos = strpos($_SERVER['REQUEST_URI'], '?');
        if ($pos === false) {
            define('__FFM_REQUEST__', $_SERVER['REQUEST_URI']);
        } else {
            define('__FFM_REQUEST__', substr($_SERVER['REQUEST_URI'], 0, $pos));
        }

        foreach ($this->config as $rule) {
            if (preg_match($rule[URL], __FFM_REQUEST__, $matches)) {
                require "Apps/{$rule[HANDLER]}.php";
                exit();
            }
        }

        foreach ($this->redirect as $rule) {
            if (preg_match($rule[URL], __FFM_REQUEST__, $matches)) {
                Utils::redirect(preg_replace($rule[URL], $rule[HANDLER], __FFM_REQUEST__));
                exit();
            }
        }

        Utils::sendResponse(404);
    }
}
