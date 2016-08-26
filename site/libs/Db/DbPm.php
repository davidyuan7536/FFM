<?php

define('STATUS_REQUEST', '0');
define('STATUS_ENABLED', '1');
define('STATUS_DISABLED', '2');

class DbPm {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function newRequest($row) {
        return self::$DB->query('INSERT INTO ?_users_pm(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function getPmListByUserId($userId, $from = 0, $count = 10) {
        return self::$DB->select('SELECT *
            FROM ?_artists, ?_users_pm
            WHERE ?_users_pm.user_id=?d AND ?_artists.artist_id = ?_users_pm.artist_id
            ORDER BY ?_users_pm.request_date DESC
            LIMIT ?d, ?d',
            $userId, $from, $count);
    }

    public static function getPmPageByStatus($status, &$totalRows, $from = 0, $count = 10) {
        $result = self::$DB->selectPage($totalRows, 'SELECT *
            FROM ?_artists, ?_users, ?_users_pm
            WHERE ?_users_pm.request_status=?d
            AND ?_artists.artist_id = ?_users_pm.artist_id
            AND ?_users.user_id = ?_users_pm.user_id
            ORDER BY ?_users_pm.request_date DESC
            LIMIT ?d, ?d',
            $status, $from, $count);
        foreach ($result as &$v) {
            unset($v['password']);
        }
        return $result;
    }

    public static function getRequestById($id) {
        $result = self::$DB->selectRow('SELECT *
            FROM ?_artists, ?_users, ?_users_pm
            WHERE ?_users_pm.request_id=?d
            AND ?_artists.artist_id = ?_users_pm.artist_id
            AND ?_users.user_id = ?_users_pm.user_id',
            $id);
        unset($result['password']);
        return $result;
    }

    public static function getRequestByUserArtist($userId, $artistId) {
        $result = self::$DB->selectRow('SELECT *
            FROM ?_artists, ?_users, ?_users_pm
            WHERE ?_users_pm.user_id=?d AND ?_users_pm.artist_id=?d
            AND ?_artists.artist_id = ?_users_pm.artist_id
            AND ?_users.user_id = ?_users_pm.user_id',
            $userId, $artistId);
        unset($result['password']);
        return $result;
    }

    public static function approveRequest($id) {
        $row = array(
            'request_status' => STATUS_ENABLED
        );
        return self::updateRequestById($id, $row);
    }

    public static function declineRequest($id) {
        $result = self::deleteRequestById($id);
        return $result['delete'];
    }

    public static function updateRequestById($id, $row) {
        return self::$DB->query('UPDATE ?_users_pm SET ?a WHERE request_id=?d', $row, $id);
    }

    public static function deleteRequestById($id) {
        $delete = self::$DB->query('DELETE FROM ?_users_pm WHERE request_id=?d', $id);
        return array('delete' => $delete);
    }
}
