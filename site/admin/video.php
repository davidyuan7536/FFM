<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        if (isset($_GET['id'])) {
            require_once "Db/DbVideo.php";
            $dbVideo = new DbVideo();
            $video = $dbVideo->getVideoById($_GET['id']);

            if ($video == array()) {
                $response = new Response('admin/main.tpl');
                $response->assign('Title', 'Video not found');
                $response->assign('Section', 'videos');
                $response->assign('Template', '404');
                $response->write();
            } else {
                require_once "Db/DbArtists.php";
                $dbArtists = new DbArtists();
                $video['artist'] = $dbArtists->getArtistById($video['artist_id']);

                $response = new Response('admin/main.tpl');
                $response->assign('Video', $video);
                $response->assign('Title', $video['video_name']);
                $response->assign('Section', 'videos');
                $response->assign('Template', 'video');
                $response->write();
            }
        } else {
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'New Video');
            $response->assign('Section', 'videos');
            $response->assign('Template', 'video');
            $response->write();
        }
    }

    public function post() {
        require_once "Db/DbVideo.php";
        $dbVideo = new DbVideo();

        switch ($_POST['action']) {
            case "save":
                if ($_POST['Id'] == '') {
                    $row = array(
                        'video_name' => $_POST['Name'],
                        'service_name' => $_POST['ServiceName'],
                        'service_id' => $_POST['ServiceId'],
                        'artist_id' => $_POST['ArtistId']
                    );

                    echo $dbVideo->newVideo($row);
                } else {
                    $result = $dbVideo->updateVideoById($_POST['Id'], array(
                        'video_name' => $_POST['Name'],
                        'service_name' => $_POST['ServiceName'],
                        'service_id' => $_POST['ServiceId'],
                        'artist_id' => $_POST['ArtistId']
                    ));

                    echo $result == 0 || $result == 1 ? "OK" : $result;
                }
                break;
            case "delete":
                $result = $dbVideo->deleteVideo($_POST['Id']);
                echo "OK";
                break;
        }
    }
}

new Page(array(), false);
