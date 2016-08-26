<?php

class LabelRelease extends RequestHandler {
    public function get($parameters) {
        require_once "Db/DbLabelReleases.php";
        $dbLabelReleases = new DbLabelReleases();

        $release = $dbLabelReleases->getReleaseByName($parameters[1]);

        if ($release == array()) {
            Utils::sendResponse(404);
        } else {
            $this->displayRelease($release);
        }
    }

    public function displayRelease($release) {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $genres = $dbGenres->getGenresByLabelReleaseId($release['release_id'], 0, 50);
        $release['genres'] = $genres;

        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists = $dbArtists->getArtistsByLabelReleaseId($release['release_id'], 0, 50);
        foreach ($artists as &$artist) {
            Utils::convertArtistImage($artist, 's');
        }
        $release['artists'] = $artists;

        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $geoTag = $dbGeoTags->getGeoTagById($release['geo_tag_id']);
        $geoTagList = $dbGeoTags->getGeoTags();

        if (empty($geoTag['parent_id'])) {
            $country = $geoTag;
        } else {
            $country = $dbGeoTags->getGeoTagById($geoTag['parent_id']);
        }

        $NMPlace = \db\DbPlace::getById($release['nm_place_id']);

        $response = new Response('label_release.tpl');
        $response->assign('Section', 'label');
        $response->assign('Title', $release['name']);
        $response->assign('Release', $release);
        $response->assign('GeoTag', $geoTag);
        $response->assign('GeoTagList', $geoTagList);
        $response->assign('Country', $country);
        $response->assign('NMPlace', $NMPlace);
        $response->write();
    }
}

$app = new Application(array(
    array('/^\/label\/([a-z0-9._%+-]{1,200}).html$/', 'LabelRelease', false),
));

$app->run();