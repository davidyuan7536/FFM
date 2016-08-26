<?php

class Promoters extends RequestHandler {
    public function get() {
        $pagesToSide = 5;
        $itemsPerPage = 12;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $genre = isset($_GET['genre']) ? $_GET['genre'] : null;
        $region = isset($_GET['region']) ? $_GET['region'] : null;

        require_once "Db/DbPromoters.php";
        $promoters = DbPromoters::getPromotersFilteredByPage(
            $genre,
            $region,
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        foreach ($promoters as &$promoter) {
            $_genres = $dbGenres->getGenresByPromoterId($promoter['promoter_id']);
            $promoter['genres'] = $_genres;
        }

        $genres = $dbGenres->getGenresAsTree();

        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $geoTags = $dbGeoTags->getGeoTagsAsTree();

        $link = '/promoters/?';
        if ($genre != array()) {
            $link .= "genre={$genre['filename']}&";
        }
        if ($region != array()) {
            $link .= "region={$region['filename']}&";
        }
        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks($link . 'page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);


        $response = new Response('promoters.tpl');
        $response->assign('Promoters', $promoters);

        if ($genre != array()) {
            $response->assign('Title', $genre['name']);
            $response->assign('Filtered', true);
        } else if ($region != array()) {
            $response->assign('Title', $region['name']);
            $response->assign('Filtered', true);
        } else {
            $response->assign('Title', $GLOBALS[FFM_LANG]['headers']['promoters']);
            $response->assign('Filtered', false);
        }

        $response->assign('Section', 'promoters');
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->assign('Genres', $genres);
        $response->assign('GeoTags', $geoTags);
        $response->write();
    }
}

$app = new Application(array(
    array('/^(\/promoters\/?)$/', 'Promoters', true)
));

$app->run();

