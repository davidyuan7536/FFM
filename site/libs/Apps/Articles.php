<?php

class Articles extends RequestHandler {
    public function get($parameters) {
        $pagesToSide = 5;
        $itemsPerPage = 10;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $genre = isset($_GET['genre']) ? $_GET['genre'] : null;
        $region = isset($_GET['region']) ? $_GET['region'] : null;

        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticlesFilteredByPage(
            $genre,
            $region,
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $geoTags = $dbGeoTags->getGeoTagsAsTree();

        $link = '/articles/?';
        if ($genre != array()) {
            $link .= "genre={$genre['filename']}&";
        }
        if ($region != array()) {
            $link .= "region={$region['filename']}&";
        }
        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks($link . 'page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $genres = $dbGenres->getGenresAsTree();

        if ($region != array()) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $artists = $dbArtists->getArtistsByGeoTag($region);
            foreach ($artists as &$artist) {
                $_genres = $dbGenres->getGenresByArtistId($artist['artist_id']);
                $artist['genres'] = $_genres;
            }
        } elseif (!empty($articles)) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $ids = array();
            foreach ($articles as $_article) {
                array_push($ids, $_article['article_id']);
            }
            $artists = $dbArtists->getArtistsByArticleIds($ids);
            foreach ($artists as &$artist) {
                $_genres = $dbGenres->getGenresByArtistId($artist['artist_id']);
                $artist['genres'] = $_genres;
            }
        }

        $response = new Response('articles.tpl');
        $response->assign('Articles', $articles);

        if ($genre != array()) {
            $response->assign('Title', $genre['name']);
            $response->assign('Filtered', true);
        } else if ($region != array()) {
            $response->assign('Title', $region['name']);
            $response->assign('Filtered', true);
        } else {
            $response->assign('Title', $GLOBALS[FFM_LANG]['headers']['articles']);
            $response->assign('Filtered', false);
        }

        $response->assign('Section', 'articles');
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->assign('Genres', $genres);
        $response->assign('GeoTags', $geoTags);
        $response->assign('Region', $region);
        $response->assign('Artists', $artists);
        $response->write();
    }
}

$app = new Application(array(
    array('/^(\/articles\/?)$/', 'Articles', true)
));

$app->run();

