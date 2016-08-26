<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $ESearch = isset($_GET['ESearch']) ? trim($_GET['ESearch']) : null;
        $NSearch = isset($_GET['NSearch']) ? trim($_GET['NSearch']) : null;

        if (!empty($ESearch)) {
            require_once "Db/DbUsers.php";
            $dbUsers = new DbUsers();
            $users = $dbUsers->getUsersBySearchEmail($ESearch);
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Users');
            $response->assign('Section', 'users');
            $response->assign('Template', 'users');
            $response->assign('Users', $users);
            $response->assign('ESearch', $ESearch);
            $response->write();
        } else if (!empty($NSearch)) {
            require_once "Db/DbUsers.php";
            $dbUsers = new DbUsers();
            $users = $dbUsers->getUsersBySearchName($NSearch);
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Users');
            $response->assign('Section', 'users');
            $response->assign('Template', 'users');
            $response->assign('Users', $users);
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

        require_once "Db/DbUsers.php";
        $dbUsers = new DbUsers();
        $result = $dbUsers->getUsersByPage(
            $totalRows,
                $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/users.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Users');
        $response->assign('Section', 'users');
        $response->assign('Template', 'users');
        $response->assign('Users', $result);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }
}

new Page(array(), false);
