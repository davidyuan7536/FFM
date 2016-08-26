<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Pictures');
        $response->assign('Section', 'pictures');
        $response->assign('Template', 'pictures');
        $response->write();
    }

    public function post() {
        require_once "Db/DbPictures.php";
        $dbPictures = new DbPictures();

        switch ($_POST['action']) {
            case "years":
                $result = $dbPictures->getPictureYears();
                echo json_encode($result);
                break;
            case "months":
                $result = $dbPictures->getPictureMonths($_POST['year']);
                echo json_encode($result);
                break;
            case "pictures":
                $result = $dbPictures->getPicturesByMonth($_POST['year'], $_POST['month']);
                echo json_encode($result);
                break;
            case "article":
                $result = $dbPictures->getPicturesByArticleId($_POST['articleId']);
                echo json_encode($result);
                break;
            case "delete":
                $result = $dbPictures->deletePicture($_POST['Id']);
                echo "OK";
                break;
        }
    }
}

new Page(array(), false);
