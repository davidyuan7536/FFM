<?php

class Events extends RequestHandler {
    public function get() {
        $videos = null;
        $audios = null;

        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();

        require_once "Db/DbEvents.php";
        $dbEvents = new DbEvents();

        $promoEvents = $dbEvents->getEventsFromToday(0, 5);

        $nextEvents = $dbEvents->getEventsFromToday(0, 20);
        $events = array();
        foreach ($nextEvents as &$val) {
            $date = strtotime($val['event_date']);
            $i = date('Y-m-d', $date);
            if (!isset($events[$i])) {
                $events[$i] = array(
                    'date' => $date,
                    'events' => array()
                );
            }
            if ($val['geo_tag_id'] != 0) {
                $val['geo_tag'] = $dbGeoTags->getGeoTagById($val['geo_tag_id']);
            }
            array_push($events[$i]['events'], $val);
        }

        if (!empty($promoEvents)) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $_ids = array();
            foreach ($promoEvents as $_event) {
                array_push($_ids, $_event['event_id']);
            }
            $artistIds = $dbArtists->getArtistsIdsByEventIds($_ids, 0, 10);
            if (!empty($artistIds)) {
                require_once "Db/DbVideo.php";
                $dbVideo = new DbVideo();
                $videos = $dbVideo->getVideosByArtistIds($artistIds, 0, 2);
                foreach ($videos as &$video) {
                    $_artist = $dbArtists->getArtistById($video['artist_id']);
                    $video['artist'] = $_artist;
                }
                require_once "Db/DbAudio.php";
                $dbAudio = new DbAudio();
                $audios = $dbAudio->getAudioByArtistIds($artistIds, 0, 5);
                foreach ($audios as &$audio) {
                    $_artist = $dbArtists->getArtistById($audio['artist_id']);
                    $audio['artist'] = $_artist;
                }
            }
        }

        $response = new Response('events.tpl');
        $response->assign('Title', $GLOBALS[FFM_LANG]['headers']['events']);
        $response->assign('Section', 'events');
        $response->assign('PromoEvents', $promoEvents);
        $response->assign('Events', $events);
        $response->assign('Videos', $videos);
        $response->assign('Audios', $audios);
        $response->write();
    }
}

$app = new Application(array(
    array('/^(\/events\/?)$/', 'Events', true)
));

$app->run();

