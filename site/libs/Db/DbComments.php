<?php

define('COMMENT_CATEGORY_ARTICLE', 1);
define('COMMENT_CATEGORY_ARTIST', 2);
define('COMMENT_CATEGORY_PROMOTER', 3);
define('COMMENT_CATEGORY_TRACK', 4);

class DbComments {
    private static $DB = NULL;

    private static function getDB() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
        return self::$DB;
    }

    public static function newComment($row) {
        return self::getDb()->query('INSERT INTO ?_comments(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function getCommentsByParentId($parent_id, $category, $from = 0, $count = 999) {
        return self::getDb()->select('SELECT *
            FROM ?_comments, ?_users
            WHERE ?_comments.parent_id=?d AND ?_comments.comment_category=?
                AND ?_users.user_id=?_comments.user_id
            ORDER BY ?_comments.comment_date DESC',
            $parent_id, $category, $from, $count);
    }

    public static function getCommentsByPage(&$totalRows, $from = 0, $count = 10) {
        require_once "Db/DbArticles.php";
        require_once "Db/DbArtists.php";
        require_once "Db/DbPromoters.php";
        $dbArticles = new DbArticles();
        $dbArtists = new DbArtists();

        $result = self::getDb()->selectPage($totalRows, 'SELECT *
            FROM ?_comments, ?_users
            WHERE ?_users.user_id=?_comments.user_id
            ORDER BY ?_comments.comment_date DESC
            LIMIT ?d, ?d',
            $from, $count);

        foreach ($result as &$v) {
            switch ($v['comment_category']) {
                case COMMENT_CATEGORY_ARTICLE:
                    $v['object'] = $dbArticles->getArticleById($v['parent_id']);
                    break;
                case COMMENT_CATEGORY_ARTIST:
                    $v['object'] = $dbArtists->getArtistById($v['parent_id']);
                    break;
                case COMMENT_CATEGORY_PROMOTER:
                    $v['object'] = DbPromoters::getPromoterById($v['parent_id']);
                    break;
            }
        }

        return $result;
    }

    public static function updateCommentById($id, $row) {
        return self::getDb()->query('UPDATE ?_comments SET ?a WHERE comment_id=?d', $row, $id);
    }

    public static function deleteCommentById($id) {
        return self::getDb()->query('DELETE FROM ?_comments WHERE comment_id=?d', $id);
    }
}
