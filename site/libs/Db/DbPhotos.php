<?php

define('PHOTO_TYPE_ARTIST', '0');
define('PHOTO_TYPE_PROMOTER', '1');

class DbPhotos {
    private static $DB = NULL;

    private static function getDB() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
        return self::$DB;
    }

    public static function getPhotoById($id) {
        return self::getDB()->selectRow('SELECT * FROM ?_photos WHERE photo_id=?', $id);
    }

    public static function getPhotosByArtistId($id, $from = 0, $count = 100) {
        return self::getDB()->select('SELECT * FROM ?_photos
            WHERE parent_id=?d AND photo_type=?d
            ORDER BY photo_upload_time DESC
            LIMIT ?d, ?d',
            $id, PHOTO_TYPE_ARTIST, $from, $count);
    }

    public static function getPhotosByPromoterId($id, $from = 0, $count = 100) {
        return self::getDB()->select('SELECT * FROM ?_photos
            WHERE parent_id=?d AND photo_type=?d
            ORDER BY photo_upload_time DESC
            LIMIT ?d, ?d',
            $id, PHOTO_TYPE_PROMOTER, $from, $count);
    }

    public static function newPhoto($row) {
        return self::getDB()->query('INSERT INTO ?_photos(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updatePhotoById($id, $row) {
        return self::getDB()->query('UPDATE ?_photos SET ?a WHERE photo_id=?d', $row, $id);
    }

    public static function deletePhotosByParentId($id, $type) {
        $photos = self::getDB()->select('SELECT * FROM ?_photos
            WHERE parent_id=?d AND photo_type=?d', $id, $type);

        foreach ($photos as $photo) {
            self::deletePhotoById($photo['photo_id'], $type);
        }
    }

    public static function deleteArtistPhotoById($id) {
        self::deletePhotoById($id, PHOTO_TYPE_ARTIST);
    }

    public static function deletePromoterPhotoById($id) {
        self::deletePhotoById($id, PHOTO_TYPE_PROMOTER);
    }

    public static function deletePhotoById($id, $type) {
        $photo = self::getPhotoById($id);
        if (empty($photo))
            return false;

        if ($type == PHOTO_TYPE_ARTIST) {
            $profile = self::getDB()->selectRow('SELECT * FROM ?_artists WHERE artist_id=?d', $photo['parent_id']);
            if (empty($profile))
                return false;

            $d = __FFM_PROFILE__ . $profile['filename'];
        } else {
            $profile = self::getDB()->selectRow('SELECT * FROM ?_promoters WHERE promoter_id=?d', $photo['parent_id']);
            if (empty($profile))
                return false;

            $d = __FFM_PROMOTER__ . $profile['promoter_filename'];
        }

        unlink($d . '/b/' . $photo['photo_filename']);
        unlink($d . '/m/' . $photo['photo_filename']);
        unlink($d . '/o/' . $photo['photo_filename']);

        $delete = self::getDB()->query('DELETE FROM ?_photos WHERE photo_id=?', $id);
        return array('delete' => $delete);
    }
}