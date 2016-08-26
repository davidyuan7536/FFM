<?php

class DbReleases {
    private static $DB = NULL;

    private static function getDB() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
        return self::$DB;
    }

    public static function getReleasesByPage(&$totalRows, $from = 0, $count = 10) {
        return self::getDB()->selectPage($totalRows, 'SELECT * FROM ?_releases ORDER BY release_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getReleaseById($id) {
        return self::getDB()->selectRow('SELECT * FROM ?_releases WHERE release_id=?', $id);
    }

    public static function getReleasesByArtistId($id, $from = 0, $count = 10) {
        return self::getDB()->select('SELECT * FROM ?_releases
            WHERE artist_id=?
            ORDER BY release_year DESC
            LIMIT ?d, ?d',
            $id, $from, $count);
    }

    public static function getReleaseByHash($hash) {
        return self::getDB()->selectRow('SELECT * FROM ?_releases WHERE release_hash=?', $hash);
    }

    public static function setReleaseGenres($id, $ids) {
        self::getDB()->query('DELETE FROM ?_releases_genres WHERE release_id=?d', $id);
        if (!empty($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('release_id' => $id, 'genre_id' => $v);
                self::getDB()->query('INSERT INTO ?_releases_genres(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function newRelease($row) {
        return self::getDB()->query('INSERT INTO ?_releases(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateReleaseById($id, $row) {
        return self::getDB()->query('UPDATE ?_releases SET ?a WHERE release_id=?d', $row, $id);
    }

    public static function deleteReleaseById($id) {
        $delete = self::getDB()->query('DELETE FROM ?_releases WHERE release_id=?', $id);
        return array('delete' => $delete);
    }

    public static function deleteReleaseByArtistId($id) {
        return array(); // TODO 
    }
}