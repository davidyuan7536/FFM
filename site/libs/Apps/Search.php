<?php

class Search extends RequestHandler {
    public function get() {
        $response = new Response('search.tpl');
        $response->write();
    }

    public function post() {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists = $dbArtists->getArtistsBySearch($_POST['Search'], 0, 7);
        foreach ($artists as &$artist) {
            Utils::convertArtistImage($artist, 's');
        }
        echo json_encode($artists);
    }
}

$app = new Application(array(
    array('/^(\/search\/?)$/', 'Search', true)
));

$app->run();

