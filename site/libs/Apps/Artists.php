<?php

class Artists extends RequestHandler {
    public function get() {
        $pagesToSide = 5;
        $itemsPerPage = 12;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $genre = isset($_GET['genre']) ? $_GET['genre'] : null;
        $region = isset($_GET['region']) ? $_GET['region'] : null;

        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists = $dbArtists->getArtistsFilteredByPage(
            $genre,
            $region,
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Db/DbAudio.php";
        $dbAudio = new DbAudio();
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        foreach ($artists as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id']);
            $artist['genres'] = $_genres;
            $a = $dbAudio->getAudioByArtistId($artist['artist_id'], 0, 1);
            $artist['audio'] = empty($a) ? null : $a[0];
        }

        $genres = $dbGenres->getGenresAsTree();

        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $geoTags = $dbGeoTags->getGeoTagsAsTree();

        $link = '/artists/?';
        if ($genre != array()) {
            $link .= "genre={$genre['filename']}&";
        }
        if ($region != array()) {
            $link .= "region={$region['filename']}&";
        }
        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks($link . 'page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);


        $response = new Response('artists.tpl');
        $response->assign('Artists', $artists);

        if ($genre != array()) {
            $response->assign('Title', $genre['name']);
            $response->assign('Filtered', true);
        } else if ($region != array()) {
            $response->assign('Title', $region['name']);
            $response->assign('Filtered', true);
        } else {
            $response->assign('Title', $GLOBALS[FFM_LANG]['headers']['artists']);
            $response->assign('Filtered', false);
        }

        $response->assign('Section', 'artists');
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->assign('Genres', $genres);
        $response->assign('GeoTags', $geoTags);
        $response->write();
    }
}

$app = new Application(array(
    array('/^(\/artists\/?)$/', 'Artists', true)
));

$app->run();

