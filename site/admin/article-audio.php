<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
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
            $response = new Response('admin/main.tpl');
            $response->assign('Article', $article);
            $response->assign('Title', $article['title']);
            $response->assign('Section', 'articles');
            $response->assign('Template', 'article-audio');
            $response->write();
        }
    }

    public function post() {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();

        require_once "Db/DbAudio.php";
        $dbAudio = new DbAudio();

        switch ($_POST['action']) {
            case "list":
                $tracks = $dbAudio->getAudioByArticleId($_POST['article_id'], 0, 100);
                echo json_encode($tracks);
                break;
            case "search":
                $tracks = $dbAudio->getAudioBySearch($_POST['search'], 0, 20);
                echo json_encode($tracks);
                break;
            case "add":
                $result = $dbArticles->addArticleAudio($_POST['article_id'], $_POST['audio_id']);
                echo json_encode(array(
                    'status' => 'OK',
                    'id' => $result
                ));
                break;
            case "remove":
                $result = $dbArticles->removeArticleAudio($_POST['article_id'], $_POST['audio_id']);
                if ($result == 0 || $result == 1) {
                    echo json_encode(array(
                        'status' => 'OK'
                    ));
                } else {
                    echo json_encode(array(
                        'status' => 'ERROR',
                        'message' => $result
                    ));
                }
                break;
        }
    }
}

new Page(array(), false);
