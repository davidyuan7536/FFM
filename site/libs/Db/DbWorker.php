<?php

class DbWorker {
    private static $instance = NULL;
    private $connection = NULL;

    public static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new DbWorker();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function __construct() {
        require_once "DbSimple/Generic.php";
        $this->connection = DbSimple_Generic::connect(__FFM_CONNECT__);
        $this->connection->setErrorHandler(array($this, 'mysqlErrorHandler'));
        $this->connection->setIdentPrefix('ffm_');
        $this->connection->query("SET NAMES 'utf8'");
    }

    public function mysqlErrorHandler($message, $info) {
        require_once "Site/Utils.php";
        // Utils::mailMessage('Database Error', $message, $info['query']);
        if (!error_reporting()) return;
        Utils::sendError('Internal server error');
        exit();
    }
}
