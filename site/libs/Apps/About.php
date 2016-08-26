<?php

class About extends RequestHandler {
    public function get() {
        require_once "Db/DbGeotags.php";
        $dbGeoTags = new DbGeotags();
        $geoTags = $dbGeoTags->getGeoTags();

        require_once "Db/DbAbout.php";
        $dbAbout = new DbAbout();
        $about = $dbAbout->getAbout();

        $response = new Response('about.tpl');
        $response->assign('About', $about);
        $response->assign('Title', $GLOBALS[FFM_LANG]['headers']['about']);
        $response->assign('Section', 'about');
        $response->assign('GeoTags', $geoTags);
        $response->write();
    }

    public function post() {
        if ('video' == $_POST['Action']) {
            $to = __FFM_EMAIL__;
            $subject = 'FFM: Video Submit';
            $headers = 'From: notification@farfrommoscow.com' . "\r\n" .
                    'Reply-To: notification@farfrommoscow.com' . "\r\n";

            $n = trim($_POST['Name']);
            $e = trim($_POST['Email']);
            $s = trim($_POST['Subject']);
            $t = wordwrap(trim($_POST['Text']), 100);

            $message = '';
            $message .= "Name: {$n}\r\n";
            $message .= "Email: {$e}\r\n";
            $message .= "Link: {$s}\r\n";
            $message .= "\r\n{$t}\r\n";

            if (@mail($to, $subject, $message, $headers)) {
                echo "OK";
            } else {
                echo "Something happened! Your message has NOT been sent successfully. Please try to e-mail us: dmacfady@humnet.ucla.edu";
            }
        } else {
            $to = __FFM_EMAIL__;
            $subject = 'FFM: Contact Us';
            $headers = 'From: notification@farfrommoscow.com' . "\r\n" .
                    'Reply-To: notification@farfrommoscow.com' . "\r\n";

            $n = trim($_POST['Name']);
            $e = trim($_POST['Email']);
            $s = trim($_POST['Subject']);
            $t = wordwrap(trim($_POST['Text']), 100);

            $message = '';
            $message .= "Name: {$n}\r\n";
            $message .= "Email: {$e}\r\n";
            $message .= "Subject: {$s}\r\n";
            $message .= "\r\n{$t}\r\n";

            if (@mail($to, $subject, $message, $headers)) {
                echo "OK";
            } else {
                echo "Something happened! Your message has NOT been sent successfully. Please try to e-mail us: dmacfady@humnet.ucla.edu";
            }
        }
    }
}

$app = new Application(array(
    array('/^(\/about\/?)$/', 'About', true)
));

$app->run();

