<?php

define('STATUS_PUBLISH', 'publish');

class DbLabelReleases {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function newRelease($row) {
        return self::$DB->query('INSERT INTO ?_label_releases(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateReleaseById($id, $row) {
        return self::$DB->query('UPDATE ?_label_releases SET ?a WHERE release_id=?d', $row, $id);
    }

    public static function setReleaseGenres($id, $ids) {
        self::$DB->query('DELETE FROM ?_label_releases_genres WHERE release_id=?d', $id);
        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('release_id' => $id, 'genre_id' => $v);
                self::$DB->query('INSERT INTO ?_label_releases_genres(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function setReleaseArtists($id, $ids) {
        self::$DB->query('DELETE FROM ?_label_releases_artists WHERE release_id=?d', $id);
        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('release_id' => $id, 'artist_id' => $v);
                self::$DB->query('INSERT INTO ?_label_releases_artists(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function getReleaseById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_label_releases WHERE release_id=?d', $id);
    }

    public static function getReleaseByName($filename) {
        return self::$DB->selectRow('SELECT * FROM ?_label_releases WHERE filename=?', $filename);
    }

    public static function deleteRelease($id) {
        $delete = self::$DB->query('DELETE FROM ?_label_releases WHERE release_id=?', $id);
        $artists = self::$DB->query('DELETE FROM ?_label_releases_artists WHERE release_id=?', $id);
        $genres = self::$DB->query('DELETE FROM ?_label_releases_genres WHERE release_id=?', $id);

        return array(
            'delete' => $delete,
            'artists' => $artists,
            'genres' => $genres,
        );
    }

    public static function getReleasesByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_label_releases ORDER BY ?_label_releases.date DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getReleasesFilteredByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_label_releases WHERE status = ? ORDER BY ?_label_releases.date DESC LIMIT ?d, ?d', STATUS_PUBLISH, $from, $count);
    }

    public static function getReleasesBySearch($search, $from = 0, $count = 50) {
        return self::$DB->select('SELECT * FROM ?_label_releases WHERE title LIKE ? ORDER BY ?_label_releases.date DESC LIMIT ?d, ?d', "%{$search}%", $from, $count);
    }

    public static function getReleasesByGeotag($geotagId) {
        return self::$DB->select('SELECT * FROM ?_label_releases WHERE geo_tag_id = ? ORDER BY ?_label_releases.date DESC', $geotagId);
    }

    public static function updateLabelArtistsList($artistIds) {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();

        if (is_array($artistIds) && count($artistIds) > 0) {
            foreach($artistIds as $id) {
                $dbArtists->updateArtistById($id, array('label_artist' => 1));
            }
        }
    }

    public static function getReleases($from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_label_releases WHERE status=? ORDER BY ?_label_releases.date DESC LIMIT ?d, ?d', STATUS_PUBLISH, $from, $count);
    }

    public static function getLastAddedRelease() {
        $result = self::$DB->select('SELECT * FROM ?_label_releases WHERE status=? ORDER BY ?_label_releases.date DESC LIMIT 1', STATUS_PUBLISH);
        return $result[0];
    }
}