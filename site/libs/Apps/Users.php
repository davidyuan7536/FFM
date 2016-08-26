<?php

class Users extends RequestHandler {
    public function get($parameters) {
        if (isset($parameters[1])) {
            require_once "Db/DbUsers.php";
            $users = new DbUsers();
            $user = $users->getUserByHash($parameters[1]);

            if (empty($user)) {
                Utils::sendResponse(404);
            } else {
                unset($user['password']);

                $response = new Response('user.tpl');
                $response->assign('Title', $user['user_name']);
                $response->assign('User', $user);
                $response->assign('Current', !empty($_SESSION['user']) && $_SESSION['user']['user_id'] == $user['user_id']);
                $response->assign('Section', 'users');
                $response->write();
            }
        } else {
            Utils::sendResponse(404);
        }
    }

    public function post() {
        if (empty($_SESSION['user'])) {
            Utils::sendResponse(404);
            exit;
        }
        switch ($_POST['Action']) {
            case 'Search':
                require_once "Db/DbArtists.php";
                $dbArtists = new DbArtists();
                $artists = $dbArtists->getArtistsBySearch($_POST['Search'], 0, 10);
                foreach ($artists as &$artist) {
                    Utils::convertArtistImage($artist, 's');
                }
                echo json_encode($artists);
                break;
            case 'Request':
                $row = array(
                    'user_id' => $_SESSION['user']['user_id'],
                    'artist_id' => trim($_POST['Id']),
                    'request_email' => trim($_POST['Email']),
                    'request_text' => trim($_POST['Text']),
                    'request_date' => date('Y-m-d H:i:s')
                );

                $errors = $this->getFieldValues($row);
                if (!empty($errors)) {
                    echo json_encode($errors);
                } else {
                    require_once "Db/DbPm.php";
                    $dbPm = new DbPm();
                    $id = $dbPm->newRequest($row);

                    $this->send_notification($dbPm->getRequestById($id));

                    echo json_encode(array(
                        'status' => 'OK',
                        'message' => Utils::getMessage('q002')
                    ));
                }
                break;
            case 'Load':
                require_once "Db/DbPm.php";
                $dbPm = new DbPm();
                $artists = $dbPm->getPmListByUserId($_SESSION['user']['user_id'], 0, 100);
                foreach ($artists as &$artist) {
                    Utils::convertArtistImage($artist, 's');
                }
                require_once "Db/DbPromoters.php";
                $promoters = DbPromoters::getPromoterByUserId($_SESSION['user']['user_id'], 0, 100);
                foreach ($promoters as &$promoter) {
                    Utils::convertPromoterImage($promoter, 's');
                }
                echo json_encode(array_merge($artists, $promoters));
                break;
            default:
                Utils::sendResponse(404);
        }
    }

    private function getFieldValues(&$row) {
        $result = array();

        if (empty($row['request_email'])) {
            $result['fields']['Email'] = Utils::getMessage('f001');
        }

        if (empty($row['request_text'])) {
            $result['fields']['Text'] = Utils::getMessage('f001');
        }

        return $result;
    }

    private function send_notification($row) {
        $message  = "<div>User <a href='http://" . __FFM_HOST__ . "/site/admin/user.php?id={$row['user_id']}'>{$row['user_name']}</a>";
        $message .= " asks for administer permission <a href='http://" . __FFM_HOST__ . "/site/admin/artist.php?id={$row['artist_id']}'>{$row['name']}</a>:</div>";
        $message .= "<pre style='font-size:10pt;padding:1em 0;'>{$row['request_text']}\r\n\r\n- {$row['request_email']}\r\n- {$row['request_date']}</pre>";
        $message .= "<div>#{$row['request_id']}: <a href='http://" . __FFM_HOST__ . "/site/admin/requests.php'>Control Panel</a></div>";

        return Utils::mailMessage('Request notification', $message, null, false, __FFM_EMAIL__);
    }
}

$app = new Application(array(
    array('/^\/users\/([a-zA-Z\d]{1,32})\/?$/', 'Users', true),
));

$app->run();

