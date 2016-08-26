<?php

class DbLog {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function newLog($row) {
        return self::$DB->query('INSERT INTO ?_log(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function getLogById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_log WHERE log_id=?d', $id);
    }

    public static function getLogByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_log ORDER BY ?_log.log_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getFullLogByPage(&$totalRows, $from = 0, $count = 10) {
        $result = self::$DB->selectPage($totalRows, 'SELECT *
            FROM ?_log, ?_users
            WHERE ?_log.user_id=?_users.user_id 
            ORDER BY ?_log.log_id DESC LIMIT ?d, ?d', $from, $count);
        foreach($result as &$v) {
            if (!empty($v['artist_id'])) {
                $r = self::$DB->selectRow('SELECT `name` AS `artist_name` FROM ?_artists WHERE artist_id=?d', $v['artist_id']);
                $v = array_merge($v, $r);
            }
            if (!empty($v['event_id'])) {
                $r = self::$DB->selectRow('SELECT `event_name` FROM ?_events WHERE event_id=?d', $v['event_id']);
                $v = array_merge($v, $r);
            }
            if (!empty($v['promoter_id'])) {
                $r = self::$DB->selectRow('SELECT `promoter_name` FROM ?_promoters WHERE promoter_id=?d', $v['promoter_id']);
                $v = array_merge($v, $r);
            }
        }
        return $result;
    }
}
