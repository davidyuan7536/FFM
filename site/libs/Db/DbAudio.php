<?php

class DbAudio {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function getAudioTracks($from = 0, $count = 5) {
        return self::$DB->select('SELECT * FROM ?_audio ORDER BY ?_audio.audio_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getAudioTracksGrouped($from = 0, $count = 5) {
        return self::$DB->select('SELECT * FROM ?_audio GROUP BY artist_id ORDER BY audio_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getAudioTracksGroupedLatest($from = 0, $count = 5) {
        return self::$DB->select('SELECT * FROM (SELECT * FROM ?_audio ORDER BY audio_id DESC LIMIT 0, 100) a GROUP BY artist_id ORDER BY audio_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getAudioBySearch($search, $from = 0, $count = 20) {
        return self::$DB->select('SELECT * FROM ?_audio
            WHERE audio_filename LIKE ?
                OR audio_name LIKE ?
            ORDER BY ?_audio.audio_id
            DESC LIMIT ?d, ?d',
            "%{$search}%",
            "%{$search}%",
            $from, $count);
    }

    public static function getAudioByArticleId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_audio.* FROM ?_audio, ?_articles_audio WHERE ?_audio.audio_id=?_articles_audio.audio_id AND ?_articles_audio.article_id=?d ORDER BY ?_audio.audio_name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getAudioByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_audio ORDER BY ?_audio.audio_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getAudioByArtistId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_audio WHERE artist_id=?d ORDER BY ?_audio.audio_id DESC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getAudioByArtistIds($ids, $from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_audio WHERE artist_id IN(?a) ORDER BY RAND() DESC LIMIT ?d, ?d', $ids, $from, $count);
    }

    public static function getAudioByName($filename) {
        return self::$DB->selectRow('SELECT * FROM ?_audio WHERE audio_filename=?', $filename);
    }

    public static function getAudioById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_audio WHERE audio_id=?d', $id);
    }

    public static function newAudio($row) {
        return self::$DB->query('INSERT INTO ?_audio(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateAudioById($id, $row) {
        return self::$DB->query('UPDATE ?_audio SET ?a WHERE audio_id=?d', $row, $id);
    }

    public static function deleteAudio($id) {
        $audio = self::getAudioById($id);
        $delete = self::$DB->query('DELETE FROM ?_audio WHERE audio_id=?', $id);
        $articles = self::$DB->query('DELETE FROM ?_articles_audio WHERE audio_id=?', $id);

        unlink(__FFM_AUDIO__ . $audio['audio_filename']);

        return array(
            'delete' => $delete,
            'articles' => $articles
        );
    }
    
    public static function deleteAudioByArtistId($id) {
        return array(); // TODO 
    }
}


