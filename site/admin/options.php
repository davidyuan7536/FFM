<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Options');
        $response->assign('Section', 'options');
        $response->assign('Template', 'options');
        $response->write();
    }

    public function post() {
        switch ($_POST['Action']) {
            case 'get':
                $this->getOptions();
                break;
            case 'save':
                $this->saveOption();
                break;
            case 'delete':
                $this->deleteOption();
                break;
        }
    }

    private function getOptions() {
        require_once "Db/DbOptions.php";
        $result = DbOptions::getOptions();
        echo json_encode(array(
            'options' => $result
        ));
    }

    private function saveOption() {
        if (empty($_POST['option']['option_id'])) {
            unset($_POST['option']['option_id']);
        }
        require_once "Db/DbOptions.php";
        DbOptions::updateOption($_POST['option']);
    }

    private function deleteOption() {
        require_once "Db/DbOptions.php";
        DbOptions::deleteOption($_POST['option_id']);
    }
}

new Page(array(), false);
