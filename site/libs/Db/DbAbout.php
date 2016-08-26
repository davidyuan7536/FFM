<?php

class DbAbout {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function updateAbout($row) {
        if (count(self::getAbout()) > 0) {
            return self::$DB->query('UPDATE ?_about SET ?a', $row);
        } else {
            return self::$DB->query('INSERT INTO ?_about(?#) VALUES(?a)', array_keys($row), array_values($row));
        }
    }

    public static function getAbout() {
        return self::$DB->selectRow('SELECT * FROM ?_about');
    }
}