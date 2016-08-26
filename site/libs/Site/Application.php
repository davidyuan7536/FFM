<?php

class Application {
    private $parameters;

    public function __construct($parameters) {
        $this->parameters = $parameters;
    }

    public function run() {
        $matched = false;
        foreach ($this->parameters as $rule) {
            if (preg_match($rule[0], __FFM_REQUEST__, $matches)) {
                new $rule[1]($matches, $rule[2]);
                $matched = true;
            }
        }
        if (!$matched) {
            Utils::sendResponse(404);
        }
    }
}
