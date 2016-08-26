<?php

class DbUsers {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function getUsersByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_users ORDER BY user_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getUserById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_users WHERE user_id=?', $id);
    }

    public static function getUserByPassword($login, $password) {
        return self::$DB->selectRow('SELECT user_id FROM ?_users WHERE enabled=? and user_email=? and password=?', 1, $login, $password);
    }

    public static function getUserByEmail($email) {
        return self::$DB->selectRow('SELECT * FROM ?_users WHERE user_email=?', $email);
    }

    public static function getUserByFBId($fbid) {
        return self::$DB->selectRow('SELECT * FROM ?_users WHERE fbid=?', $fbid);
    }

    public static function getUserByHash($hash) {
        return self::$DB->selectRow('SELECT * FROM ?_users WHERE user_hash=?', $hash);
    }

    public static function getUserByActivationCode($code) {
        return self::$DB->selectRow('SELECT * FROM ?_users WHERE activation_code=?', $code);
    }

    public static function getUserByForgotCode($code) {
        return self::$DB->selectRow('SELECT * FROM ?_users WHERE forgot_code=?', $code);
    }

    public function getUsersBySearchEmail($search, $from = 0, $count = 50) {
        return self::$DB->select('SELECT * FROM ?_users WHERE user_email LIKE ? LIMIT ?d, ?d', "%{$search}%", $from, $count);
    }

    public function getUsersBySearchName($search, $from = 0, $count = 50) {
        return self::$DB->select('SELECT * FROM ?_users WHERE user_name LIKE ? LIMIT ?d, ?d', "%{$search}%", $from, $count);
    }

    public static function activateUserEmail($id) {
        $row = array(
            'activation_code' => '',
            'enabled' => 1
        );
        return self::updateUserById($id, $row);
    }

    public function setUserEnabled($id, $enabled) {
        $row = array(
            'enabled' => $enabled
        );
        return self::updateUserById($id, $row);
    }

    public static function createUser($row) {
        return self::$DB->query('INSERT INTO ?_users(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateUserById($id, $row) {
        return self::$DB->query('UPDATE ?_users SET ?a WHERE user_id=?d', $row, $id);
    }

    public static function deleteUserById($id) {
        $delete = self::$DB->query('DELETE FROM ?_users WHERE user_id=?', $id);
        return array('delete' => $delete);
    }
}