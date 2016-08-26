<?php

define('STATUS_PUBLISH', 'publish');

class DbArticles {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function getArticlesBySearch($search, $from = 0, $count = 50) {
        return self::$DB->select('SELECT * FROM ?_articles WHERE title LIKE ? ORDER BY ?_articles.date DESC LIMIT ?d, ?d', "%{$search}%", $from, $count);
    }

    /**
     * PUBLIC
     */
    public static function getArticles($from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_articles WHERE status=? ORDER BY ?_articles.date DESC LIMIT ?d, ?d', STATUS_PUBLISH, $from, $count);
    }

    public static function getArticlesByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_articles ORDER BY ?_articles.date DESC LIMIT ?d, ?d', $from, $count);
    }

    /**
     * PUBLIC
     */
    public static function getArticlesFilteredByPage(&$genre, &$region, &$totalRows, $from = 0, $count = 10) {
        if (isset($genre)) {
            $genre = self::$DB->selectRow('SELECT * FROM ?_genres WHERE filename=?', $genre);
        }
        if (isset($region)) {
            $region = self::$DB->selectRow('SELECT * FROM ?_geo_tags WHERE filename=?', $region);
        }

        if ($genre != array()) {

            $result = self::$DB->selectPage($totalRows, 'SELECT ?_articles.*
                FROM ?_articles, ?_articles_genres
                WHERE ?_articles.article_id=?_articles_genres.article_id
                    AND ?_articles_genres.genre_id=?
                    AND ?_articles.status=?
                ORDER BY ?_articles.date DESC
                LIMIT ?d, ?d',
                $genre['genre_id'], STATUS_PUBLISH,
                $from, $count);

        } else if ($region != array()) {

            if ($region['parent_id'] == 0) {
                $regions = self::$DB->select('SELECT * FROM ?_geo_tags WHERE parent_id=?d', $region['geo_tag_id']);

                $ids = array($region['geo_tag_id']);

                foreach ($regions as $r) {
                    array_push($ids, $r['geo_tag_id']);
                }

                $result = self::$DB->selectPage($totalRows, 'SELECT ?_articles.*
                    FROM ?_articles
                    WHERE ?_articles.geo_tag_id IN(?a) AND ?_articles.status=?
                    ORDER BY ?_articles.date DESC
                    LIMIT ?d, ?d',
                    $ids, STATUS_PUBLISH,
                    $from, $count);

            } else {
                $result = self::$DB->selectPage($totalRows, 'SELECT ?_articles.*
                    FROM ?_articles
                    WHERE ?_articles.geo_tag_id=?d AND ?_articles.status=?
                    ORDER BY ?_articles.date DESC
                    LIMIT ?d, ?d',
                    $region['geo_tag_id'], STATUS_PUBLISH,
                    $from, $count);
            }

        } else {
            $result = self::$DB->selectPage($totalRows, 'SELECT *
                FROM ?_articles
                WHERE status=?
                ORDER BY ?_articles.date
                DESC LIMIT ?d, ?d',
                STATUS_PUBLISH,
                $from, $count);
        }

        return $result;
    }

    /**
     * PUBLIC
     */
    public static function getArticlesByGenre($genre, $count = 10) {
        $genre = self::$DB->selectRow('SELECT * FROM ?_genres WHERE filename=?', $genre);
        if ($genre == array()) {
            return false;
        } else {
            return self::$DB->select('SELECT ?_articles.*
                FROM ?_articles, ?_articles_genres
                WHERE ?_articles.article_id=?_articles_genres.article_id
                    AND ?_articles_genres.genre_id=?
                    AND ?_articles.status=?
                ORDER BY ?_articles.date DESC LIMIT ?d, ?d',
                $genre['genre_id'], STATUS_PUBLISH, 0, $count);
        }
    }

    public static function getArticleByName($filename) {
        return self::$DB->selectRow('SELECT * FROM ?_articles WHERE filename=?', $filename);
    }

    public static function getArticleById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_articles WHERE article_id=?d', $id);
    }

    public static function getArticlesByArtistId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_articles.*
            FROM ?_articles, ?_articles_artists
            WHERE ?_articles.article_id=?_articles_artists.article_id
                AND ?_articles_artists.artist_id=?
                AND ?_articles.status=?
            ORDER BY ?_articles.date
            DESC LIMIT ?d, ?d',
            $id, STATUS_PUBLISH,
            $from, $count);
    }

    public static function getArticlesByPromoterId($id, $from = 0, $count = 10) {
        $artists = self::$DB->select('SELECT ?_promoters_artists.artist_id
            FROM ?_promoters_artists
            WHERE ?_promoters_artists.promoter_id=?d', $id);

        $ids = array();
        foreach ($artists as $r) {
            array_push($ids, $r['artist_id']);
        }

        return empty($ids) ? array() : self::$DB->select('SELECT DISTINCT ?_articles.*
            FROM ?_articles, ?_articles_artists
            WHERE ?_articles.article_id=?_articles_artists.article_id
                AND ?_articles_artists.artist_id IN(?a) AND ?_articles.status=?
            ORDER BY ?_articles.date DESC
            LIMIT ?d, ?d',
            $ids, STATUS_PUBLISH,
            $from, $count);
    }

    public static function newArticle($row) {
        return self::$DB->query('INSERT INTO ?_articles(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateArticleById($id, $row) {
        return self::$DB->query('UPDATE ?_articles SET ?a WHERE article_id=?d', $row, $id);
    }

    public static function setArticleGenres($id, $ids) {
        self::$DB->query('DELETE FROM ?_articles_genres WHERE article_id=?d', $id);
        if (count($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('article_id' => $id, 'genre_id' => $v);
                self::$DB->query('INSERT INTO ?_articles_genres(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function setArticleArtists($id, $ids) {
        self::$DB->query('DELETE FROM ?_articles_artists WHERE article_id=?d', $id);
        if (count($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('article_id' => $id, 'artist_id' => $v);
                self::$DB->query('INSERT INTO ?_articles_artists(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function setArticleAudio($id, $ids) {
        self::$DB->query('DELETE FROM ?_articles_audio WHERE article_id=?d', $id);
        if (count($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('article_id' => $id, 'audio_id' => $v);
                self::$DB->query('INSERT INTO ?_articles_audio(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function addArticleAudio($id, $audio_id) {
        $row = array('article_id' => $id, 'audio_id' => $audio_id);
        return self::$DB->query('INSERT INTO ?_articles_audio(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function removeArticleAudio($id, $audio_id) {
        return self::$DB->query('DELETE FROM ?_articles_audio
            WHERE article_id=?d
                AND audio_id=?d',
            $id, $audio_id);
    }

    public static function setArticleImageById($id, $image) {
        return self::$DB->query('UPDATE ?_articles SET image=? WHERE article_id=?d', $image, $id);
    }

    public static function deleteArticle($id) {
        $delete = self::$DB->query('DELETE FROM ?_articles WHERE article_id=?', $id);
        $articles = self::$DB->query('DELETE FROM ?_articles_artists WHERE article_id=?', $id);
        $events = self::$DB->query('DELETE FROM ?_articles_audio WHERE article_id=?', $id);
        $genres = self::$DB->query('DELETE FROM ?_articles_genres WHERE article_id=?', $id);
        $pictures = self::$DB->query('UPDATE ?_pictures SET article_id=?d WHERE article_id=?d', 0, $id);

        return array(
            'delete' => $delete,
            'articles' => $articles,
            'events' => $events,
            'genres' => $genres,
            'pictures' => $pictures
        );
    }
}


