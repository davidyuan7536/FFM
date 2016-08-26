<?php

class Promoter extends RequestHandler {
    public function get($parameters) {
        require_once "Db/DbPromoters.php";
        $promoter = DbPromoters::getPromoterByName($parameters[1]);

        if ($promoter == array()) {
            Utils::sendResponse(404);
        } else {
            if (isset($_GET['term'])) {
                $this->searchArtists($_GET['term']);
            } else {
                $this->displayPromoter($promoter);
            }
        }
    }

    public function post($parameters) {
        require_once "Db/DbPromoters.php";
        $promoter = DbPromoters::getPromoterByName($parameters[1]);

        if (!empty($promoter)) {
            if (isset($_POST['Action'])) {
                switch ($_POST['Action']) {
                    case "SaveProfileImage":
                        $this->saveImage($promoter);
                        break;
                    case "SavePhoto":
                        $this->savePhoto($promoter);
                        break;
                    case "DeletePhoto":
                        $this->deletePhoto($promoter);
                        break;
                    case "Search":
                        $this->getGenres();
                        break;
                    case "GetInfo":
                        $this->getInfo($promoter);
                        break;
                    case "SaveInfo":
                        $this->saveInfo($promoter);
                        break;
                    case "AddPromotersArtist":
                        $this->addPromotersArtist($promoter);
                        break;
                    case "DeletePromotersArtist":
                        $this->deletePromotersArtist($promoter);
                        break;
                    case "AddRecommendsArtist":
                        $this->addRecommendsArtist($promoter);
                        break;
                    case "DeleteRecommendsArtist":
                        $this->deleteRecommendsArtist($promoter);
                        break;
                }
            } else {
                switch ($_GET['tab']) {
                    case "articles":
                        $this->getArticles($promoter);
                        break;
                    case "events":
                        $this->getEvents($promoter);
                        break;
                    case "videos":
                        $this->getVideos($promoter);
                        break;
                    case "photos":
                        $this->getPhotos($promoter);
                        break;
                }
            }
        }
    }

    private function log($promoter, $str) {
        Utils::logPromoter($_SESSION['user']['user_id'], $promoter['promoter_id'], $str);
    }

    private function isEditable($userId) {
//        if (!empty($_SESSION['user'])) {
//            return $_SESSION['user']['super_user'] == 1 || $_SESSION['user']['user_id'] == $userId;
//        }
        return false;
    }

    private function getGenres() {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $genres = $dbGenres->getGenresByName($_POST['Search']);
        echo json_encode($genres);
    }

    private function searchArtists($str) {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists = $dbArtists->getArtistsBySearch($str);
        echo json_encode($artists);
    }

    private function getInfo($promoter) {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $genres = $dbGenres->getGenresByPromoterId($promoter['promoter_id'], 0, 50);

        $elements = array(
            '#ProfileName' => $promoter['promoter_name'],
            '#ProfileNameRu' => $promoter['promoter_name_ru'],
            '#ProfileDescription' => $promoter['promoter_description'],
            '#ProfileLinks' => $promoter['promoter_links'],
            '#ProfileExtra' => $promoter['promoter_extra'],
            '#ProfileClub' => $promoter['promoter_status'] == PROMOTER_STATUS_CLUB,
            '#GeoSearchId' => '',
            '#GeoSearch' => ''
        );

        if (!empty($promoter['geo_tag_id'])) {
            require_once "Db/DbGeotags.php";
            $dbGeoTags = new DbGeotags();
            $geoTag = $dbGeoTags->getGeoTagById($promoter['geo_tag_id']);
            $elements['#GeoSearchId'] = $promoter['geo_tag_id'];
            $elements['#GeoSearch'] = $geoTag['name'];
        }

        if (!empty($promoter['geo_tag_text'])) {
            $elements['#GeoSearch'] = $promoter['geo_tag_text'];
        }

        echo json_encode(array(
            'elements' => $elements,
            'genres' => $genres
        ));
    }

