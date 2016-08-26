<?php

define('RECOMMENDS_TYPE_ARTIST', '0');
define('RECOMMENDS_TYPE_PROMOTER', '1');

class DbArtists {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function getAllArtists() {
        return self::$DB->select('SELECT * FROM ?_artists ORDER BY ?_artists.artist_id DESC');
    }

    public static function getArtistsBySearch($search, $from = 0, $count = 20) {
        return self::$DB->select('SELECT * FROM ?_artists WHERE name LIKE ? OR name_ru LIKE ? ORDER BY ?_artists.artist_id DESC LIMIT ?d, ?d', "%{$search}%", "%{$search}%", $from, $count);
    }

    public static function getArtistsByArticleId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_artists.* FROM ?_artists, ?_articles_artists WHERE ?_artists.artist_id=?_articles_artists.artist_id AND ?_articles_artists.article_id=?d ORDER BY ?_artists.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getArtistsByLabelReleaseId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_artists.*
            FROM ?_artists, ?_label_releases_artists
            WHERE ?_artists.artist_id=?_label_releases_artists.artist_id AND ?_label_releases_artists.release_id=?
            ORDER BY ?_artists.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getTopArtists($count = 10) {
        return self::$DB->select('SELECT ?_artists.* FROM ?_artists ORDER BY ?_artists.artist_rating DESC LIMIT ?d, ?d', 0, $count);
    }

    public static function getArtistsByArticleIds($ids, $from = 0, $count = 10) {
        $_Ids = self::$DB->select('SELECT DISTINCT artist_id
            FROM ?_articles_artists
            WHERE ?_articles_artists.article_id IN(?a)
            LIMIT ?d, ?d',
            $ids, $from, $count);

        $artistsIds = array();
        foreach ($_Ids as $_id) {
            array_push($artistsIds, $_id['artist_id']);
        }

        if (count($artistsIds) > 0) {
            $artists = self::$DB->select('SELECT *
                FROM ?_artists
                WHERE artist_id IN(?a)
                ORDER BY artist_id DESC',
                $artistsIds);
        } else {
            $artists = array();
        }

        return $artists;
    }

    public static function getArtistsByEventId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_artists.* FROM ?_artists, ?_artists_events WHERE ?_artists.artist_id=?_artists_events.artist_id AND ?_artists_events.event_id=?d ORDER BY ?_artists.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getArtistsIdsByEventIds($ids, $from = 0, $count = 10) {
        $_Ids = self::$DB->select('SELECT DISTINCT artist_id
            FROM ?_artists_events
            WHERE ?_artists_events.event_id IN(?a)
            LIMIT ?d, ?d',
            $ids, $from, $count);

        $artistsIds = array();
        foreach ($_Ids as $_id) {
            array_push($artistsIds, $_id['artist_id']);
        }

        return $artistsIds;
    }

    public static function getArtistsByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_artists ORDER BY ?_artists.artist_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getArtistByName($filename) {
        return self::$DB->selectRow('SELECT * FROM ?_artists WHERE filename=?', $filename);
    }

