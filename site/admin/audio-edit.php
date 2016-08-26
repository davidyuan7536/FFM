<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        if (isset($_GET['id'])) {
            require_once "Db/DbAudio.php";
            $dbAudio = new DbAudio();
            $audio = $dbAudio->getAudioById($_GET['id']);

            if ($audio == array()) {
                $this->response404();
            } else {
                require_once "Db/DbArtists.php";
                $dbArtists = new DbArtists();
                $audio['artist'] = $dbArtists->getArtistById($audio['artist_id']);

                $response = new Response('admin/main.tpl');
                $response->assign('Audio', $audio);
                $response->assign('Title', $audio['audio_name']);
                $response->assign('Section', 'audio');
                $response->assign('Template', 'audio-edit');
                $response->write();
            }
        } else {
            $this->response404();
        }
    }

    private function response404() {
        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'MP3 file not found');
        $response->assign('Section', 'audio');
        $response->assign('Template', '404');
        $response->write();
    }

    public function post() {
        require_once "Db/DbAudio.php";
        $dbAudio = new DbAudio();

        switch ($_POST['action']) {
            case "save":
                $result = $dbAudio->updateAudioById($_POST['Id'], array(
                    'audio_name' => $_POST['Name'],
                    'audio_album' => $_POST['AudioAlbum'],
                    'artist_id' => $_POST['ArtistId']
                ));

                echo $result == 0 || $result == 1 ? "OK" : $result;
                break;
            case "info":
                $audio = $dbAudio->getAudioById($_POST['Id']);
                $this->getID3Tags($_POST['Id'], $audio['audio_filename']);
                break;
            case "delete":
                $result = $dbAudio->deleteAudio($_POST['Id']);
                echo "OK";
                break;
        }
    }

    private function getID3Tags($id, $filename) {
        require_once('../getid3/getid3.php');

        $getID3 = new getID3;
        $getID3->setOption(array('encoding' => 'UTF-8'));

        $info = $getID3->analyze(__FFM_AUDIO__ . $filename);
        getid3_lib::CopyTagsToComments($info);

        echo json_encode($info['comments']);
    }
}

new Page(array(), false);
