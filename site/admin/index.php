<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $pagesToSide = 5;
        $itemsPerPage = 16;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbLog.php";
        $dbLog = new DbLog();
        $logs = $dbLog->getFullLogByPage(
            $totalRows,
            $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Db/DbPromoters.php";
        foreach($logs as &$log) {
            if (!empty($log['promoter_id'])) {
                $p = DbPromoters::getPromoterById($log['promoter_id']);
                $log['promoter_filename'] = $p['promoter_filename'];
            }
        }

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Dashboard');
        $response->assign('Section', 'dashboard');
        $response->assign('Template', 'dashboard');
        $response->assign('Logs', $logs);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }
}

new Page(array(), false);
