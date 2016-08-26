<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        if (isset($_GET['id'])) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $artist = $dbArtists->getArtistById($_GET['id']);

            if ($artist == array()) {
                $response = new Response('admin/main.tpl');
                $response->assign('Title', 'Artist not found');
                $response->assign('Section', 'artists');
                $response->assign('Template', '404');
                $response->write();
            } else {
                require_once "Db/DbGenres.php";
                $dbGenres = new DbGenres();
                $artist['genres'] = $dbGenres->getGenresByArtistId($artist['artist_id'], 0, 50);

                require_once "Db/DbGeotags.php";
                $dbGeoTags = new DbGeotags();
                $artist['geo_tag'] = $dbGeoTags->getGeoTagById($artist['geo_tag_id']);

                $response = new Response('admin/main.tpl');
                $response->assign('Artist', $artist);
                $response->assign('Title', $artist['name']);
                $response->assign('Section', 'artists');
                $response->assign('Template', 'artist');
                $response->write();
            }
        } else {
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'New Artist');
            $response->assign('Section', 'artists');
            $response->assign('Template', 'artist');
            $response->write();
        }
    }

    public function post() {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();

        switch ($_POST['action']) {
            case "save":
                if ($_POST['Id'] == '') {
                    include_once "formatting.php";
                    $row = array(
                        'filename' => $_POST['Filename'] != '' ? $_POST['Filename'] : Utils::sanitizeName($_POST['Name']),
                        'name' => $_POST['Name'],
                        'name_ru' => $_POST['NameRu'],
                        'description' => $_POST['Description'],
                        'links' => $_POST['Links'],
                        'geo_tag_id' => $_POST['GeoTagId'],
                        'geo_tag_text' => ''
                    );

                    $result = $dbArtists->newArtist($row);

                    if ($result > 0) {
                        $dbArtists->setArtistGenres($result, $_POST['GenresIds']);
                    }

                    echo $result;
                } else {
                    $dbArtists->setArtistGenres($_POST['Id'], $_POST['GenresIds']);

                    $result = $dbArtists->updateArtistById($_POST['Id'], array(
                        'filename' => $_POST['Filename'],
                        'name' => $_POST['Name'],
                        'name_ru' => $_POST['NameRu'],
                        'description' => $_POST['Description'],
                        'links' => $_POST['Links'],
                        'geo_tag_id' => $_POST['GeoTagId'],
                        'geo_tag_text' => ''
                    ));

                    echo $result == 0 || $result == 1 ? "OK" : $result;
                }
                break;
            case "removeImage":
                $data = array(
                    'image' => 0
                );
                $result = $dbArtists->updateArtistById($_POST['id'], $data);
                echo $result == 0 || $result == 1 ? "OK" : $result;
                break;
            case "delete":
                $result = $dbArtists->deleteArtist($_POST['Id']);
                echo "OK";
                break;
        }
    }
}

new Page(array(), false);
