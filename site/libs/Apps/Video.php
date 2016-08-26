<?php

class Video extends RequestHandler {
    public function get() {
        $pagesToSide = 5;
        $itemsPerPage = 6;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbVideo.php";
        $dbVideo = new DbVideo();
        $videos = $dbVideo->getVideosByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        foreach ($videos as &$video) {
            $_artist = $dbArtists->getArtistById($video['artist_id']);
            $video['artist'] = $_artist;
        }

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/video/?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('video.tpl');
        $response->assign('Title', $GLOBALS[FFM_LANG]['headers']['video']);
        $response->assign('Section', 'video');
        $response->assign('Videos', $videos);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->assign('CurrentPage', $currentPage);
        $response->write();
    }
}

$app = new Application(array(
    array('/^(\/video\/?)$/', 'Video', true)
));

$app->run();

