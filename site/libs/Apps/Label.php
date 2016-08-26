<?php

class Label extends RequestHandler {
    public function get() {
        $pagesToSide = 5;
        $itemsPerPage = 12;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbLabelReleases.php";
        $dbLabelReleases = new DbLabelReleases();
        $releases = $dbLabelReleases->getReleasesFilteredByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Site/PagesLinks.php";
        $link = '/label/?';
        $pagesLinks = new PagesLinks($link . 'page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();

        $releases = $this->setReleaseData($releases);

        $lastAddedRelease = DbLabelReleases::getLastAddedRelease();
        $lastAddedReleaseNMPlace = \db\DbPlace::getById($lastAddedRelease['nm_place_id']);

        $labelArtists = $dbArtists->getLabelArtistsList();
        foreach ($labelArtists as &$artist) {
            Utils::convertArtistImage($artist, 's');
        }

        $response = new Response('label.tpl');
        $response->assign('Title', $GLOBALS[FFM_LANG]['headers']['label']);
        $response->assign('Section', 'label');
        $response->assign('releases', $releases);
        $response->assign('labelArtists', $labelArtists);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->assign('LastAddedReleaseNMPlace', $lastAddedReleaseNMPlace);
        $response->write();
    }

    function setReleaseData($releases) {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();

        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();

        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();

        foreach ($releases as &$release) {
            $release['genres'] = $dbGenres->getGenresByLabelReleaseId($release['release_id'], 0, 50);

            $releaseArtists = $dbArtists->getArtistsByLabelReleaseId($release['release_id'], 0, 50);
            if (count($releaseArtists) > 1) {
                $release['artist'] = 'Various artists';
            } else {
                $release['artist'] = $releaseArtists[0];
            }

            $release['geo_tag'] = $dbGeoTags->getGeoTagById($release['geo_tag_id']);
        }

        return $releases;
    }
}

$app = new Application(array(
    array('/^(\/label\/?)$/', 'Label', true)
));

$app->run();
