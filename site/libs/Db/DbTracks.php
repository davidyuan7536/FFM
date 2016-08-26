<?php

class DbTracks {
    private static $DB = NULL;

    private static function getDB() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
        return self::$DB;
    }

//    public static function getTracksByPage(&$totalRows, $from = 0, $count = 10) {
//        return self::getDB()->selectPage($totalRows, 'SELECT * FROM ?_tracks ORDER BY track_id DESC LIMIT ?d, ?d', $from, $count);
//    }

    public static function getTrackById($id) {
        return self::getDB()->selectRow('SELECT * FROM ?_tracks WHERE track_id=?', $id);
    }

//    public static function getTracksByArtistId($id, $from = 0, $count = 10) {
//        return self::getDB()->select('SELECT * FROM ?_tracks
//            WHERE artist_id=?
//            ORDER BY track_year DESC
//            LIMIT ?d, ?d',
//            $id, $from, $count);
//    }

    public static function getRandomTrackByArtistId($id) {
        return self::getDB()->selectRow('SELECT * FROM ?_tracks
            WHERE artist_id=?', $id);
    }

    public static function getTracksByReleaseHash($id, $from = 0, $count = 10) {
        return self::getDB()->select('SELECT * FROM ?_tracks
            WHERE release_hash=?
            ORDER BY track_upload_time ASC
            LIMIT ?d, ?d',
            $id, $from, $count);
    }

//    public static function getTrackByHash($hash) {
//        return self::getDB()->selectRow('SELECT * FROM ?_tracks WHERE track_hash=?', $hash);
//    }

    public static function setTrackGenres($id, $ids) {
        self::getDB()->query('DELETE FROM ?_tracks_genres WHERE track_id=?d', $id);
        if (!empty($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('track_id' => $id, 'genre_id' => $v);
                self::getDB()->query('INSERT INTO ?_tracks_genres(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function newTrack($row) {
        return self::getDB()->query('INSERT INTO ?_tracks(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateTrackById($id, $row) {
        return self::getDB()->query('UPDATE ?_tracks SET ?a WHERE track_id=?d', $row, $id);
    }

    public static function deleteTrackById($id) {
        $delete = self::getDB()->query('DELETE FROM ?_tracks WHERE track_id=?', $id);
        return array('delete' => $delete);
    }

//    public static function deleteTrackByArtistId($id) {
//        return array(); // TODO
//    }
}