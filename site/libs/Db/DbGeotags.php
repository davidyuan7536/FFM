<?php

class DbGeotags {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function setGeoTagParentId($sourceId, $targetId) {
        return self::$DB->query('UPDATE ?_geo_tags SET parent_id=? WHERE geo_tag_id=?d', $targetId, $sourceId);
    }

    public static function getGeoTagById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_geo_tags WHERE geo_tag_id=?d', $id);
    }

    public static function getGeoTagByName($filename) {
        return self::$DB->selectRow('SELECT * FROM ?_geo_tags WHERE filename=?', $filename);
    }

    public static function getGeoTagByLabel($name) {
        return self::$DB->selectRow('SELECT * FROM ?_geo_tags WHERE name=?', $name);
    }

    public static function getGeoTags() {
        return self::$DB->select('SELECT * FROM ?_geo_tags WHERE parent_id!=0');
    }

    public static function getGeoTagsAsForest() {
        return self::$DB->select('SELECT *,
            ?_geo_tags.geo_tag_id AS ARRAY_KEY,
            ?_geo_tags.parent_id AS PARENT_KEY
            FROM ?_geo_tags
            ORDER BY ?_geo_tags.name ASC');
    }

    public static function getGeoTagsAsTree() {
        $forest = self::getGeoTagsAsForest();

        $tree = array();
        foreach ($forest as $branch) {
            $tree[$branch['filename']] = array(
                'geo_tag_id' => $branch['geo_tag_id'],
                'name' => $branch['name'],
                'childNodes' => $branch['childNodes']
            );
        }

        return $tree;
    }


    public static function newGeoTag($row) {
        return self::$DB->query('INSERT INTO ?_geo_tags(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateGeoTag($id, $row) {
        return self::$DB->query('UPDATE ?_geo_tags SET ?a WHERE geo_tag_id=?d', $row, $id);
    }

    public static function deleteGeoTag($id) {
        $delete = self::$DB->query('DELETE FROM ?_geo_tags WHERE geo_tag_id=?', $id);
        $articles = self::$DB->query('UPDATE ?_articles SET geo_tag_id=?d WHERE geo_tag_id=?d', 0, $id);
        $artists = self::$DB->query('UPDATE ?_artists SET geo_tag_id=?d WHERE geo_tag_id=?d', 0, $id);
        $events = self::$DB->query('UPDATE ?_events SET geo_tag_id=?d WHERE geo_tag_id=?d', 0, $id);
        $geoTags = self::$DB->query('UPDATE ?_geo_tags SET parent_id=?d WHERE parent_id=?d', 0, $id);

        return array(
            'delete' => $delete,
            'articles' => $articles,
            'artists' => $artists,
            'events' => $events,
            'geoTags' => $geoTags
        );
    }
}

