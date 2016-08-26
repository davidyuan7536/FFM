<?php

class FAuth extends RequestHandler {
    public function get() {
        require_once "Auth/facebook.php";
        $facebook = new Facebook(array(
            'appId' => __FFM_FBID__,
            'secret' => __FFM_FBSECRET__,
            'cookie' => true,
        ));
        $session = $facebook->getSession();
        $fbUser = null;
        if ($session) {
            try {
                $fbUser = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
            }
        }
        if (!empty($fbUser)) {
            require_once "Db/DbUsers.php";
            $users = new DbUsers();
            $user = $users->getUserByFBId($fbUser['id']);

            if (empty($user)) {
                $user = $users->getUserByEmail($fbUser['email']);
                if (empty($user)) {
                    $ip = Utils::getIpAddress();
                    $row = array(
                        'user_name' => $fbUser['name'],
                        'user_email' => $fbUser['email'],
                        'subscribe' => 1,
                        'enabled' => 1,
                        'fbid' => $fbUser['id'],
                        'ip' => $ip
                    );

                    $record = Utils::getGeoRecord($ip);
                    if (!empty($record->country_code)) {
                        $row['country_code'] = $record->country_code;
                        $row['city'] = $record->city;
                        $row['latitude'] = $record->latitude;
                        $row['longitude'] = $record->longitude;
                    }


                    $id = $users->createUser($row);
                    if ($id) {
                        $hash = array(
                            'user_hash' => Utils::hash($id)
                        );
                        $users->updateUserById($id, $hash);
                        $user = $users->getUserById($id);
                    }
                } else {
                    $row = array(
                        'fbid' => $fbUser['id']
                    );
                    $users->updateUserById($user['user_id'], $row);
                }
            }

            Utils::setUser($user);
        }
        Utils::redirect('http://' . __FFM_HOST__ . '/users/' . $user['user_hash'] . '/');
    }
}

$app = new Application(array(
    array('/^\/fauth\/$/', 'FAuth', false)
));

$app->run();

