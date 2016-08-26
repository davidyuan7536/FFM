<?php

define('PROMOTER_STATUS_PERSON', '1');
define('PROMOTER_STATUS_CLUB', '2');

class DbPromoters {
    private static $DB = NULL;

    private static function getDB() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
        return self::$DB;
    }

    public static function getPromotersBySearch($search, $from = 0, $count = 20) {
        return self::getDB()->select('SELECT * FROM ?_promoters
            WHERE promoter_name LIKE ?
                OR promoter_name_ru LIKE ?
            ORDER BY ?_promoters.promoter_id DESC
            LIMIT ?d, ?d',
            "%{$search}%", "%{$search}%",
            $from, $count);
    }

    public static function getPromotersByPage(&$totalRows, $from = 0, $count = 10) {
        return self::getDB()->selectPage($totalRows, 'SELECT * FROM ?_promoters ORDER BY ?_promoters.promoter_id DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getPromoterByUserId($id, $from = 0, $count = 20) {
        return self::getDB()->select('SELECT * FROM ?_promoters WHERE user_id=?d LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getPromoterByName($filename) {
        return self::getDB()->selectRow('SELECT * FROM ?_promoters WHERE promoter_filename=?', $filename);
    }

    public static function getPromoterById($id) {
        return self::getDB()->selectRow('SELECT * FROM ?_promoters WHERE promoter_id=?d', $id);
    }

    public static function getLatestPromoters($count = 10) {
        $result = self::getDB()->select('SELECT ?_promoters.*
            FROM ?_promoters
            ORDER BY ?_promoters.promoter_id DESC
            LIMIT ?d, ?d', 0, $count);
        return $result;
    }

    public static function getPromotersFilteredByPage(&$genre, &$region, &$totalRows, $from = 0, $count = 10) {
        if (isset($genre)) {
            $genre = self::getDB()->selectRow('SELECT * FROM ?_genres WHERE filename=?', $genre);
        }
        if (isset($region)) {
            $region = self::getDB()->selectRow('SELECT * FROM ?_geo_tags WHERE filename=?', $region);
        }

        if ($genre != array()) {

            $result = self::getDB()->selectPage($totalRows, 'SELECT ?_promoters.*
                FROM ?_promoters, ?_promoters_genres
                WHERE ?_promoters.promoter_id=?_promoters_genres.promoter_id AND ?_promoters_genres.genre_id=?d
                ORDER BY ?_promoters.promoter_name ASC
                LIMIT ?d, ?d',
                $genre['genre_id'],
                $from, $count);

            return $result;

        } else if ($region != array()) {

            if ($region['parent_id'] == 0) {
                $regions = self::getDB()->select('SELECT * FROM ?_geo_tags WHERE parent_id=?d', $region['geo_tag_id']);

                $ids = array($region['geo_tag_id']);

                foreach ($regions as $r) {
                    array_push($ids, $r['geo_tag_id']);
                }

                $result = self::getDB()->selectPage($totalRows, 'SELECT ?_promoters.*
                    FROM ?_promoters
                    WHERE ?_promoters.geo_tag_id IN(?a)
                    ORDER BY ?_promoters.promoter_name ASC
                    LIMIT ?d, ?d',
                    $ids,
                    $from, $count);

            } else {
                $result = self::getDB()->selectPage($totalRows, 'SELECT ?_promoters.*
                    FROM ?_promoters
                    WHERE ?_promoters.geo_tag_id=?d
                    ORDER BY ?_promoters.promoter_name ASC
                    LIMIT ?d, ?d',
                    $region['geo_tag_id'],
                    $from, $count);
            }

            return $result;

        } else {

            return self::getDB()->selectPage($totalRows, 'SELECT * FROM ?_promoters ORDER BY ?_promoters.promoter_id DESC LIMIT ?d, ?d', $from, $count);
        }
    }

//    public static function getPromotersByGeoTag($region, $from = 0, $count = 10) {
//        if ($region['parent_id'] == 0) {
//            $regions = self::getDB()->select('SELECT * FROM ?_geo_tags WHERE parent_id=?d', $region['geo_tag_id']);
//
//            $ids = array($region['geo_tag_id']);
//
//            foreach ($regions as $r) {
//                array_push($ids, $r['geo_tag_id']);
//            }
//
//            $result = self::getDB()->select('SELECT ?_promoters.*
//                FROM ?_promoters
//                WHERE ?_promoters.geo_tag_id IN(?a)
//                ORDER BY ?_promoters.promoter_id DESC
//                LIMIT ?d, ?d',
//                $ids,
//                $from, $count);
//
//        } else {
//            $result = self::getDB()->select('SELECT ?_promoters.*
//                FROM ?_promoters
//                WHERE ?_promoters.geo_tag_id=?d
//                ORDER BY ?_promoters.promoter_id DESC
//                LIMIT ?d, ?d',
//                $region['geo_tag_id'],
//                $from, $count);
//        }
//
//        return $result;
//    }

    public static function newPromoter($row) {
        return self::getDB()->query('INSERT INTO ?_promoters(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updatePromoterById($id, $row) {
        return self::getDB()->query('UPDATE ?_promoters SET ?a WHERE promoter_id=?d', $row, $id);
    }

    public static function setPromoterGenres($id, $ids) {
        self::getDB()->query('DELETE FROM ?_promoters_genres WHERE promoter_id=?d', $id);
        if (!empty($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('promoter_id' => $id, 'genre_id' => $v);
                self::getDB()->query('INSERT INTO ?_promoters_genres(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function addPromotersArtist($promoter_id, $artist_id) {
        $r = self::getDB()->selectRow('SELECT * FROM ?_promoters_artists WHERE promoter_id=?d AND artist_id=?d', $promoter_id, $artist_id);
        if (empty($r)) {
            $row = array('promoter_id' => $promoter_id, 'artist_id' => $artist_id);
            self::getDB()->query('INSERT INTO ?_promoters_artists(?#) VALUES(?a)', array_keys($row), array_values($row));
            return true;
        } else {
            return false;
        }
    }

    public static function deletePromotersArtist($promoter_id, $artist_id) {
        return self::getDB()->query('DELETE FROM ?_promoters_artists WHERE promoter_id=?d AND artist_id=?d', $promoter_id, $artist_id);
    }

    public static function getPromotersArtists($promoter_id, $from = 0, $count = 50) {
        return self::getDB()->select('SELECT ?_artists.*
            FROM ?_artists, ?_promoters_artists
            WHERE ?_artists.artist_id=?_promoters_artists.artist_id AND ?_promoters_artists.promoter_id=?d
            ORDER BY ?_artists.name ASC
            LIMIT ?d, ?d',
            $promoter_id,
            $from, $count);
    }

    public static function setPromoterImageById($id, $image) {
        return self::getDB()->query('UPDATE ?_promoters SET promoter_image=? WHERE promoter_id=?d', $image, $id);
    }

    public static function deletePromoter($id) {
        $promoter = self::getPromoterById($id);
        if (empty($promoter))
            return false;

        $artists = self::getDB()->query('DELETE FROM ?_promoters_artists WHERE promoter_id=?', $id);
        $genres = self::getDB()->query('DELETE FROM ?_promoters_genres WHERE promoter_id=?', $id);

        require_once "DbArtists.php";
        $recommends = self::getDB()->query('DELETE FROM ?_recommends_artists WHERE profile_id=? AND profile_type=?', $id, RECOMMENDS_TYPE_PROMOTER);

        require_once "DbPhotos.php";
        $photos = DbPhotos::deletePhotosByParentId($id, PHOTO_TYPE_PROMOTER);

        $files = array(
            __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/a/s.jpg',
            __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/a/m.jpg',
            __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/a/b.jpg',
            __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/a/o.jpg'
        );
        foreach ($files as $file) {
            if (file_exists($file))
                unlink($file);
        }

        $delete = self::getDB()->query('DELETE FROM ?_promoters WHERE promoter_id=?', $id);

        return array(
            'delete' => $delete,
            'artists' => $artists,
            'genres' => $genres,
            'recommends' => $recommends,
            'photos' => $photos
        );
    }
}


