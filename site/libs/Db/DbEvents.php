<?php

class DbEvents {
    private static $DB = NULL;

    public function __construct() {
        if (self::$DB == NULL) {
            require_once "DbWorker.php";
            self::$DB = DbWorker::getInstance()->getConnection();
        }
    }

    public static function getEventById($id) {
        return self::$DB->selectRow('SELECT * FROM ?_events WHERE event_id=?d', $id);
    }

    public static function getEventsBySearch($search, $from = 0, $count = 20) {
        return self::$DB->select('SELECT * FROM ?_events WHERE event_name LIKE ? ORDER BY event_date ASC LIMIT ?d, ?d', "%{$search}%", $from, $count);
    }

    public static function getEvents($from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_events ORDER BY event_date ASC LIMIT ?d, ?d', $from, $count);
    }

    public static function getEventsFromToday($from = 0, $count = 10) {
        return self::$DB->select('SELECT * FROM ?_events WHERE event_date >= ? ORDER BY event_date ASC LIMIT ?d, ?d', date('Y-m-d 00:00:00'), $from, $count);
    }

    public static function getEventsFromTodayByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_events WHERE event_date >= ? ORDER BY event_date ASC LIMIT ?d, ?d', date('Y-m-d 00:00:00'), $from, $count);
    }

    public static function getEventsByPage(&$totalRows, $from = 0, $count = 10) {
        return self::$DB->selectPage($totalRows, 'SELECT * FROM ?_events ORDER BY event_date DESC LIMIT ?d, ?d', $from, $count);
    }

    public static function getEventsByArtistId($artistId, $from = 0, $count = 10) {
        return self::$DB->select('SELECT ?_events.*
            FROM ?_events, ?_artists_events
            WHERE ?_events.event_id=?_artists_events.event_id
                AND ?_artists_events.artist_id=?
            ORDER BY ?_events.event_date DESC
            LIMIT ?d, ?d',
            $artistId, $from, $count);
    }

    public static function getEventsByPromoterId($id, $from = 0, $count = 10) {
        $artists = self::$DB->select('SELECT ?_promoters_artists.artist_id
            FROM ?_promoters_artists
            WHERE ?_promoters_artists.promoter_id=?d', $id);

        $ids = array();
        foreach ($artists as $r) {
            array_push($ids, $r['artist_id']);
        }

        return empty($ids) ? array() : self::$DB->select('SELECT DISTINCT ?_events.*
            FROM ?_events, ?_artists_events
            WHERE ?_events.event_id=?_artists_events.event_id
                AND ?_artists_events.artist_id IN(?a)
            ORDER BY ?_events.event_date DESC
            LIMIT ?d, ?d',
            $ids, $from, $count);
    }

    public static function newEvent($row) {
        return self::$DB->query('INSERT INTO ?_events(?#) VALUES(?a)', array_keys($row), array_values($row));
    }

    public static function updateEventById($id, $row) {
        return self::$DB->query('UPDATE ?_events SET ?a WHERE event_id=?d', $row, $id);
    }

    public static function setEventArtists($id, $ids) {
        self::$DB->query('DELETE FROM ?_artists_events WHERE event_id=?d', $id);
        if (count($ids) > 0) {
            foreach ($ids as $v) {
                $row = array('event_id' => $id, 'artist_id' => $v);
                self::$DB->query('INSERT INTO ?_artists_events(?#) VALUES(?a)', array_keys($row), array_values($row));
            }
        }
    }

    public static function setEventImageById($id, $image) {
        return self::$DB->query('UPDATE ?_events SET event_image=? WHERE event_id=?d', $image, $id);
    }

    public static function deleteEvent($id) {
        $delete = self::$DB->query('DELETE FROM ?_events WHERE event_id=?', $id);
        $artists = self::$DB->query('DELETE FROM ?_artists_events WHERE event_id=?', $id);

        return array(
            'delete' => $delete,
            'artists' => $artists
        );
    }
}


