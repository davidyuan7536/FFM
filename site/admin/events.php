<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        if ($search != '') {
            require_once "Db/DbEvents.php";
            $dbEvents = new DbEvents();
            $events = $dbEvents->getEventsBySearch($search);
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'Events');
            $response->assign('Section', 'events');
            $response->assign('Template', 'events');
            $response->assign('Events', $events);
            $response->assign('Search', $search);
            $response->write();
        } else {
            $this->getTableView();
        }
    }

    private function getTableView() {
        $pagesToSide = 5;
        $itemsPerPage = 16;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbEvents.php";
        $dbEvents = new DbEvents();
        $events = $dbEvents->getEventsByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/events.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Events');
        $response->assign('Section', 'events');
        $response->assign('Template', 'events');
        $response->assign('Events', $events);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }
}

new Page(array(), false);
