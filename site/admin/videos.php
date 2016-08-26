<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $Search = isset($_GET['Search']) ? trim($_GET['Search']) : null;

        if (!empty($Search)) {
            require_once "Db/DbVideo.php";
            $dbVideo = new DbVideo();
            $videos = $dbVideo->getVideoByServiceId($Search);
            $this->getVideoArtists($videos);
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Video');
            $response->assign('Section', 'videos');
            $response->assign('Template', 'videos');
            $response->assign('Videos', $videos);
            $response->assign('Search', $Search);
            $response->write();
        } else {
            $this->getTableView();
        }
    }

    private function getTableView() {
        $pagesToSide = 5;
        $itemsPerPage = 22;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbVideo.php";
        $dbVideo = new DbVideo();
        $videos = $dbVideo->getVideosByPage(
            $totalRows,
                $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        $this->getVideoArtists($videos);

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/videos.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Video');
        $response->assign('Section', 'videos');
        $response->assign('Template', 'videos');
        $response->assign('Videos', $videos);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }

    private function getVideoArtists(&$videos) {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();

        foreach ($videos as &$v) {
            $artist = $dbArtists->getArtistById($v['artist_id']);
            $v['artist'] = $artist;
        }
    }
}

new Page(array(), false);
