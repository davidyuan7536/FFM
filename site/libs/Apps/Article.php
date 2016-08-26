<?php

class Article extends RequestHandler {
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
            foreach($audios as &$audio) {
                $_artist = $dbArtists->getArtistById($audio['artist_id']);
                $audio['artist'] = $_artist;
            }

            require_once "Db/DbComments.php";
            $comments = DbComments::getCommentsByParentId($article['article_id'], COMMENT_CATEGORY_ARTICLE);

            $response = new Response('article.tpl');
            $response->assign('Article', $article);
            $response->assign('Artists', $artists);
            $response->assign('GeoTag', $geoTag);
            $response->assign('Audios', $audios);
            $response->assign('Title', $article['title']);
            $response->assign('Section', 'articles');
            $response->assign('Comments', $comments);
            $response->write();
        }
    }
}

$app = new Application(array(
    array('/^\/articles\/([a-z0-9._%+-]{1,200}).html$/', 'Article', false)
));

$app->run();

