<?php

abstract class Singleton {

    protected static $instance;

    private function __construct() {

    }

    final protected function __clone() {

    }

    public static function getInstance() {
        if (!static::$instance instanceof static) {
            static::$instance = new static();
        }
        return static::$instance;
    }

}

?>