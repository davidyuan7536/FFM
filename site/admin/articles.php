<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        if ($search != '') {
            require_once "Db/DbArticles.php";
            $dbArticles = new DbArticles();
            $articles = $dbArticles->getArticlesBySearch($search);
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Articles');
            $response->assign('Section', 'articles');
            $response->assign('Template', 'articles');
            $response->assign('Search', $search);
            $response->assign('Articles', $articles);
            $response->write();
        } else {
            $this->getTableView();
        }
    }

    private function getTableView() {
        $pagesToSide = 5;
        $itemsPerPage = 16;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticlesByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/articles.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Articles');
        $response->assign('Section', 'articles');
        $response->assign('Template', 'articles');
        $response->assign('Articles', $articles);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }
}

new Page(array(), false);
