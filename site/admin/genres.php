<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Genres');
        $response->assign('Section', 'genres');
        $response->assign('Template', 'genres');
        $response->write();
    }

    public function post() {
        require_once "Db/DbGenres.php";
        $dbGenres = new DbGenres();

        switch ($_POST['action']) {
            case "list":
                $result = $dbGenres->getGenresAsForest();
                echo json_encode($result);
                break;
            case "move":
                $result = $dbGenres->setGenreParentId($_POST['sourceId'], $_POST['targetId']);
                echo "OK";
                break;
            case "save":
                if ($_POST['Id'] == '') {
                    include_once "formatting.php";
                    $result = $dbGenres->newGenre(
                        $_POST['Name'],
                        $_POST['Filename'] != '' ? $_POST['Filename'] : Utils::sanitizeName($_POST['Name'])
                    );
                } else {
                    $result = $dbGenres->updateGenre(
                        $_POST['Id'],
                        $_POST['Name'],
                        $_POST['Filename']
                    );
                }
                echo "OK";
                break;
            case "delete":
                $result = $dbGenres->deleteGenre($_POST['Id']);
                echo "OK";
                break;
        }
    }
}

new Page(array(), false);