    public static function getArtistById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_artists WHERE artist_id=?d', $id);
    }

    public function getArtistsByGeotagQueue() {
        return self::$DB->select('SELECT * FROM ?_artists WHERE geo_tag_text!=""');
    }

    public static function getLatestArtistsByCountryId($id, $from = 0, $count = 10) {
        $ids = array($id);
        $geoTags = self::$DB->select('SELECT * FROM ?_geo_tags WHERE parent_id=?d', $id);
        foreach ($geoTags as $gt) {
            array_push($ids, $gt['geo_tag_id']);
        }
        $result = self::$DB->select('SELECT ?_artists.*
            FROM ?_artists
            WHERE ?_artists.geo_tag_id IN(?a)
            ORDER BY ?_artists.artist_id DESC
            LIMIT ?d, ?d',
            $ids, $from, $count);
        return $result;
    }

    public static function getLatestArtistsExcludeCountryId($ids, $from = 0, $count = 10) {
        $geoTags = self::$DB->select('SELECT * FROM ?_geo_tags WHERE parent_id IN(?a)', $ids);
        foreach ($geoTags as $gt) {
            array_push($ids, $gt['geo_tag_id']);
        }
        $result = self::$DB->select('SELECT ?_artists.*
            FROM ?_artists
            WHERE ?_artists.geo_tag_id NOT IN(?a)
            ORDER BY ?_artists.artist_id DESC
            LIMIT ?d, ?d',
            $ids, $from, $count);
        return $result;
    }

    public static function getArtistsFilteredByPage(&$genre, &$region, &$totalRows, $from = 0, $count = 10) {
        if (isset($genre)) {
            $genre = self::$DB->selectRow('SELECT * FROM ?_genres WHERE filename=?', $genre);
        }
        if (isset($region)) {
            $region = self::$DB->selectRow('SELECT * FROM ?_geo_tags WHERE filename=?', $region);
        }

        if ($genre != array()) {

            $result = self::$DB->selectPage($totalRows, 'SELECT ?_artists.*
                FROM ?_artists, ?_artists_genres
                WHERE ?_artists.artist_id=?_artists_genres.artist_id AND ?_artists_genres.genre_id=?d
                ORDER BY ?_artists.name ASC
                LIMIT ?d, ?d',
                $genre['genre_id'],
                $from, $count);

            return $result;

        } else if ($region != array()) {

            if ($region['parent_id'] == 0) {
                $regions = self::$DB->select('SELECT * FROM ?_geo_tags WHERE parent_id=?d', $region['geo_tag_id']);

                $ids = array($region['geo_tag_id']);

                foreach ($regions as $r) {
                    array_push($ids, $r['geo_tag_id']);
                }

                $result = self::$DB->selectPage($totalRows, 'SELECT ?_artists.*
                    FROM ?_artists
                    WHERE ?_artists.geo_tag_id IN(?a)
                    ORDER BY ?_artists.name ASC
                    LIMIT ?d, ?d',
                    $ids,
                    $from, $count);

            } else {
                $result = self::$DB->selectPage($totalRows, 'SELECT ?_artists.*
                    FROM ?_artists
                    WHERE ?_artists.geo_tag_id=?d
                    ORDER BY ?_artists.name ASC
                    LIMIT ?d, ?d',
                    $region['geo_tag_id'],
                    $from, $count);
            }

            return $result;

        } else {

            return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_artists ORDER BY ?_artists.artist_id DESC LIMIT ?d, ?d', $from, $count);
        }
    }

    public static function getArtistsByGeoTag($region, $from = 0, $count = 10) {
        if ($region['parent_id'] == 0) {
            $regions = self::$DB->select('SELECT * FROM ?_geo_tags WHERE parent_id=?d', $region['geo_tag_id']);

            $ids = array($region['geo_tag_id']);

            foreach ($regions as $r) {
                array_push($ids, $r['geo_tag_id']);
            }

            $result = self::$DB->select('SELECT ?_artists.*
                FROM ?_artists
                WHERE ?_artists.geo_tag_id IN(?a)
                ORDER BY ?_artists.artist_id DESC
                LIMIT ?d, ?d',
                $ids,
                $from, $count);

        } else {
            $result = self::$DB->select('SELECT ?_artists.*
                FROM ?_artists
                WHERE ?_artists.geo_tag_id=?d
                ORDER BY ?_artists.artist_id DESC
                LIMIT ?d, ?d',
                $region['geo_tag_id'],
                $from, $count);
        }

        return $result;
    }

    public static function getLabelArtistsList() {
        $result = self::$DB->select('SELECT ?_artists.*
            FROM ?_artists
            WHERE ?_artists.label_artist = "1"
            ORDER BY ?_artists.artist_id');

        return $result;
    }

    public static function addRecommendsArtist($profile_id, $artist_id, $profile_type) {
        $r = self::$DB->selectRow('SELECT * FROM ?_recommends_artists WHERE profile_id=?d AND artist_id=?d AND profile_type=?d', $profile_id, $artist_id, $profile_type);
        if (empty($r)) {
            $row = array('profile_id' => $profile_id, 'artist_id' => $artist_id, 'profile_type' => $profile_type);
            self::$DB->query('INSERT INTO ?_recommends_artists(?#) VALUES(?a)', array_keys($row), array_values($row));
            return true;
        } else {
            return false;
        }
    }

    public static function deleteRecommendsArtist($profile_id, $artist_id, $profile_type) {
        return self::$DB->query('DELETE FROM ?_recommends_artists WHERE profile_id=?d AND artist_id=?d AND profile_type=?d', $profile_id, $artist_id, $profile_type);
    }

    public static function getRecommendsArtists($profile_id, $profile_type, $from = 0, $count = 50) {
        return self::$DB->select('SELECT ?_artists.*
            FROM ?_artists, ?_recommends_artists
            WHERE ?_artists.artist_id=?_recommends_artists.artist_id AND ?_recommends_artists.profile_id=?d
                AND ?_recommends_artists.profile_type=?d
            ORDER BY ?_artists.name ASC
            LIMIT ?d, ?d',
            $profile_id,
            $profile_type,
            $from, $count);
    }

    public static function newArtist($row) {
        return self::$DB->query('INSERT INTO ?_artists(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateArtistById($id, $row) {
        return self::$DB->query('UPDATE ?_artists SET ?a WHERE artist_id=?d', $row, $id);
    }

    public static function setArtistGenres($id, $ids) {
        self::$DB->query('DELETE FROM ?_artists_genres WHERE artist_id=?d', $id);
        if (!empty($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('artist_id' => $id, 'genre_id' => $v);
                self::$DB->query('INSERT INTO ?_artists_genres(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function deleteArtist($id) {
        $artist = self::getArtistById($id);
        if (empty($artist))
            return false;

        $articles = self::$DB->query('DELETE FROM ?_articles_artists WHERE artist_id=?', $id);
        $events = self::$DB->query('DELETE FROM ?_artists_events WHERE artist_id=?', $id);
        $genres = self::$DB->query('DELETE FROM ?_artists_genres WHERE artist_id=?', $id);
        $video = self::$DB->query('UPDATE ?_video SET artist_id=?d WHERE artist_id=?d', 0, $id);
        $recommends = self::$DB->query('DELETE FROM ?_recommends_artists WHERE profile_id=? AND profile_type=?', $id, RECOMMENDS_TYPE_ARTIST);

        require_once "DbReleases.php";
        $releases = DbReleases::deleteReleaseByArtistId($id);

        require_once "DbAudio.php";
        $audio = DbAudio::deleteAudioByArtistId($id);

        require_once "DbPhotos.php";
        $photos = DbPhotos::deletePhotosByParentId($id, PHOTO_TYPE_ARTIST);

        $files = array(
            __FFM_PROFILE__ . $artist['filename'] . '/a/s.jpg',
            __FFM_PROFILE__ . $artist['filename'] . '/a/m.jpg',
            __FFM_PROFILE__ . $artist['filename'] . '/a/b.jpg',
            __FFM_PROFILE__ . $artist['filename'] . '/a/o.jpg'
        );
        foreach ($files as $file) {
            if (file_exists($file))
                unlink($file);
        }

        $delete = self::$DB->query('DELETE FROM ?_artists WHERE artist_id=?', $id);

        return array(
            'delete' => $delete,
            'articles' => $articles,
            'events' => $events,
            'genres' => $genres,
            'video' => $video,
            'recommends' => $recommends,
            'releases' => $releases,
            'audio' => $audio,
            'photos' => $photos
        );
    }
}


