<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        if ($search != '') {
            require_once "Db/DbAudio.php";
            $dbAudio = new DbAudio();
            $files = $dbAudio->getAudioBySearch($search);
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            foreach ($files as &$v) {
                $artist = $dbArtists->getArtistById($v['artist_id']);
                $v['artist'] = $artist;
            }
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Audio');
            $response->assign('Section', 'audio');
            $response->assign('Template', 'audio');
            $response->assign('Audio', $files);
            $response->assign('Search', $search);
            $response->write();
        } else {
            $this->getTableView();
        }
    }

    public function post() {
        require_once "Db/DbAudio.php";
        $dbAudio = new DbAudio();

        switch ($_POST['action']) {
            case "confirm":
                $result = $dbAudio->updateAudioById($_POST['Id'], array(
                    'audio_name' => $_POST['Title'],
                    'audio_album' => $_POST['Album'],
                    'artist_id' => $_POST['ArtistId']
                ));

                echo $result == 0 || $result == 1 ? "OK" : $result;
                break;
        }
    }

    private function getTableView() {
        $pagesToSide = 5;
        $itemsPerPage = 22;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbAudio.php";
        $dbAudio = new DbAudio();
        $files = $dbAudio->getAudioByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();

        foreach ($files as &$v) {
            $artist = $dbArtists->getArtistById($v['artist_id']);
            $v['artist'] = $artist;
        }

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/audio.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Audio');
        $response->assign('Section', 'audio');
        $response->assign('Template', 'audio');
        $response->assign('Audio', $files);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }
}

new Page(array(), false);
