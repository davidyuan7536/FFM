<?php

class HomePage extends RequestHandler {
    public function get() {
        $RU = 2;
        $UA = 8;
        $BY = 1;

        $articles = array();

        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles['dance'] = $dbArticles->getArticlesByGenre('dance', 3);
        $articles['electronic'] = $dbArticles->getArticlesByGenre('electronic', 3);
        $articles['pop'] = $dbArticles->getArticlesByGenre('pop', 3);
        $articles['rock'] = $dbArticles->getArticlesByGenre('rock', 3);
        $articles['latest'] = $dbArticles->getArticles(0, 5);

        $artists = array();
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists['ru'] = $dbArtists->getLatestArtistsByCountryId($RU, 0, 4);
        $artists['ua'] = $dbArtists->getLatestArtistsByCountryId($UA, 0, 4);
        $artists['by'] = $dbArtists->getLatestArtistsByCountryId($BY, 0, 4);
        $artists['zz'] = $dbArtists->getLatestArtistsExcludeCountryId(array($RU, $UA, $BY), 0, 4);

        require_once "Db/DbAudio.php";
        $dbAudio = new DbAudio();
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        foreach ($artists['ru'] as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id'], 0, 5);
            $artist['genres'] = $_genres;
            $a = $dbAudio->getAudioByArtistId($artist['artist_id'], 0, 1);
            $artist['audio'] = empty($a) ? null : $a[0];
        }
        foreach ($artists['ua'] as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id'], 0, 5);
            $artist['genres'] = $_genres;
            $a = $dbAudio->getAudioByArtistId($artist['artist_id'], 0, 1);
            $artist['audio'] = empty($a) ? null : $a[0];
        }
        foreach ($artists['by'] as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id'], 0, 5);
            $artist['genres'] = $_genres;
            $a = $dbAudio->getAudioByArtistId($artist['artist_id'], 0, 1);
            $artist['audio'] = empty($a) ? null : $a[0];
        }
        foreach ($artists['zz'] as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id'], 0, 5);
            $artist['genres'] = $_genres;
            $a = $dbAudio->getAudioByArtistId($artist['artist_id'], 0, 1);
            $artist['audio'] = empty($a) ? null : $a[0];
        }

        require_once "Db/DbGeotags.php";
        $dbGeotags = new DbGeotags();
        $top = $dbArtists->getTopArtists();
        foreach ($top as &$artist) {
            $a = $dbAudio->getAudioByArtistId($artist['artist_id'], 0, 1);
            $artist['audio'] = empty($a) ? null : $a[0];
            $artist['geo_tag'] = $dbGeotags->getGeoTagById($artist['geo_tag_id']);
        }

        require_once "Db/DbPromoters.php";
        $promoters = DbPromoters::getLatestPromoters(4);
        foreach ($promoters as &$promoter) {
            $_genres = $dbGenres->getGenresByPromoterId($promoter['promoter_id'], 0, 5);
            $promoter['genres'] = $_genres;
        }

        require_once "Db/DbEvents.php";
        $dbEvents = new DbEvents();
        $events = $dbEvents->getEventsFromToday(0, 4);

        require_once "Db/DbVideo.php";
        $dbVideo = new DbVideo();
        $videos = $dbVideo->getVideos(0, 2);

        $audios = $dbAudio->getAudioTracksGroupedLatest();
        foreach ($audios as &$audio) {
            $_artist = $dbArtists->getArtistById($audio['artist_id']);
            $audio['artist'] = $_artist;
        }

        require_once "Db/DbLabelReleases.php";
        $dbLabelReleases = new DbLabelReleases();

        $releases = $dbLabelReleases->getReleases(0, 4);

        foreach ($releases as &$release) {
            $release['genres'] = $dbGenres->getGenresByLabelReleaseId($release['release_id'], 0, 50);

            $releaseArtists = $dbArtists->getArtistsByLabelReleaseId($release['release_id'], 0, 50);
            if (count($releaseArtists) > 1) {
                $release['artist'] = 'Various artists';
            } else {
                $release['artist'] = $releaseArtists[0];
            }

            $release['geo_tag'] = $dbGeotags->getGeoTagById($release['geo_tag_id']);
        }

        $response = new Response('index.tpl');
        $response->assign('Section', 'home');
        $response->assign('Articles', $articles);
        $response->assign('Artists', $artists);
        $response->assign('Top', $top);
        $response->assign('Promoters', $promoters);
        $response->assign('Events', $events);
        $response->assign('Videos', $videos);
        $response->assign('Audios', $audios);
        $response->assign('Releases', $releases);
        $response->write();
    }
}

$app = new Application(array(
    array('/^\/(index.php)?$/', 'HomePage', false)
));

$app->run();

