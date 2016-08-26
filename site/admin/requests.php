<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        $this->getTableView();
    }

    private function getTableView() {
        $pagesToSide = 5;
        $itemsPerPage = 16;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        require_once "Db/DbPm.php";
        $dbPm = new DbPm();
        $result = $dbPm->getPmPageByStatus(
            STATUS_REQUEST,
            $totalRows,
                $itemsPerPage * ($currentPage - 1),
            $itemsPerPage
        );

        require_once "Site/PagesLinks.php";
        $pagesLinks = new PagesLinks('/site/admin/requests.php?page=', $currentPage, $totalRows, $itemsPerPage, $pagesToSide);

        $response = new Response('admin/main.tpl');
        $response->assign('Title', 'Requests');
        $response->assign('Section', 'requests');
        $response->assign('Template', 'requests');
        $response->assign('Requests', $result);
        $response->assign('Pages', $pagesLinks->getLinks());
        $response->write();
    }

    public function post() {
        require_once "Db/DbPm.php";
        $dbPm = new DbPm();
        $result = 0;

        switch ($_POST['Action']) {
            case 'approve':
                $result = $dbPm->approveRequest($_POST['Id']);
                break;
            case 'decline':
                $result = $dbPm->declineRequest($_POST['Id']);
                break;
            default:
                Utils::sendResponse(404);
                exit;
        }

        if ($result == 0 || $result == 1) {
            echo json_encode(array(
                'status' => 'OK'
            ));
        } else {
            echo json_encode(array(
                'status' => 'ERROR',
                'message' => $result
            ));
        }
    }
}

new Page(array(), false);
