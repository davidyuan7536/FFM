<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        require_once "Db/DbArtists.php";
        $dbArtists = new DbArtists();
        $artists = $dbArtists->getArtistsByGeotagQueue();

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Geotag Queue');
        $response->assign('Section', 'queue');
        $response->assign('Template', 'queue');
        $response->assign('Artists', $artists);
        $response->write();
    }
}

new Page(array(), false);
