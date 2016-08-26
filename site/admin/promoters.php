<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $NSearch = isset($_GET['NSearch']) ? trim($_GET['NSearch']) : null;

        if (!empty($NSearch)) {
            require_once "Db/DbPromoters.php";
            $promoters = DbPromoters::getPromotersBySearch($NSearch);
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Promoters');
            $response->assign('Section', 'promoters');
            $response->assign('Template', 'promoters');
            $response->assign('Promoters', $promoters);
            $response->assign('NSearch', $NSearch);
            $response->write();
        } else {
            $this->getTableView();
        }
    }

    private function getTableView() {
        $pagesToSide = 5;
        $itemsPerPage = 16;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbPromoters.php";
        $result = DbPromoters::getPromotersByPage(
            $totalRows,
                $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/promoters.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Promoters');
        $response->assign('Section', 'promoters');
        $response->assign('Template', 'promoters');
        $response->assign('Promoters', $result);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }

    public function post() {
        switch ($_POST['Action']) {
            case 'delete':
                $this->deletePromoter();
                break;
        }
    }

    private function deletePromoter() {
        require_once "Db/DbPromoters.php";
        DbPromoters::deletePromoter($_POST['Id']);
        Utils::redirect($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/site/admin/promoters.php');
    }
}

new Page(array(), false);
