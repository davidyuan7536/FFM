<?php

require_once "base.php";

class Page extends RequestHandler {
    public function get() {
        if (isset($_GET['id'])) {
            require_once "Db/DbUsers.php";
            $dbUsers = new DbUsers();
            $user = $dbUsers->getUserById($_GET['id']);
        }

        if (isset($user) && !empty($user)) {
            $response = new Response('admin/main.tpl');
            $response->assign('User', $user);
            $response->assign('Title', $user['user_name']);
            $response->assign('Section', 'users');
            $response->assign('Template', 'user');
            if (!empty($user['country_code'])) {
                include "Geo/geoip.inc";
                $gi = new GeoIP;
                $number = $gi->GEOIP_COUNTRY_CODE_TO_NUMBER[$user['country_code']];
                $response->assign('Country', $gi->GEOIP_COUNTRY_NAMES[$number]);
            }
            $response->write();
        } else {
            $response = new Response('admin/main.tpl');
            $response->assign('Title', 'User not found');
            $response->assign('Section', 'users');
            $response->assign('Template', '404');
            $response->write();
        }
    }

    public function post() {
        require_once "Db/DbUsers.php";
        $dbUsers = new DbUsers();

        switch ($_POST['action']) {
            case "save":
                if ($_POST['Id'] != '') {
                    $row = array(
                        'user_name' => $_POST['Name'],
                        'user_hash' => $_POST['Hash'],
                        'user_email' => $_POST['Email'],
                        'enabled' => $_POST['Enabled'],
                        'subscribe' => $_POST['Subscribe']
                    );

                    if ($_POST['Password'] != '') {
                        $row['password'] = md5($_POST['Password']);
                    }

                    $result = $dbUsers->updateUserById($_POST['Id'], $row);

                    echo $result == 0 || $result == 1 ? "OK" : $result;
                } else {
                    echo "User not found";
                }
                break;
            case "delete":
                echo "OK";
                break;
        }
    }
}

new Page(array(), false);
