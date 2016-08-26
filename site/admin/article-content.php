<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $id = isset($_GET['id']) ? $_GET['id'] : 1;
        $lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';

        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $article = $dbArticles->getArticleById($id);

        if ($article == array()) {
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Article not found');
            $response->assign('Section', 'articles');
            $response->assign('Template', '404');
            $response->write();
        } else {
            require_once "Db/DbGenres.php";
            $dbGenres = new DbGenres();
            $genres = $dbGenres->getGenresByArticleId($article['article_id']);
            $article['genres'] = $genres;

            require_once "Db/DbGeotags.php";
            $dbGeoTags = new DbGeotags();
            $geoTag = $dbGeoTags->getGeoTagById($article['geo_tag_id']);
            $article['geo_tag'] = $geoTag;

            $response = new Response('admin/main.tpl');
            $response->assign('Article', $article);
            $response->assign('Title', $article['title']);
            $response->assign('Section', 'articles');
            $response->assign('Template', 'article-content');
            $response->assign('Lang', $lang);
            $response->write();
        }
    }

    public function post() {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();

        switch ($_POST['action']) {
            case "save":
                if ($_POST['Lang'] == 'ru') {
                    $result = $dbArticles->updateArticleById($_POST['Id'], array(
                        'content_ru' => $_POST['Content']
                    ));
                } else {
                    $result = $dbArticles->updateArticleById($_POST['Id'], array(
                        'content' => $_POST['Content']
                    ));
                }

                echo $result == 0 || $result == 1 ? "OK" : $result;
                break;
        }
    }
}

new Page(array(), false);
