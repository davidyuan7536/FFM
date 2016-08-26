<?php

class Account extends RequestHandler {
    public function get($parameters) {
        require_once "Db/DbUsers.php";
        $users = new DbUsers();

        switch ($parameters[1]) {
            case 'forgot':
                if (empty($parameters[2])) {
                    $response = new Response('forgot.tpl');
                    $response->assign('Form', true);
                    $response->write();
                } else {
                    $user = $users->getUserByForgotCode($parameters[2]);
                    if (empty($user)) {
                        Utils::sendResponse(404);
                    } else {
                        $response = new Response('forgot.tpl');
                        $response->assign('Form', false);
                        $response->assign('Code', $parameters[2]);
                        $response->write();
                    }
                }
                break;
            case 'activate':
                $user = $users->getUserByActivationCode($parameters[2]);
                if (!empty($user)) {
                    $users->activateUserEmail($user['user_id']);
                    Utils::setUser($users->getUserById($user['user_id']));
                    Utils::redirect('http://' . __FFM_HOST__ . '/users/' . $user['user_hash'] . '/');
                } else {
                    Utils::sendError("Activation Failed");
                }
                break;
            case 'logout':
                unset($_SESSION['user']);
                setcookie('user', '', time() - 3600, '/');
                setcookie('pass', '', time() - 3600, '/');
                Utils::redirect($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/');
                break;
            default:
                Utils::sendResponse(404);
        }
    }

    public function post() {
        require_once "Db/DbUsers.php";
        $users = new DbUsers();

        switch ($_POST['Action']) {
            case 'PostComment':
                if (empty($_SESSION['user'])) {
                    echo json_encode(array(
                        'message' => Utils::getMessage('e001')
                    ));
                } else {
                    $user = $_SESSION['user'];
                    $CommentCategory = Utils::escape($_POST['CommentCategory']);
                    $CommentParentId = Utils::escape($_POST['CommentParentId']);
                    $CommentText = Utils::escape($_POST['CommentText']);
                    if (empty($CommentText)) {
                        echo json_encode(array(
                            'message' => Utils::getMessage('f001')
                        ));
                    } else if (strlen($CommentText) > 1000) {
                        echo json_encode(array(
                            'message' => Utils::getMessage('f025')
                        ));
                    } else {
                        $data = array(
                            'user_id' => $user['user_id'],
                            'comment_text' => $CommentText,
                            'comment_category' => $CommentCategory,
                            'parent_id' => $CommentParentId
                        );

                        require_once "Db/DbArticles.php";
                        require_once "Db/DbArtists.php";
                        require_once "Db/DbPromoters.php";
                        $dbArticles = new DbArticles();
                        $dbArtists = new DbArtists();
                        switch ($CommentCategory) {
                            case 1:
                                $o = $dbArticles->getArticleById($CommentParentId);
                                $Link = 'http://' . __FFM_HOST__ . "/articles/{$o['filename']}.html#comments";
                                $Subject = htmlspecialchars($o['title']);
                                break;
                            case 2:
                                $o = $dbArtists->getArtistById($CommentParentId);
                                $Link = 'http://' . __FFM_HOST__ . "/artists/{$o['filename']}.html#comments";
                                $Subject = htmlspecialchars($o['name']);
                                break;
                            case 3:
                                $o = DbPromoters::getPromoterById($CommentParentId);
                                $Link = 'http://' . __FFM_HOST__ . "/promoters/{$o['promoter_filename']}.html#comments";
                                $Subject = htmlspecialchars($o['promoter_name']);
                                break;
                            default:
                                $Link = 'error';
                                $Subject = 'Error';
                        }
                        $p = nl2br($CommentText);
                        $h = __FFM_HOST__;
                        $m = <<<EOD
<p>{$p}</p>
<p style='font-size:10px;'>--<br>
    <a href='{$Link}'>{$Subject}</a>
    <br><a href='http://{$h}/site/admin/comments.php' style='color:#000;'>Control Panel</a></p>
EOD;

                        Utils::mailMessage('Comment at ' . $Subject, $m, false, false, __FFM_EMAIL__);

                        require_once "Db/DbComments.php";
                        DbComments::newComment($data);

                        $comments = DbComments::getCommentsByParentId($CommentParentId, $CommentCategory);
                        $response = new Response('');
                        $response->assign('Comments', $comments);
                        echo json_encode(array(
                            'elements' => array(
                                '#CommentsList' => $response->fetch('includes/comment_list.tpl'),
                            )
                        ));
                    }
                }
                break;
            case 'ChangePassword':
                $p = $_POST['Password'];
                if (empty($p)) {
                    echo json_encode(array(
                        'message' => Utils::getMessage('f001')
                    ));
                } else {
                    if (strlen($p) < 6) {
                        echo json_encode(array(
                            'message' => Utils::getMessage('f020')
                        ));
                    } else if (strlen($p) > 32) {
                        echo json_encode(array(
                            'message' => Utils::getMessage('f021')
                        ));
                    } else {
                        $user = $users->getUserByForgotCode($_POST['Code']);
                        if (empty($user)) {
                            echo json_encode(array(
                                'message' => Utils::getMessage('e001')
                            ));
                        } else {
                            $data = array(
                                'forgot_code' => '',
                                'password' => md5($_POST['Password'])
                            );
                            $users->updateUserById($user['user_id'], $data);
                            echo json_encode(array(
                                'elements' => array(
                                    '#Content' => "<h1 style='text-transform: none;'>{$GLOBALS[FFM_LANG]['user']['forgotPasswordChanged']}</h1>"
                                )
                            ));
                        }
                    }
                }
                break;
            case 'Forgot':
                $user = $users->getUserByEmail($_POST['Email']);
                if (empty($user) || !empty($user['fbid'])) {
                    echo json_encode(array(
                        'message' => Utils::getMessage('e007')
                    ));
                } else {
                    $data = array(
                        'forgot_code' => md5($user['user_id'] . time())
                    );
                    $users->updateUserById($user['user_id'], $data);
                    $link = 'http://' . __FFM_HOST__ . '/accounts/forgot/' . $data['forgot_code'];
                    $message = <<<EOD
    <p>{$GLOBALS[FFM_LANG]['user']['forgotChange']}</p>
    <p><a href="{$link}">{$link}</a></p>
    <p>{$GLOBALS[FFM_LANG]['user']['forgotCopyLink']}</p>
    <p>{$GLOBALS[FFM_LANG]['user']['forgotMessage']}</p>
EOD;
                    Utils::mailTemplate($_POST['Email'], $GLOBALS[FFM_LANG]['user']['forgotSubject'], $message);
                    $content = '<h1>' . $GLOBALS[FFM_LANG]['user']['forgotSent'] . '</h1><p>' . $GLOBALS[FFM_LANG]['user']['forgotInstructions'] . '</p>';
                    echo json_encode(array(
                        'elements' => array(
                            '#Content' => $content
                        )
                    ));
                }
                break;
            case 'login':
                $login = trim($_POST['Login']);
                $password = trim($_POST['Password']);
                $user = $users->getUserByPassword($login, md5($password));

                if (!empty($user)) {
                    if (!empty($_POST['Remember'])) {
                        $u = $users->getUserById($user['user_id']);
                        $expire = time() + 5184000; // 60 days
                        $cookie_pass = sha1(sha1($u['password']) . sha1($u['user_hash']) . sha1($u['user_email']));

                        setcookie('user', $u['user_hash'], $expire, '/');
                        setcookie('pass', $cookie_pass, $expire, '/');
                    }
                    $_SESSION['user'] = $user;
                    echo json_encode(array(
                        'status' => 'OK'
                    ));
                } else {
                    echo json_encode(array(
                        'message' => Utils::getMessage('u003')
                    ));
                }
                break;
            default:
                $row = array(
                    'user_name' => trim($_POST['Name']),
                    'user_email' => trim($_POST['Email']),
                    'password' => trim($_POST['Password']),
                    'activation_code' => md5(time()),
                    'subscribe' => $_POST['Subscribe']
                );

                $errors = $this->getFieldValues($row);

                if (!empty($errors)) {
                    echo json_encode($errors);
                } else {
                    $ip = Utils::getIpAddress();
                    $record = Utils::getGeoRecord($ip);
                    $row['ip'] = $ip;
                    if (!empty($record->country_code)) {
                        $row['country_code'] = $record->country_code;
                        $row['city'] = $record->city;
                        $row['latitude'] = $record->latitude;
                        $row['longitude'] = $record->longitude;
                    }

                    $id = $users->createUser($row);

                    if (!$id) {
                        echo json_encode(array(
                            'message' => Utils::getMessage('u001')
                        ));
                    } else {
                        $hash = array(
                            'user_hash' => Utils::hash($id)
                        );
                        $users->updateUserById($id, $hash);

                        $this->send_activation($row);

                        echo json_encode(array(
                            'status' => 'OK',
                            'message' => Utils::getMessage('u002')
                        ));
                    }
                }
                break;
        }
    }

    private function getFieldValues(&$row) {
        $result = array();

        if (empty($row['user_name'])) {
            $result['fields']['Name'] = Utils::getMessage('f001');
        } else if (strlen($row['user_name']) > 100) {
            $result['fields']['Name'] = Utils::getMessage('f022');
        }

        if (empty($row['user_email'])) {
            $result['fields']['Email'] = Utils::getMessage('f001');
        } else if (strpos($row['user_email'], '@') === false) {
            $result['fields']['Email'] = Utils::getMessage('f014');
        } else {
            $e = explode('@', $row['user_email']);
            if (count($e) > 2) {
                $result['fields']['Email'] = Utils::getMessage('f013');
            } else if (empty($e[0])) {
                $result['fields']['Email'] = Utils::getMessage('f015');
            } else if (empty($e[1])) {
                $result['fields']['Email'] = Utils::getMessage('f012');
            } else if (strlen($e[0] > 64) || strlen($e[1] > 255)) {
                $result['fields']['Email'] = Utils::getMessage('f017');
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $e[1])) {
                $result['fields']['Email'] = Utils::getMessage('f016');
            } else if (preg_match('/\\.\\./', $e[1])) {
                $result['fields']['Email'] = Utils::getMessage('f016');
            } else {
                $v = true;
                if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $e[0]))) {
                    if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $e[0]))) {
                        $v = false;
                        $result['fields']['Email'] = Utils::getMessage('f011');
                    }
                }
                if ($v && !(checkdnsrr($e[1], "MX") || checkdnsrr($e[1], "A"))) {
                    $result['fields']['Email'] = Utils::getMessage('f016');
                } else {
                    require_once "Db/DbUsers.php";
                    $users = new DbUsers();
                    $user = $users->getUserByEmail($row['user_email']);
                    if (!empty($user)) {
                        $result['fields']['Email'] = Utils::getMessage('f024');
                    }
                }
            }
        }

        if (empty($row['password'])) {
            $result['fields']['Password'] = Utils::getMessage('f001');
        } else {
            if (strlen($row['password']) < 6) {
                $result['fields']['Password'] = Utils::getMessage('f020');
            } else if (strlen($row['password']) > 32) {
                $result['fields']['Password'] = Utils::getMessage('f021');
            } else if ($row['password'] == $row['user_email']) {
                $result['fields']['Password'] = Utils::getMessage('f023');
            } else {
                $row['password'] = md5($row['password']);
            }
        }

        return $result;
    }

    private function send_activation($row) {
        $link = 'http://' . __FFM_HOST__ . '/accounts/activate/' . $row['activation_code'];
        $message = <<<EOD
<p>To verify your account and complete the signup process please go to the link below. </p>
<p><a href="{$link}">{$link}</a></p>
EOD;
        return Utils::mailTemplate($row['user_email'], 'Account Activation', $message);
    }
}

$app = new Application(array(
    array('/^\/accounts\/?$/', 'Account', true),
    array('/^\/accounts\/(forgot)\/?$/', 'Account', true),
    array('/^\/accounts\/(forgot)\/([a-fA-F\d]{32})$/', 'Account', false),
    array('/^\/accounts\/(activate)\/([a-fA-F\d]{32})$/', 'Account', false),
    array('/^\/accounts\/(logout)$/', 'Account', false),
));

$app->run();

