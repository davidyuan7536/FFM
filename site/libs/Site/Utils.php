<?php

class Utils {
    private static $ip;
    private static $PARTIALLY_UPLOADED = 'The uploaded file was only partially uploaded';

    static public $statusTexts = array(
        '100' => 'Continue',
        '101' => 'Switching Protocols',
        '200' => 'OK',
        '201' => 'Created',
        '202' => 'Accepted',
        '203' => 'Non-Authoritative Information',
        '204' => 'No Content',
        '205' => 'Reset Content',
        '206' => 'Partial Content',
        '300' => 'Multiple Choices',
        '301' => 'Moved Permanently',
        '302' => 'Found',
        '303' => 'See Other',
        '304' => 'Not Modified',
        '305' => 'Use Proxy',
        '307' => 'Temporary Redirect',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '402' => 'Payment Required',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '405' => 'Method Not Allowed',
        '406' => 'Not Acceptable',
        '407' => 'Proxy Authentication Required',
        '408' => 'Request Timeout',
        '409' => 'Conflict',
        '410' => 'Gone',
        '411' => 'Length Required',
        '412' => 'Precondition Failed',
        '413' => 'Request Entity Too Large',
        '414' => 'Request-URI Too Long',
        '415' => 'Unsupported Media Type',
        '416' => 'Requested Range Not Satisfiable',
        '417' => 'Expectation Failed',
        '500' => 'Internal Server Error',
        '501' => 'Not Implemented',
        '502' => 'Bad Gateway',
        '503' => 'Service Unavailable',
        '504' => 'Gateway Timeout',
        '505' => 'HTTP Version Not Supported',
    );

    public static function sanitizeName($name) {
        include_once "formatting.php";
        return preg_replace('/[^A-Za-z0-9-]/', '', strtolower(remove_accents(sanitize_file_name($name))));
    }

    public static function debug($var) {
        echo '<pre>';
        print_r($var);
        exit;
    }

    public static function getMessage($code) {
        return $GLOBALS[FFM_LANG]['sys'][$code];
    }

