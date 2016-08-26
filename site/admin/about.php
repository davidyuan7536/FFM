<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        require_once "Db/DbAbout.php";
        $dbAbout = new DbAbout();
        $about = $dbAbout->getAbout();

        $response = new Response('admin/main.tpl');
        $response->assign('About', $about);
        $response->assign('Title', 'About');
        $response->assign('Section', 'about');
        $response->assign('Template', 'about');
        $response->write();
    }

    public function post() {
        require_once "Db/DbAbout.php";
        $dbAbout = new DbAbout();

        $result = $dbAbout->updateAbout(array(
//            'about_header' => $_POST['aboutHeader'],
//            'about_header_ru' => $_POST['aboutHeaderRu'],
            'about' => $_POST['about'],
            'about_ru' => $_POST['aboutRu']
        ));

        echo $result == 0 || $result == 1 ? "OK" : $result;
    }
}

new Page(array(), false);
