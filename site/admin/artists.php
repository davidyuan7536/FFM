<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        if ($search != '') {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $artists = $dbArtists->getArtistsBySearch($search);
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Artists');
            $response->assign('Section', 'artists');
            $response->assign('Template', 'artists');
            $response->assign('Artists', $artists);
            $response->assign('Search', $search);
            $response->assign('WeekHash', md5(date('W')));
            $response->write();
        } else {
            $this->getTableView();
        }
    }

    public function post() {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();

        switch ($_POST['action']) {
            case "list":
                $result = $dbArtists->getArtistsBySearch($_POST['search'], 0, 50);
                $list = array();
                foreach ($result as $value) {
                    array_push($list, $value);
                }
                echo json_encode($list);
                break;
        }
    }

    private function getTableView() {
        $pagesToSide = 5;
        $itemsPerPage = 16;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists = $dbArtists->getArtistsByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/artists.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Artists');
        $response->assign('Section', 'artists');
        $response->assign('Template', 'artists');
        $response->assign('Artists', $artists);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->assign('WeekHash', md5(date('W')));
        $response->write();
    }
}

new Page(array(), false);