    private function saveInfo($promoter) {
        if ($this->isEditable($promoter['user_id'])) {
            require_once "Db/DbPromoters.php";
            DbPromoters::setPromoterGenres($promoter['promoter_id'], $_POST['GenresIds']);
            $row = array(
                'promoter_name' => Utils::escape($_POST['Name']),
                'promoter_name_ru' => Utils::escape($_POST['NameRu']),
                'promoter_description' => Utils::escape($_POST['Description']),
                'promoter_links' => Utils::escape($_POST['Links']),
                'promoter_extra' => Utils::escape($_POST['Extra']),
                'promoter_status' => Utils::escape($_POST['Club'])
            );

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

            $result = DbPromoters::updatePromoterById($promoter['promoter_id'], $row);

            $this->log($promoter, "User [{$_SESSION['user']['user_name']}] has made changes: \r\n" . var_export($row, true));

            if ($result == 0 || $result == 1) {
                $new_promoter = DbPromoters::getPromoterById($promoter['promoter_id']);
                echo json_encode(array(
                    'elements' => $this->displayPromoter($new_promoter, false)
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

    private function addPromotersArtist($promoter) {
        if ($this->isEditable($promoter['user_id'])) {
            require_once "Db/DbPromoters.php";
            $result = DbPromoters::addPromotersArtist($promoter['promoter_id'], $_POST['Id']);

            if ($result) {
                $this->getArtists($promoter);
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

    private function deletePromotersArtist($promoter) {
        if ($this->isEditable($promoter['user_id'])) {
            require_once "Db/DbPromoters.php";
            $result = DbPromoters::deletePromotersArtist($promoter['promoter_id'], $_POST['Id']);

            if ($result) {
                $this->getArtists($promoter);
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

    private function addRecommendsArtist($promoter) {
        if ($this->isEditable($promoter['user_id'])) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $result = $dbArtists->addRecommendsArtist($promoter['promoter_id'], $_POST['Id'], RECOMMENDS_TYPE_PROMOTER);

            if ($result) {
                $this->getRecommendsArtists($promoter);
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

    private function deleteRecommendsArtist($promoter) {
        if ($this->isEditable($promoter['user_id'])) {
            require_once "Db/DbArtists.php";
            $dbArtists = new DbArtists();
            $result = $dbArtists->deleteRecommendsArtist($promoter['promoter_id'], $_POST['Id'], RECOMMENDS_TYPE_PROMOTER);

            if ($result) {
                $this->getRecommendsArtists($promoter);
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

    private function getArtists($promoter) {
        require_once "Db/DbPromoters.php";
        $artists = DbPromoters::getPromotersArtists($promoter['promoter_id']);
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        foreach ($artists as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id']);
            $artist['genres'] = $_genres;
        }
        $response = new Response('');
        $response->assign('Artists', $artists);
        $response->assign('Editable', $this->isEditable($promoter['user_id']));
        echo json_encode(array(
            'elements' => array(
                '#PromotersArtists' => $response->fetch('promoter/promoters_artists.tpl')
            )
        ));
    }

    private function getRecommendsArtists($promoter) {
        require_once "Db/DbTracks.php";
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $recommends = $dbArtists->getRecommendsArtists($promoter['promoter_id'], RECOMMENDS_TYPE_PROMOTER);
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        foreach ($recommends as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id']);
            $artist['genres'] = $_genres;
            $artist['track'] = DbTracks::getRandomTrackByArtistId($artist['artist_id']);
        }
        $response = new Response('');
        $response->assign('Recommends', $recommends);
        $response->assign('Editable', $this->isEditable($promoter['user_id']));
        echo json_encode(array(
            'elements' => array(
                '#RecommendsArtists' => $response->fetch('profile/recommends_artists.tpl')
            )
        ));
    }

    private function getArticles($promoter) {
        require_once "Db/DbArticles.php";
        $dbArticles = new DbArticles();
        $articles = $dbArticles->getArticlesByPromoterId($promoter['promoter_id'], 0, 100);
        $response = new Response('profile/articles.tpl');
        $response->assign('Promoter', $promoter);
        $response->assign('Articles', $articles);
        $response->write();
    }

    private function getEvents($promoter) {
        require_once "Db/DbEvents.php";
        $dbEvents = new DbEvents();
        $events = $dbEvents->getEventsByPromoterId($promoter['promoter_id'], 0, 100);

        $response = new Response('profile/events.tpl');
        $response->assign('Events', $events);
        $response->write();
    }

    private function getVideos($promoter) {
        require_once "Db/DbVideo.php";
        $dbVideo = new DbVideo();
        $videos = $dbVideo->getVideosByPromoterId($promoter['promoter_id'], 0, 100);

        $response = new Response('profile/videos.tpl');
        $response->assign('Videos', $videos);
        $response->write();
    }

    private function getPhotos($promoter, $fullPage = true) {
        require_once "Db/DbPhotos.php";
        $photos = DbPhotos::getPhotosByPromoterId($promoter['promoter_id'], 0, 200);
        $response = new Response('profile/photos.tpl');
        $response->assign('Promoter', $promoter);
        $response->assign('Photos', $photos);
        $response->assign('Editable', $this->isEditable($promoter['user_id']));
        if ($fullPage) {
            $response->write();
        } else {
            return array(
                '#PhotoList' => $response->fetch('profile/photos_list.tpl')
            );
        }
    }

    private function savePhoto($promoter) {
        Utils::checkFileUpload();
        if ($this->isEditable($promoter['user_id'])) {
            require_once "Db/DbPhotos.php";
            require_once "formatting.php";
            $filename = wp_unique_filename(__FFM_PROMOTER__ . $promoter['promoter_filename'] . '/m/', $_FILES['Filedata']['name']);

            $r = Utils::createImage(array(
                array(
                    'filename' => __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/m/' . $filename,
                    'width' => 130,
                    'height' => 130
                ),
                array(
                    'filename' => __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/o/' . $filename,
                    'width' => 0,
                    'height' => 0
                )
            ));

            $r = $r && Utils::createImage(array(
                array(
                    'filename' => __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/b/' . $filename,
                    'width' => 720,
                    'height' => 700
                )
            ), 0);

            if ($r) {
                $data = array(
                    'parent_id' => $promoter['promoter_id'],
                    'photo_filename' => $filename,
                    'photo_type' => PHOTO_TYPE_PROMOTER
                );
                DbPhotos::newPhoto($data);

                $this->log($promoter, "User [{$_SESSION['user']['user_name']}] has uploaded photo");

                echo json_encode(array(
                    'elements' => $this->getPhotos($promoter, false)
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

    private function deletePhoto($promoter) {
        if ($this->isEditable($promoter['user_id'])) {
            require_once "Db/DbPhotos.php";

            if (empty($_POST['Id'])) {
                echo json_encode(array(
                    'message' => Utils::getMessage('e002')
                ));
            } else {
                $result = DbPhotos::deletePromoterPhotoById($_POST['Id']);

                echo json_encode(array(
                    'elements' => $this->getPhotos($promoter, false)
                ));
            }
        } else {
            echo json_encode(array(
                'message' => Utils::getMessage('e001')
            ));
        }
    }

    private function displayPromoter($promoter, $fullPage = true) {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();
        $genres = $dbGenres->getGenresByPromoterId($promoter['promoter_id'], 0, 50);
        $promoter['genres'] = $genres;

        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $geoTag = $dbGeoTags->getGeoTagById($promoter['geo_tag_id']);
        $geoTagList = $dbGeoTags->getGeoTags();

        if (empty($geoTag['parent_id'])) {
            $country = $geoTag;
        } else {
            $country = $dbGeoTags->getGeoTagById($geoTag['parent_id']);
        }

        $NMPlace = \db\DbPlace::getById($geoTag['nm_place_id']);

        require_once "Db/DbPromoters.php";
        $artists = DbPromoters::getPromotersArtists($promoter['promoter_id']);
        foreach ($artists as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id']);
            $artist['genres'] = $_genres;
        }

        require_once "Db/DbTracks.php";
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $recommends = $dbArtists->getRecommendsArtists($promoter['promoter_id'], RECOMMENDS_TYPE_PROMOTER);
        foreach ($recommends as &$artist) {
            $_genres = $dbGenres->getGenresByArtistId($artist['artist_id']);
            $artist['genres'] = $_genres;
            $artist['track'] = DbTracks::getRandomTrackByArtistId($artist['artist_id']);
        }

        require_once "Db/DbComments.php";
        $comments = DbComments::getCommentsByParentId($promoter['promoter_id'], COMMENT_CATEGORY_PROMOTER);

        $response = new Response('promoter.tpl');
        $response->assign('Section', 'promoters');
        $response->assign('Title', $promoter['promoter_name']);
        $response->assign('Promoter', $promoter);
        $response->assign('GeoTag', $geoTag);
        $response->assign('GeoTagList', $geoTagList);
        $response->assign('Country', $country);
        $response->assign('NMPlace', $NMPlace);
        $response->assign('Artists', $artists);
        $response->assign('Recommends', $recommends);
        $response->assign('Comments', $comments);
        $response->assign('Editable', $this->isEditable($promoter['user_id']));

        if ($fullPage) {
            $response->write();
        } else {
            return array(
                '#ProfileTitle' => $response->fetch('promoter/title.tpl'),
                '#ProfileCard' => $response->fetch('promoter/card.tpl'),
                '#ProfileGenres' => $response->fetch('promoter/genres.tpl')
            );
        }
    }

    private function saveImage($promoter) {
        if ($this->isEditable($promoter['user_id'])) {
            $r = Utils::createImage(array(
                array(
                    'filename' => __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/a/s.jpg',
                    'width' => 50,
                    'height' => 50
                ),
                array(
                    'filename' => __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/a/m.jpg',
                    'width' => 130,
                    'height' => 130
                ),
                array(
                    'filename' => __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/a/b.jpg',
                    'width' => 180,
                    'height' => 180
                ),
                array(
                    'filename' => __FFM_PROMOTER__ . $promoter['promoter_filename'] . '/a/o.jpg',
                    'width' => 0,
                    'height' => 0
                )
            ));

            if ($r) {
                require_once "Db/DbPromoters.php";
                $data = array(
                    'promoter_image' => rand(1, 127)
                );
                DbPromoters::updatePromoterById($promoter['promoter_id'], $data);
                $this->log($promoter, "User [{$_SESSION['user']['user_name']}] has uploaded profile picture");

                $promoter['promoter_image'] = $data['promoter_image'];
                Utils::convertPromoterImage($promoter);
                echo json_encode(array(
                    'promoter' => true,
                    'image' => $promoter['promoter_image']
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
}

class NewPromoter extends RequestHandler {
    public function get() {
        if (empty($_SESSION['user'])) {
            Utils::sendResponse(404);
        } else {
            $response = new Response('promoter_new.tpl');
            $response->assign('Title', 'Untitled');
            $response->assign('Section', 'promoters');
            $response->assign('Editable', true);
            $response->write();
        }
    }

    public function post() {
        if (!empty($_SESSION['user'])) {
            $name = Utils::escape($_POST['Name']);
            $name_ru = Utils::escape($_POST['NameRu']);

            if (!empty($name)) {
                include_once "formatting.php";
                require_once "Db/DbPromoters.php";

                $filename = Utils::sanitizeName($_POST['Name']);
                $r = DbPromoters::getPromoterByName($filename);
                if (!empty($r)) {
                    echo json_encode(array(
                        'message' => Utils::getMessage('e004')
                    ));
                } else {
                    $row = array(
                        'user_id' => $_SESSION['user']['user_id'],
                        'promoter_name' => $name,
                        'promoter_name_ru' => $name_ru,
                        'promoter_filename' => $filename
                    );

                    $result = DbPromoters::newPromoter($row);

                    if ($result > 0) {
                        /**********************************************************************************************************/
                        /* LOG                                                                                                    */
                        /**********************************************************************************************************/
                        Utils::logPromoter($_SESSION['user']['user_id'], $result, "User [{$_SESSION['user']['user_name']}] has created Promoter Profile: \r\n" . var_export($row, true));

                        echo json_encode(array(
                            'url' => 'http://' . __FFM_HOST__ . '/promoters/' . $filename . '.html'
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
    array('/^\/promoters\/([a-z0-9._%+-]{1,200}).html$/', 'Promoter', false),
//    array('/^\/promoters\/new\/$/', 'NewPromoter', false)
));

$app->run();

