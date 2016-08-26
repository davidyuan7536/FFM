<?php

class Rss extends RequestHandler {
    public function get() {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticles(0, 10);

        $response = new Response('rss.tpl');
        $response->assign('Articles', $articles);
        $response->write();
    }
}

class Podcast extends RequestHandler {
    public function get() {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticles(0, 10);

        require_once "Db/DbAudio.php";
        $dbAudio = new DbAudio();
        foreach ($articles as &$article) {
            $audio = $dbAudio->getAudioByArticleId($article['article_id']);
            $article['audio'] = $audio;
        }

        $response = new Response('rss-podcast.tpl');
        $response->assign('Articles', $articles);
        $response->write();
    }
}

$app = new Application(array(
    array('/^\/rss.xml$/', 'Rss', false),
    array('/^\/podcast.xml$/', 'Podcast', false)
));

$app->run();

