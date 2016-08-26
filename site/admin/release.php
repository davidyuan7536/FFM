<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        if (isset($_GET['id'])) {
            require_once "Db/DbLabelReleases.php";
            $dbLabelReleases = new DbLabelReleases();
            $release = $dbLabelReleases->getReleaseById($_GET['id']);

            if ($release == array()) {
                $response = new Response('admin/main.tpl');
                $response->assign('Title', 'Release not found');
                $response->assign('Section', 'label');
                $response->assign('Template', '404');
                $response->write();
            } else {
                require_once "Db/DbArtists.php";
                $dbArtists = new DbArtists();
                $release['artists'] = $dbArtists->getArtistsByLabelReleaseId($release['release_id'], 0, 50);

                require_once "Db/DbGenres.php";
                $dbGenres = new DbGenres();
                $release['genres'] = $dbGenres->getGenresByLabelReleaseId($release['release_id'], 0, 50);

                require_once "Db/DbGeotags.php";
                $dbGeoTags = new DbGeotags();
                $release['geo_tag'] = $dbGeoTags->getGeoTagById($release['geo_tag_id']);

                $response = new Response('admin/main.tpl');
                $response->assign('Release', $release);
                $response->assign('Title', $release['title']);
                $response->assign('Section', 'label');
                $response->assign('Template', 'release');
                $response->write();
            }
        } else {
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'New Release');
            $response->assign('Section', 'label');
            $response->assign('Template', 'release');
            $response->write();
        }

    }

    public function post() {
        require_once "Db/DbLabelReleases.php";
        $dbLabelReleases = new DbLabelReleases();

        switch ($_POST['action']) {
            case 'save':
                if ($_POST['Id'] == '') {
                    include_once "formatting.php";
                    $row = array(
                        'ffm_id' => $_POST['FFM_id'],
                        'filename' => $_POST['Filename'] != '' ? $_POST['Filename'] : Utils::sanitizeName($_POST['Name']),
                        'title' => $_POST['Name'],
                        'description' => $_POST['Description'],
                        'title_ru' => $_POST['NameRu'],
                        'description_ru' => $_POST['DescriptionRu'],
                        'geo_tag_id' => $_POST['GeoTagId'],
                        'status' => $_POST['Status'],
                        'date' => date('Y-m-d H:i:s'),
                        'player_for_list' => $_POST['PlayerForList'],
                        'player_for_page' => $_POST['PlayerForPage'],
                        'download_link' => $_POST['DownloadLink']
                    );

                    $id = $dbLabelReleases->newRelease($row);

                    if ($id > 0) {
                        $dbLabelReleases->setReleaseGenres($id, $_POST['GenresIds']);
                        $dbLabelReleases->setReleaseArtists($id, $_POST['ArtistsIds']);
                    }

                    $dbLabelReleases->updateLabelArtistsList($_POST['ArtistsIds']);
                    $this->updateNoisyMapPlace($id, $row, $_POST['GeoTagId']);

                    echo $id;
                } else {
                    $dbLabelReleases->setReleaseGenres($_POST['Id'], $_POST['GenresIds']);
                    $dbLabelReleases->setReleaseArtists($_POST['Id'], $_POST['ArtistsIds']);

                    $row = array(
                        'ffm_id' => $_POST['FFM_id'],
                        'filename' => $_POST['Filename'],
                        'title' => $_POST['Name'],
                        'description' => $_POST['Description'],
                        'title_ru' => $_POST['NameRu'],
                        'description_ru' => $_POST['DescriptionRu'],
                        'geo_tag_id' => $_POST['GeoTagId'],
                        'status' => $_POST['Status'],
                        'player_for_list' => $_POST['PlayerForList'],
                        'player_for_page' => $_POST['PlayerForPage'],
                        'download_link' => $_POST['DownloadLink']
                    );

                    $result = $dbLabelReleases->updateReleaseById($_POST['Id'], $row);

                    $dbLabelReleases->updateLabelArtistsList($_POST['ArtistsIds']);
                    $this->updateNoisyMapPlace($_POST['Id'], $row, $_POST['GeoTagId']);

                    echo $result == 0 || $result == 1 ? "OK" : $result;
                }
                break;
            case "delete":
                $result = $dbLabelReleases->deleteRelease($_POST['Id']);
                echo "OK";
                break;
        }
    }

    private function updateNoisyMapPlace($releaseId, $release, $geotagId) {
        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $geotag = DbGeotags::getGeoTagById($geotagId);
        $place = array(
            \db\DbPlace::NAME => $geotag['name'],
            \db\DbPlace::ABOUT => $geotag['wiki'],
            \db\DbPlace::LAT => $geotag['lat'],
            \db\DbPlace::LNG => $geotag['lng'],
            \db\DbPlace::USER_ID => 5
        );

        $NMPlace = \db\DbPlace::getByLatAndLng($geotag['lat'], $geotag['lng']);
        if (count($NMPlace) > 0) {
            $placeDTO = \db\DbPlace::updateById($NMPlace['place_id'], $place);
        } else {
            $placeDTO = \db\DbPlace::create($place);
        }

        $release['nm_place_id'] = $placeDTO['place_id'];

        DbLabelReleases::updateReleaseById($releaseId, $release);
    }
}

new Page(array(), false);