    public static function sendResponse($code) {
        $statusCode = (int) $code;
        if ($statusCode < 100 || $statusCode > 599) {
            throw new InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $code));
        }

        header("HTTP/1.0 {$code} " . self::$statusTexts[$statusCode]);
    }

    public static function redirect($location) {
        header("Location: {$location}", true, 302);
    }

    public static function redirectToHome() {
        header("Location: http://" . __FFM_HOST__ . "/", true, 302);
    }

    public static function checkCurrentWeekNumberMd5($str) {
        return md5(date("W")) === $str;
    }

    public static function convertArtistImage(&$artist, $format = 'm') {
        $artist['image'] = empty($artist['image']) ? "/i/decor/placeholder-artist_{$format}.png" : __FFM_PROFILE_FRONT__ . $artist['filename'] . "/a/{$format}.jpg?v={$artist['image']}";
    }

    public static function convertPromoterImage(&$promoter, $format = 'm') {
        $promoter['promoter_image'] = empty($promoter['promoter_image']) ? "/i/decor/placeholder-promoter_{$format}.png" : __FFM_PROMOTER_FRONT__ . $promoter['promoter_filename'] . "/a/{$format}.jpg?v={$promoter['promoter_image']}";
    }

    public static function getArtistPhotosNumberHeader($n) {
        global $__FFM_LANG__;
        return self::getLocalisedString($__FFM_LANG__['headers']['artistPhotos'], $__FFM_LANG__['id'], $n);
    }

    public static function getPromoterPhotosNumberHeader($n) {
        global $__FFM_LANG__;
        return self::getLocalisedString($__FFM_LANG__['headers']['promoterPhotos'], $__FFM_LANG__['id'], $n);
    }

    public static function getLocalisedString($forms, $lang, $n) {
        if ($n == 0) {
            return $forms[0];
        } else {
            if ($lang == 'ru') {
                $t = $n % 10 == 1 && $n % 100 != 11 ? $forms[1] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[2] : $forms[3]);
            } else {
                $t = $n == 1 ? $forms[1] : $forms[2];
            }

            return str_replace('$1', $n, $t);
        }
    }

    /* Next prime greater than 62 ^ n / 1.618033988749894848 */
    private static $golden_primes = array(
        1, 41, 2377, 147299, 9132313, 566201239, 35104476161, 2176477521929, 134941606358706
    );

    /* Ascii :                    0  9,         A  Z,         a  z     */
    /* $chars = array_merge(range(48,57), range(65,90), range(97,122)) */
    private static $chars = array(
        0 => 48, 1 => 49, 2 => 50, 3 => 51, 4 => 52, 5 => 53, 6 => 54, 7 => 55, 8 => 56, 9 => 57, 10 => 65,
        11 => 66, 12 => 67, 13 => 68, 14 => 69, 15 => 70, 16 => 71, 17 => 72, 18 => 73, 19 => 74, 20 => 75,
        21 => 76, 22 => 77, 23 => 78, 24 => 79, 25 => 80, 26 => 81, 27 => 82, 28 => 83, 29 => 84, 30 => 85,
        31 => 86, 32 => 87, 33 => 88, 34 => 89, 35 => 90, 36 => 97, 37 => 98, 38 => 99, 39 => 100, 40 => 101,
        41 => 102, 42 => 103, 43 => 104, 44 => 105, 45 => 106, 46 => 107, 47 => 108, 48 => 109, 49 => 110,
        50 => 111, 51 => 112, 52 => 113, 53 => 114, 54 => 115, 55 => 116, 56 => 117, 57 => 118, 58 => 119,
        59 => 120, 60 => 121, 61 => 122
    );

    private static function base62($int) {
        $key = "";
        while ($int > 0) {
            $mod = $int - (floor($int / 62) * 62);
            $key .= chr(self::$chars[$mod]);
            $int = floor($int / 62);
        }
        return strrev($key);
    }

    public static function hash($num, $len = 6) {
        $ceil = pow(62, $len);
        $prime = self::$golden_primes[$len];
        $dec = ($num * $prime) - floor($num * $prime / $ceil) * $ceil;
        $hash = self::base62($dec);
        return str_pad($hash, $len, "0", STR_PAD_LEFT);
    }

    public static function setUser($user) {
        if (isset($user['enabled']) && $user['enabled'] == 1) {
            $_SESSION['user'] = $user;
            unset($_SESSION['user']['password']);
        } else {
            unset($_SESSION['user']);
        }
    }

    public static function sendError($msg) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo json_encode(array(
                'message' => $msg
            ));
        } else {
            echo $msg;
            exit;
            $response = new Response('error.tpl'); // TODO: REMOVE RECURSION
            $response->assign('Title', $msg);
            $response->write();
        }
    }

    private static function getHeaders() {
        $headers = "From: notification@farfrommoscow.com\r\n" .
                "Reply-To: notification@farfrommoscow.com\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;" . "\r\n";
        return $headers;
    }

    public static function mailMessage($subject, $message = null, $dump = null, $vars = true, $to = __FFM_SUPPORT__) {
        $subject = "FFM[" . __FFM_NAME__ . "]: " . $subject;
        if (!empty($_SESSION['user'])) {
            $u = "<br/>User: <a href='http://" . __FFM_HOST__ . "/site/admin/user.php?id={$_SESSION['user']['user_id']}'>" . $_SESSION['user']['user_email'] . "</a>";
        }
        $m = "<p>IP: <strong>" . Utils::getIpAddress() . "</strong>{$u}</p>";
        if (!empty($message)) {
            $m .= '<div style="margin: 10px; padding: 10px; border: 1px solid #efefef; background:#ffffcc;">' . $message . '</div>';
        }
        if (!empty($dump)) {
            $m .= '<pre style="font-size:11px;margin: 10px; padding: 10px; border: 1px solid #efefef; background:#fff;">$dump:<br>';
            $m .= print_r($dump, true);
            $m .= '</pre>';
        }
        if ($vars) {
            $m .= '<pre style="font-size:11px;margin: 10px; padding: 10px; border: 1px solid #aaa; background:#efefef;">$_POST<br>';
            $m .= print_r($_POST, true);
            $m .= '</pre><pre style="font-size:11px;margin: 10px; padding: 10px; border: 1px solid #aaa; background:#efefef;">$_GET<br>';
            $m .= print_r($_GET, true);
            $m .= '</pre><pre style="font-size:11px;margin: 10px; padding: 10px; border: 1px solid #aaa; background:#efefef;">$_SERVER<br>';
            $m .= print_r($_SERVER, true);
            $m .= '</pre>';
        }

        return @mail($to, $subject, $m, self::getHeaders());
    }

    public static function mailTemplate($to, $subject, $message, $name = "Friend") {
        $subject = "FFM: {$subject}";
        $m = <<<EOD
<div style="width: 720px; margin: 0 auto; color: #212121; font: 12px Helvetica, Arial, sans-serif;">
    <div style="padding: 20px 0; border-bottom: 1px solid #c0c5cd; color: #f70000; font-size: 30px; letter-spacing: -0.06em;">FAR FROM MOSCOW</div>
    <div style="padding: 16px 0 0; font-size: 20px;">Dear {$name},</div>
    <div style="line-height: 20px;">
        {$message}
    </div>
    <div style="margin-top: 3em; padding: 10px 0 20px; border-top: 1px solid #c0c5cd; color: #808080; font-size: 11px; font-weight: bold;">
        <div>&copy; 2011 <a href="http://www.farfrommoscow.com/" style="color: #000;">Far from Moscow</a>. All right Reserved</div>
        <div>This is a post-only mailing. Replies to this message are not monitored or answered.</div>
    </div>
</div>
EOD;

        return @mail($to, $subject, $m, self::getHeaders());
    }

    public static function file_upload_error_message($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return self::$PARTIALLY_UPLOADED;
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }

    public static function logArtist($user_id, $id, $message) {
        require_once "Db/DbLog.php";
        $dbLog = new DbLog();
        $row = array(
            'user_id' => $user_id,
            'artist_id' => $id,
            'message' => $message
        );
        $dbLog->newLog($row);
    }

    public static function logEvent($user_id, $id, $message) {
        require_once "Db/DbLog.php";
        $dbLog = new DbLog();
        $row = array(
            'user_id' => $user_id,
            'event_id' => $id,
            'message' => $message
        );
        $dbLog->newLog($row);
    }

    public static function logPromoter($user_id, $id, $message) {
        require_once "Db/DbLog.php";
        $dbLog = new DbLog();
        $row = array(
            'user_id' => $user_id,
            'promoter_id' => $id,
            'message' => $message
        );
        $dbLog->newLog($row);
    }

    public static function checkFileUpload() {
        if (isset($_FILES['Filedata']['error']) && $_FILES['Filedata']['error'] !== UPLOAD_ERR_OK) {
            $message = self::file_upload_error_message($_FILES['Filedata']['error']);
            if ($message != self::$PARTIALLY_UPLOADED) {
                self::mailMessage('Upload Error', $message, $_FILES);
            }
            echo json_encode(array(
                'message' => Utils::getMessage('e003')
            ));
            exit;
        }
    }

    public static function getGeoRecord($ip) {
        include('Geo/geoipcity.inc');
        $gi = geoip_open(GEOIP_STANDARD);
        $record = geoip_record_by_addr($gi, $ip);
        geoip_close($gi);
        return $record;
    }

    public static function getIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && self::validateIp($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if (self::validateIp($ip))
                    return $ip;
            }
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED']) && self::validateIp($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && self::validateIp($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && self::validateIp($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        if (!empty($_SERVER['HTTP_FORWARDED']) && self::validateIp($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];

        return $_SERVER['REMOTE_ADDR'];
    }

    public static function validateIp($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false)
            return false;

        self::$ip = ip2long($ip);
        return true;
    }

    public static function escape($str) {
        return trim($str);
    }

    public static function createImage($settings, $crop = 1) {
        if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
            return false;
        }

        return self::createImageByName($_FILES["Filedata"]["tmp_name"], $settings, $crop);
    }

    public static function createImageByName($tmp_name, $settings, $crop = 1) {
        foreach ($settings as $val) {
            if (!is_dir(dirname($val['filename']))) {
                include_once "formatting.php";
                wp_mkdir_p(dirname($val['filename']));
            }
            if (empty($val['width'])) {
                copy($tmp_name, $val['filename']);
            } else {
                if (empty($crop)) {
                    $thumb = self::getThumbnail($tmp_name, $val['width'], $val['height']);
                } else {
                    $thumb = self::getThumbnailCrop($tmp_name, $val['width'], $val['height']);
                }
                if (!$thumb) {
                    copy($tmp_name, $val['filename']);
                } else {
                    if (!imagejpeg($thumb, $val['filename'], 90)) {
                        return false;
                    }
                    imagedestroy($thumb);
                }
            }
            $stat = stat(dirname($val['filename']));
            $perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
            @chmod($val['filename'], $perms);
        }
        return true;
    }

    public static function getThumbnailCrop($imgSrc, $thumbnail_width, $thumbnail_height) {
        list($width_orig, $height_orig) = @getimagesize($imgSrc);
        $myImage = imagecreatefromjpeg($imgSrc);
        if (!$myImage) {
            return false;
        }
        $ratio_orig = $width_orig / $height_orig;

        if ($thumbnail_width / $thumbnail_height > $ratio_orig) {
            $new_height = $thumbnail_width / $ratio_orig;
            $new_width = $thumbnail_width;
        } else {
            $new_width = $thumbnail_height * $ratio_orig;
            $new_height = $thumbnail_height;
        }

        $x_mid = $new_width / 2; //horizontal middle
        $y_mid = $new_height / 2; //vertical middle

        $process = imagecreatetruecolor(round($new_width), round($new_height));

        imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
        $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
        imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

        imagedestroy($process);
        imagedestroy($myImage);
        return $thumb;
    }

    public static function getThumbnail($imgSrc, $max_w, $max_h) {
        list($orig_w, $orig_h) = @getimagesize($imgSrc);
        $image = imagecreatefromjpeg($imgSrc);
        if (!$image) {
            return false;
        }

        $dims = self::image_resize_dimensions($orig_w, $orig_h, $max_w, $max_h);
        if (!$dims) {
            return $dims;
        }
        list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = $dims;

        $newimage = imagecreatetruecolor($dst_w, $dst_h);

        imagecopyresampled($newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        imagedestroy($image);

        return $newimage;
    }

    /**
     * Calculates the new dimentions for a downsampled image.
     *
     * Same as {@link wp_shrink_dimensions()}, except the max parameters are
     * optional. If either width or height are empty, no constraint is applied on
     * that dimension.
     *
     * @since 2.5.0
     *
     * @param int $current_width Current width of the image.
     * @param int $current_height Current height of the image.
     * @param int $max_width Optional. Maximum wanted width.
     * @param int $max_height Optional. Maximum wanted height.
     * @return array First item is the width, the second item is the height.
     */
    public static function wp_constrain_dimensions($current_width, $current_height, $max_width = 0, $max_height = 0) {
        if (!$max_width and !$max_height)
            return array($current_width, $current_height);

        $width_ratio = $height_ratio = 1.0;

        if ($max_width > 0 && $current_width > 0 && $current_width > $max_width)
            $width_ratio = $max_width / $current_width;

        if ($max_height > 0 && $current_height > 0 && $current_height > $max_height)
            $height_ratio = $max_height / $current_height;

        // the smaller ratio is the one we need to fit it to the constraining box
        $ratio = min($width_ratio, $height_ratio);

        return array(intval($current_width * $ratio), intval($current_height * $ratio));
    }

    /**
     * Retrieve calculated resized dimensions for use in imagecopyresampled().
     *
     * Calculate dimensions and coordinates for a resized image that fits within a
     * specified width and height. If $crop is true, the largest matching central
     * portion of the image will be cropped out and resized to the required size.
     *
     * @since 2.5.0
     *
     * @param int $orig_w Original width.
     * @param int $orig_h Original height.
     * @param int $dest_w New width.
     * @param int $dest_h New height.
     * @param bool $crop Optional, default is false. Whether to crop image or resize.
     * @return bool|array False, on failure. Returned array matches parameters for imagecopyresampled() PHP function.
     */
    public static function image_resize_dimensions($orig_w, $orig_h, $dest_w, $dest_h, $crop = false) {

        if ($orig_w <= 0 || $orig_h <= 0)
            return false;
        // at least one of dest_w or dest_h must be specific
        if ($dest_w <= 0 && $dest_h <= 0)
            return false;

        if ($crop) {
            // crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
            $aspect_ratio = $orig_w / $orig_h;
            $new_w = min($dest_w, $orig_w);
            $new_h = min($dest_h, $orig_h);

            if (!$new_w) {
                $new_w = intval($new_h * $aspect_ratio);
            }

            if (!$new_h) {
                $new_h = intval($new_w / $aspect_ratio);
            }

            $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

            $crop_w = round($new_w / $size_ratio);
            $crop_h = round($new_h / $size_ratio);

            $s_x = floor(($orig_w - $crop_w) / 2);
            $s_y = floor(($orig_h - $crop_h) / 2);
        } else {
            // don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
            $crop_w = $orig_w;
            $crop_h = $orig_h;

            $s_x = 0;
            $s_y = 0;

            list($new_w, $new_h) = self::wp_constrain_dimensions($orig_w, $orig_h, $dest_w, $dest_h);
        }

        // if the resulting image would be the same size or larger we don't want to resize it
        if ($new_w >= $orig_w && $new_h >= $orig_h)
            return false;

        // the return array matches the parameters to imagecopyresampled()
        // int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
        return array(0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h);
    }
}
