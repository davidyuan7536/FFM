<?php

class RequestHandler {
    public function __construct($matches, $isDir) {
        if ($isDir && substr($matches[0], -1) != '/') {
            Utils::redirect($matches[0] . '/');
        } else {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $this->post($matches);
                    break;

                case 'HEAD':
                    $this->head($matches);
                    break;

                default:
                    $this->get($matches);
            }
        }
    }

    public function head() {
    }

    public function get() {
    }

    public function post() {
    }
}
