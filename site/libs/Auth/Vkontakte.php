<?php

class Vkontakte {

    function __construct($api_secret, $api_id) {
        $this->api_secret = $api_secret;
        $this->api_id = $api_id;
    }

    function getProfiles($uids) {
        $request['fields'] = 'uid,first_name,last_name,nickname,sex,bdate(birthdate),city,country,timezone,photo,photo_medium,photo_big';
        $request['uids'] = $uids;
        $request['method'] = 'secure.getProfiles';
        return $this->request($request);
    }

    function sendNotification($uids, $message) {
        $request['uids'] = $uids;
        $request['message'] = iconv('windows-1251', 'utf-8', $message);
        $request['method'] = 'secure.sendNotification';
        return $this->request($request);
    }

    function saveAppStatus($uid, $status) {
        $request['uid'] = $uid;
        $request['status'] = iconv('windows-1251', 'utf-8', $status);
        $request['method'] = 'secure.saveAppStatus';
        return $this->request($request);
    }

    function getAppStatus($uid) {
        $request['uid'] = $uid;
        $request['method'] = 'secure.getAppStatus';
        return $this->request($request);
    }

    function getAppBalance() {
        $request['method'] = 'secure.getAppBalance';
        return $this->request($request);
    }

    function getBalance($uid) {
        $request['uid'] = $uid;
        $request['method'] = 'secure.getBalance';
        return $this->request($request);
    }

    function addVotes($uid, $votes) {
        $request['uid'] = $uid;
        $request['votes'] = $votes;
        $request['method'] = 'secure.addVotes';
        return $this->request($request);
    }

    function withdrawVotes($uid, $votes) {
        $request['uid'] = $uid;
        $request['votes'] = $votes;
        $request['method'] = 'secure.withdrawVotes';
        return $this->request($request);
    }

    function transferVotes($uid_from, $uid_to, $votes) {
        $request['uid_from'] = $uid_from;
        $request['uid_to'] = $uid_to;
        $request['votes'] = $votes;
        $request['method'] = 'secure.transferVotes';
        return $this->request($request);
    }

    function getTransactionsHistory() {
        $request['method'] = 'secure.getTransactionsHistory';
        return $this->request($request);
    }

    function addRating($uid, $rate) {
        $request['uid'] = $uid;
        $request['rate'] = $rate;
        $request['method'] = 'secure.addRating';
        return $this->request($request);
    }

    function setCounter($uid, $counter) {
        $request['uid'] = $uid;
        $request['counter'] = $counter;
        $request['method'] = 'secure.setCounter';
        return $this->request($request);
    }

    function request($request) {
        $request['random'] = rand(100000, 999999);
        $request['timestamp'] = time();
        $request['format'] = 'JSON';
        $request['api_id'] = $this->api_id;

        ksort($request);
        $str = '';
        foreach ($request as $key => $value) {
            $str .= trim($key) . "=" . trim($value);
        }

        $request['sig'] = md5(trim($str . $this->api_secret));

        $q = http_build_query($request);
        $result = json_decode(file_get_contents("http://api.vkontakte.ru/api.php?" . $q), TRUE);

        return $result;
    }

    //Check if user is realy authorized through vkontakte api
    public function checkAuth($app_cookie) {

        $session = array();
        $member = FALSE;
        $valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
//        $app_cookie = $_COOKIE['vk_app_' . $APP_ID];
        if ($app_cookie) {
            $session_data = explode('&', $app_cookie, 10);
            foreach ($session_data as $pair) {
                list($key, $value) = explode('=', $pair, 2);
                if (empty($key) || empty($value) || !in_array($key, $valid_keys)) {
                    continue;
                }
                $session[$key] = $value;
            }
            foreach ($valid_keys as $key) {
                if (!isset($session[$key]))
                    return $member;
            }
            ksort($session);

            $sign = '';
            foreach ($session as $key => $value) {
                if ($key != 'sig') {
                    $sign .= ($key . '=' . $value);
                }
            }
            $sign .= $this->api_secret;
            $sign = md5($sign);
            if ($session['sig'] == $sign && $session['expire'] > time()) {
                $member = array(
                    'id' => intval($session['mid']),
                    'secret' => $session['secret'],
                    'sid' => $session['sid']
                );
            }
        }
        return $member;
    }

}