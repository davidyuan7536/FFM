<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        if (isset($_GET['id'])) {
            require_once "Db/DbArticles.php";
            $dbArticles = new DbArticles();
            $article = $dbArticles->getArticleById($_GET['id']);

            if ($article == array()) {
                $response = new Response('admin/main.tpl');
                $response->assign('Title', 'Article not found');
                $response->assign('Section', 'articles');
                $response->assign('Template', '404');
                $response->write();
            } else {
                require_once "Db/DbArtists.php";
                $dbArtists = new DbArtists();
                $article['artists'] = $dbArtists->getArtistsByArticleId($article['article_id'], 0, 50);

                require_once "Db/DbGenres.php";
                $dbGenres = new DbGenres();
                $article['genres'] = $dbGenres->getGenresByArticleId($article['article_id'], 0, 50);

                require_once "Db/DbGeotags.php";
                $dbGeoTags = new DbGeotags();
                $article['geo_tag'] = $dbGeoTags->getGeoTagById($article['geo_tag_id']);

                $response = new Response('admin/main.tpl');
                $response->assign('Article', $article);
                $response->assign('Title', $article['title']);
                $response->assign('Section', 'articles');
                $response->assign('Template', 'article-summary');
                $response->write();
            }
        } else {
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'New Article');
            $response->assign('Section', 'articles');
            $response->assign('Template', 'article-summary');
            $response->write();
        }

    }

    public function post() {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();

        switch ($_POST['action']) {
            case "save":
                if ($_POST['Id'] == '') {
                    include_once "formatting.php";
                    $row = array(
                        'filename' => $_POST['Filename'] != '' ? $_POST['Filename'] : Utils::sanitizeName($_POST['Name']),
                        'title' => $_POST['Name'],
                        'description' => $_POST['Description'],
                        'title_ru' => $_POST['NameRu'],
                        'description_ru' => $_POST['DescriptionRu'],
                        'geo_tag_id' => $_POST['GeoTagId'],
                        'status' => $_POST['Status'],
                        'date' => date('Y-m-d H:i:s')
                    );

                    $id = $dbArticles->newArticle($row);

                    if ($id > 0) {
                        $dbArticles->setArticleGenres($id, $_POST['GenresIds']);
                        $dbArticles->setArticleArtists($id, $_POST['ArtistsIds']);
                    }

                    echo $id;
                } else {
                    $dbArticles->setArticleGenres($_POST['Id'], $_POST['GenresIds']);
                    $dbArticles->setArticleArtists($_POST['Id'], $_POST['ArtistsIds']);

                    $result = $dbArticles->updateArticleById($_POST['Id'], array(
                        'filename' => $_POST['Filename'],
                        'title' => $_POST['Name'],
                        'description' => $_POST['Description'],
                        'title_ru' => $_POST['NameRu'],
                        'description_ru' => $_POST['DescriptionRu'],
                        'geo_tag_id' => $_POST['GeoTagId'],
                        'status' => $_POST['Status']
                    ));

                    echo $result == 0 || $result == 1 ? "OK" : $result;
                }
                break;
            case "removeImage":
                $result = $dbArticles->setArticleImageById($_POST['id'], '');
                echo $result == 0 || $result == 1 ? "OK" : $result;
                break;
            case "delete":
                $result = $dbArticles->deleteArticle($_POST['Id']);
                echo "OK";
                break;
        }
    }
}

new Page(array(), false);
