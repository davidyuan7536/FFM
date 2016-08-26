<?php

class Artist extends RequestHandler {
    public function get($parameters) {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artist = $dbArtists->getArtistByName($parameters[1]);

        if ($artist == array()) {
            Utils::sendResponse(404);
        } else {
            if (isset($_GET['term'])) {
                $this->searchArtists($_GET['term']);
            } else {
                $this->displayArtist($artist);
            }
        }
    }

    public function post($parameters) {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artist = $dbArtists->getArtistByName($parameters[1]);

        if (!empty($artist)) {
            if (isset($_POST['Action'])) {
                switch ($_POST['Action']) {
                    case "SaveProfileImage":
                        $this->saveProfileImage($artist);
                        break;
                    case "SaveReleaseImage":
                        $this->saveReleaseImage($artist);
                        break;
                    case "SaveEventImage":
                        $this->saveEventImage($artist);
                        break;
                    case "SavePhoto":
                        $this->savePhoto($artist);
                        break;
                    case "DeletePhoto":
                        $this->deletePhoto($artist);
                        break;
                    case "SaveTrackAudio":
                        $this->saveTrackAudio($artist);
                        break;
                    case "Search":
                        $this->getGenres();
                        break;
                    case "GetAllVideos":
                        $this->getAllVideos($artist);
                        break;
                    case "GetInfo":
                        $this->getInfo($artist);
                        break;
                    case "SaveInfo":
                        $this->saveInfo($artist);
                        break;
                    case "GetEvent":
                        $this->getEvent($artist);
                        break;
                    case "SaveEvent":
                        $this->saveEvent($artist);
                        break;
                    case "DeleteEvent":
                        $this->deleteEvent($artist);
                        break;
                    case "GetRelease":
                        $this->getRelease($artist);
                        break;
                    case "SaveRelease":
                        $this->saveRelease($artist);
                        break;
                    case "DeleteRelease":
                        $this->deleteRelease($artist);
                        break;
                    case "GetTrack":
                        $this->getTrack($artist);
                        break;
                    case "GetTrackList":
                        $this->getTrackList($artist);
                        break;
                    case "SaveTrack":
                        $this->saveTrack($artist);
                        break;
                    case "DeleteTrack":
                        $this->deleteTrack($artist);
                        break;
                    case "AddRecommendsArtist":
                        $this->addRecommendsArtist($artist);
                        break;
                    case "DeleteRecommendsArtist":
                        $this->deleteRecommendsArtist($artist);
                        break;
                    default:
                        echo json_encode(array(
                            'message' => Utils::getMessage('e002')
                        ));
                }
            } else {
                switch ($_GET['tab']) {
                    case "articles":
                        $this->getArticles($artist);
                        break;
                    case "events":
                        $this->getEvents($artist);
                        break;
                    case "releases":
                        $this->getReleases($artist);
                        break;
                    case "photos":
                        $this->getPhotos($artist);
                        break;
                    case "videos":
                        $this->getVideos($artist);
                        break;
                    default:
                        echo Utils::getMessage('e002');
                }
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function isEditable($artistId) {
        if (!empty($_SESSION['user'])) {
            if ($_SESSION['user']['super_user'] == 1) {
                return true;
            } else {
                require_once "Db/DbPm.php";
                $dbPm = new DbPm();
                $pm = $dbPm->getRequestByUserArtist($_SESSION['user']['user_id'], $artistId);
                return !empty($pm) && $pm['request_status'] == STATUS_ENABLED;
            }
        }

        return false;
    }

    private function getGenres() {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $genres = $dbGenres->getGenresByName($_POST['Search']);
        echo json_encode($genres);
    }


    private function getAllVideos($artist) {
        require_once "Db/DbVideo.php";
        $dbVideo = new DbVideo();
        $videos = $dbVideo->getVideosByArtistId($artist['artist_id'], 0, 50);

        $response = new Response('');
        $response->assign('Artist', $artist);
        $response->assign('Videos', $videos);
        echo json_encode(array(
            'elements' => array(
                '#AllVideosWrap' => $response->fetch('profile/videos.tpl')
            )
        ));
    }


    private function searchArtists($str) {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists = $dbArtists->getArtistsBySearch($str);
        echo json_encode($artists);
    }

    private function log($artist, $str) {
        Utils::logArtist($_SESSION['user']['user_id'], $artist['artist_id'], $str);
    }

    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /*                                                                                                                */
    /* ARTIST                                                                                                         */
    /*                                                                                                                */
    private function getInfo($artist) {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $genres = $dbGenres->getGenresByArtistId($artist['artist_id'], 0, 50);

        $elements = array(
            '#ProfileName' => $artist['name'],
            '#ProfileNameRu' => $artist['name_ru'],
            '#ProfileDescription' => $artist['description'],
            '#ProfileLinks' => $artist['links'],
            '#GeoSearchId' => '',
            '#GeoSearch' => ''
        );

        if (!empty($artist['geo_tag_id'])) {
            require_once "Db/DbGeotags.php";
            $dbGeoTags = new DbGeotags();
            $geoTag = $dbGeoTags->getGeoTagById($artist['geo_tag_id']);
            $elements['#GeoSearchId'] = $artist['geo_tag_id'];
            $elements['#GeoSearch'] = $geoTag['name'];
        }

        if (!empty($artist['geo_tag_text'])) {
            $elements['#GeoSearch'] = $artist['geo_tag_text'];
        }

        echo json_encode(array(
            'elements' => $elements,
            'genres' => $genres
        ));
    }

    private function saveInfo($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $dbArtists->setArtistGenres($artist['artist_id'], $_POST['GenresIds']);
            $row = array(
                'name' => Utils::escape($_POST['Name']),
                'name_ru' => Utils::escape($_POST['NameRu']),
                'description' => Utils::escape($_POST['Description']),
                'links' => Utils::escape($_POST['Links'])
            );

            $this->getGeoInformation($row);

            $result = $dbArtists->updateArtistById($artist['artist_id'], $row);

            $this->log($artist, "User [{$_SESSION['user']['user_name']}] has made changes: \r\n" . var_export($row, true));

            if ($result == 0 || $result == 1) {
                $new_artist = $dbArtists->getArtistById($artist['artist_id']);
                echo json_encode(array(
                    'elements' => $this->displayArtist($new_artist, false)
                ));
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function getGeoInformation(&$row) {
        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $GeoSearch = Utils::escape($_POST['GeoSearch']);
        if (empty($GeoSearch)) {
            $row['geo_tag_text'] = '';
        } else if (!empty($GeoSearch)) {
            $geoTag = $dbGeoTags->getGeoTagByLabel($GeoSearch);
            if (empty($geoTag)) {
                $row['geo_tag_text'] = $GeoSearch;
            } else {
                $row['geo_tag_id'] = $geoTag['geo_tag_id'];
                $row['geo_tag_text'] = '';
            }
        }
    }


    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /*                                                                                                                */
    /* EVENTS                                                                                                         */
    /*                                                                                                                */
    private function getEvent($artist) {
        if (empty($_POST['Id'])) {
            echo json_encode(array(
                'message' => Utils::getMessage('e002')
            ));
        } else {
            require_once "Db/DbEvents.php";
            $dbEvents = new DbEvents();
            $result = $dbEvents->getEventById($_POST['Id']);

            if (!empty($result['geo_tag_id'])) {
                require_once "Db/DbGeotags.php";
                $dbGeoTags = new DbGeotags();
                $geoTag = $dbGeoTags->getGeoTagById($result['geo_tag_id']);
                $result['geo_tag_text'] = $geoTag['name'];
            }

            echo json_encode($result);
        }
    }

    private function saveEvent($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbEvents.php";
            $dbEvents = new DbEvents();

            $artistsIds = array($artist['artist_id']);

            if (empty($_POST['Id'])) {
                $row = array(
                    'event_name' => Utils::escape($_POST['Name']),
                    'event_description' => Utils::escape($_POST['Description']),
                    'event_address' => Utils::escape($_POST['Address']),
                    'event_date' => Utils::escape($_POST['Date'])
                );
                $this->getGeoInformation($row);
                $id = $dbEvents->newEvent($row);

                Utils::logEvent($_SESSION['user']['user_id'], $id, "User [{$_SESSION['user']['user_name']}] has created Event: \r\n" . var_export($row, true));

                if ($id > 0) {
                    $dbEvents->setEventArtists($id, $artistsIds);
                }
            } else {
                $dbEvents->setEventArtists($_POST['Id'], $artistsIds);

                $row = array(
                    'event_name' => Utils::escape($_POST['Name']),
                    'event_description' => Utils::escape($_POST['Description']),
                    'event_address' => Utils::escape($_POST['Address']),
                    'event_date' => Utils::escape($_POST['Date'])
                );
                $this->getGeoInformation($row);
                $result = $dbEvents->updateEventById($_POST['Id'], $row);

                Utils::logEvent($_SESSION['user']['user_id'], $_POST['Id'], "User [{$_SESSION['user']['user_name']}] has made changes: \r\n" . var_export($row, true));
            }

            echo json_encode(array(
                'elements' => $this->getEvents($artist, false)
            ));
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function deleteEvent($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbEvents.php";
            $dbEvents = new DbEvents();

            if (empty($_POST['Id'])) {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            } else {
                $result = $dbEvents->deleteEvent($_POST['Id']);

                echo json_encode(array(
                    'elements' => $this->getEvents($artist, false)
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }


    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /*                                                                                                                */
    /* RELEASES                                                                                                       */
    /*                                                                                                                */
    private function getRelease($artist) {
        if (empty($_POST['Id'])) {
            echo json_encode(array(
                'message' => Utils::getMessage('e002')
            ));
        } else {
            require_once "Db/DbReleases.php";
            $result = DbReleases::getReleaseById($_POST['Id']);

            if (!empty($result)) {
                require_once "Db/DbGenres.php";
                $dbGenres = new DbGenres();
                $genres = $dbGenres->getGenresByReleaseId($result['release_id'], 0, 50);

                $elements = array(
                    '#ReleaseId' => $result['release_id'],
                    '#ReleaseName' => $result['release_name'],
                    '#ReleaseYear' => $result['release_year'],
                    '#ReleaseLabel' => $result['release_label']
                );
                echo json_encode(array(
                    'elements' => $elements,
                    'genres' => $genres,
                    'image' => $result['release_image'] == 0 ? '' : __FFM_ARCHIVE_FRONT__ . $result['release_hash']
                ));
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            }
        }
    }

    private function saveRelease($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbReleases.php";

            $row = array(
                'artist_id' => $artist['artist_id'],
                'release_name' => Utils::escape($_POST['Name']),
                'release_year' => Utils::escape($_POST['Year']),
                'release_label' => Utils::escape($_POST['Label'])
            );

            if (empty($_POST['Id'])) {
                $id = DbReleases::newRelease($row);

                if ($id > 0) {
                    $hash = array(
                        'release_hash' => Utils::hash($id, 8)
                    );
                    DbReleases::updateReleaseById($id, $hash);
                }

                $this->log($artist, "User [{$_SESSION['user']['user_name']}] has created release: \r\n" . var_export($row, true));
            } else {
                $result = DbReleases::updateReleaseById($_POST['Id'], $row);
                $id = $_POST['Id'];

                $this->log($artist, "User [{$_SESSION['user']['user_name']}] has saved release:" . var_export($row, true));
            }

            if (!empty($_POST['GenresIds'])) {
                DbReleases::setReleaseGenres($id, $_POST['GenresIds']);
            }

            echo json_encode(array(
                'elements' => $this->getReleases($artist, false)
            ));
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function deleteRelease($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbReleases.php";

            if (empty($_POST['Id'])) {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            } else {
                $result = DbReleases::deleteReleaseById($_POST['Id']);

                echo json_encode(array(
                    'elements' => $this->getReleases($artist, false)
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }


    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /*                                                                                                                */
    /* TRACKS                                                                                                         */
    /*                                                                                                                */
    private function getTrack($artist) {
        if (empty($_POST['Id'])) {
            echo json_encode(array(
                'message' => Utils::getMessage('e002')
            ));
        } else {
            require_once "Db/DbTracks.php";
            $track = DbTracks::getTrackById($_POST['Id']);

            if (!empty($track)) {
                require_once "Db/DbGenres.php";
                $dbGenres = new DbGenres();
                $genres = $dbGenres->getGenresByTrackId($track['track_id'], 0, 50);

                $elements = array(
                    '#TrackId' => $track['track_id'],
                    '#TrackName' => $track['track_name'],
                    '#TrackYear' => $track['track_year'],
                    '#TrackLabel' => $track['track_label'],
                    '#TrackKeywords' => $track['track_keywords'],
                    '#TrackDescription' => $track['track_description'],
                    '#TrackShare' => $track['track_share']
                );
                $mp3 = '';
                if (!empty($track['track_filename'])) {
                    $mp3 = __FFM_ARCHIVE_FRONT__ . $track['release_hash'] . '/' . $track['track_filename'];
                }
                echo json_encode(array(
                    'elements' => $elements,
                    'genres' => $genres,
                    'mp3' => $mp3
                ));
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            }
        }
    }

    private function getTrackList($artist) {
        if (empty($_POST['Id'])) {
            echo json_encode(array(
                'message' => Utils::getMessage('e002')
            ));
        } else {
            require_once "Db/DbReleases.php";
            $release = DbReleases::getReleaseByHash($_POST['Id']);

            if (!empty($release)) {
                echo json_encode(array(
                    'elements' => $this->displayTrackList($artist, $release)
                ));
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            }
        }
    }

    private function saveTrack($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbTracks.php";
            require_once "Db/DbReleases.php";

            $row = array(
                'track_name' => Utils::escape($_POST['Name']),
                'track_year' => Utils::escape($_POST['Year']),
                'track_label' => Utils::escape($_POST['Label']),
                'track_keywords' => Utils::escape($_POST['Keywords']),
                'track_description' => Utils::escape($_POST['Description']),
                'track_share' => Utils::escape($_POST['Share']),
                'artist_id' => $artist['artist_id'],
                'release_hash' => Utils::escape($_POST['ReleaseHash']),
                'user_id' => $_SESSION['user']['user_id'],
                'track_upload_user_ip' => Utils::getIpAddress()
            );

            if (empty($_POST['Id'])) {
                $id = DbTracks::newTrack($row);

                $this->log($artist, "User [{$_SESSION['user']['user_name']}] has created track:" . var_export($row, true));
            } else {
                $result = DbTracks::updateTrackById($_POST['Id'], $row);
                $id = $_POST['Id'];

                $this->log($artist, "User [{$_SESSION['user']['user_name']}] has saved track:" . var_export($row, true));
            }

            if (!empty($_POST['GenresIds'])) {
                DbTracks::setTrackGenres($id, $_POST['GenresIds']);
            }

            $track = DbTracks::getTrackById($id);
            $release = DbReleases::getReleaseByHash($track['release_hash']);

            if ($_POST['Share']) {
                $this->zipFolder(__FFM_ARCHIVE__ . $track['release_hash'], $track['release_hash']);
            }

            echo json_encode(array(
                'elements' => $this->displayTrackList($artist, $release)
            ));
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function deleteTrack($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbTracks.php";
            require_once "Db/DbReleases.php";

            if (empty($_POST['Id'])) {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            } else {
                $track = DbTracks::getTrackById($_POST['Id']);
                $result = DbTracks::deleteTrackById($track['track_id']);

                ////////////////////////////////////////////////////////////////////////////////////////////////////////
                $f = __FFM_ARCHIVE__ . $track['release_hash'] . '/' . $track['track_filename'];
                if (!empty($track['track_filename']) && file_exists($f)) {
                    unlink($f);
                    $this->zipFolder(__FFM_ARCHIVE__ . $track['release_hash'], $track['release_hash']);
                }
                ////////////////////////////////////////////////////////////////////////////////////////////////////////

                $release = DbReleases::getReleaseByHash($track['release_hash']);

                echo json_encode(array(
                    'elements' => $this->displayTrackList($artist, $release)
                ));

                $this->log($artist, "User [{$_SESSION['user']['user_name']}] has deleted track");
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function displayTrackList($artist, $release) {
        require_once "Db/DbTracks.php";
        $tracks = DbTracks::getTracksByReleaseHash($release['release_hash'], 0, 100);

        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $enable_download = 0;

        foreach ($tracks as &$track) {
            $_genres = $dbGenres->getGenresByTrackId($track['track_id']);
            $track['genres'] = $_genres;
            $enable_download += $track['track_share'];
        }

        $response = new Response('');
        $response->assign('Artist', $artist);
        $response->assign('Release', $release);
        $response->assign('Tracks', $tracks);
        $response->assign('Downloadable', $enable_download);
        $response->assign('Editable', $this->isEditable($artist['artist_id']));

        return array(
            '#TrackList' => $response->fetch('profile/tracks_list.tpl')
        );
    }


    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /*                                                                                                                */
    /* RECOMMENDS                                                                                                     */
    /*                                                                                                                */
    private function addRecommendsArtist($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $result = $dbArtists->addRecommendsArtist($artist['artist_id'], $_POST['Id'], RECOMMENDS_TYPE_ARTIST);

            if ($result) {
                $this->getRecommendsArtists($artist);
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e006')
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function deleteRecommendsArtist($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $result = $dbArtists->deleteRecommendsArtist($artist['artist_id'], $_POST['Id'], RECOMMENDS_TYPE_ARTIST);

            if ($result) {
                $this->getRecommendsArtists($artist);
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function getRecommendsArtists($artist) {
        require_once "Db/DbTracks.php";
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $recommends = $dbArtists->getRecommendsArtists($artist['artist_id'], RECOMMENDS_TYPE_ARTIST);
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        foreach ($recommends as &$profile) {
            $_genres = $dbGenres->getGenresByArtistId($profile['artist_id']);
            $profile['genres'] = $_genres;
            $profile['track'] = DbTracks::getRandomTrackByArtistId($profile['artist_id']);
        }
        $response = new Response('');
        $response->assign('Recommends', $recommends);
        $response->assign('Editable', $this->isEditable($artist['artist_id']));
        echo json_encode(array(
            'elements' => array(
                '#RecommendsArtists' => $response->fetch('profile/recommends_artists.tpl')
            )
        ));
    }


    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /*                                                                                                                */
    /* TABS                                                                                                           */
    /*                                                                                                                */
    private function getArticles($artist) {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticlesByArtistId($artist['artist_id'], 0, 100);
        $response = new Response('profile/articles.tpl');
        $response->assign('Artist', $artist);
        $response->assign('Articles', $articles);
        $response->write();
    }

    private function getEvents($artist, $fullPage = true) {
        require_once "Db/DbEvents.php";
        $dbEvents = new DbEvents();
        $events = $dbEvents->getEventsByArtistId($artist['artist_id'], 0, 100);

        $response = new Response('profile/events.tpl');
        $response->assign('Artist', $artist);
        $response->assign('Events', $events);
        $response->assign('Editable', $this->isEditable($artist['artist_id']));

        if ($fullPage) {
            $response->write();
        } else {
            return array(
                '#EventList' => $response->fetch('profile/events_list.tpl')
            );
        }
    }

    private function getReleases($artist, $fullPage = true) {
        require_once "Db/DbReleases.php";
        $releases = DbReleases::getReleasesByArtistId($artist['artist_id'], 0, 100);

        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        require_once "Db/DbTracks.php";
        $dbGenres = new DbGenres();
        foreach ($releases as &$release) {
            $_genres = $dbGenres->getGenresByReleaseId($release['release_id']);
            $release['genres'] = $_genres;
            $_tracks = DbTracks::getTracksByReleaseHash($release['release_hash'], 0, 100);
            $release['tracks'] = $_tracks;
        }

        $response = new Response('profile/releases.tpl');
        $response->assign('Artist', $artist);
        $response->assign('Releases', $releases);
        $response->assign('Editable', $this->isEditable($artist['artist_id']));

        if ($fullPage) {
            $response->write();
        } else {
            return array(
                '#ReleaseList' => $response->fetch('profile/releases_list.tpl')
            );
        }
    }

    private function getPhotos($artist, $fullPage = true) {
        require_once "Db/DbPhotos.php";
        $photos = DbPhotos::getPhotosByArtistId($artist['artist_id'], 0, 200);
        $response = new Response('profile/photos.tpl');
        $response->assign('Artist', $artist);
        $response->assign('Photos', $photos);
        $response->assign('Editable', $this->isEditable($artist['artist_id']));
        if ($fullPage) {
            $response->write();
        } else {
            return array(
                '#PhotoList' => $response->fetch('profile/photos_list.tpl')
            );
        }
    }

    private function getVideos($artist) {
        require_once "Db/DbVideo.php";
        $dbVideo = new DbVideo();
        $videos = $dbVideo->getVideosByArtistId($artist['artist_id'], 0, 50);

        $response = new Response('profile/videos.tpl');
        $response->assign('Artist', $artist);
        $response->assign('Videos', $videos);
        $response->write();
    }

    private function displayArtist($artist, $fullPage = true) {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $genres = $dbGenres->getGenresByArtistId($artist['artist_id'], 0, 50);
        $artist['genres'] = $genres;

        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $geoTag = $dbGeoTags->getGeoTagById($artist['geo_tag_id']);
        $geoTagList = $dbGeoTags->getGeoTags();

        if (empty($geoTag['parent_id'])) {
            $country = $geoTag;
        } else {
            $country = $dbGeoTags->getGeoTagById($geoTag['parent_id']);
        }

        $NMPlace = \db\DbPlace::getById($geoTag['nm_place_id']);

        require_once "Db/DbVideo.php";
        $dbVideo = new DbVideo();
        $videos = $dbVideo->getVideosByArtistId($artist['artist_id'], 0, 3);

        require_once "Db/DbTracks.php";
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $recommends = $dbArtists->getRecommendsArtists($artist['artist_id'], RECOMMENDS_TYPE_ARTIST);
        foreach ($recommends as &$profile) {
            $_genres = $dbGenres->getGenresByArtistId($profile['artist_id']);
            $profile['genres'] = $_genres;
            $profile['track'] = DbTracks::getRandomTrackByArtistId($profile['artist_id']);
        }

        require_once "Db/DbComments.php";
        $comments = DbComments::getCommentsByParentId($artist['artist_id'], COMMENT_CATEGORY_ARTIST);

        require_once "Db/DbAudio.php";
        $dbAudio = new DbAudio();
        $audios = $dbAudio->getAudioByArtistId($artist['artist_id'], 0, 10);
        foreach($audios as &$audio) {
            $_artist = $dbArtists->getArtistById($audio['artist_id']);
            $audio['artist'] = $_artist;
        }

        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticlesByArtistId($artist['artist_id'], 0, 50);

        $response = new Response('artist.tpl');
        $response->assign('Section', 'artists');
        $response->assign('Title', $artist['name']);
        $response->assign('Artist', $artist);
        $response->assign('GeoTag', $geoTag);
        $response->assign('GeoTagList', $geoTagList);
        $response->assign('Country', $country);
        $response->assign('NMPlace', $NMPlace);
        $response->assign('Videos', $videos);
        $response->assign('Recommends', $recommends);
        $response->assign('Comments', $comments);
        $response->assign('Audios', $audios);
        $response->assign('Articles', $articles);
        $response->assign('Editable', $this->isEditable($artist['artist_id']));

        if ($fullPage) {
            $response->write();
        } else {
            return array(
                '#ProfileTitle' => $response->fetch('profile/title.tpl'),
                '#ProfileCard' => $response->fetch('profile/card.tpl'),
                '#ProfileGenres' => $response->fetch('profile/genres.tpl')
            );
        }
    }


    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /*                                                                                                                */
    /* FILES                                                                                                          */
    /*                                                                                                                */
    private function saveProfileImage($artist) {
        Utils::checkFileUpload();
        if ($this->isEditable($artist['artist_id'])) {
            $r = Utils::createImage(array(
                array(
                    'filename' => __FFM_PROFILE__ . $artist['filename'] . '/a/s.jpg',
                    'width' => 50,
                    'height' => 50
                ),
                array(
                    'filename' => __FFM_PROFILE__ . $artist['filename'] . '/a/m.jpg',
                    'width' => 130,
                    'height' => 130
                ),
                array(
                    'filename' => __FFM_PROFILE__ . $artist['filename'] . '/a/b.jpg',
                    'width' => 180,
                    'height' => 180
                ),
                array(
                    'filename' => __FFM_PROFILE__ . $artist['filename'] . '/a/o.jpg',
                    'width' => 0,
                    'height' => 0
                )
            ));

            if ($r) {
                require_once "Db/DbArtists.php";
                $dbArtists = new DbArtists();
                $data = array(
                    'image' => rand(1, 127)
                );
                $dbArtists->updateArtistById($artist['artist_id'], $data);
                $artist['image'] = $data['image'];
                Utils::convertArtistImage($artist);
                echo json_encode(array(
                    'image' => $artist['image']
                ));
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e003')
                ));
            }

            $this->log($artist, "User [{$_SESSION['user']['user_name']}] has uploaded profile picture");
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function saveEventImage($artist) {
        Utils::checkFileUpload();
        if ($this->isEditable($artist['artist_id']) && !empty($_POST['Id'])) {
            require_once "Db/DbEvents.php";
            $dbEvents = new DbEvents();
            $event = $dbEvents->getEventById($_POST['Id']);

            if (!empty($event)) {
                $d = dirname(__FILE__) . '/../../../thumbnails/';
                $r = Utils::createImage(array(
                    array(
                        'filename' => $d . "events/{$event['event_id']}.jpg",
                        'width' => 210,
                        'height' => 130
                    ),
                    array(
                        'filename' => $d . "events_big/{$event['event_id']}.jpg",
                        'width' => 405,
                        'height' => 300
                    )
                ));

                if ($r) {
                    $data = array(
                        'event_image' => $event['event_id']
                    );
                    $dbEvents->updateEventById($event['event_id'], $data);

                    $this->log($artist, "User [{$_SESSION['user']['user_name']}] has uploaded event picture");

                    echo json_encode(array(
                        'image' => $event['event_id']
                    ));
                } else {
                    echo json_encode(array(
                        'message' => Utils::getMessage('e003')
                    ));
                }
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function saveReleaseImage($artist) {
        Utils::checkFileUpload();
        if ($this->isEditable($artist['artist_id']) && !empty($_POST['Id'])) {
            require_once "Db/DbReleases.php";
            $release = DbReleases::getReleaseById($_POST['Id']);

            if (!empty($release)) {
                $r = Utils::createImage(array(
                    array(
                        'filename' => __FFM_ARCHIVE__ . $release['release_hash'] . '/cover_s.jpg',
                        'width' => 50,
                        'height' => 50
                    ),
                    array(
                        'filename' => __FFM_ARCHIVE__ . $release['release_hash'] . '/cover_m.jpg',
                        'width' => 130,
                        'height' => 130
                    ),
                    array(
                        'filename' => __FFM_ARCHIVE__ . $release['release_hash'] . '/cover_b.jpg',
                        'width' => 180,
                        'height' => 180
                    ),
                    array(
                        'filename' => __FFM_ARCHIVE__ . $release['release_hash'] . '/cover_o.jpg',
                        'width' => 0,
                        'height' => 0
                    )
                ));

                if ($r) {
                    $data = array(
                        'release_image' => rand(1, 127)
                    );
                    DbReleases::updateReleaseById($release['release_id'], $data);

                    echo json_encode(array(
                        'image' => __FFM_ARCHIVE_FRONT__ . $release['release_hash']
                    ));
                } else {
                    echo json_encode(array(
                        'message' => Utils::getMessage('e003')
                    ));
                }
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function savePhoto($artist) {
        Utils::checkFileUpload();
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbPhotos.php";
            require_once "formatting.php";
            $filename = wp_unique_filename(__FFM_PROFILE__ . $artist['filename'] . '/m/', $_FILES['Filedata']['name']);

            $r = Utils::createImage(array(
                array(
                    'filename' => __FFM_PROFILE__ . $artist['filename'] . '/m/' . $filename,
                    'width' => 130,
                    'height' => 130
                ),
                array(
                    'filename' => __FFM_PROFILE__ . $artist['filename'] . '/o/' . $filename,
                    'width' => 0,
                    'height' => 0
                )
            ));

            $r = $r && Utils::createImage(array(
                array(
                    'filename' => __FFM_PROFILE__ . $artist['filename'] . '/b/' . $filename,
                    'width' => 720,
                    'height' => 700
                )
            ), 0);

            if ($r) {
                $data = array(
                    'parent_id' => $artist['artist_id'],
                    'photo_filename' => $filename,
                    'photo_type' => PHOTO_TYPE_ARTIST
                );
                DbPhotos::newPhoto($data);

                $this->log($artist, "User [{$_SESSION['user']['user_name']}] has uploaded photo");

                echo json_encode(array(
                    'elements' => $this->getPhotos($artist, false)
                ));
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e003')
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function deletePhoto($artist) {
        if ($this->isEditable($artist['artist_id'])) {
            require_once "Db/DbPhotos.php";

            if (empty($_POST['Id'])) {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            } else {
                $result = DbPhotos::deleteArtistPhotoById($_POST['Id']);

                $this->log($artist, "User [{$_SESSION['user']['user_name']}] has deleted photo");

                echo json_encode(array(
                    'elements' => $this->getPhotos($artist, false)
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function saveTrackAudio($artist) {
        Utils::checkFileUpload();
        if ($this->isEditable($artist['artist_id']) && !empty($_POST['Id'])) {
            require_once "Db/DbTracks.php";
            $track = DbTracks::getTrackById($_POST['Id']);

            if (!empty($track)) {
                $f = __FFM_ARCHIVE__ . $track['release_hash'] . '/' . $track['track_filename'];
                if (!empty($track['track_filename']) && file_exists($f)) {
                    unlink($f);
                }

                include_once "formatting.php";
                $filename = wp_unique_filename(__FFM_ARCHIVE__ . $track['release_hash'], $_FILES['Filedata']['name']);

                $path = __FFM_ARCHIVE__ . $track['release_hash'] . '/' . $filename;
                if (!is_dir(dirname($path))) {
                    wp_mkdir_p(dirname($path));
                }
                move_uploaded_file($_FILES["Filedata"]["tmp_name"], $path);

                require_once(dirname(__FILE__) . '/../../getid3/getid3.php');
                $getID3 = new getID3;
                $getID3->setOption(array('encoding' => 'UTF-8'));
                $info = $getID3->analyze($path);

                $data = array(
                    'track_filename' => $filename,
                    'track_size' => filesize($path),
                    'track_length' => isset($info['playtime_seconds']) ? $info['playtime_seconds'] : 0,
                    'track_bitrate' => isset($info['audio']['bitrate']) ? $info['audio']['bitrate'] : 0,
                );
                DbTracks::updateTrackById($track['track_id'], $data);

                $this->zipFolder(__FFM_ARCHIVE__ . $track['release_hash'], $track['release_hash']);

                $this->log($artist, "User [{$_SESSION['user']['user_name']}] has uploaded track");

                echo json_encode(array(
                    'mp3' => $mp3 = __FFM_ARCHIVE_FRONT__ . $track['release_hash'] . '/' . $filename
                ));
            } else {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function zipFolder($dir, $release_hash) {
        if ($handle = opendir($dir)) {
            $z = $dir . '/' . $release_hash . '.zip';
            if (file_exists($z)) {
                unlink($z);
            }
            $zip = new ZipArchive();
            $zip->open($z, ZIPARCHIVE::CREATE);
            while (false !== ($f = readdir($handle))) {
                if (substr($f, -4) == '.mp3' && is_file($dir . '/' . $f)) {
                    $zip->addFile($dir . '/' . $f, $f);
                }
            }
            closedir($handle);
            $zip->close();
            require_once "Db/DbReleases.php";
            $release = DbReleases::getReleaseByHash($release_hash);
            if (is_file($z)) {
                $release['release_zip'] = filesize($z);
            } else {
                $release['release_zip'] = 0;
            }
            DbReleases::updateReleaseById($release['release_id'], $release);
        }
    }
}

class NewArtist extends RequestHandler {
    private function logId($id, $str) {
        Utils::logArtist($_SESSION['user']['user_id'], $id, $str);
    }

    public function get($parameters) {
        $response = new Response('artist_new.tpl');
        $response->assign('Title', 'Untitled');
        $response->assign('Section', 'artists');
        $response->assign('Access', isset($parameters[1]) && Utils::checkCurrentWeekNumberMd5($parameters[1]));
        $response->write();
    }

    public function post() {
        if (!empty($_SESSION['user'])) {
            $name = Utils::escape($_POST['Name']);
            $name_ru = Utils::escape($_POST['NameRu']);

            if (!empty($name)) {
                include_once "formatting.php";
                require_once "Db/DbArtists.php";
                $dbArtists = new DbArtists();

                $filename = Utils::sanitizeName($_POST['Name']);
                $r = $dbArtists->getArtistByName($filename);
                if (!empty($r)) {
                    echo json_encode(array(
                        'message' => Utils::getMessage('e004')
                    ));
                } else {
                    $row = array(
                        'filename' => $filename,
                        'name' => $name,
                        'name_ru' => $name_ru
                    );

                    $result = $dbArtists->newArtist($row);

                    if ($result > 0) {
                        require_once "Db/DbPm.php";
                        $dbPm = new DbPm();

                        $r = array(
                            'user_id' => $_SESSION['user']['user_id'],
                            'artist_id' => $result,
                            'request_date' => date('Y-m-d H:i:s'),
                            'request_email' => '',
                            'request_text' => '[automatical]',
                            'request_status' => STATUS_ENABLED
                        );

                        $dbPm->newRequest($r);

                        $this->logId($result, "User [{$_SESSION['user']['user_name']}] has created Artist Profile: \r\n" . var_export($row, true));

                        echo json_encode(array(
                            'url' => 'http://' . __FFM_HOST__ . '/artists/' . $filename . '.html'
                        ));
                    } else {
                        echo json_encode(array(
                            'message' => Utils::getMessage('e002')
                        ));
                    }
                }
            }
        }
    }
}

$app = new Application(array(
    array('/^\/artists\/([a-z0-9._%+-]{1,200}).html$/', 'Artist', false),
//    array('/^\/artists\/new\/([\w]{32})?$/', 'NewArtist', false)
));

$app->run();

