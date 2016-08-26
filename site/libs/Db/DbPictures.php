<?php

class DbPictures {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function getPictureYears() {
        return self::$DB->select('SELECT COUNT( * ) AS Rows, picture_year
            FROM ?_pictures
            GROUP BY picture_year
            ORDER BY picture_year DESC');
    }

    public static function getPictureMonths($year) {
        return self::$DB->select('SELECT COUNT( * ) AS Rows, picture_month
            FROM ?_pictures
            WHERE picture_year =  ?
            GROUP BY picture_month
            ORDER BY picture_month',
            $year
        );
    }

    public static function getPicturesByMonth($year, $month, $from = 0, $count = 1000) {
        return self::$DB->select('SELECT *
            FROM ?_pictures
            WHERE picture_year=? AND picture_month=?
            LIMIT ?d, ?d',
            $year, $month,
            $from, $count);
    }

    public static function getPicturesByArticleId($id, $from = 0, $count = 100) {
        return self::$DB->select('SELECT *
            FROM ?_pictures
            WHERE article_id=?d
            LIMIT ?d, ?d',
            $id,
            $from, $count);
    }

    public static function getPictureByName($year, $month, $filename) {
        return self::$DB->selectRow('SELECT *
            FROM ?_pictures
            WHERE picture_year=?
                AND picture_month=?
                AND picture_filename=?',
            $year, $month,
            $filename);
    }

    public static function getPictureById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_pictures WHERE picture_id=?d', $id);
    }

    public static function newPicture($row) {
        return self::$DB->query('INSERT INTO ?_pictures(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function deletePicture($id) {
        $picture = self::getPictureById($id);
        $dir = __FFM_PICTURES__ . "{$picture['picture_year']}/{$picture['picture_month']}/";
        unlink($dir . $picture['o_filename']);
        unlink($dir . $picture['s_filename']);
        if (!empty($picture['m_filename'])) {
            unlink($dir . $picture['m_filename']);
        }

        $delete = self::$DB->query('DELETE FROM ?_pictures WHERE picture_id=?', $id);

        return array(
            'delete' => $delete,
        );
    }
}


