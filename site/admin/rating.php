<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists = $dbArtists->getAllArtists();

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Rating');
        $response->assign('Section', 'rating');
        $response->assign('Template', 'rating');
        $response->assign('Artists', $artists);
        $response->write();
    }

    public function post() {
        switch ($_POST['Action']) {
            case "GetRating":
                $this->getRating();
                break;
        }
    }

    private function getRating() {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artist = $dbArtists->getArtistById($_POST['Id']);
        require_once "Auth/facebook.php";
        $facebook = new Facebook(array(
            'appId' => __FFM_FBID__,
            'secret' => __FFM_FBSECRET__,
            'cookie' => true,
        ));
        $url = 'http://' . (__FFM_NAME__ == 'PROD' ? __FFM_HOST__ : 'dev.moscow.ucla.edu') . '/artists/' . $artist['filename'] . '.html';
        try {
            $data = $facebook->api('/' . $url);
        } catch (FacebookApiException $e) {
            error_log($e);
        }

        if (isset($data) && isset($data['likes'])) {
            $row = array(
                'artist_rating' => $data['likes'] - $artist['artist_likes'],
                'artist_likes' => $data['likes']
            );
            $r = $dbArtists->updateArtistById($_POST['Id'], $row);
            if (!empty($r)) {
                $artist = $dbArtists->getArtistById($_POST['Id']);
            }
        }


        $elements = array(
            '.Status' => 'Loaded',
            '.Likes' => $artist['artist_likes'],
            '.Rating' => $artist['artist_rating'],
        );

        echo json_encode(array(
            'elements' => $elements
        ));
    }
}

new Page(array(), false);
