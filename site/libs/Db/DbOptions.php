<?php

class DbOptions {
    private static $DB = NULL;

    private static function getDB() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
        return self::$DB;
    }

    public static function getAutoOptions() {
        return self::getDB()->select('SELECT option_name AS ARRAY_KEY, option_value AS `value`
            FROM ?_options WHERE autoload=1');
    }

    public static function getOptions() {
        return self::getDB()->select('SELECT * FROM ?_options ORDER BY option_name ASC');
    }

    public static function updateOption($row) {
        //        include_once "formatting.php";
        //        $row['option_value'] = maybe_serialize($row['option_value']);
        return self::getDB()->query("INSERT INTO ?_options(?#) VALUES(?a) ON DUPLICATE KEY UPDATE ?a", array_keys($row), array_values($row), $row);
    }

    public static function deleteOption($option_id) {
        return self::getDB()->query('DELETE FROM ?_options WHERE option_id=?d', $option_id);
    }
}
