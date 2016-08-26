<?php

class DbGenres {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function setGenreParentId($sourceId, $targetId) {
        return self::$DB->query('UPDATE ?_genres SET parent_id=? WHERE genre_id=?', $targetId, $sourceId);
    }

    public static function getGenresAsForest() {
        return self::$DB->select('SELECT *,
            ?_genres.genre_id AS ARRAY_KEY,
            ?_genres.parent_id AS PARENT_KEY
            FROM ?_genres
            ORDER BY  ?_genres.name ASC');
    }

    public static function getGenresByArticleId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_genres.*
            FROM ?_genres, ?_articles_genres
            WHERE ?_genres.genre_id=?_articles_genres.genre_id AND ?_articles_genres.article_id=?
            ORDER BY ?_genres.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getGenresByArtistId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_genres.*
            FROM ?_genres, ?_artists_genres
            WHERE ?_genres.genre_id=?_artists_genres.genre_id AND ?_artists_genres.artist_id=?
            ORDER BY ?_genres.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getGenresByPromoterId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_genres.*
            FROM ?_genres, ?_promoters_genres
            WHERE ?_genres.genre_id=?_promoters_genres.genre_id AND ?_promoters_genres.promoter_id=?
            ORDER BY ?_genres.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getGenresByReleaseId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_genres.*
            FROM ?_genres, ?_releases_genres
            WHERE ?_genres.genre_id=?_releases_genres.genre_id AND ?_releases_genres.release_id=?
            ORDER BY ?_genres.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getGenresByTrackId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_genres.*
            FROM ?_genres, ?_tracks_genres
            WHERE ?_genres.genre_id=?_tracks_genres.genre_id AND ?_tracks_genres.track_id=?
            ORDER BY ?_genres.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getGenresByLabelReleaseId($id, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_genres.*
            FROM ?_genres, ?_label_releases_genres
            WHERE ?_genres.genre_id=?_label_releases_genres.genre_id AND ?_label_releases_genres.release_id=?
            ORDER BY ?_genres.name ASC LIMIT ?d, ?d', $id, $from, $count);
    }

    public static function getGenresByName($search, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_genres.*
            FROM ?_genres
            WHERE name LIKE ? OR filename LIKE ?  
            ORDER BY ?_genres.name ASC LIMIT ?d, ?d',
            "%{$search}%", "%{$search}%",
            $from, $count);
    }

    public static function getGenresAsTree() {
        $forest = self::getGenresAsForest();

        $tree = array();
        foreach ($forest as $branch) {
            $tree[$branch['filename']] = array(
                'genre_id' => $branch['genre_id'],
                'name' => $branch['name'],
                'childNodes' => $branch['childNodes']
            );
        }

        return $tree;
    }

    public static function newGenre($name, $filename) {
        return self::$DB->query('INSERT INTO ?_genres(`name`, `filename`) VALUES(?, ?)', $name, $filename);
    }

    public static function updateGenre($id, $name, $filename) {
        return self::$DB->query('UPDATE ?_genres SET `name`=?, `filename`=? WHERE genre_id=?', $name, $filename, $id);
    }

    public static function deleteGenre($id) {
        $delete = self::$DB->query('DELETE FROM ?_genres WHERE genre_id=?', $id);
        $articles = self::$DB->query('DELETE FROM ?_articles_genres WHERE genre_id=?', $id);
        $artists = self::$DB->query('DELETE FROM ?_artists_genres WHERE genre_id=?', $id);
        $promoters = self::$DB->query('DELETE FROM ?_promoters_genres WHERE genre_id=?', $id);
        $releases = self::$DB->query('DELETE FROM ?_releases_genres WHERE genre_id=?', $id);
        $tracks = self::$DB->query('DELETE FROM ?_tracks_genres WHERE genre_id=?', $id);
        $genres = self::$DB->query('UPDATE ?_genres SET parent_id=?d WHERE parent_id=?d', 0, $id);

        return array(
            'delete' => $delete,
            'articles' => $articles,
            'artists' => $artists,
            'promoters' => $promoters,
            'releases' => $releases,
            'tracks' => $tracks,
            'genres' => $genres
        );
    }
}


