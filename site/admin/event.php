<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        if (isset($_GET['id'])) {
            require_once "Db/DbEvents.php";
            $dbEvents = new DbEvents();
            $event = $dbEvents->getEventById($_GET['id']);

            if ($event == array()) {
                $response = new Response('admin/main.tpl');
                $response->assign('Title', 'Event not found');
                $response->assign('Section', 'events');
                $response->assign('Template', '404');
                $response->write();
            } else {
                require_once "Db/DbArtists.php";
                $dbArtists = new DbArtists();
                $event['artists'] = $dbArtists->getArtistsByEventId($event['event_id'], 0, 50);

                require_once "Db/DbGeotags.php";
                $dbGeoTags = new DbGeotags();
                $event['geo_tag'] = $dbGeoTags->getGeoTagById($event['geo_tag_id']);

                $response = new Response('admin/main.tpl');
                $response->assign('Event', $event);
                $response->assign('Title', $event['event_name']);
                $response->assign('Section', 'events');
                $response->assign('Template', 'event');
                $response->write();
            }
        } else {
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'New Event');
            $response->assign('Section', 'events');
            $response->assign('Template', 'event');
            $response->write();
        }
    }

    public function post() {
        require_once "Db/DbEvents.php";
        $dbEvents = new DbEvents();

        switch ($_POST['action']) {
            case "save":
                if ($_POST['Id'] == '') {
                    $row = array(
                        'event_name' => $_POST['Name'],
                        'event_description' => $_POST['Description'],
                        'event_address' => $_POST['Address'],
                        'event_date' => $_POST['Date'],
                        'geo_tag_id' => $_POST['GeoTagId']
                    );

                    $id = $dbEvents->newEvent($row);

                    if ($id > 0) {
                        $dbEvents->setEventArtists($id, $_POST['ArtistsIds']);
                    }

                    echo $id;
                } else {
                    $dbEvents->setEventArtists($_POST['Id'], $_POST['ArtistsIds']);

                    $result = $dbEvents->updateEventById($_POST['Id'], array(
                        'event_name' => $_POST['Name'],
                        'event_description' => $_POST['Description'],
                        'event_address' => $_POST['Address'],
                        'event_date' => $_POST['Date'],
                        'geo_tag_id' => $_POST['GeoTagId']
                    ));

                    echo $result == 0 || $result == 1 ? "OK" : $result;
                }
                break;
            case "removeImage":
                $result = $dbEvents->setEventImageById($_POST['id'], '');
                echo $result == 0 || $result == 1 ? "OK" : $result;
                break;
            case "delete":
                $result = $dbEvents->deleteEvent($_POST['Id']);
                echo "OK";
                break;
        }
    }
}

new Page(array(), false);
