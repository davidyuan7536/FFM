<?php

class DbVideo {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function getVideos($from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_video ORDER BY video_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getVideoById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_video WHERE video_id=?d', $id);
    }

    public static function getVideoByServiceId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_video WHERE service_id=?', $id, $from, $count);
    }

    public static function getVideosByArtistId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_video WHERE artist_id=?d LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getVideosByArtistIds($ids, $from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_video WHERE artist_id IN(?a) ORDER BY RAND() LIMIT ?d, ?d', $ids, $from, $count);
    }

    public static function getVideosByPromoterId($id, $from = 0, $count = 10) {
        $artists = self::$DB->select('SELECT ?_promoters_artists.artist_id
            FROM ?_promoters_artists
            WHERE ?_promoters_artists.promoter_id=?d', $id);

        $ids = array();
        foreach ($artists as $r) {
            array_push($ids, $r['artist_id']);
        }

        return empty($ids) ? array() : self::$DB->select('SELECT ?_video.*, ?_artists.*
            FROM ?_video, ?_artists
            WHERE ?_video.artist_id=?_artists.artist_id
                AND ?_video.artist_id IN(?a)
            ORDER BY video_id DESC
            LIMIT ?d, ?d',
            $ids, $from, $count);
    }

    public static function getVideosByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_video ORDER BY video_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function newVideo($row) {
        return self::$DB->query('INSERT INTO ?_video(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateVideoById($id, $row) {
        return self::$DB->query('UPDATE ?_video SET ?a WHERE video_id=?d', $row, $id);
    }

    public static function deleteVideo($id) {
        $delete = self::$DB->query('DELETE FROM ?_video WHERE video_id=?', $id);

        return array(
            'delete' => $delete
        );
    }
}


