<?php

class Mobile extends RequestHandler {
    public function get() {
        $itemsPerPage = 10;
        $genre = '';
        $region = '';

        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticlesFilteredByPage($genre, $region, $totalArticles, 0, $itemsPerPage);
        $articlesPagesCount = ceil($totalArticles / $itemsPerPage);

        require_once "Db/DbEvents.php";
        $dbEvents = new DbEvents();
        $events = $dbEvents->getEventsFromTodayByPage($totalEvents, 0, $itemsPerPage);
        $eventsPagesCount = ceil($totalEvents / $itemsPerPage);

        $response = new Response('mobile.tpl');
        $response->assign('Articles', $articles);
        $response->assign('Events', $events);
        $response->assign('NextArticlesPage', $articlesPagesCount > 1 ? 2 : 0);
        $response->assign('NextEventsPage', $eventsPagesCount > 1 ? 2 : 0);
        $response->write();
    }
}

class MobileArticles extends RequestHandler {
    public function get($parameters) {
        $itemsPerPage = 10;
        $currentPage = $parameters[1];
        $genre = '';
        $region = '';

        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticlesFilteredByPage(
            $genre,
            $region,
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        $pagesCount = ceil($totalRows / $itemsPerPage);
        $next = $currentPage + 1 > $pagesCount ? 0 : $currentPage + 1;

        $response = new Response('mobile-articles.tpl');
        $response->assign('Articles', $articles);
        $response->assign('NextArticlesPage', $next);
        $response->write();
    }
}

class MobileEvents extends RequestHandler {
    public function get($parameters) {
        $itemsPerPage = 10;
        $currentPage = $parameters[1];

        require_once "Db/DbEvents.php";
        $dbEvents = new DbEvents();
        $events = $dbEvents->getEventsFromTodayByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        $pagesCount = ceil($totalRows / $itemsPerPage);
        $next = $currentPage + 1 > $pagesCount ? 0 : $currentPage + 1;

        $response = new Response('mobile-events.tpl');
        $response->assign('Events', $events);
        $response->assign('NextEventsPage', $next);
        $response->write();
    }
}

class MobileSearch extends RequestHandler {
    public function get() {
        $search = $_GET['search'];

        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticlesBySearch($search, 0, 50);

        $response = new Response('mobile-search.tpl');
        $response->assign('Articles', $articles);
        $response->write();
    }
}

class MobileArticle extends RequestHandler {
    public function get($parameters) {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $article = $dbArticles->getArticleByName($parameters[1]);

        if ($article == array()) {
            Utils::sendResponse(404);
        } else {
            require_once "Db/DbGenres.php";
            $dbGenres = new DbGenres();
            $genres = $dbGenres->getGenresByArticleId($article['article_id'], 0, 50);
            $article['genres'] = $genres;

            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $artists = $dbArtists->getArtistsByArticleId($article['article_id'], 0, 20);

            foreach($artists as &$artist) {
                $_genres = $dbGenres->getGenresByArtistId($artist['artist_id']);
                $artist['genres'] = $_genres;
            }

            require_once "Db/DbGeotags.php";
            $dbGeoTags = new DbGeotags();
            $geoTag = $dbGeoTags->getGeoTagById($article['geo_tag_id']);

            require_once "Db/DbAudio.php";
            $dbAudio = new DbAudio();
            $audios = $dbAudio->getAudioByArticleId($article['article_id']);

            if (count($audios) > 0) {
                $link = "<a href=\"/m/p/{$article['filename']}.html\" target=\"_blank\"><img src=\"/mobile/player.png\" width='21' height='21' alt='' style='vertical-align: -5px;' /></a> ";
            }

            $response = new Response('mobile-article.tpl');
            $response->assign('Article', $article);
            $response->assign('Artists', $artists);
            $response->assign('GeoTag', $geoTag);
            $response->assign('Audios', $audios);
            $response->assign('AudioLink', $link);
            $response->assign('Title', $article['title']);
            $response->write();
        }
    }
}

class MobilePlayer extends RequestHandler {
    public function get($parameters) {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $article = $dbArticles->getArticleByName($parameters[1]);

        if ($article == array()) {
            Utils::sendResponse(404);
        } else {
            require_once "Db/DbAudio.php";
            $dbAudio = new DbAudio();
            $audios = $dbAudio->getAudioByArticleId($article['article_id']);

            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
                foreach($audios as &$audio) {
                $_artist = $dbArtists->getArtistById($audio['artist_id']);
                $audio['artist'] = $_artist;
            }
            
            $response = new Response('mobile-player.tpl');
            $response->assign('Article', $article);
            $response->assign('Audios', $audios);
            $response->assign('Title', $article['title']);
            $response->write();
        }
    }
}

$app = new Application(array(
    array('/^(\/m\/?)$/', 'Mobile', true),
    array('/^\/m\/a([0-9]{1,2})$/', 'MobileArticles', false),
    array('/^\/m\/e([0-9]{1,2})$/', 'MobileEvents', false),
    array('/^\/m\/search$/', 'MobileSearch', false),
    array('/^\/m\/a\/([a-z0-9._%+-]{1,200}).html$/', 'MobileArticle', false),
    array('/^\/m\/p\/([a-z0-9._%+-]{1,200}).html$/', 'MobilePlayer', false)
));

$app->run();

