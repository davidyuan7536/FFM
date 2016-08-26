<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Geotags');
        $response->assign('Section', 'geotags');
        $response->assign('Template', 'geotags');
        $response->write();
    }

    public function post() {
        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();

        switch ($_POST['action']) {
            case "list":
                $result = $dbGeoTags->getGeoTagsAsForest();
                $list = array();
                foreach ($result as $value) {
                    $children = array();
                    foreach($value['childNodes'] as $c) {
                        array_push($children, $c);
                    }
                    $value['childNodes'] = $children;
                    array_push($list, $value);
                }
                echo json_encode($list);
                break;
            case "move":
                $result = $dbGeoTags->setGeoTagParentId($_POST['sourceId'], $_POST['targetId']);
                echo "OK";
                break;
            case "save":
                if ($_POST['Id'] == '') {
                    include_once "formatting.php";
                    $row = array(
                        'name' => $_POST['Name'],
                        'longname' => $_POST['Fullname'],
                        'filename' => $_POST['Filename'] != '' ? $_POST['Filename'] : Utils::sanitizeName($_POST['Name']),
                        'wiki' => $_POST['Wikilink'],
                        'lat' => $_POST['Lat'],
                        'lng' => $_POST['Lng'],
                        'zoom' => $_POST['Zoom']
                    );
                    $result = $dbGeoTags->newGeoTag($row);
                } else {
                    $row = array(
                        'name' => $_POST['Name'],
                        'longname' => $_POST['Fullname'],
                        'filename' => $_POST['Filename'],
                        'wiki' => $_POST['Wikilink'],
                        'lat' => $_POST['Lat'],
                        'lng' => $_POST['Lng'],
                        'zoom' => $_POST['Zoom']
                    );
                    $result = $dbGeoTags->updateGeoTag($_POST['Id'], $row);
                }
                echo "OK";
                break;
            case "delete":
                $result = $dbGeoTags->deleteGeoTag($_POST['Id']);
                echo "OK";
                break;
        }
    }
}

new Page(array(), false);
