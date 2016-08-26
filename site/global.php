<?php
if (isset($_POST["PHPSESSID"])) {
    session_id($_POST["PHPSESSID"]);
}

session_start();

define('__FFM_VERSION__', '204');

if (!defined("PATH_SEPARATOR")) {
    define("PATH_SEPARATOR", getenv("COMSPEC") ? ";" : ":");
}

define('__NOISY_MAP_ROOT__', __DIR__ . DIRECTORY_SEPARATOR .  ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'mapdev' . DIRECTORY_SEPARATOR);

$include_path = ini_get("include_path");
$include_path .= PATH_SEPARATOR . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'libs';

ini_set("include_path", $include_path);
ini_set("error_reporting", E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);

require_once "config_ffm.php";
require_once "libs/langs.php";
require_once "libs/Site/Utils.php";
require_once "libs/Db/DbUsers.php";
$users = new DbUsers();

if (!empty($_SESSION['user'])) {
    $user = $users->getUserById($_SESSION['user']['user_id']);
    Utils::setUser($user);
}

if (empty($_SESSION['user']) && isset($_COOKIE['user'], $_COOKIE['pass'])) {
    $user = $users->getUserByHash($_COOKIE['user']);
    if ($user) {
        $check_pass = sha1(sha1($user['password']) . sha1($user['user_hash']) . sha1($user['user_email']));
        if ($check_pass == $_COOKIE['pass']) {
            Utils::setUser($user);
        } else {
            setcookie('user', '', time() - 3600, '/');
            setcookie('pass', '', time() - 3600, '/');
        }
    }
}

class Autoloader
{
    function noisymap_classes_autoload($class_name)
    {
        $className = str_replace('\\', DS, $class_name);
        $file = __NOISY_MAP_ROOT__ . 'src' . DS . 'classes' . DS . $className . '.php';

        if (! file_exists($file)) {
            return false;
        }

        require_once $file;

        return true;
    }

    function classes_autoload($class_name) {
        $className = str_replace('\\', DS, $class_name);
        $file = __DIR__ . DS . $className . ".php";

        if (is_readable($file)) {
            require_once $file;
        }
    }
}

$al = new Autoloader();

spl_autoload_register(array($al, 'noisymap_classes_autoload'));
spl_autoload_register(array($al, 'classes_autoload'));
