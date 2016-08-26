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
            $response->assign('Template', 'article-pictures');
            $response->write();
        }
    }

    public function post() {
    }
}

new Page(array(), false);
