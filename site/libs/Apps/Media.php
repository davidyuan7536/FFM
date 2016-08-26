<?php

class Media extends RequestHandler {
    public function get($parameters) {
        if (isset($parameters[1])) {
            require_once "Db/DbTracks.php";
            $track = DbTracks::getTrackById($parameters[1]);

            if (empty($track)) {
                Utils::sendResponse(404);
            } else {
                require_once "Db/DbReleases.php";
                require_once "Db/DbArtists.php";
                require_once "Db/DbGenres.php";
                $dbArtists = new DbArtists();
                $artist = $dbArtists::getArtistById($track['artist_id']);
                $release = DbReleases::getReleaseByHash($track['release_hash']);

                $dbGenres = new DbGenres();
                $track['genres'] = $dbGenres->getGenresByTrackId($track['track_id'], 0, 5);
                
                if (isset($parameters[2])) {
                    $response = new Response('media-embed.tpl');
                    $response->assign('Track', $track);
                    $response->assign('Release', $release);
                    $response->assign('Artist', $artist);
                    $response->write();
                } else {
                    $response = new Response('media.tpl');
                    $response->assign('Title', $track['track_name']);
                    $response->assign('Track', $track);
                    $response->assign('Release', $release);
                    $response->assign('Artist', $artist);
                    $response->write();
                }
            }
        } else {
            Utils::sendResponse(404);
        }
    }
}

$app = new Application(array(
    array('/^\/media\/t\/([0-9]{1,20})\/?$/', 'Media', true),
    array('/^\/media\/t\/([0-9]{1,20})\/(embed)$/', 'Media', false),
));

$app->run();

