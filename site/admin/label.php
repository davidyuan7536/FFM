<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        if ($search != '') {
            require_once "Db/DbLabelReleases.php";
            $dbLabelReleases = new DbLabelReleases();
            $releases = $dbLabelReleases->getReleasesBySearch($search);

            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Label');
            $response->assign('Section', 'label');
            $response->assign('Template', 'label');
            $response->assign('Search', $search);
            $response->assign('Releases', $releases);
            $response->write();
        } else {
            $this->getTableView();
        }
    }

    private function getTableView() {
        $pagesToSide = 5;
        $itemsPerPage = 16;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbLabelReleases.php";
        $dbLabelReleases = new DbLabelReleases();
        $releases = $dbLabelReleases->getReleasesByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/label.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Label');
        $response->assign('Section', 'label');
        $response->assign('Template', 'label');
        $response->assign('Releases', $releases);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }
}

new Page(array(), false);
